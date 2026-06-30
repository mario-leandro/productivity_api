<?php

class Database
{
    protected $db_connection = null;

    private function isSequentialStringList($value)
    {
        if (!is_array($value)) {
            return false;
        }

        if (array_values($value) !== $value) {
            return false;
        }

        foreach ($value as $item) {
            if (!is_string($item)) {
                return false;
            }
        }

        return true;
    }

    private function normalizeParams($params)
    {
        $normalized = [];

        foreach ($params as $key => $value) {
            $normalized[ltrim((string)$key, ':')] = $value;
        }

        return $normalized;
    }

    private function buildWhereClause($condicao, $prefix = 'where')
    {
        if (is_array($condicao)) {
            $parts = [];
            $params = [];
            $index = 0;

            foreach ($condicao as $coluna => $valor) {
                $paramName = $prefix . '_' . preg_replace('/[^a-zA-Z0-9_]/', '_', (string)$coluna) . '_' . $index;
                $parts[] = $coluna . ' = :' . $paramName;
                $params[$paramName] = $valor;
                $index++;
            }

            return [implode(' AND ', $parts), $params];
        }

        return [$condicao, []];
    }

    function __construct()
    {
        $this->db_connection = self::getDBConnection();
    }

    private function getDBConnection()
    {
        $this->db_connection = null;

        try {
            $this->db_connection = new PDO(
                "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS
            );
            $this->db_connection->exec("set names utf8");
        } catch (PDOException $e) {
            throw new Exception("Database connection error" . $e->getMessage());
            logMsg("Connection error: " . $e->getMessage());
        }

        return $this->db_connection;
    }

    public function isConnected()
    {
        return $this->db_connection !== null;
    }

    public function get($tabela, $condicao = "", $params = [], $colunas = ['*'])
    {
        try {
            if ($this->isSequentialStringList($params) && is_string($condicao)) {
                $colunas = $params;
                $params = [];
            } elseif ($this->isSequentialStringList($params) && is_array($condicao)) {
                $colunas = $params;
                $params = [];
            }

            $colunasSql = '*';
            if ($this->isSequentialStringList($colunas) && count($colunas) > 0) {
                $colunasSql = implode(', ', $colunas);
            }

            [$whereSql, $whereParams] = $this->buildWhereClause($condicao, 'get');

            $sql = "SELECT " . $colunasSql . " FROM " . $tabela;
            if ($whereSql != "") {
                $sql .= " WHERE " . $whereSql;
            }

            $query = $this->db_connection->prepare($sql);

            $allParams = array_merge(
                $this->normalizeParams($whereParams),
                $this->normalizeParams(is_array($params) ? $params : [])
            );

            $query->execute($allParams);

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Database get error: " . $e->getMessage();
            logMsg("Database get error: " . $e->getMessage());
            return false;
        }
    }

    public function get_limit($tabela, $condicao = "", $limit = 10)
    {
        try {
            $sql = "SELECT * FROM " . $tabela;
            [$whereSql, $whereParams] = $this->buildWhereClause($condicao, 'limit');

            if ($whereSql != "") {
                $sql .= " WHERE " . $whereSql;
            }
            $sql .= " LIMIT " . intval($limit);

            $query = $this->db_connection->prepare($sql);
            $query->execute($this->normalizeParams($whereParams));

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Database get_limit error: " . $e->getMessage();
            logMsg("Database get_limit error: " . $e->getMessage());
            return false;
        }
    }

    public function insert($tabela, $dados)
    {
        try {
            $colunas = implode(", ", array_keys($dados));
            $placeholders = ":" . implode(", :", array_keys($dados));

            $sql = "INSERT INTO " . $tabela . " (" . $colunas . ") VALUES (" . $placeholders . ")";
            $query = $this->db_connection->prepare($sql);
            $query->execute($dados);

            return $this->db_connection->lastInsertId();
        } catch (Exception $e) {
            echo "Database insert error: " . $e->getMessage();
            logMsg("Database insert error: " . $e->getMessage());
            return false;
        }
    }

    public function update($tabela, $dados, $condicao, $condicaoParams = [])
    {
        try {
            $setClause = "";
            $setParams = [];
            foreach ($dados as $coluna => $valor) {
                $paramName = 'set_' . preg_replace('/[^a-zA-Z0-9_]/', '_', (string)$coluna);
                $setClause .= $coluna . " = :" . $paramName . ", ";
                $setParams[$paramName] = $valor;
            }
            $setClause = rtrim($setClause, ", ");

            [$whereSql, $whereFromArrayParams] = $this->buildWhereClause($condicao, 'upd');

            $sql = "UPDATE " . $tabela . " SET " . $setClause . " WHERE " . $whereSql;
            $query = $this->db_connection->prepare($sql);

            // Mesclar parâmetros do SET com parâmetros da condição WHERE
            $allParams = array_merge(
                $this->normalizeParams($setParams),
                $this->normalizeParams($whereFromArrayParams),
                $this->normalizeParams(is_array($condicaoParams) ? $condicaoParams : [])
            );
            $query->execute($allParams);

            return $query->rowCount();
        } catch (Exception $e) {
            echo "Database update error: " . $e->getMessage();
            logMsg("Database update error: " . $e->getMessage());
            return false;
        }
    }

    public function delete($tabela, $condicao)
    {
        try {
            [$whereSql, $whereParams] = $this->buildWhereClause($condicao, 'del');

            $sql = "DELETE FROM " . $tabela . " WHERE " . $whereSql;
            $query = $this->db_connection->prepare($sql);
            $query->execute($this->normalizeParams($whereParams));

            return $query->rowCount();
        } catch (Exception $e) {
            echo "Database delete error: " . $e->getMessage();
            logMsg("Database delete error: " . $e->getMessage());
            return false;
        }
    }

    public function sql($sql)
    {
        try {
            $query = $this->db_connection->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "Database sql error: " . $e->getMessage();
            logMsg("Database sql error: " . $e->getMessage());
            return false;
        }
    }

    public function lastInsertId()
    {
        return $this->db_connection->lastInsertId();
    }
}
