<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1); // Ensure errors are displayed
error_reporting(E_ALL);     // Report all errors

$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$limite = isset($_GET['limite']) ? intval($_GET['limite']) : 50;
$offset = ($pagina - 1) * $limite;
$termoBusca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

try {
    $conn = new PDO("mysql:host=localhost;dbname=admlerin", "root", "SuperLerin!123");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");

    // Base query parts
    $selectSql = "SELECT * FROM geral_nova";
    $countSql = "SELECT COUNT(*) FROM geral_nova";
    $whereClause = '';
    $params = []; // Array to hold parameters for prepared statements

    if (!empty($termoBusca)) {
        $searchFields = [
            'funcionrio',
            'vnculo',
            'cargo',
            'equipamento',
            'matrcula',
            'data_admisso'
        ];

        $likeConditions = [];
        foreach ($searchFields as $field) {
            // Changed from '?' to a named parameter, e.g., :search_0, :search_1
            $likeConditions[] = "$field LIKE :search_" . count($likeConditions);
        }
        $whereClause = " WHERE (" . implode(" OR ", $likeConditions) . ")";

        $paramBusca = '%' . $termoBusca . '%';
        // Build named parameters for the search
        for ($i = 0; $i < count($searchFields); $i++) {
            $params[":search_$i"] = $paramBusca;
        }
    }

    // --- Query for Total Records (Count) ---
    $totalStmt = $conn->prepare($countSql . $whereClause);
    // Bind search parameters for count query
    if (!empty($termoBusca)) {
        foreach ($params as $paramName => $paramValue) {
            $totalStmt->bindValue($paramName, $paramValue, PDO::PARAM_STR);
        }
    }
    $totalStmt->execute();
    $totalRegistros = $totalStmt->fetchColumn();
    $totalPaginas = ceil($totalRegistros / $limite);

    // --- Query for Data ---
    // Now all parameters, including search, will be named
    $stmt = $conn->prepare($selectSql . $whereClause . " LIMIT :limite OFFSET :offset");

    // Bind search parameters for data query
    if (!empty($termoBusca)) {
        foreach ($params as $paramName => $paramValue) {
            $stmt->bindValue($paramName, $paramValue, PDO::PARAM_STR);
        }
    }
    // Bind limit and offset parameters
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'dados' => $dados,
        'totalPaginas' => $totalPaginas
    ]);

} catch (PDOException $e) {
    error_log('PDO Error in listaGeral.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor ao acessar o banco de dados: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log('General Error in listaGeral.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno inesperado no servidor: ' . $e->getMessage()]);
}
?>