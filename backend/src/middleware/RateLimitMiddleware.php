<?php

namespace App\Middleware;

use App\Config\Database;
use App\Utils\Response;
use PDO;

class RateLimitMiddleware
{
    private static $db;

    private static function getDb()
    {
        if (!self::$db) {
            self::$db = Database::getInstance()->getConnection();
        }
        return self::$db;
    }

    public static function check($endpoint, $maxRequests = 100, $windowSeconds = 3600)
    {
        if (!($_ENV['RATE_LIMIT_ENABLED'] ?? true)) {
            return;
        }

        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $db = self::getDb();

        // Clean old entries
        $cleanQuery = "DELETE FROM rate_limits WHERE window_start < DATE_SUB(NOW(), INTERVAL :window SECOND)";
        $stmt = $db->prepare($cleanQuery);
        $stmt->bindParam(':window', $windowSeconds, PDO::PARAM_INT);
        $stmt->execute();

        // Check current rate
        $checkQuery = "SELECT requests_count, window_start FROM rate_limits
                       WHERE ip_address = :ip AND endpoint = :endpoint
                       AND window_start >= DATE_SUB(NOW(), INTERVAL :window SECOND)
                       LIMIT 1";

        $stmt = $db->prepare($checkQuery);
        $stmt->bindParam(':ip', $ipAddress);
        $stmt->bindParam(':endpoint', $endpoint);
        $stmt->bindParam(':window', $windowSeconds, PDO::PARAM_INT);
        $stmt->execute();

        $record = $stmt->fetch();

        if ($record) {
            if ($record['requests_count'] >= $maxRequests) {
                Response::error('Too many requests. Please try again later.', 429);
            }

            // Increment counter
            $updateQuery = "UPDATE rate_limits SET requests_count = requests_count + 1
                           WHERE ip_address = :ip AND endpoint = :endpoint";
            $stmt = $db->prepare($updateQuery);
            $stmt->bindParam(':ip', $ipAddress);
            $stmt->bindParam(':endpoint', $endpoint);
            $stmt->execute();
        } else {
            // Create new record
            $insertQuery = "INSERT INTO rate_limits (ip_address, endpoint, requests_count)
                           VALUES (:ip, :endpoint, 1)
                           ON DUPLICATE KEY UPDATE requests_count = 1, window_start = CURRENT_TIMESTAMP";
            $stmt = $db->prepare($insertQuery);
            $stmt->bindParam(':ip', $ipAddress);
            $stmt->bindParam(':endpoint', $endpoint);
            $stmt->execute();
        }
    }
}
