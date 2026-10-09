<?php

require_once __DIR__ . "/../../Database/database.php";

$data = $GLOBALS["REQUEST_DATA"] ?? [];
$usuarioId = $GLOBALS["usuario_id"] ?? null;

if (!isset($data) || empty($data)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Dados da requisição ausentes."
    ]);
    exit;
}

$title = $data['title'];
$description = $data['description'];
$status = $data['status'];
$priority = $data['priority'];
$dueDate = $data['dueDate'];

if (!isset($title) || !isset($description) || !isset($status) || !isset($priority) || !isset($dueDate)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Todos os campos são obrigatórios."
    ]);
    exit;
}

$db_connection = null;

try {
    $db_connection = new Database();
    
    $query = $db_connection->sql("INSERT INTO tarefas (usuario_id, title, description, status, priority, due_date) VALUES (:usuario_id, :title, :description, :status, :priority, :due_date)");
    $sql = $query;

    $dados = [
        ":usuario_id" => $usuarioId,
        ":title" => $title,
        ":description" => $description,
        ":status" => $status,
        ":priority" => $priority,
        ":due_date" => $dueDate
    ];
    $sql->execute($dados);

    if ($sql->rowCount() === 0) {
        http_response_code(500);
        throw new Exception("Falha ao criar a tarefa.");
    } else {
        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "Tarefa criada com sucesso."
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erro ao criar tarefa: " . $e->getMessage()
    ]);
    exit;
} finally {
    if ($db_connection !== null) {
        $db_connection->closeConnection();
    }
}