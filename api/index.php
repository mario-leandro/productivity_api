<?php

require_once __DIR__ . "/commom.php";

headers();

global $rotasPublicasZz;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método não permitido"]);
    logMsg("Método não permitido: " . $_SERVER['REQUEST_METHOD'] . " em " . $_SERVER['REQUEST_URI']);
    exit;
}

$dados = json_decode(file_get_contents("php://input"), true);

if (!$dados) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Body inválido ou vazio"
    ]);
    exit;
}

$type = $dados["type"];
$action = $dados["action"];
$data = $dados["data"] ?? [];

// var_dump($type);
// var_dump($action);

if (!$type || !$action) {
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Rota não encontrada"
    ]);
    exit;
}

$routes = pathRoutes($type, $action);

if (!$routes) {
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Rota não encontrada"
    ]);
    exit;
}

$rotaAtual = $type . "/" . $action;

if (!in_array($rotaAtual, $rotasPublicas)) {
    $usuarioId = autenticar();
    $GLOBALS["usuario_id"] = $usuarioId;
}

$GLOBALS["REQUEST_DATA"] = $data;
// var_dump($GLOBALS["REQUEST_DATA"] = $data);

// var_dump([
//     "type" => $type,
//     "action" => $action,
//     "rotaAtual" => $type . "/" . $action,
//     "routes" => $routes,
//     "exists" => file_exists($routes)
// ]);
// exit;

// echo "entrou aqui";

require_once $routes;

logMsg([
    "type" => $type,
    "action" => $action,
    "usuario" => $GLOBALS["usuario_id"] ?? null
]);