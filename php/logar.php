<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8"); // importante para JSON
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
$_SESSION['LAST_ACTIVITY'] = time();


include_once 'conexao.php';

$email  = $_POST['email'];
$senha  = md5($_POST['senha']); // já direto no md5

// Conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die(json_encode(["erro" => "Connection failed: " . $conn->connect_error]));
}

// Consulta
$sql = "SELECT * FROM tab_user WHERE email='$email' AND senha='$senha' LIMIT 1";
$result = $conn->query($sql);

$user = [];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user = [
        'idUser' => $row['idUser'],
        'nome' => $row['nome'],
        'email' => $row['email'],
        'status' => $row['status']
    ];
    $_SESSION['logado'] = 1;  
} else {
    $user = ['idUser' => 0];
}

$conn->close();

// Retorna como JSON
echo json_encode($user);
?>
