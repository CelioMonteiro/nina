<?php
require 'vendor/autoload.php'; // Certifique-se de que o autoload do Composer está configurado

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 1. Receber o termo de busca do frontend (exemplo)
$termoBusca = isset($_GET['termo_busca']) ? $_GET['termo_busca'] : '';

// 2. Conectar ao banco de dados e obter *todos* os dados filtrados
$servername = "localhost";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id, nome, email, data_cadastro FROM sua_tabela WHERE 1=1";
    $params = [];

    if (!empty($termoBusca)) {
        $sql .= " AND (nome LIKE :termo OR email LIKE :termo)"; // Exemplo de busca em 'nome' e 'email'
        $params[':termo'] = '%' . $termoBusca . '%';
    }

    // Adicione ORDER BY se quiser uma ordem específica
    // $sql .= " ORDER BY nome ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Erro na conexão ou consulta: " . $e->getMessage());
}

// 3. Criar a planilha Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Adicionar cabeçalhos
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nome');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Data de Cadastro');
// ... adicione mais colunas conforme necessário

// Adicionar os dados
$row = 2; // Começa na segunda linha, pois a primeira é o cabeçalho
foreach ($results as $data) {
    $sheet->setCellValue('A' . $row, $data['id']);
    $sheet->setCellValue('B' . $row, $data['nome']);
    $sheet->setCellValue('C' . $row, $data['email']);
    $sheet->setCellValue('D' . $row, $data['data_cadastro']);
    // ... adicione mais colunas
    $row++;
}

// Opcional: Definir largura automática das colunas
foreach (range('A', $sheet->getHighestColumn()) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// 4. Definir cabeçalhos HTTP para download
$fileName = 'dados_exportados_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// 5. Salvar e enviar o arquivo para o navegador
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;