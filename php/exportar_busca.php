<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ... (Código PHP para obter $dataToExport como mostrado acima) ...

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Adicionar cabeçalhos (baseado nas chaves do seu $dataToExport)
if (!empty($dataToExport)) {
    $headers = array_keys($dataToExport[0]);
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }
}

// Adicionar dados
$row = 2;
foreach ($dataToExport as $rowData) {
    $col = 'A';
    foreach ($rowData as $cellValue) {
        $sheet->setCellValue($col . $row, $cellValue);
        $col++;
    }
    $row++;
}

// Configurações de download
$filename = 'resultados_busca_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>