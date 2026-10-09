<?php

class Database
{
    protected ?PDO $db_connection = null;

    function __construct()
    {
        $this->db_connection = $this->getDBConnection();
    }

    public function getDBConnection(): PDO
    {
        $this->db_connection = null;

        try {
            $this->db_connection = new PDO(
                "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
                $_ENV['DB_USER'],
                $_ENV['DB_PASS']
            );

            $this->db_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db_connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
            return $this->db_connection;
        } catch (Exception $e) {
            throw new Exception("Erro ao conectar ao banco de dados: " . $e->getMessage());
        }
    }

    public function closeConnection(): void
    {
        $this->db_connection = null;
    }

    public function sql(string $query): PDOStatement
    {
        return $this->getDBConnection()->prepare($query);
    }
}
