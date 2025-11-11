<?php

namespace App\Controllers;

use App\Models\User;
use App\Utils\Response;

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function getProfile()
    {
        $userId = $GLOBALS['currentUser']['userId'];
        $stats = $this->userModel->getUserStats($userId);

        if (!$stats) {
            Response::notFound('User not found');
        }

        Response::success($stats);
    }

    public function updateProfile()
    {
        $userId = $GLOBALS['currentUser']['userId'];
        $data = json_decode(file_get_contents('php://input'), true);

        $allowedFields = ['name'];
        $updateData = [];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            Response::error('No valid fields to update', 400);
        }

        $success = $this->userModel->update($userId, $updateData);

        if ($success) {
            $user = $this->userModel->findById($userId);
            Response::success($user, 'Profile updated successfully');
        } else {
            Response::serverError('Failed to update profile');
        }
    }

    public function getCertificates()
    {
        $userId = $GLOBALS['currentUser']['userId'];
        $certificates = $this->userModel->getUserCertificates($userId);

        Response::success(['certificates' => $certificates]);
    }
}
