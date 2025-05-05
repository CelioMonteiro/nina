<?php include 'proteger.php'; ?>
<?php 
header("Access-Control-Allow-Origin: *");
include_once 'conexao.php';

$idUser             = $_POST['idUserEdit'];
$nome	            = $_POST['nome'];
//$senha              = md5($_POST['senha']);
$telefone           = $_POST['telefone'];
$email              = $_POST['email'];
$endereco           = $_POST['endereco'];
$cargo              = $_POST['cargo'];
$tipo               = $_POST['tipo'];


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE tab_user
            SET 
                tipo            = '$tipo',
                nome            = '$nome', 
                email           = '$email', 
                telefone        = '$telefone',
                endereco        = '$endereco',
                cargo           = '$cargo'
                
            WHERE 
                idUser = '$idUser'";

if ($conn->query($sql) === TRUE) {
    echo 'Update Realizado com sucesso';
}else {
    echo 'Erro: ' . $conn->error;
}    
$conn->close();
		
?>