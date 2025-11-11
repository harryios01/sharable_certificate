<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Color\Color;

class QRCodeService
{
    private $storagePath;

    public function __construct()
    {
        $this->storagePath = __DIR__ . '/../../storage/qrcodes/';

        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }

    public function generateQRCode($data, $filename)
    {
        $qrCode = QrCode::create($data)
            ->setSize(300)
            ->setMargin(10)
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        $filepath = $this->storagePath . $filename;
        $result->saveToFile($filepath);

        return $filepath;
    }

    public function generateCertificateQRCode($certificateId)
    {
        $verificationUrl = $_ENV['APP_URL'] . '/verify/' . $certificateId;
        $filename = $certificateId . '.png';

        return $this->generateQRCode($verificationUrl, $filename);
    }

    public function getQRCodeUrl($certificateId)
    {
        return $_ENV['API_URL'] . '/storage/qrcodes/' . $certificateId . '.png';
    }
}
