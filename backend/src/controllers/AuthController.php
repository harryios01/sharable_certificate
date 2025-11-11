<?php

namespace App\Controllers;

use App\Models\User;
use App\Utils\JWTHelper;
use App\Utils\Response;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\Instagram;

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function googleLogin()
    {
        $provider = new Google([
            'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
            'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
            'redirectUri'  => $_ENV['GOOGLE_REDIRECT_URI'],
        ]);

        if (!isset($_GET['code'])) {
            $authUrl = $provider->getAuthorizationUrl([
                'scope' => ['profile', 'email']
            ]);
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: ' . $authUrl);
            exit;
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            Response::error('Invalid state parameter', 400);
        } else {
            try {
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                $ownerDetails = $provider->getResourceOwner($token);
                $googleUser = $ownerDetails->toArray();

                $user = $this->userModel->findByGoogleId($googleUser['sub']);

                if (!$user) {
                    $user = $this->userModel->findByEmail($googleUser['email']);
                    if ($user) {
                        $this->userModel->update($user['id'], [
                            'google_id' => $googleUser['sub'],
                            'provider' => $user['instagram_id'] ? 'both' : 'google',
                            'profile_picture' => $googleUser['picture'] ?? $user['profile_picture']
                        ]);
                        $user = $this->userModel->findById($user['id']);
                    } else {
                        $user = $this->userModel->create([
                            'google_id' => $googleUser['sub'],
                            'instagram_id' => null,
                            'name' => $googleUser['name'],
                            'email' => $googleUser['email'],
                            'profile_picture' => $googleUser['picture'] ?? null,
                            'provider' => 'google'
                        ]);
                    }
                }

                $this->userModel->updateLastLogin($user['id']);

                $jwtToken = JWTHelper::generateToken($user['id'], $user['email']);
                $refreshToken = JWTHelper::generateRefreshToken($user['id']);

                // Redirect to frontend with token
                $frontendUrl = $_ENV['APP_URL'] . '/auth/callback?token=' . urlencode($jwtToken) . '&refresh=' . urlencode($refreshToken);
                header('Location: ' . $frontendUrl);
                exit;

            } catch (\Exception $e) {
                error_log('Google OAuth error: ' . $e->getMessage());
                Response::error('Authentication failed', 500);
            }
        }
    }

    public function instagramLogin()
    {
        $provider = new Instagram([
            'clientId'     => $_ENV['INSTAGRAM_CLIENT_ID'],
            'clientSecret' => $_ENV['INSTAGRAM_CLIENT_SECRET'],
            'redirectUri'  => $_ENV['INSTAGRAM_REDIRECT_URI'],
            'host'         => 'https://api.instagram.com'
        ]);

        if (!isset($_GET['code'])) {
            $authUrl = $provider->getAuthorizationUrl([
                'scope' => ['user_profile']
            ]);
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: ' . $authUrl);
            exit;
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            Response::error('Invalid state parameter', 400);
        } else {
            try {
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                $ownerDetails = $provider->getResourceOwner($token);
                $instagramUser = $ownerDetails->toArray();

                $user = $this->userModel->findByInstagramId($instagramUser['id']);

                if (!$user) {
                    // For Instagram, we might not have email, so use Instagram ID as identifier
                    $email = $instagramUser['username'] . '@instagram.local';

                    $user = $this->userModel->create([
                        'google_id' => null,
                        'instagram_id' => $instagramUser['id'],
                        'name' => $instagramUser['username'],
                        'email' => $email,
                        'profile_picture' => null,
                        'provider' => 'instagram'
                    ]);
                }

                $this->userModel->updateLastLogin($user['id']);

                $jwtToken = JWTHelper::generateToken($user['id'], $user['email']);
                $refreshToken = JWTHelper::generateRefreshToken($user['id']);

                // Redirect to frontend with token
                $frontendUrl = $_ENV['APP_URL'] . '/auth/callback?token=' . urlencode($jwtToken) . '&refresh=' . urlencode($refreshToken);
                header('Location: ' . $frontendUrl);
                exit;

            } catch (\Exception $e) {
                error_log('Instagram OAuth error: ' . $e->getMessage());
                Response::error('Authentication failed', 500);
            }
        }
    }

    public function logout()
    {
        // In a production app, you would invalidate the refresh token here
        Response::success([], 'Logged out successfully');
    }

    public function me()
    {
        $userId = $GLOBALS['currentUser']['userId'];
        $user = $this->userModel->findById($userId);

        if (!$user) {
            Response::notFound('User not found');
        }

        unset($user['google_id'], $user['instagram_id']);
        Response::success($user);
    }

    public function refresh()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $refreshToken = $data['refreshToken'] ?? null;

        if (!$refreshToken) {
            Response::error('Refresh token required', 400);
        }

        $payload = JWTHelper::verifyRefreshToken($refreshToken);

        if (!$payload) {
            Response::error('Invalid refresh token', 401);
        }

        $user = $this->userModel->findById($payload['userId']);

        if (!$user) {
            Response::error('User not found', 404);
        }

        $newToken = JWTHelper::generateToken($user['id'], $user['email']);
        $newRefreshToken = JWTHelper::generateRefreshToken($user['id']);

        Response::success([
            'token' => $newToken,
            'refreshToken' => $newRefreshToken
        ]);
    }
}
