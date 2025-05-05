<?php include 'proteger.php'; ?>
<?php 
header("Access-Control-Allow-Origin: *");

include_once 'conexao.php';

$idCliente	 = $_POST['idCliente'];
$idProduto	 = $_POST['idProduto'];
$idUSer      = $_POST['idRepresentante'];
$quantidade  = $_POST['quantidade'];
$descricao	 = $_POST['descricao'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO 
        tab_venda 
            (
            idCliente,
            idRepresentante,
            idProduto, 
            quantidade,
            descricao,
            data_cadastro
            ) 
        VALUES 		
        (
            '$idCliente',
            '$idUSer',
            '$idProduto', 
            '$quantidade',
            '$descricao',
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
