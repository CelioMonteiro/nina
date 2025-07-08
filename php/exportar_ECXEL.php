<?php
// REMOVA OU COMENTE AS LINHAS ABAIXO EM AMBIENTE DE PRODUÇÃO POR RAZÕES DE SEGURANÇA
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ** Caminho absoluto para o autoload.php conforme especificado **
// Certifique-se de que este caminho está correto para o seu ambiente.
require '/home/lerin/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 1. Receber o termo de busca do frontend (exemplo)
// Este termo será usado para filtrar os resultados da busca no banco de dados.
$termoBusca = isset($_GET['termo_busca']) ? $_GET['termo_busca'] : '';

// 2. Conectar ao banco de dados e obter os dados filtrados
// Certifique-se de que o arquivo 'conexao.php' existe no mesmo diretório
// e contém as variáveis $servername, $dbname, $username, $password
// com as credenciais corretas do seu banco de dados.
include_once('conexao.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ** Nome da tabela atualizado para 'geral_nova' **
    // Certifique-se de que todos os nomes dos campos (colunas) abaixo estão EXATAMENTE como no seu banco de dados.
    $sql = "SELECT funcionrio, vnculo, cargo, equipamento, matrcula, telefones, data_admisso, turno, endereo_bairro, data_incio_e_fim_aviso_prvio, email, cpf, telefones1, obs FROM geral_nova WHERE 1=1";
    $params = [];

    // Adiciona condição de busca se um termo for fornecido
    if (!empty($termoBusca)) {
        // Incluindo múltiplos campos na cláusula LIKE para busca mais abrangente.
        // Mantenha os nomes dos campos EXATAMENTE como no seu banco de dados.
        $sql .= " AND (funcionrio LIKE :termo OR email LIKE :termo OR cpf LIKE :termo OR matrcula LIKE :termo OR cargo LIKE :termo OR equipamento LIKE :termo OR turno LIKE :termo OR endereo_bairro LIKE :termo OR obs LIKE :termo)";
        $params[':termo'] = '%' . $termoBusca . '%';
    }

    // Adiciona ordenação dos resultados, se desejar.
    $sql .= " ORDER BY funcionrio ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    // Em caso de erro na conexão ou consulta ao banco de dados, exibe a mensagem de erro.
    die("Erro na conexão ou consulta: " . $e->getMessage());
}

// 3. Criar a planilha Excel usando PhpSpreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Adicionar cabeçalhos na primeira linha da planilha.
// Os nomes dos cabeçalhos devem corresponder aos nomes dos campos da sua tabela MySQL.
$sheet->setCellValue('A1', 'Funcionário');
$sheet->setCellValue('B1', 'Vínculo');
$sheet->setCellValue('C1', 'Cargo');
$sheet->setCellValue('D1', 'Equipamento');
$sheet->setCellValue('E1', 'Matrcula');
$sheet->setCellValue('F1', 'Telefones');
$sheet->setCellValue('G1', 'Data de Admissão');
$sheet->setCellValue('H1', 'Turno');
$sheet->setCellValue('I1', 'Endereço Bairro');
$sheet->setCellValue('J1', 'Data Início/Fim Aviso Prévio');
$sheet->setCellValue('K1', 'Email');
$sheet->setCellValue('L1', 'CPF');
$sheet->setCellValue('M1', 'Telefones 1');
$sheet->setCellValue('N1', 'Observações');

// Adicionar os dados do banco de dados na planilha, começando da segunda linha.
$row = 2; // Inicia na segunda linha, pois a primeira é o cabeçalho

// Itera sobre os resultados, pulando o primeiro elemento se ele for um placeholder.
foreach ($results as $index => $data) {
    // Esta condição verifica se é a primeira linha E se ela contém o valor de placeholder "$funcionario".
    // Se o seu banco de dados retornar dados válidos na primeira linha, ou se o placeholder for diferente,
    // você pode ajustar ou remover esta condição.
    if ($index === 0 && isset($data['funcionrio']) && $data['funcionrio'] === '$funcionario') {
        continue; // Pula a iteração atual (a primeira linha de placeholder)
    }

    $sheet->setCellValue('A' . $row, $data['funcionrio']);
    $sheet->setCellValue('B' . $row, $data['vnculo']);
    $sheet->setCellValue('C' . $row, $data['cargo']);
    $sheet->setCellValue('D' . $row, $data['equipamento']);
    $sheet->setCellValue('E' . $row, $data['matrcula']);
    $sheet->setCellValue('F' . $row, $data['telefones']);
    $sheet->setCellValue('G' . $row, $data['data_admisso']);
    $sheet->setCellValue('H' . $row, $data['turno']);
    $sheet->setCellValue('I' . $row, $data['endereo_bairro']);
    $sheet->setCellValue('J' . $row, $data['data_incio_e_fim_aviso_prvio']);
    $sheet->setCellValue('K' . $row, $data['email']);
    $sheet->setCellValue('L' . $row, $data['cpf']);
    $sheet->setCellValue('M' . $row, $data['telefones1']);
    $sheet->setCellValue('N' . $row, $data['obs']);
    $row++;
}

// Opcional: Ajustar automaticamente a largura das colunas para o conteúdo.
foreach (range('A', $sheet->getHighestColumn()) as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// 4. Definir cabeçalhos HTTP para iniciar o download do arquivo Excel
$fileName = 'dados_funcionarios_exportados_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $fileName . '"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1'); // Para compatibilidade com IE 9

// Se você estiver usando HTTPS, adicione estas linhas para evitar problemas de cache no SSL
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Data no passado
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // Sempre modificado agora
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

// 5. Salvar a planilha e enviar o arquivo para o navegador para download
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;