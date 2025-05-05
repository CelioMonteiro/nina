<?php include 'proteger.php'; ?>
<?php 
header("Access-Control-Allow-Origin: *");
header('Content-Type:' . "text/plain");

include_once 'conexao.php';

$idUser = $_GET['idUser'];

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
	$sql = "SELECT * FROM geral_nova LIMIT 250";
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
	 } 

 	echo json_encode($dados, JSON_PRETTY_PRINT); 
 } 
}
?>
