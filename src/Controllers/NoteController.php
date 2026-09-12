<?php

namespace Src\Controllers;

use Src\Helpers\Helper;
use Src\Middleware\AuthMiddleware;

class NoteController
{
    public function index(): void
    {
        $userId = AuthMiddleware::getUserId();

        $noteModel = new \Src\Models\Note();
        $notes = $noteModel->allByUser($userId);

        Helper::Response([
            'success' => true,
            'data' => $notes
        ]);
    }

    public function create(): void
    {
        $data = Helper::Request();
        $userId = AuthMiddleware::getUserId();

        $noteModel = new \Src\Models\Note();
        
        $noteModel->create(array_merge($data, ['user_id' => $userId]));

        Helper::Response([
            'success' => true,
            'message' => 'Nota criada com sucesso'
        ], 201);
    }

    public function update(int $id): void
    {
        $data = Helper::Request();
        $userId = AuthMiddleware::getUserId();

        $noteModel = new \Src\Models\Note();
        $updated = $noteModel->update($id, $userId, $data);

        if ($updated) {
            Helper::Response([
                'success' => true,
                'message' => 'Nota atualizada com sucesso'
            ]);
        } else {
            Helper::Response([
                'success' => false,
                'message' => 'Falha ao atualizar a nota'
            ], 400);
        }
    }

    public function create_folder(): void
    {
        $data = Helper::Request();
        $userId = AuthMiddleware::getUserId();

        $noteModel = new \Src\Models\Note();
        $noteModel->create_folder(array_merge($data, ['user_id' => $userId]));

        Helper::Response([
            'success' => true,
            'message' => 'Pasta criada com sucesso'
        ], 201);
    }

    public function update_folder(int $id): void
    {
        $data = Helper::Request();
        $userId = AuthMiddleware::getUserId();

        $noteModel = new \Src\Models\Note();
        $updated = $noteModel->update_folder($id, $userId, $data);

        if ($updated) {
            Helper::Response([
                'success' => true,
                'message' => 'Pasta atualizada com sucesso'
            ]);
        } else {
            Helper::Response([
                'success' => false,
                'message' => 'Falha ao atualizar a pasta'
            ], 400);
        }
    }

    public function get_folders(): void
    {
        $userId = AuthMiddleware::getUserId();

        $noteModel = new \Src\Models\Note();
        $folders = $noteModel->get_folders($userId);

        Helper::Response([
            'success' => true,
            'data' => $folders
        ]);
    }

    public function delete_folder(int $id): void
    {
        $userId = AuthMiddleware::getUserId();

        $noteModel = new \Src\Models\Note();
        $deleted = $noteModel->delete_folder($id, $userId);

        if ($deleted) {
            Helper::Response([
                'success' => true,
                'message' => 'Pasta deletada com sucesso'
            ]);
        } else {
            Helper::Response([
                'success' => false,
                'message' => 'Falha ao deletar a pasta'
            ], 400);
        }
    }
}