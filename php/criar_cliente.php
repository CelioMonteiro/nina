<?php 
header("Access-Control-Allow-Origin: *");

include_once 'conexao.php';

//$idRepresentante	= $_POST['idRepresentante'];
$nome	            = $_POST['nome'];
$senha              = md5($_POST['senha']);
$telefone           = $_POST['telefone'];
$email              = $_POST['email'];
$endereco           = $_POST['endereco'];
$cargo              = $_POST['cargo'];
$tipo               = $_POST['tipo'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO 
        tab_user
            (
            nome, 
            telefone,
            email,
            endereco,
            data_cadastro
            senha,
            cargo,
            tipo
            
            ) 
        VALUES 		
        (
            '$nome', 
            '$telefone',
            '$email',
            '$endereco',
            current_timestamp(),
            '$senha',
            '$cargo',
            '$tipo'

        )";

if ($conn->query($sql) === TRUE) {
        //include_once ('pegarultimoid.php');
        //include_once ('enviar_emailValidacao.php');
        //echo '<script>alert("venda Realizado com sucesso!")</script>';
        //echo '<script>window.location.href = "localhsot/lerin/web/signup.html";</script>';
        $flag = 1;
    } else {
        $flag = 0;
        //echo '<script>alert("Tivemos um erro! Tente novamente mais tarde")</script>';
        //echo '<script>window.location.href = "localhsot/lerin/web/signup.html";</script>';
        
        }

$conn->close();
?>