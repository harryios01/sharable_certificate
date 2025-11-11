<?php

namespace App\Services;

use Mpdf\Mpdf;

class PDFService
{
    private $storagePath;

    public function __construct()
    {
        $this->storagePath = __DIR__ . '/../../storage/certificates/';

        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }

    public function generateCertificate($data)
    {
        $html = $this->getCertificateTemplate($data);

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L', // Landscape
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
        ]);

        $mpdf->WriteHTML($html);

        $filename = 'Certificate_' . $data['technology'] . '_' . $data['certificate_id'] . '.pdf';
        $filepath = $this->storagePath . $filename;

        $mpdf->Output($filepath, 'F');

        return $filepath;
    }

    private function getCertificateTemplate($data)
    {
        $profileImg = $data['profile_picture'] ? $this->getBase64Image($data['profile_picture']) : '';
        $qrCodeImg = $this->getBase64Image($data['qr_code_path']);

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 40px;
            background: linear-gradient(135deg, #f5f5f5 0%, #fefcf3 100%);
        }
        .certificate {
            border: 15px solid #c9a961;
            padding: 40px;
            background: white;
            position: relative;
            min-height: 500px;
        }
        .inner-border {
            border: 2px solid #c9a961;
            padding: 30px;
            min-height: 450px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 48px;
            color: #2c3e50;
            margin: 0;
            font-weight: bold;
        }
        .subtitle {
            font-size: 20px;
            color: #c9a961;
            letter-spacing: 4px;
            margin-top: 10px;
        }
        .profile-section {
            text-align: center;
            margin: 30px 0;
        }
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #c9a961;
        }
        .recipient-name {
            font-size: 36px;
            color: #2c3e50;
            margin: 20px 0;
            font-family: 'Brush Script MT', cursive;
        }
        .body-text {
            text-align: center;
            font-size: 18px;
            color: #555;
            line-height: 1.6;
            margin: 20px auto;
            max-width: 600px;
        }
        .technology {
            font-size: 28px;
            color: #c9a961;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            display: table;
            width: 100%;
        }
        .footer-left, .footer-center, .footer-right {
            display: table-cell;
            vertical-align: bottom;
        }
        .footer-left, .footer-right {
            width: 35%;
        }
        .footer-center {
            width: 30%;
            text-align: center;
        }
        .date {
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }
        .cert-id {
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }
        .qr-section {
            text-align: center;
        }
        .qr-code {
            width: 80px;
            height: 80px;
        }
        .qr-text {
            font-size: 10px;
            color: #999;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="inner-border">
            <div class="header">
                <h1 class="title">Certificate</h1>
                <p class="subtitle">OF COMPLETION</p>
            </div>

            <div class="profile-section">
                {profileImgHtml}
                <h2 class="recipient-name">{$data['user_name']}</h2>
                <p class="body-text">
                    Has successfully completed the <span class="technology">{$data['technology']}</span> Technology Assessment
                </p>
                <p class="body-text">
                    This certificate is presented in recognition of exceptional dedication and outstanding performance
                    in completing the technology evaluation program.
                </p>
            </div>

            <div class="footer">
                <div class="footer-left"></div>
                <div class="footer-center">
                    <div class="qr-section">
                        <img src="{$qrCodeImg}" class="qr-code" alt="QR Code" />
                        <p class="qr-text">Scan to verify</p>
                    </div>
                </div>
                <div class="footer-right" style="text-align: right;">
                    <p class="date">{$data['date']}</p>
                    <p class="cert-id">Certificate ID: {$data['certificate_id']}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;

        $profileImgHtml = $profileImg ? "<img src='{$profileImg}' class='profile-img' alt='Profile' />" : '';
        return str_replace('{profileImgHtml}', $profileImgHtml, $html);
    }

    private function getBase64Image($url)
    {
        if (file_exists($url)) {
            $imageData = file_get_contents($url);
            $base64 = base64_encode($imageData);
            $mimeType = mime_content_type($url);
            return "data:{$mimeType};base64,{$base64}";
        }
        return '';
    }

    public function getCertificateUrl($filename)
    {
        return $_ENV['API_URL'] . '/storage/certificates/' . $filename;
    }
}
