<?php
require 'vendor/autoload.php'; // Certifique-se de que o autoload do Composer está configurado

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 1. Receber o termo de busca do frontend (exemplo)
$termoBusca = isset($_GET['termo_busca']) ? $_GET['termo_busca'] : '';

// 2. Conectar ao banco de dados e obter *todos* os dados filtrados
include_once('conexao.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Update table name to your actual table name if it's not 'sua_tabela'
    // Ensure all field names are exactly as they appear in your MySQL table
    $sql = "SELECT funcionrio, vnculo, cargo, equipamento, matrcula, telefones, data_admisso, turno, endereo_bairro, data_incio_e_fim_aviso_prvio, email, cpf, telefones1, obs FROM sua_tabela WHERE 1=1";
    $params = [];

    if (!empty($termoBusca)) {
        // Example search across multiple relevant fields
        // Keep the field names exactly as in your MySQL table
        $sql .= " AND (funcionrio LIKE :termo OR email LIKE :termo OR cpf LIKE :termo OR matrcula LIKE :termo)"; 
        $params[':termo'] = '%' . $termoBusca . '%';
    }

    // Adicione ORDER BY se quiser uma ordem específica
    $sql .= " ORDER BY funcionrio ASC"; // Ordering by 'funcionrio' for example

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Erro na conexão ou consulta: " . $e->getMessage());
}

// 3. Criar a planilha Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Adicionar cabeçalhos - using the exact field names as titles
$sheet->setCellValue('A1', 'funcionrio');
$sheet->setCellValue('B1', 'vnculo');
$sheet.setCellValue('C1', 'cargo');
$sheet.setCellValue('D1', 'equipamento');
$sheet.setCellValue('E1', 'matrcula');
$sheet.setCellValue('F1', 'telefones');
$sheet.setCellValue('G1', 'data_admisso');
$sheet.setCellValue('H1', 'turno');
$sheet.setCellValue('I1', 'endereo_bairro');
$sheet.setCellValue('J1', 'data_incio_e_fim_aviso_prvio');
$sheet.setCellValue('K1', 'email');
$sheet.setCellValue('L1', 'cpf');
$sheet.setCellValue('M1', 'telefones1');
$sheet.setCellValue('N1', 'obs');

// Adicionar os dados - directly using the field names as keys
$row = 2; // Começa na segunda linha, pois a primeira é o cabeçalho
foreach ($results as $data) {
    $sheet.setCellValue('A' . $row, $data['funcionrio']);
    $sheet.setCellValue('B' . $row, $data['vnculo']);
    $sheet.setCellValue('C' . $row, $data['cargo']);
    $sheet.setCellValue('D' . $row, $data['equipamento']);
    $sheet.setCellValue('E' . $row, $data['matrcula']);
    $sheet.setCellValue('F' . $row, $data['telefones']);
    $sheet.setCellValue('G' . $row, $data['data_admisso']);
    $sheet.setCellValue('H' . $row, $data['turno']);
    $sheet.setCellValue('I' . $row, $data['endereo_bairro']);
    $sheet.setCellValue('J' . $row, $data['data_incio_e_fim_aviso_prvio']);
    $sheet.setCellValue('K' . $row, $data['email']);
    $sheet.setCellValue('L' . $row, $data['cpf']);
    $sheet.setCellValue('M' . $row, $data['telefones1']);
    $sheet.setCellValue('N' . $row, $data['obs']);
    $row++;
}

// Opcional: Definir largura automática das colunas
foreach (range('A', $sheet->getHighestColumn()) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// 4. Definir cabeçalhos HTTP para download
$fileName = 'dados_funcionarios_exportados_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// 5. Salvar e enviar o arquivo para o navegador
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;