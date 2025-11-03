<?php
session_start();
$erro = isset($_GET['erro']) ? htmlspecialchars(urldecode($_GET['erro'])) : null;
$sucesso = isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso';

$usuario = $_SESSION['usuario'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Menu Principal</title>
</head>

<body>
    <h1>Menu Principal</h1>
    <?php if ($usuario): ?>
        <p>Bem-vindo, <?= htmlspecialchars($usuario['nome']) ?>!</p>
    <?php else: ?>
        <form action="./pages/login.php" method="post">
                <button type="submit">Login</button>
            </form>
    <?php endif; ?>
    <?php if ($erro): ?>
        <p>
            <?= $erro ?>
        </p>
    <?php endif; ?>
    <ul>
        <li><a href="./pages/mesas.php">Agendar Mesa</a></li>
        <li><a href="./pages/menu-clientes.php">Pedir entrega</a></li>
        <?php if ($usuario): ?>
            <?php if ($usuario['tipo'] === 'adm'): ?>
                <li><a href="./pages/painelAdministrativo.php">Painel Administrativo (ADM)</a></li>
            <?php endif; ?>
            <form action="./src/cliente/logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        <?php endif; ?>
    </ul>
</body>

</html>