<?php 
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');  // Alterado para "application/json"
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('conexao.php');

// Conectar ao MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die(json_encode([["erro" => "Falha na conexão: " . $conn->connect_error]], JSON_PRETTY_PRINT));
}

// SQL de busca
$sql = "SELECT * FROM tab_user";
$result = $conn->query($sql);

// Verificar erro na consulta
if (!$result) {
    die(json_encode([["erro" => "Erro na consulta SQL: " . $conn->error]], JSON_PRETTY_PRINT));
}

// Obter número de registros
$n = mysqli_num_rows($result);

if ($n < 1) {
    echo json_encode([["erro" => "Não há nenhum dado cadastrado"]], JSON_PRETTY_PRINT);
} else {
    // Mesclar resultados em um array
    $dados = [];
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }

    // Retornar JSON
    echo json_encode($dados, JSON_PRETTY_PRINT);
}

// Fechar conexão
$conn->close();
?>
