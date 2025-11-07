<?php
session_start();

$caminho_usuarios = '../database/usuarios.json';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuarios = json_decode(file_get_contents($caminho_usuarios), true);

$usuario_logado = null;
foreach ($usuarios as $u) {
    if ($u['id'] == $_SESSION['usuario']['id']) {
        $usuario_logado = $u;
        break;
    }
}

if (!$usuario_logado) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../css/menu-clientes.css">
    <link rel="stylesheet" href="../css/editar-perfil.css">
    <script src="https://kit.fontawesome.com/8c889e6dd8.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="menu-container">
        <header class="topbar">
            <div class="logo">
                <i class="fa-solid fa-fire-burner"></i>
                <h1>Lababadi</h1>
            </div>
            <div class="user-options">
                <a href="menu-clientes.php?secao=sobre" class="login-link">Voltar</a>
            </div>
        </header>

        <main class="editar-perfil-container">
            <?php if (isset($_GET['sucesso'])): ?>
                <div class="msg-sucesso">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Perfil atualizado com sucesso!</span>
                </div>
            <?php endif; ?>

            <h2>Editar Perfil</h2>
            <form action="../src/cliente/salvar-edicao.php" method="POST" class="editar-form">
                <label>Nome</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($usuario_logado['nome']) ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($usuario_logado['email']) ?>" required>

                <label>Nova Senha (opcional)</label>
                <input type="password" name="senha" placeholder="Deixe em branco para manter a atual">

                <button type="submit" class="btn-salvar">Salvar Alterações</button>
            </form>
        </main>

        <footer>
            <p>© 2025 Lababadi — Todos os direitos reservados.</p>
        </footer>
    </div>
</body>

</html>