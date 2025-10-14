_<?php
function redirect($sucesso = true, $msg = '')
{
    // Define o parâmetro com base no sucesso ou erro
    $param = $sucesso ? 'cadastro=sucesso' : 'erro=' . urlencode($msg);
    // Redireciona de volta para a página de login/cadastro
    header('Location: ../../pages/index.php?' . $param);
    exit;
}

// 1. verifica se enviou o form
if (!isset($_POST['acao']) || $_POST['acao'] !== 'cadastrar') {
    redirect(false, 'Acesso inválido.');
}

// 2. caminho para o json
$jsonPath = __DIR__ . '/../../database/usuarios.json';

// 3. coleta e limpa os dados
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';
$confirmaSenha = $_POST['confirma_senha'] ?? '';

// 4. valida os dados
if (empty($nome) || empty($email) || empty($senha)) {
    redirect(false, "Por favor, preencha todos os campos.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect(false, "O formato do e-mail é inválido.");
}
if (strlen($senha) < 6) {
    redirect(false, "A senha deve ter no mínimo 6 caracteres.");
}
if ($senha !== $confirmaSenha) {
    redirect(false, "As senhas não coincidem.");
}

// 5. interacao do "bd"

// Carrega os usuários existentes ou cria um array vazio
$usuarios = [];
if (file_exists($jsonPath)) {
    $usuarios = json_decode(file_get_contents($jsonPath), true);
    if (!is_array($usuarios)) $usuarios = []; // Garante que é um array
}

// Verifica se o e-mail já está em uso
foreach ($usuarios as $user) {
    if ($user['email'] === $email) {
        redirect(false, "Este e-mail já está cadastrado.");
    }
}

// 6. segurança da senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// 7. cria novo usuário
$novoUsuario = [
    'id'            => count($usuarios) + 1,
    'nome'          => htmlspecialchars($nome), // Prevenção extra de segurança
    'email'         => $email,
    'senha'         => $senhaHash,
    'data_cadastro' => date('Y-m-d H:i:s')
];

// 8. salva no arquivo
$usuarios[] = $novoUsuario;
if (file_put_contents($jsonPath, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    // Sucesso!
    redirect(true, "Cadastro realizado com sucesso! Você já pode fazer o login.");
} else {
    // Erro ao salvar
    redirect(false, "Ocorreu um erro no servidor. Tente novamente.");
}

?>