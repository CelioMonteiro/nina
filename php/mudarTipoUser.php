<?php include 'proteger.php'; ?>
<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include_once 'conexao.php';

$idUserApagar = $_GET['idUserApagar'];
$tipo = $_GET['tipo'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Falha na conexão: " . $conn->connect_error
    ]);
    exit;
}

$sql = "UPDATE tab_user SET tipo = $tipo WHERE idUser = '$idUserApagar'";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        "status" => "sucesso",
        "mensagem" => "Usuário desativado do sistema"
    ]);
} else {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro ao desativar: " . $conn->error
    ]);
}

$conn->close();
?>
