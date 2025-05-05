<?php
session_start();

header("Content-Type: application/json");

$max_idle_time = 15 * 60; // 15 minutos

if (!isset($_SESSION['idUser'])) {
    echo json_encode(["erro" => "Sessão não iniciada"]);
    exit;
}

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $max_idle_time)) {
    session_unset();
    session_destroy();
    echo json_encode(["erro" => "Sessão expirada"]);
    exit;
}

$_SESSION['LAST_ACTIVITY'] = time(); // Atualiza tempo de atividade
echo json_encode(["status" => "ok"]);
?>
