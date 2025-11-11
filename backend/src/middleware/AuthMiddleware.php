<?php

namespace App\Middleware;

use App\Utils\JWTHelper;
use App\Utils\Response;

class AuthMiddleware
{
    public static function authenticate()
    {
        $token = JWTHelper::getTokenFromHeader();

        if (!$token) {
            Response::unauthorized('No token provided');
        }

        $payload = JWTHelper::verifyToken($token);

        if (!$payload) {
            Response::unauthorized('Invalid or expired token');
        }

        // Store user info in global state for controllers to access
        $GLOBALS['currentUser'] = $payload;

        return $payload;
    }

    public static function optional()
    {
        $token = JWTHelper::getTokenFromHeader();

        if ($token) {
            $payload = JWTHelper::verifyToken($token);
            if ($payload) {
                $GLOBALS['currentUser'] = $payload;
                return $payload;
            }
        }

        return null;
    }
}
