<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// ... (Código PHP para obter $dataToExport como mostrado acima) ...

// Gerar o HTML da tabela
$html = '<style>
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid black; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
        </style>';
$html .= '<h1>Resultados da Busca</h1>';
$html .= '<table>';
// Cabeçalhos
if (!empty($dataToExport)) {
    $html .= '<thead><tr>';
    foreach (array_keys($dataToExport[0]) as $header) {
        $html .= '<th>' . htmlspecialchars($header) . '</th>';
    }
    $html .= '</tr></thead>';
}
// Dados
$html .= '<tbody>';
foreach ($dataToExport as $row) {
    $html .= '<tr>';
    foreach ($row as $cell) {
        $html .= '<td>' . htmlspecialchars($cell) . '</td>';
    }
    $html .= '</tr>';
}
$html .= '</tbody></table>';

// Instanciar Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Se tiver imagens externas, etc.
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

// (Opcional) Definir tamanho do papel e orientação
$dompdf->setPaper('A4', 'landscape'); // 'portrait' ou 'landscape'

// Renderizar o HTML como PDF
$dompdf->render();

// Enviar o PDF para o navegador
$filename = 'resultados_busca_' . date('Ymd_His') . '.pdf';
$dompdf->stream($filename, ["Attachment" => true]); // true para download, false para abrir no navegador
exit;
?>