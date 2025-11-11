<?php

namespace App\Controllers;

use App\Models\Certificate;
use App\Utils\Response;

class VerificationController
{
    private $certificateModel;

    public function __construct()
    {
        $this->certificateModel = new Certificate();
    }

    public function verify()
    {
        $certificateId = $_GET['certificateId'] ?? null;

        if (!$certificateId) {
            Response::error('Certificate ID required', 400);
        }

        $certificate = $this->certificateModel->findByCertificateId($certificateId);

        if (!$certificate) {
            Response::json([
                'valid' => false,
                'message' => 'Certificate not found'
            ], 404);
        }

        // Increment verification count
        $this->certificateModel->incrementVerificationCount($certificateId);

        Response::json([
            'valid' => $certificate['is_valid'],
            'certificate' => [
                'certificateId' => $certificate['certificate_id'],
                'userName' => $certificate['user_name'],
                'profilePicture' => $certificate['profile_picture'],
                'technology' => $certificate['technology'],
                'issuedDate' => $certificate['issued_date'],
                'certificateUrl' => $certificate['pdf_url'],
                'verificationCount' => $certificate['verification_count'] + 1
            ]
        ]);
    }
}
