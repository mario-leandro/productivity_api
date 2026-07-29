<?php

namespace Src\Models;

use Src\Core\Database;
use PDO;

class Task
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function allByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM tasks
             WHERE user_id = ?
             ORDER BY position ASC, created_at DESC"
        );

        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tasks
            (user_id, title, description, status, priority, due_date, position)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $data['user_id'],
            $data['title'],
            $data['description'] ?? null,
            $data['status'] ?? 'todo',
            $data['priority'] ?? 'medium',
            $data['due_date'] ?? null,
            $data['position'] ?? 0,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function updateStatusAndPosition(
        int $id,
        int $userId,
        string $status,
        int $position
    ): bool {

        $stmt = $this->db->prepare(
            "UPDATE tasks
         SET status = ?, position = ?
         WHERE id = ? AND user_id = ?"
        );

        return $stmt->execute([
            $status,
            $position,
            $id,
            $userId
        ]);
    }
}
