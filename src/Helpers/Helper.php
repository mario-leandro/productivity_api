<?php

namespace Src\Helpers;

class Helper
{
    public static function logs($message): void
    {
        $logFile = __DIR__ . "/../storage/logs/" . date("Y-m-d") . ".txt";
        file_put_contents($logFile, date("Y-m-d H:i:s") . " - " . $message . "\n", FILE_APPEND);
    }

    public static function Response(array $data, int $status = 200): array
    {
        return [
            'status' => $status,
            'data' => $data
        ];
    }

    public static function Request(): array
    {
        $raw = file_get_contents('php://input');

        if ($raw === '') {
            return [
                'success' => false,
                'message' => 'Corpo da requisição vazio'
            ];
        }

        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            self::Response([
                'success' => false,
                'message' => 'JSON inválido: ' . json_last_error_msg()
            ], 400);

            exit;
        }

        if (!is_array($data)) {
            self::Response([
                'success' => false,
                'message' => 'O corpo da requisição deve ser um objeto JSON'
            ], 400);

            exit;
        }

        return $data;
    }
}
