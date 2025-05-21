<?php 
header("Access-Control-Allow-Origin: *");
include_once 'conexao.php';


$idCliente  = $_POST['idCliente'];
$nome	    = $_POST['nome'];
$telefone   = $_POST['telefone']; 
$email	    = $_POST['email'];
$endereco   = $_POST['endereco'];

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE tab_clientes
            SET nome = '$nome', telefone = '$telefone', email = '$email', endereco = '$endereco'
            WHERE idCliente = '$idCliente'";

if ($conn->query($sql) === TRUE) {
    echo 'Update Realizado com sucesso';
}    
$conn->close();
		
?>