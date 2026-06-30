<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responseApi(405, false, "Método não permitido");
}

$dados = json_decode(file_get_contents("php://input"), true);

if (!$dados) {
    responseApi(400, false, "Body inválido ou vazio");
}
