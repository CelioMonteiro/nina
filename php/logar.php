<?php
// Define a codificação de caracteres para garantir que acentos e caracteres especiais
// sejam tratados corretamente, especialmente em mensagens de erro ou logs.
header('Content-Type: application/json; charset=utf-8');

// Inicia a sessão se ainda não estiver iniciada. É crucial para usar $_SESSION.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// *** CONFIGURAÇÕES DE EXIBIÇÃO DE ERROS (MUITA ATENÇÃO AQUI!) ***
// EM AMBIENTE DE PRODUÇÃO, SEMPRE DESATIVE 'display_errors'.
// Para evitar o "SyntaxError: Unexpected token '<'", NUNCA exiba erros no navegador em APIs JSON.
ini_set('display_errors', 0); // Desativar exibição de erros no navegador
ini_set('display_startup_errors', 0); // Desativar exibição de erros de inicialização
error_reporting(E_ALL); // Registrar TODOS os tipos de erros (irão para o log do servidor, ex: /var/log/apache2/error.log)

// Buffer de saída para capturar qualquer coisa que seja impressa acidentalmente antes do JSON.
ob_start();

// --- Configurações do Banco de Dados ---
$servername = "localhost"; // Ou "127.0.0.1"
$username = "root";
$password = "SuperLerin!123";
$dbname = "admlerin";

// Variável para armazenar a resposta JSON final
$response_data = ['success' => false, 'message' => 'Email ou senha incorretos.'];

try {
    // Cria uma nova conexão MySQLi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Define o charset da conexão para UTF-8
    $conn->set_charset("utf8mb4");

    // Obtém os dados de email e senha do método POST.
    // Usamos o operador de coalescência nula (??) para evitar avisos se a variável não estiver definida.
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? ''; // Esta é a senha em texto plano enviada pelo usuário

    // Validação básica de entrada para evitar consultas desnecessárias
    if (empty($email) || empty($senha)) {
        throw new Exception("Email e senha são obrigatórios.");
    }

    // Prepara a consulta SQL para buscar o usuário pelo email
    // *** CORREÇÃO AQUI: USANDO tab_user E idUser ***
    $stmt = $conn->prepare("SELECT idUser, nome, email, senha FROM tab_user WHERE email = ?");
    if ($stmt === false) {
        throw new Exception("Falha ao preparar a declaração SQL: " . $conn->error);
    }

    // Vincula o parâmetro 'email' à declaração
    $stmt->bind_param("s", $email);

    // Executa a declaração
    $stmt->execute();

    // Obtém o resultado da declaração
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user_row = $result->fetch_assoc();

        // *** MUDANÇA CRÍTICA AQUI: VERIFICAÇÃO DE SENHA MD5 ***
        // Gera o hash MD5 da senha fornecida pelo usuário
        $senha_digitada_md5 = md5($senha);

        // Compara o hash MD5 da senha digitada com o hash MD5 armazenado no banco de dados
        if ($senha_digitada_md5 === $user_row['senha']) {
            // Senha correta: Login bem-sucedido 🎉
            $response_data = [
                'success' => true,
                'message' => 'Login bem-sucedido!',
                'idUser' => $user_row['idUser'],
                'nome' => $user_row['nome'],
                'email' => $user_row['email']
            ];
            // Define variáveis de sessão para indicar que o usuário está logado
            $_SESSION['logado'] = 1;
            $_SESSION['idUser'] = $user_row['idUser'];
            $_SESSION['nome'] = $user_row['nome'];

        } else {
            // Senha incorreta
            error_log("Tentativa de login falha para o email: " . $email . " - Senha incorreta.");
            // A mensagem de erro padrão ('Email ou senha incorretos.') já cobre este caso.
        }
    } else {
        // Usuário não encontrado (ou mais de um usuário com o mesmo email, o que não deveria acontecer)
        error_log("Tentativa de login falha - Email não encontrado ou duplicado: " . $email);
        // A mensagem de erro padrão ('Email ou senha incorretos.') já cobre este caso.
    }

    // Fecha a declaração e a conexão com o banco de dados
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Em caso de qualquer exceção/erro, logar e retornar uma mensagem genérica para o usuário
    error_log("Erro no logar.php: " . $e->getMessage());
    $response_data = ['success' => false, 'message' => 'Ocorreu um erro no servidor. Por favor, tente novamente mais tarde.'];
}

// Finaliza o buffer de saída e garante que nada foi impresso antes do JSON.
$pre_output = ob_get_clean();
if (!empty($pre_output)) {
    // Isso indicaria que algo foi impresso antes, o que pode causar o erro JSON.parse.
    // Registre isso para depuração, mas não envie ao cliente diretamente.
    error_log("WARNING: Saída inesperada antes do JSON em logar.php: " . $pre_output);
}

// Retorna a resposta final como JSON
echo json_encode($response_data);
?>