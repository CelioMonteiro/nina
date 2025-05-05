<?php 
header("Access-Control-Allow-Origin: *");

include_once 'conexao.php';

$idRepresentante	= $_POST['idRepresentante'];
$nome_produto	    = $_POST['nome_produto'];
$descricao          = $_POST['descricao'];
$preco              = $_POST['preco'];
$quantidade           = $_POST['quantidade'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO 
        tab_produto
            (
			idRepresentante,
            nome_produto, 
            descricao,
            preco,
            quantidade,
            data_cadastro
            ) 
        VALUES 		
        (
            '$idRepresentante',
            '$nome_produto', 
            '$descricao',
            '$preco',
            '$quantidade',
            current_timestamp()
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