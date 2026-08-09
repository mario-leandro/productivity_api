<?php

namespace Src\Helpers;

class Helper
{
    public static function logs($message): void
    {
        $logFile = DIR_LOGS . date("Y-m-d") . ".log";
        file_put_contents($logFile, date("Y-m-d H:i:s") . " - " . $message . "\n", FILE_APPEND);
    }

    public static function Response(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function Request(): array
    {
        return json_decode(
            file_get_contents('php://input'),
            true
        ) ?? [];
    }
}
