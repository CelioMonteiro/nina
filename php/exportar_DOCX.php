<?php
// REMOVA OU COMENTE AS LINHAS ABAIXO EM AMBIENTE DE PRODUÇÃO POR RAZÕES DE SEGURANÇA
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ** Caminho absoluto para o autoload.php conforme especificado **
// Certifique-se de que este caminho está correto para o seu ambiente.
require '/home/lerin/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\SimpleType\DocUnit;

// 1. Receber o termo de busca do frontend (exemplo)
$termoBusca = isset($_GET['termo_busca']) ? $_GET['termo_busca'] : '';

// 2. Conectar ao banco de dados e obter os dados filtrados
include_once('conexao.php');

$results = [];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT funcionrio, vnculo, cargo, equipamento, matrcula, telefones, data_admisso, turno, endereo_bairro, data_incio_e_fim_aviso_prvio, email, cpf, telefones1, obs FROM geral_nova WHERE 1=1";
    $params = [];

    if (!empty($termoBusca)) {
        $sql .= " AND (funcionrio LIKE :termo OR email LIKE :termo OR cpf LIKE :termo OR matrcula LIKE :termo OR cargo LIKE :termo OR equipamento LIKE :termo OR turno LIKE :termo OR endereo_bairro LIKE :termo OR obs LIKE :termo)";
        $params[':termo'] = '%' . $termoBusca . '%';
    }

    $sql .= " ORDER BY funcionrio ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Erro na conexão ou consulta: " . $e->getMessage());
}

// 3. Criar o documento Word usando PhpWord
$phpWord = new PhpWord();

// ** Definir orientação da página para paisagem (Landscape) e margens **
$section = $phpWord->addSection(
    [
        'orientation' => 'landscape',
        'marginLeft' => 700, // Margens um pouco menores para dar mais espaço (aprox. 1.2 cm)
        'marginRight' => 700,
        'marginTop' => 700,
        'marginBottom' => 700,
    ]
);

// Definir o estilo da fonte padrão para o documento
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(8); // Mantendo 8pt como padrão

// Definir um estilo para o título
$phpWord->addTitleStyle(1, ['size' => 12, 'bold' => true, 'align' => 'center']);
$section->addTitle('Relatório de Funcionários', 1);

// Definir estilos para a tabela
$phpWord->addTableStyle('myTable', array(
    'borderSize' => 6,
    'borderColor' => '000000',
    'cellMargin' => 30, // Margem interna das células ainda menor (30 twips)
    'width' => 100 * 50, // 100% de largura da página
    'unit' => TblWidth::PERCENT,
));

// Estilo de texto para as células da tabela (reduzir fonte)
$tableTextStyle = ['size' => 7]; // Reduzindo para 7pt para o conteúdo da célula
$headerTextStyle = ['bold' => true, 'size' => 7]; // Reduzindo para 7pt para o cabeçalho

// Estilo de parágrafo para as células (remover espaçamento extra)
$cellParagraphStyle = ['spaceAfter' => 0, 'spaceBefore' => 0, 'lineHeight' => 1.0];

// Adicionar uma tabela
$table = $section->addTable('myTable');

// Larguras das colunas em TWIPS (1440 twips = 1 polegada).
// A largura total utilizável da página A4 em paisagem com margens de 0.7cm (~265 twips) é aproximadamente:
// (29.7cm - 2 * 0.7cm) * 567 twips/cm = (29.7 - 1.4) * 567 = 28.3 * 567 = ~16046 twips.
// Vamos somar as larguras para tentar chegar perto disso, distribuindo melhor o espaço.
$columnWidths = [
    1500, // 1. Funcionário (ligeiramente maior)
    700,  // 2. Vínculo (menor)
    1000, // 3. Cargo (ajustado)
    1100, // 4. Equipamento (ajustado)
    800,  // 5. Matrícula (menor)
    1000, // 6. Telefones (ajustado)
    1200, // 7. Data de Admissão (ajustado)
    600,  // 8. Turno (bem menor)
    1600, // 9. Endereço Bairro (maior)
    1500, // 10. Data Início/Fim Aviso Prévio (maior)
    1200, // 11. Email (ajustado)
    800,  // 12. CPF (menor)
    1000, // 13. Telefones 1 (ajustado)
    2000  // 14. Observações (maior, mas com a soma ajustada para caber)
];
// Soma aproximada: 16100 twips. Isso deve caber melhor.

// Adicionar cabeçalhos da tabela
if (!empty($results)) {
    $headers = [
        'Funcionário', 'Vínculo', 'Cargo', 'Equipamento', 'Matrícula', 'Telefones',
        'Data de Admissão', 'Turno', 'Endereço Bairro', 'Data Início/Fim Aviso Prévio',
        'Email', 'CPF', 'Telefones 1', 'Observações'
    ];

    $table->addRow(); // Adiciona uma nova linha para os cabeçalhos
    foreach ($headers as $index => $header) {
        $cell = $table->addCell($columnWidths[$index], ['bgColor' => 'F2F2F2']);
        // Usar o estilo de parágrafo para centralizar e controlar espaçamento
        $cell->addText($header, $headerTextStyle, ['alignment' => 'center', 'spaceAfter' => 0, 'spaceBefore' => 0]);
    }

    // Adicionar dados da tabela
    foreach ($results as $data) {
        // Esta condição verifica se é a primeira linha E se ela contém o valor de placeholder "$funcionario".
        if (isset($data['funcionrio']) && $data['funcionrio'] === '$funcionario') {
            continue; // Pula a iteração atual (a primeira linha de placeholder)
        }

        $table->addRow();
        // Aplicando a largura de coluna correspondente, estilo de texto e estilo de parágrafo.
        $table->addCell($columnWidths[0])->addText(htmlspecialchars($data['funcionrio'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[1])->addText(htmlspecialchars($data['vnculo'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[2])->addText(htmlspecialchars($data['cargo'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[3])->addText(htmlspecialchars($data['equipamento'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[4])->addText(htmlspecialchars($data['matrcula'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[5])->addText(htmlspecialchars($data['telefones'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[6])->addText(htmlspecialchars($data['data_admisso'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[7])->addText(htmlspecialchars($data['turno'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[8])->addText(htmlspecialchars($data['endereo_bairro'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[9])->addText(htmlspecialchars($data['data_incio_e_fim_aviso_prvio'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[10])->addText(htmlspecialchars($data['email'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[11])->addText(htmlspecialchars($data['cpf'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[12])->addText(htmlspecialchars($data['telefones1'] ?? ''), $tableTextStyle, $cellParagraphStyle);
        $table->addCell($columnWidths[13])->addText(htmlspecialchars($data['obs'] ?? ''), $tableTextStyle, $cellParagraphStyle);
    }
} else {
    $section->addText('Nenhum resultado encontrado para exportar.');
}

// Configurações de download
$filename = 'dados_funcionarios_exportados_' . date('Ymd_His') . '.docx';
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('php://output');
exit;