<?php

namespace Src\Controllers;

use Src\Core\Request;
use Src\Core\Response;
use Src\Middleware\AuthMiddleware;
use Src\Models\Task;

class TaskController
{
    public function index(): void
    {
        $user = AuthMiddleware::handle();

        $taskModel = new Task();

        $tasks = $taskModel->allByUser((int) $user['sub']);

        Response::json($tasks);
    }

    public function store(): void
    {
        $user = AuthMiddleware::handle();

        $data = Request::json();

        if (empty($data['title'])) {
            Response::json([
                'success' => false,
                'message' => 'Título é obrigatório'
            ], 422);
        }

        $taskModel = new Task();

        $id = $taskModel->create([
            ...$data,
            'user_id' => (int) $user['sub']
        ]);

        Response::json([
            'success' => true,
            'message' => 'Tarefa criada com sucesso',
            'id' => $id
        ], 201);
    }

    public function updateStatus(string $id): void
    {
        $user = AuthMiddleware::handle();

        $data = Request::json();

        $allowed = ['todo', 'in_progress', 'done'];

        if (!in_array($data['status'] ?? '', $allowed)) {
            Response::json([
                'message' => 'Status inválido'
            ], 422);
        }

        $taskModel = new Task();

        $taskModel->updateStatusAndPosition(
            (int) $id,
            (int) $user['sub'],
            $data['status'],
            (int) ($data['position'] ?? 0)
        );

        Response::json([
            'message' => 'Tarefa atualizada'
        ]);
    }
}
