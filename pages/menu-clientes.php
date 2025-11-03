<?php
session_start();

$secao = isset($_GET['secao']) ? $_GET['secao'] : 'sobre';
$caminho_json = '../database/produtos.json';
$produtos = [];

if (file_exists($caminho_json)) {
    $json_data = file_get_contents($caminho_json);
    $produtos = json_decode($json_data, true);
}
$itens_no_carrinho = 0;
if (isset($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $itens_no_carrinho += $item['quantidade'];
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal - Restaurante</title>
    <link rel="stylesheet" href="../css/menu-clientes.css">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8c889e6dd8.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="menu-container">
        <header class="topbar">
            <div class="logo">
                <i class="fa-solid fa-fire-burner"></i>
                <h1>Lababadi</h1>
            </div>
            <nav class="top-menu">
                <a href="?secao=sobre" class="<?= $secao === 'sobre' ? 'active' : '' ?>">Sobre</a>
                <a href="?secao=entrega" class="<?= $secao === 'entrega' ? 'active' : '' ?>">Entrega</a>
                <a href="?secao=mesas" class="<?= $secao === 'mesas' ? 'active' : '' ?>">Mesas</a>
                <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['tipo'] === 'adm'): ?>
                    <a href="./painelAdministrativo.php" class="<?= $secao === 'admin' ? 'active' : '' ?>">Admin</a>
                <?php endif; ?>

            </nav>
            <div class="user-options">
                <a href="carrinho.php" class="cart-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <?php if ($itens_no_carrinho > 0): ?>
                        <span class="cart-counter"><?= $itens_no_carrinho ?></span>
                    <?php endif; ?>
                </a>

                <?php if (isset($_SESSION['usuario'])): ?>
                    <!-- Usuário logado -->
                    <a href="../src/cliente/logout.php" title="Sair">
                        <i class="fa-solid fa-user"></i>
                    </a>
                <?php else: ?>
                    <!-- Usuário NÃO logado -->
                    <a href="./login.php" class="login-link">
                        Fazer login
                    </a>
                <?php endif; ?>

            </div>
        </header>


        <main>
            <?php
            if ($secao === 'entrega') {
                include 'menu-sections/entrega.php';
            } elseif ($secao === 'mesas') {
                include 'menu-sections/mesas.php';
            } else {
                include 'menu-sections/sobre.php';
            }
            ?>
        </main>

        <footer>
            <p>© 2025 Lababadi — Todos os direitos reservados.</p>
        </footer>
    </div>
</body>

</html>