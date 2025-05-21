<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 10;
$offset = ($pagina - 1) * $limite;

$conn = new PDO("mysql:host=localhost;dbname=admlerin", "root", "SuperLerin!123");

// Total de registros
$totalStmt = $conn->query("SELECT COUNT(*) FROM geral_nova");
$totalRegistros = $totalStmt->fetchColumn();
$totalPaginas = ceil($totalRegistros / $limite);

// Registros da página
$stmt = $conn->prepare("SELECT * FROM geral_nova LIMIT :limite OFFSET :offset");
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// JSON de resposta
echo json_encode([
  'dados' => $dados,
  'totalPaginas' => $totalPaginas
]);
?>
