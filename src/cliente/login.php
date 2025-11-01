<?php
session_start();

// Função para redirecionar
function redirect($ok = true, $msg = '')
{
    $param = $ok ? '' : 'erro=' . urlencode($msg);
    header('Location: ../../pages/login.php?' . $param);
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(false, 'Acesso inválido.');
}

// Caminho do “banco”
$jsonPath = __DIR__ . '/../../database/usuarios.json';

// Coleta os dados
$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($email === '' || $senha === '') {
    redirect(false, 'Preencha todos os campos.');
}

// Carrega os usuários
if (!file_exists($jsonPath)) {
    redirect(false, 'Nenhum usuário cadastrado.');
}
$usuarios = json_decode(file_get_contents($jsonPath), true);
if (!is_array($usuarios)) {
    redirect(false, 'Erro ao ler dados de usuários.');
}

// Procura o usuário
foreach ($usuarios as $u) {
    if ($u['email'] === $email && password_verify($senha, $u['senha'])) {
        // Login bem-sucedido
        $_SESSION['usuario'] = [
            'id' => $u['id'],
            'nome' => $u['nome'],
            'email' => $u['email'],
            'tipo' => $u['tipo'] ?? 'cliente' 
        ];
        header('Location: ../../index.php');
        exit;
    }
}

// Se chegar aqui, falhou
redirect(false, 'E-mail ou senha incorretos.');
