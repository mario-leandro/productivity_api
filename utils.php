<?php

function responseApi(int $status, bool $success, string $msg)
{
    http_response_code($status);
    echo json_encode([
        "success" => $success,
        "msg" => $msg
    ]);
}

function logMsg($msg)
{
    $arquivo_log = DIR_LOGS . "log_" . date("Y-m-d") . ".txt";
    $data_hora = date("Y-m-d H:i:s");
    $metodo = $_SERVER['REQUEST_METHOD'] ?? 'N/A';
    $rota = $_SERVER['REQUEST_URI'] ?? 'Rota não disponível';

    if (is_array($msg) || is_object($msg)) {
        $msg = print_r($msg, true);
    }

    $linha_log = "[$data_hora] [$metodo] [$rota] - $msg" . PHP_EOL;
    file_put_contents($arquivo_log, $linha_log, FILE_APPEND);
}
