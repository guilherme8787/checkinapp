<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrCodeManager extends Controller
{

    public function generetor(string $content)
    {
        $content = str_replace('@', '><', $content);
        $content = str_replace(';', '/', $content);

        if ($content === 'coringa') {
            if (isset($_GET['content'])) {
                $content = $_GET['content'];
            }
        }

        if ($content === 'manual') {
            if (isset($_GET['content'])) {
                $content = $_GET['content'];
            }
        }

        $options = new QROptions([
            'version' => 6,
            'eccLevel' => QRCode::ECC_L,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'imageBase64' => false,
            'scale' => 20
        ]);

        $qrCode = (new QRCode($options))->render($content);
        header("Content-Type: image/png");
        header("Content-Length: " . strlen($qrCode));
        header("Cache-Control: public", true);
        header("Pragma: public", true);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT', true);
        echo $qrCode;
        exit;
    }
}
