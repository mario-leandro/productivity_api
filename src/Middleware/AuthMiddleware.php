<?php

namespace Src\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Src\Core\Response;

class AuthMiddleware
{
    public static function handle(): array
    {
        $headers = getallheaders();

        $auth = $headers['Authorization'] ?? '';

        if (!str_starts_with($auth, 'Bearer ')) {
            Response::json([
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

            Response::json([
                'success' => false,
                'message' => 'Token inválido'
            ], 401);
        }
    }
}
