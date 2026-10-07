<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../home/walletmotion/");
$dotenv->load();

define('DB_HOST', $_ENV["DB_HOST"]);
define('DB_USER', $_ENV["DB_USER"]);
define('DB_PASS', $_ENV["DB_PASS"]);
define('DB_NAME', $_ENV["DB_NAME"]);

define("DIR_LOGS", __DIR__ . "/logs/");

define("CHAVE_JWT", $_ENV["JWT_SECRET"]);
define("JWT_ISS", $_ENV["JWT_ISS"]);
define("JWT_AUD", $_ENV["JWT_AUD"]);

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
            "create" => __DIR__ . "/api/tasks/adicionarTask.php",
            "list" => __DIR__ . "/api/tasks/listarTasks.php",
            "delete" => __DIR__ . "/api/tasks/delete.php"
        ],
        "check_users" => array(
            // "update_user" => __DIR__ . "/api/usuarios/atualizarUsuario.php",
            "list_users" => __DIR__ . "/api/usuarios/listarUsuarios.php",
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