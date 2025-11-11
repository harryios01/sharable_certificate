<?php

use App\Controllers\AuthController;
use App\Controllers\AssessmentController;
use App\Controllers\CertificateController;
use App\Controllers\VerificationController;
use App\Controllers\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RateLimitMiddleware;

class Router
{
    private $routes = [];

    public function get($path, $handler, $middleware = [])
    {
        $this->routes['GET'][$path] = ['handler' => $handler, 'middleware' => $middleware];
    }

    public function post($path, $handler, $middleware = [])
    {
        $this->routes['POST'][$path] = ['handler' => $handler, 'middleware' => $middleware];
    }

    public function put($path, $handler, $middleware = [])
    {
        $this->routes['PUT'][$path] = ['handler' => $handler, 'middleware' => $middleware];
    }

    public function delete($path, $handler, $middleware = [])
    {
        $this->routes['DELETE'][$path] = ['handler' => $handler, 'middleware' => $middleware];
    }

    public function dispatch($method, $path)
    {
        // Remove query string
        $path = strtok($path, '?');

        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        foreach ($this->routes[$method] as $route => $config) {
            if ($this->matchRoute($route, $path)) {
                // Execute middleware
                foreach ($config['middleware'] as $middleware) {
                    call_user_func($middleware);
                }

                // Execute handler
                call_user_func($config['handler']);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }

    private function matchRoute($route, $path)
    {
        return $route === $path;
    }
}

$router = new Router();

// Authentication Routes
$router->get('/api/auth/google', [new AuthController(), 'googleLogin']);
$router->get('/api/auth/google/callback', [new AuthController(), 'googleLogin']);
$router->get('/api/auth/instagram', [new AuthController(), 'instagramLogin']);
$router->get('/api/auth/instagram/callback', [new AuthController(), 'instagramLogin']);
$router->post('/api/auth/logout', [new AuthController(), 'logout'], [[AuthMiddleware::class, 'authenticate']]);
$router->get('/api/auth/me', [new AuthController(), 'me'], [[AuthMiddleware::class, 'authenticate']]);
$router->post('/api/auth/refresh', [new AuthController(), 'refresh']);

// User Routes
$router->get('/api/user/profile', [new UserController(), 'getProfile'], [[AuthMiddleware::class, 'authenticate']]);
$router->put('/api/user/profile', [new UserController(), 'updateProfile'], [[AuthMiddleware::class, 'authenticate']]);
$router->get('/api/user/certificates', [new UserController(), 'getCertificates'], [[AuthMiddleware::class, 'authenticate']]);

// Assessment Routes
$router->post('/api/assessment/start', function() {
    RateLimitMiddleware::check('/api/assessment/start', 20, 3600);
    AuthMiddleware::optional();
    (new AssessmentController())->start();
});
$router->get('/api/assessment/questions', [new AssessmentController(), 'getQuestions']);
$router->post('/api/assessment/answer', function() {
    AuthMiddleware::optional();
    (new AssessmentController())->submitAnswer();
});
$router->get('/api/assessment', function() {
    AuthMiddleware::optional();
    (new AssessmentController())->getAssessment();
});
$router->post('/api/assessment/assign', [new AssessmentController(), 'assignToUser'], [[AuthMiddleware::class, 'authenticate']]);

// Certificate Routes
$router->post('/api/certificate/generate', function() {
    RateLimitMiddleware::check('/api/certificate/generate', 10, 3600);
    AuthMiddleware::authenticate();
    (new CertificateController())->generate();
});
$router->get('/api/certificate/download', [new CertificateController(), 'download'], [[AuthMiddleware::class, 'authenticate']]);
$router->get('/api/certificate', [new CertificateController(), 'getUserCertificates'], [[AuthMiddleware::class, 'authenticate']]);
$router->delete('/api/certificate', [new CertificateController(), 'delete'], [[AuthMiddleware::class, 'authenticate']]);
$router->post('/api/certificate/share', [new CertificateController(), 'share']);

// Verification Routes (Public)
$router->get('/api/verify', function() {
    RateLimitMiddleware::check('/api/verify', 100, 3600);
    (new VerificationController())->verify();
});

return $router;
