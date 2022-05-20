<?php

namespace App\Http\Controllers;

class DownloadUtil
{

    public static function downloadData($data, string $fileName = 'untitled.txt', string $mime = 'text/plain')
    {
        header('Content-Type: ' . $mime);
        header('X-Content-Type-Options: nosniff');
        header('Content-Length: ' . strlen($data));
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Connection: close');
        while (ob_get_level()) {
            ob_end_clean();
        }
        echo $data;
        exit;
    }

}
