<?php
// REMOVA OU COMENTE AS LINHAS ABAIXO EM AMBIENTE DE PRODUÇÃO POR RAZÕES DE SEGURANÇA
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ** Caminho absoluto para o autoload.php conforme especificado **
// Certifique-se de que este caminho está correto para o seu ambiente.
require '/home/lerin/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Receber o termo de busca do frontend (exemplo)
// Este termo será usado para filtrar os resultados da busca no banco de dados.
$termoBusca = isset($_GET['termo_busca']) ? $_GET['termo_busca'] : '';

// 2. Conectar ao banco de dados e obter os dados filtrados
// Certifique-se de que o arquivo 'conexao.php' existe no mesmo diretório
// e contém as variáveis $servername, $dbname, $username, $password
// com as credenciais corretas do seu banco de dados.
include_once('conexao.php');

$results = []; // Initialize $results to an empty array

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

// 3. Gerar o HTML da tabela para o PDF
$html = '<style>
            body { font-family: sans-serif; font-size: 9px; } /* Reduzindo ainda mais a fonte */
            table { width: 100%; border-collapse: collapse; margin-top: 20px; table-layout: fixed; } /* Adicionado table-layout: fixed */
            th, td { border: 1px solid #ccc; padding: 4px; text-align: left; word-wrap: break-word; } /* Reduzido padding e adicionado word-wrap */
            th { background-color: #f2f2f2; font-weight: bold; }
            h1 { text-align: center; color: #333; }
            /* Larguras percentuais para cada coluna - ajuste conforme a necessidade */
            th:nth-child(1), td:nth-child(1) { width: 10%; } /* Funcionário */
            th:nth-child(2), td:nth-child(2) { width: 5%; }  /* Vínculo */
            th:nth-child(3), td:nth-child(3) { width: 8%; }  /* Cargo */
            th:nth-child(4), td:nth-child(4) { width: 10%; } /* Equipamento */
            th:nth-child(5), td:nth-child(5) { width: 6%; }  /* Matrícula */
            th:nth-child(6), td:nth-child(6) { width: 8%; }  /* Telefones */
            th:nth-child(7), td:nth-child(7) { width: 9%; }  /* Data de Admissão */
            th:nth-child(8), td:nth-child(8) { width: 5%; }  /* Turno */
            th:nth-child(9), td:nth-child(9) { width: 12%; } /* Endereço Bairro */
            th:nth-child(10), td:nth-child(10) { width: 10%; } /* Data Início/Fim Aviso Prévio */
            th:nth-child(11), td:nth-child(11) { width: 8%; } /* Email */
            th:nth-child(12), td:nth-child(12) { width: 6%; } /* CPF */
            th:nth-child(13), td:nth-child(13) { width: 8%; } /* Telefones 1 */
            th:nth-child(14), td:nth-child(14) { width: 15%; } /* Observações */
        </style>';
$html .= '<h1>Relatório de Funcionários</h1>';
$html .= '<table>';

// Adicionar cabeçalhos na primeira linha da tabela HTML.
// Os nomes dos cabeçalhos devem corresponder aos nomes dos campos da sua tabela MySQL.
$html .= '<thead><tr>';
$html .= '<th>Funcionário</th>';
$html .= '<th>Vínculo</th>';
$html .= '<th>Cargo</th>';
$html .= '<th>Equipamento</th>';
$html .= '<th>Matrícula</th>';
$html .= '<th>Telefones</th>';
$html .= '<th>Data de Admissão</th>';
$html .= '<th>Turno</th>';
$html .= '<th>Endereço Bairro</th>';
$html .= '<th>Data Início/Fim Aviso Prévio</th>';
$html .= '<th>Email</th>';
$html .= '<th>CPF</th>';
$html .= '<th>Telefones 1</th>';
$html .= '<th>Observações</th>';
$html .= '</tr></thead>';

// Adicionar os dados do banco de dados na tabela HTML
$html .= '<tbody>';
foreach ($results as $data) {
    // Esta condição verifica se é a primeira linha E se ela contém o valor de placeholder "$funcionario".
    // Se o seu banco de dados retornar dados válidos na primeira linha, ou se o placeholder for diferente,
    // você pode ajustar ou remover esta condição.
    if (isset($data['funcionrio']) && $data['funcionrio'] === '$funcionario') {
        continue; // Pula a iteração atual (a primeira linha de placeholder)
    }

    $html .= '<tr>';
    // Usando o operador de coalescência nula (?? '') para garantir que uma string seja sempre passada para htmlspecialchars()
    $html .= '<td>' . htmlspecialchars($data['funcionrio'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['vnculo'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['cargo'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['equipamento'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['matrcula'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['telefones'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['data_admisso'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['turno'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['endereo_bairro'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['data_incio_e_fim_aviso_prvio'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['email'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['cpf'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['telefones1'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($data['obs'] ?? '') . '</td>';
    $html .= '</tr>';
}
$html .= '</tbody></table>';

// 4. Instanciar Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

// (Opcional) Definir tamanho do papel e orientação
$dompdf->setPaper('A4', 'landscape'); // 'portrait' ou 'landscape'

// Renderizar o HTML como PDF
$dompdf->render();

// 5. Enviar o PDF para o navegador
$filename = 'dados_funcionarios_exportados_' . date('Ymd_His') . '.pdf';
$dompdf->stream($filename, ["Attachment" => true]); // true para download, false para abrir no navegador
exit;