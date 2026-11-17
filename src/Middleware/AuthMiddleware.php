<?php

namespace Src\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Src\Helpers\Helper;

class AuthMiddleware
{
    public static function handle(): array
    {
        $headers = getallheaders();

        $auth = $headers['Authorization'] ?? '';

        if (!str_starts_with($auth, 'Bearer ')) {
            Helper::Response([
                'success' => false,
                'message' => 'Token não informado'
            ], 401);
        }

        $token = str_replace('Bearer ', '', $auth);

        try {
            $decoded = JWT::decode(
                $token,
                new Key($_ENV['JWT_SECRET'], 'HS256')
            );

            return (array) $decoded;
        } catch (\Exception $e) {

            Helper::Response([
                'success' => false,
                'message' => 'Token inválido'
            ], 401);
        }
    }

    public static function getUserId(): int
    {
        $decoded = self::handle();

        if (!isset($decoded['sub'])) {
            Helper::Response([
                'success' => false,
                'message' => 'Token não possui identificação do usuário'
            ], 401);
        }

        return (int) $decoded['sub'];
    }
}
