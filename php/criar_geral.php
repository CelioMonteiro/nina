<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'conexao.php';

// Captura os dados do POST
$funcionario = $_POST['funcionrio'] ?? '';
$vinculo = $_POST['vnculo'] ?? '';
$cargo = $_POST['cargo'] ?? '';
$equipamento = $_POST['equipamento'] ?? '';
$matricula = $_POST['matrcula'] ?? '';
$telefones = $_POST['telefones'] ?? '';
$data_admissao = $_POST['data_admissao'] ?? '';
$turno = $_POST['turno'] ?? '';
$endereco_bairro = $_POST['endereo_bairro'] ?? '';
$data_incio_e_fim_aviso_prvio = $_POST['data_incio_e_fim_aviso_prvio'] ?? '';
$email = $_POST['email'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$telefones1 = $_POST['telefones1'] ?? '';
$obs = $_POST['obs'] ?? '';

// Conexão
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro de conexão: " . $conn->connect_error]);
    exit;
}

// Query
$sql = "INSERT INTO geral_nova (
    funcionrio, vnculo, cargo, equipamento, matrcula, telefones, data_admisso,
    turno, endereo_bairro, data_incio_e_fim_aviso_prvio, email, cpf, telefones1, obs
) VALUES (
    '$funcionario', '$vinculo', '$cargo', '$equipamento', '$matricula', '$telefones',
    '$data_admissao', '$turno', '$endereco_bairro', '$data_incio_e_fim_aviso_prvio',
    '$email', '$cpf', '$telefones1', '$obs'
)";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["mensagem" => "Cadastro realizado com sucesso!"]);
} else {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao inserir: " . $conn->error]);
}

$conn->close();
?>
