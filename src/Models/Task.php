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
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY status, position ASC, created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $data['status'] ?? 'A Fazer',
            $data['priority'] ?? 'Média',
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

    public function findById(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$id, $userId]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);
        return $task ?: null;
    }

    public function update(int $id, int $userId, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE tasks SET title = ?, description = ?, priority = ?, due_date = ? WHERE id = ? AND user_id = ?");
        return $stmt->execute([$data['title'], $data['description'] ?? null, $data['priority'] ?? 'Média', $data['due_date'] ?? null, $id, $userId]);
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }
}
