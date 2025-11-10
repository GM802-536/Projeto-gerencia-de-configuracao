<?php
session_start();

require_once './includes/popup-pedido.php';
if (isset($_GET['sucesso_pedido'])) {
    $id_novo_pedido = htmlspecialchars($_GET['sucesso_pedido']);
    echo "<script>alert('Pedido #$id_novo_pedido realizado com sucesso!'); window.location.href='menu-clientes.php';</script>";
}

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
                <h1 class="nome-restaurante">Lababadi</h1>
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
                <?php if (isset($_SESSION['usuario'])): ?>
                    <!-- Usuário logado -->
                    <a href="carrinho.php" class="cart-icon">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <?php if ($itens_no_carrinho > 0): ?>
                            <span class="cart-counter"><?= $itens_no_carrinho ?></span>
                        <?php endif; ?>
                    </a>

                    <div class="user-logged">
                        <div class="user-dropdown">
                            <button class="user-link" id="userDropdownBtn">
                                <i class="fa-solid fa-user"></i>
                                <?php if (isset($_SESSION['pedidos_em_andamento']) && $_SESSION['pedidos_em_andamento'] === true): ?>
                                    <span class="pedido-alerta">!</span>
                                <?php endif; ?>
                            </button>

                            <div class="dropdown-menu" id="userDropdownMenu">
                                <a href="editar-perfil.php">
                                    <i class="fa-solid fa-pen"></i> Editar Perfil
                                </a>

                                <a href="checar-pedido.php" class="checar-pedido-link">
                                    <i class="fa-solid fa-receipt"></i> Checar Pedido
                                    <?php if (isset($_SESSION['pedidos_em_andamento']) && $_SESSION['pedidos_em_andamento'] === true): ?>
                                        <span class="pedido-alerta-mini">!</span>
                                    <?php endif; ?>
                                </a>
                            </div>

                        </div>

                        <a href="../src/cliente/logout.php" title="Sair" class="logout-icon">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Usuário NÃO logado -->
                    <a href="./login.php" class="login-link">Fazer login</a>
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

    <!-- Script do icone de usuario -->
    <script>
        document.getElementById("userDropdownBtn").addEventListener("click", function (e) {
            e.stopPropagation();
            document.querySelector(".user-dropdown").classList.toggle("active");
        });

        document.addEventListener("click", function () {
            document.querySelector(".user-dropdown").classList.remove("active");
        });
    </script>

    <?php include './includes/popup-pedido.php'; ?>

</body>

</html>