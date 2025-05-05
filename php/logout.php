<?php
session_start();
session_destroy();

header('Content-Type: application/json');
echo json_encode(["status" => "ok", "message" => "Logout realizado com sucesso"]);
exit();
