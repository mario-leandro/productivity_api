<?php

namespace Src\Controllers;

use Src\Helpers\Helper;
use Src\Middleware\AuthMiddleware;
use Src\Models\Task;

class TaskController
{
    public function index(): void
    {
        $user = AuthMiddleware::handle();

        $taskModel = new Task();

        $tasks = $taskModel->allByUser((int) $user['sub']);

        Helper::Response($tasks);
    }

    public function store(): void
    {
        $user = AuthMiddleware::handle();
        $data = Helper::Request();
        if (empty($data['title'])) {
            Helper::Response(['success' => false, 'message' => 'Título é obrigatório'], 422);
        }
        $taskModel = new Task();
        $id = $taskModel->create([...$data, 'user_id' => (int) $user['sub']]);
        $task = $taskModel->findById($id, (int) $user['sub']);
        Helper::Response(['success' => true, 'message' => 'Tarefa criada com sucesso', 'data' => $task], 201);
    }

    public function update(): void
    {
        $user = AuthMiddleware::handle();
        $data = Helper::Request();
        $id = Helper::Request()['id'] ?? null;

        if (empty($data['title'])) {
            Helper::Response(['success' => false, 'message' => 'Título é obrigatório'], 422);
        }

        $taskModel = new Task();
        $taskModel->update((int) $id, (int) $user['sub'], $data);

        Helper::Response(['success' => true, 'message' => 'Tarefa atualizada com sucesso']);
    }

    public function updateStatus(string $id): void
    {
        $user = AuthMiddleware::handle();

        $data = Helper::Request();

        $allowed = ['todo', 'in_progress', 'done'];

        if (!in_array($data['status'] ?? '', $allowed)) {
            Helper::Response([
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

        Helper::Response([
            'message' => 'Tarefa atualizada'
        ]);
    }

    public function destroy(): void
    {
        $user = AuthMiddleware::handle();
        $id = Helper::Request()['id'] ?? null;

        $taskModel = new Task();
        $taskModel->delete((int) $id, (int) $user['sub']);

        Helper::Response(['success' => true, 'message' => 'Tarefa deletada com sucesso']);
    }
}
