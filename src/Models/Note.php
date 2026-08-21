<?php

namespace Src\Models;

use Src\Core\Database;
use PDO;

class Note {
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function allByUser(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY is_pinned DESC, created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = $this->db->prepare(
            "INSERT INTO notes 
            (user_id, title, content, folder_id, is_favorite, is_pinned)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $sql->execute([
            $data['user_id'],
            $data['title'],
            $data['content'],
            $data['folder_id'],
            $data['is_favorite'],
            $data['is_pinned'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, int $userId, array $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE notes
            SET title = ?, content = ?, folder_id = ?, is_favorite = ?, is_pinned = ?
            WHERE id = ? AND user_id = ?"
        );

        return $stmt->execute([
            $data['title'],
            $data['content'],
            $data['folder_id'],
            $data['is_favorite'],
            $data['is_pinned'],
            $id,
            $userId,
        ]);
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }
}