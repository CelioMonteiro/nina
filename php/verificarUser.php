<?php
session_start();

// Define o cabeçalho para retorno em JSON
header('Content-Type: application/json');

// Verifica se a variável de sessão 'logado' está definida e é igual a 1
if (isset($_SESSION['logado']) && $_SESSION['logado'] === 1) {
    echo json_encode(['status' => 'logado']);
} else {
    // Caso não esteja logado, retorna erro 401 (não autorizado)
    http_response_code(401);
    echo json_encode(['status' => 'nao_logado']);
}
?>
