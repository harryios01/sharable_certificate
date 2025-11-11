<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Middleware\CorsMiddleware;
use App\Utils\JWTHelper;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Start session
session_start();

// Initialize JWT
JWTHelper::init();

// Handle CORS
CorsMiddleware::handle();

// Error handling
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("Error: [$errno] $errstr in $errfile on line $errline");
    if ($_ENV['APP_ENV'] === 'development') {
        echo json_encode([
            'error' => 'Internal server error',
            'details' => [
                'message' => $errstr,
                'file' => $errfile,
                'line' => $errline
            ]
        ]);
    } else {
        echo json_encode(['error' => 'Internal server error']);
    }
    exit(1);
});

// Get request method and path
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Serve static files (QR codes, certificates)
if (preg_match('/^\/storage\/(qrcodes|certificates)\/(.+)$/', $path, $matches)) {
    $type = $matches[1];
    $filename = $matches[2];
    $filepath = __DIR__ . "/../storage/{$type}/{$filename}";

    if (file_exists($filepath)) {
        $mimeType = mime_content_type($filepath);
        header("Content-Type: {$mimeType}");
        header("Content-Length: " . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'File not found']);
        exit;
    }
}

// Load routes
$router = require_once __DIR__ . '/../src/routes/api.php';

// Dispatch request
$router->dispatch($method, $path);
