<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/");
$dotenv->load();

define("DIR_LOGS", __DIR__ . "/logs/");

$rotasPublicas = [
    "auth/login",
    "auth/register",
    "auth/refresh_token"
];

function pathRoutes($type, $action)
{
    $routes = array(
        "auth" => array(
            "login" => __DIR__ . "/api/auth/login.php",
            "register" => __DIR__ . "/api/auth/register.php",
            "logout" => __DIR__ . "/api/auth/logout.php",
            "refresh_token" => __DIR__ . "/api/refresh/refreshToken.php",
            "me" => __DIR__ . "/api/auth/me.php"
        ),
        "tasks" => [
            "create" => __DIR__ . "/api/tasks/criar_tarefa.php",
            "list" => __DIR__ . "/api/tasks/listar_tarefas.php",
            "delete" => __DIR__ . "/api/tasks/deletar_tarefa.php",
            "edit" => __DIR__ . "/api/tasks/editar_tarefa.php"
        ],
        "check_users" => array(
            // "update_user" => __DIR__ . "/api/usuarios/atualizarUsuario.php",
            "list_users" => __DIR__ . "/api/usuarios/listar_usuarios.php",
            // "delete_user" => __DIR__ . "/api/usuarios/excluirUsuario.php"
        ),
        "notes" => [
            "create" => __DIR__ . "/api/notes/adicionarNota.php",
            "list" => __DIR__ . "/api/notes/listarNotas.php"
        ],
        "note_folders" => [
            "create" => __DIR__ . "/api/note_folders/adicionarPastaNota.php",
            "list" => __DIR__ . "/api/note_folders/listarPastasNota.php"
        ],
    );

    if (isset($routes[$type][$action])) {
        return $routes[$type][$action];
    }

    return null;
}