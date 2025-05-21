<?php 
include 'proteger.php';
header("Access-Control-Allow-Origin: *");
include_once 'conexao.php';
$id = $_POST['idUserEdit'];

$funcionrio	                    = $_POST['funcionrio'];
$vnculo	                        = $_POST['vnculo'];
$cargo	                        = $_POST['cargo'];
$equipamento	                = $_POST['equipamento'];
$matrcula	                    = $_POST['matrcula'];
$telefones	                    = $_POST['telefones'];
$data_admisso                   = $_POST['data_admisso'];
$turno	                        = $_POST['turno'];
$endereo_bairro	                = $_POST['endereo_bairro'];
$data_incio_e_fim_aviso_prvio	= $_POST['data_incio_e_fim_aviso_prvio'];
$email                          = $_POST['email'];
$cpf                            = $_POST['cpf'];
$telefones1                     = $_POST['telefones1'];
$obs                            = $_POST['obs'];

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// Prepared Statement seguro
$stmt = $conn->prepare("UPDATE geral_nova SET 
    vnculo = ?, 
    cargo = ?, 
    equipamento = ?, 
    matrcula = ?, 
    telefones = ?, 
    data_admisso = ?, 
    turno = ?, 
    endereo_bairro = ?, 
    data_incio_e_fim_aviso_prvio = ?, 
    email = ?, 
    cpf = ?, 
    telefones1 = ?, 
    obs = ?
    WHERE funcionrio = ?");

$stmt->bind_param("ssssssssssssss",
    $vnculo, $cargo, $equipamento, $matrcula, $telefones,
    $data_admisso, $turno, $endereo_bairro, $data_incio_e_fim_aviso_prvio,
    $email, $cpf, $telefones1, $obs, $funcionrio);

if ($stmt->execute()) {
    echo 'Update Realizado com sucesso';
} else {
    echo 'Erro: ' . $stmt->error;
}


$stmt->close();
$conn->close();

echo $_POST;

?>
