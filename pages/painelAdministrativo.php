<?php
session_start();

// Verifica se o usuário está logado e é ADM
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'adm') {
    header('Location: login.php?erro=Acesso restrito a administradores.');
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="../css/painelAdministrativo.css">
</head>
<body>
    <section class="container-pai">
        <div class="card">
            <h1>Painel Administrativo</h1>
            <p>Bem-vindo, <strong><?= htmlspecialchars($usuario['nome']) ?></strong>!</p>

            <div class="botoes">
                <a href="registroProduto.php" class="btn">Cadastrar Produto</a>
                <a href="listarProdutos.php" class="btn">Gerenciar Produtos</a>
                <a href="#" class="btn desativado">Gerenciamento de Mesas (em desenvolvimento)</a>
                <a href="#" class="btn desativado">Gerenciamento de Entregas (em desenvolvimento)</a>
            </div>

            <div class="rodape">
                <a href="../index.php" class="link-voltar">← Voltar ao Menu Principal</a>
                <a href="../src/cliente/logout.php" class="link-sair">Sair</a>
            </div>
        </div>
    </section>
</body>
</html>
