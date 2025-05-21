<?php 
header("Access-Control-Allow-Origin: *");
header('Content-Type:' . "text/plain; charset=utf-8'");

include_once 'conexao.php';

$idUser = $_GET['idRepresentante'];

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(!$conn) {
	echo '[{"erro": "Não foi possível conectar ao banco"';
	echo '}]';
 }else {
	//SQL de BUSCA LISTAGEM
	$sql = "SELECT * FROM tab_venda WHERE idRepresentante = '$idUser' ORDER BY idVenda desc";
	//$sql = "SELECT * FROM Municipio";
	
	$result = $conn->query($sql);
	$n =mysqli_num_rows($result);
 
if (!$result) {
 //Caso não haja retorno
	 echo '[{"erro": "Há algum erro com a busca. Não retorna resultados"';
	 echo '}]';
 }else if($n<1) {
 //Caso não tenha nenhum item
	 echo '[{"erro": "Não há nenhum dado cadastrado"';
	 echo '}]';
 }else {
	 
	 //Mesclar resultados em um array
	 for($i = 0; $i<$n; $i++) { 
	 	$dados[] = $result -> fetch_assoc(); 
	 	//$dados[$i]['Nome'] = utf8_encode($dados[$i]['Nome']);
		$idCliente = $dados[$i]['idCliente'];
		$idCliente = intval($idCliente);
		
		//nome do cliente
		$sqlCliente = "SELECT * FROM tab_clientes WHERE idCliente = '$idCliente'";
		$resultCliente = $conn->query($sqlCliente);
		$cliente = $resultCliente -> fetch_assoc();
		$dados[$i]['nome_cliente'] = $cliente['nome'];
		
		//nome do produto
		$idProduto = $dados[$i]['idProduto'];
		$idProduto = intval($idProduto);
		
		$sqlProduto = "SELECT * FROM tab_produto WHERE idProduto = '$idProduto'";
		$resultProduto = $conn->query($sqlProduto);
		$produto = $resultProduto -> fetch_assoc();
		$dados[$i]['nome_produto'] = $produto['nome_produto'];
		//var_dump($produto['nome_produto']);
	 } 

 	//echo json_encode($dados, JSON_PRETTY_PRINT); 
 } 
}
echo json_encode($dados, JSON_PRETTY_PRINT); 
?>
