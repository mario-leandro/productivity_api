<?php

namespace Src\Controllers;

use Src\Models\User;
use Src\Core\Response;
use Firebase\JWT\JWT;
use Src\Middleware\AuthMiddleware;
use Src\Core\Request;

class AuthController
{
    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (
            empty($data['name']) ||
            empty($data['email']) ||
            empty($data['password'])
        ) {
            Response::json([
                'message' => 'Campos obrigatórios'
            ], 422);
        }

        $userModel = new User();

        $existing = $userModel->findByEmail($data['email']);

        if ($existing) {
            Response::json([
                'message' => 'E-mail já cadastrado'
            ], 409);
        }

        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

        $userModel->create(
            $data['name'],
            $data['email'],
            $passwordHash
        );

        Response::json([
            'message' => 'Usuário criado com sucesso'
        ], 201);
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new User();

        $user = $userModel->findByEmail($data['email'] ?? '');

        if (!$user || !password_verify($data['password'] ?? '', $user['password'])) {
            Response::json([
                'message' => 'Credenciais inválidas'
            ], 401);
        }

        $payload = [
            'sub' => $user['id'],
            'email' => $user['email'],
            'exp' => time() + 60 * 60 * 24 * 30
        ];

        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        Response::json([
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ]);
    }

    public function me(): void
    {
        $payload = AuthMiddleware::handle();

        Response::json([
            'id' => $payload['sub'],
            'email' => $payload['email']
        ]);
    }
}
