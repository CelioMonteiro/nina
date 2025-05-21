<?php include 'proteger.php'; ?>
<?php 
header("Access-Control-Allow-Origin: *");

include_once 'conexao.php';

$idRepresentante = $_POST['idRepresentante'];
$primeiro_nome	= $_POST['primeiro_nome'];
$segundo_nome	= $_POST['segundo_nome'];

$email			= $_POST['email'];
$telefone		= $_POST['telefone'];
$tipo			= $_POST['tipo'];
$senha			= $_POST['senha'];
$confimra_senha = $_POST['confirma_senha'];

$termo  = 1;

$conn = new mysqli($servername, $username, $password, $dbname);
$sqlEmail = "SELECT * FROM tab_user WHERE email='$email'";
$resultEmail = $conn->query($sqlEmail);

if ($resultEmail->num_rows > 0) {
	$flag = 2;
	echo $flag;
	echo '<script>alert("Email já existente em nosso banco de dados! por favor use outro email válido.")</script>';
}else{
	if($termo == 1){
	$senha = md5($senha);
	$ativo = 0;
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$sql = "INSERT INTO 
			tab_user 
				(
				idRepresentante,
				tipo,
				primeiro_nome,
				segundo_nome,
				email, 
				telefone,
				senha,
                data_cadastro
				) 
			VALUES 		
			(
				'$idRepresentante',	
				'$tipo',
				'$primeiro_nome',
				'$segundo_nome',
				'$email',
				'$telefone', 
				'$senha',
				current_timestamp()
			)";

	if ($conn->query($sql) === TRUE) {
			//include_once ('pegarultimoid.php');
			//include_once ('enviar_emailValidacao.php');
			echo '<script>alert("cadastro Realizado com sucesso!")</script>';
			echo '<script>window.location.href = "localhsot/lerin/web/signup.html";</script>';
			$flag = 1;
	    } else {
	    	$flag = 0;
			echo '<script>alert("Tivemos um erro! Tente novamente mais tarde")</script>';
			echo '<script>window.location.href = "localhsot/lerin/web/signup.html";</script>';
			
			}
	$conn->close();
	}
}


?>