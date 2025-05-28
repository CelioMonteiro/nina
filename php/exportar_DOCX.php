<?php
require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// ... (Código PHP para obter $dataToExport como mostrado acima) ...

$phpWord = new PhpWord();
$section = $phpWord->addSection();

$section->addTitle('Resultados da Busca', 1); // Adiciona um título

// Adicionar uma tabela
$table = $section->addTable();

// Adicionar cabeçalhos da tabela
if (!empty($dataToExport)) {
    $headers = array_keys($dataToExport[0]);
    $table->addRow();
    foreach ($headers as $header) {
        $table->addCell(1750)->addText($header); // Largura aproximada
    }

    // Adicionar dados da tabela
    foreach ($dataToExport as $rowData) {
        $table->addRow();
        foreach ($rowData as $cellValue) {
            $table->addCell(1750)->addText(htmlspecialchars($cellValue));
        }
    }
} else {
    $section->addText('Nenhum resultado encontrado para exportar.');
}


// Configurações de download
$filename = 'resultados_busca_' . date('Ymd_His') . '.docx';
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('php://output');
exit;
?>