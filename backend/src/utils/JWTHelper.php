<?php

namespace App\Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class JWTHelper
{
    private static $secret;
    private static $refreshSecret;
    private static $expire;
    private static $refreshExpire;

    public static function init()
    {
        self::$secret = $_ENV['JWT_SECRET'];
        self::$refreshSecret = $_ENV['JWT_REFRESH_SECRET'];
        self::$expire = (int)$_ENV['JWT_EXPIRE']; // seconds
        self::$refreshExpire = (int)$_ENV['JWT_REFRESH_EXPIRE']; // seconds
    }

    public static function generateToken($userId, $email)
    {
        $issuedAt = time();
        $expire = $issuedAt + self::$expire;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'userId' => $userId,
            'email' => $email
        ];

        return JWT::encode($payload, self::$secret, 'HS256');
    }

    public static function generateRefreshToken($userId)
    {
        $issuedAt = time();
        $expire = $issuedAt + self::$refreshExpire;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'userId' => $userId,
            'type' => 'refresh'
        ];

        return JWT::encode($payload, self::$refreshSecret, 'HS256');
    }

    public static function verifyToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$secret, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function verifyRefreshToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key(self::$refreshSecret, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getTokenFromHeader()
    {
        $headers = getallheaders();

        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}
