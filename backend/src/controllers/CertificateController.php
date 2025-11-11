<?php

namespace App\Controllers;

use App\Models\Certificate;
use App\Models\Assessment;
use App\Models\User;
use App\Services\QRCodeService;
use App\Services\PDFService;
use App\Utils\Response;
use App\Utils\UUIDGenerator;

class CertificateController
{
    private $certificateModel;
    private $assessmentModel;
    private $userModel;
    private $qrService;
    private $pdfService;

    public function __construct()
    {
        $this->certificateModel = new Certificate();
        $this->assessmentModel = new Assessment();
        $this->userModel = new User();
        $this->qrService = new QRCodeService();
        $this->pdfService = new PDFService();
    }

    public function generate()
    {
        $userId = $GLOBALS['currentUser']['userId'];
        $data = json_decode(file_get_contents('php://input'), true);

        $assessmentId = $data['assessmentId'] ?? null;

        if (!$assessmentId) {
            Response::error('Assessment ID required', 400);
        }

        $assessment = $this->assessmentModel->findById($assessmentId);

        if (!$assessment) {
            Response::notFound('Assessment not found');
        }

        if ($assessment['status'] !== 'completed') {
            Response::error('Assessment not completed', 400);
        }

        // Check if user owns this assessment
        if ($assessment['user_id'] != $userId) {
            Response::forbidden('Not authorized');
        }

        // Check if certificate already exists
        $existingCert = $this->certificateModel->findByUserId($userId, ['assessment_id' => $assessmentId]);
        if (!empty($existingCert)) {
            Response::success($existingCert[0], 'Certificate already exists');
        }

        $user = $this->userModel->findById($userId);

        // Generate unique certificate ID
        $certificateId = UUIDGenerator::generate();
        $verificationUrl = $_ENV['APP_URL'] . '/verify/' . $certificateId;

        // Generate QR Code
        $qrCodePath = $this->qrService->generateCertificateQRCode($certificateId);
        $qrCodeUrl = $this->qrService->getQRCodeUrl($certificateId);

        // Generate PDF Certificate
        $pdfData = [
            'certificate_id' => $certificateId,
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'profile_picture' => $user['profile_picture'],
            'technology' => $assessment['technology'],
            'date' => date('F d, Y'),
            'qr_code_path' => $qrCodePath
        ];

        $pdfPath = $this->pdfService->generateCertificate($pdfData);
        $pdfUrl = $_ENV['API_URL'] . '/storage/certificates/' . basename($pdfPath);

        // Save certificate to database
        $certificate = $this->certificateModel->create([
            'certificate_id' => $certificateId,
            'user_id' => $userId,
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'profile_picture' => $user['profile_picture'],
            'technology' => $assessment['technology'],
            'assessment_id' => $assessmentId,
            'pdf_url' => $pdfUrl,
            'image_url' => null,
            'qr_code_url' => $qrCodeUrl,
            'verification_url' => $verificationUrl
        ]);

        // Update user's total assessments
        $this->userModel->update($userId, [
            'total_assessments' => $user['total_assessments'] + 1
        ]);

        Response::success($certificate, 'Certificate generated successfully', 201);
    }

    public function download()
    {
        $userId = $GLOBALS['currentUser']['userId'];
        $certificateId = $_GET['certificateId'] ?? null;

        if (!$certificateId) {
            Response::error('Certificate ID required', 400);
        }

        $certificate = $this->certificateModel->findByCertificateId($certificateId);

        if (!$certificate) {
            Response::notFound('Certificate not found');
        }

        if ($certificate['user_id'] != $userId) {
            Response::forbidden('Not authorized');
        }

        $pdfPath = str_replace($_ENV['API_URL'] . '/storage/certificates/', '', $certificate['pdf_url']);
        $fullPath = __DIR__ . '/../../storage/certificates/' . $pdfPath;

        if (!file_exists($fullPath)) {
            Response::notFound('Certificate file not found');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($fullPath) . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }

    public function getUserCertificates()
    {
        $userId = $GLOBALS['currentUser']['userId'];
        $technology = $_GET['technology'] ?? null;

        $filters = [];
        if ($technology) {
            $filters['technology'] = $technology;
        }

        $certificates = $this->certificateModel->findByUserId($userId, $filters);

        Response::success(['certificates' => $certificates]);
    }

    public function delete()
    {
        $userId = $GLOBALS['currentUser']['userId'];
        $certificateId = $_GET['certificateId'] ?? null;

        if (!$certificateId) {
            Response::error('Certificate ID required', 400);
        }

        $certificate = $this->certificateModel->findByCertificateId($certificateId);

        if (!$certificate) {
            Response::notFound('Certificate not found');
        }

        if ($certificate['user_id'] != $userId) {
            Response::forbidden('Not authorized');
        }

        $success = $this->certificateModel->delete($certificate['id'], $userId);

        if ($success) {
            Response::success([], 'Certificate deleted successfully');
        } else {
            Response::serverError('Failed to delete certificate');
        }
    }

    public function share()
    {
        $certificateId = $_GET['certificateId'] ?? null;

        if (!$certificateId) {
            Response::error('Certificate ID required', 400);
        }

        $certificate = $this->certificateModel->findByCertificateId($certificateId);

        if (!$certificate) {
            Response::notFound('Certificate not found');
        }

        $this->certificateModel->incrementShareCount($certificateId);

        Response::success(['shared' => true]);
    }
}
