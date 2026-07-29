<?php

namespace Src\Models;

use Src\Core\Database;
use PDO;

class User
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(string $name, string $email, string $password): bool
    {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }
}
