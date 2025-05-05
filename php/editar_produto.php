<?php 
header("Access-Control-Allow-Origin: *");
include_once 'conexao.php';

$idProduto      = $_POST['idProduto'];
$nome_produto   = $_POST['nome_produto'];
$descricao      = $_POST['descricao']; 
$preco	        = $_POST['preco'];
$quantidade     = $_POST['quantidade'];

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE tab_produto
            SET nome_produto = '$nome_produto', descricao = '$descricao', preco = '$preco', quantidade = '$quantidade'
            WHERE idProduto = '$idProduto'";

if ($conn->query($sql) === TRUE) {
    echo 'Update Realizado com sucesso';
}    
$conn->close();
		
?>