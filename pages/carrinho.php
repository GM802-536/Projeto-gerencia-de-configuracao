<?php
session_start();

$caminho_json = '../database/produtos.json';
$produtos_json = [];
if (file_exists($caminho_json)) {
    $json_data = file_get_contents($caminho_json); 
    $produtos_json = json_decode($json_data, true);
}

$produtos_map = [];
if (!empty($produtos_json)) {
    foreach ($produtos_json as $produto) {
        $produtos_map[$produto['id']] = $produto;
    }
}

$carrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];

$total_pedido = 0;

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - Restaurante</title>
    <link rel="stylesheet" href="../css/menu-clientes.css">
    <link rel="stylesheet" href="../css/carrinho.css"> 
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
    <div class="user-options">
        <?php if (isset($_SESSION['usuario'])): ?>
            <!-- Usuário logado -->
            <a href="carrinho.php" class="cart-icon">
                <i class="fa-solid fa-cart-shopping"></i>
                <?php if (!empty($_SESSION['carrinho'])): ?>
                    <?php 
                    $itens_no_carrinho = 0;
                    foreach ($_SESSION['carrinho'] as $item) {
                        $itens_no_carrinho += $item['quantidade'];
                    }
                    ?>
                    <?php if ($itens_no_carrinho > 0): ?>
                        <span class="cart-counter"><?= $itens_no_carrinho ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </a>

            <div class="user-logged">
                <a href="editar-perfil.php" title="Editar perfil" class="user-link">
                    <i class="fa-solid fa-user"></i>
                </a>
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

        <section class="menu-section">
            <h2>Meu Carrinho</h2>

            <div class="carrinho-container">
                <?php if (empty($carrinho)): ?>
                    <div class="carrinho-vazio">
                        <p>Seu carrinho está vazio.</p>
                        <a href="menu-clientes.php" class="btn-continuar">Ver cardápio</a>
                    </div>

                <?php else: ?>
                    <table class="carrinho-tabela">
                        <thead>
                            <tr>
                                <th colspan="2">Produto</th>
                                <th>Preço Unit.</th>
                                <th>Quantidade</th>
                                <th>Subtotal</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach ($carrinho as $id_produto => $item):
                                if (isset($produtos_map[$id_produto])):
                                    $produto = $produtos_map[$id_produto];
                                    $quantidade = $item['quantidade'];
                                    
                                    $preco_unit = (float)$produto['preco'];
                                    $subtotal_item = $preco_unit * $quantidade;
                                    $total_pedido += $subtotal_item;
                                    
                                    $preco_formatado = 'R$ ' . number_format($preco_unit, 2, ',', '.');
                                    $subtotal_formatado = 'R$ ' . number_format($subtotal_item, 2, ',', '.');
                                    $caminho_imagem = '../' . htmlspecialchars($produto['imagem']);
                            ?>
                            
                            <tr>
                                <td>
                                    <img src="<?= $caminho_imagem ?>" alt="<?= htmlspecialchars($produto['nome']) ?>" class="carrinho-img-produto">
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($produto['nome']) ?></strong>
                                    <p class="carrinho-descricao"><?= htmlspecialchars($produto['descricao']) ?></p>
                                </td>
                                <td><?= $preco_formatado ?></td>
                                <td>
                                    <?= $quantidade ?>
                                </td>
                                <td><?= $subtotal_formatado ?></td>
                                <td>
                                    <form action="../src/carrinho/remover.php" method="POST" style="margin:0;">
                                        <input type="hidden" name="produto_id" value="<?= $id_produto ?>">
                                        <button type="submit" class="btn-remover">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <?php
                                endif;
                            endforeach;
                            ?>
                        </tbody>
                    </table>

                    <div class="carrinho-resumo">
                        <div class="total-pedido">
                            <h3>Total do Pedido:</h3>
                            <span>R$ <?= number_format($total_pedido, 2, ',', '.') ?></span>
                        </div>
                        <div class="carrinho-acoes">
                            <a href="menu-clientes.php?secao=entrega" class="btn-continuar">Continuar comprando</a>
                            <?php if (isset($_SESSION['usuario'])): ?>
                                <a href="../src/carrinho/finalizar.php" class="btn-finalizar">Finalizar Compra</a>
                            <?php else: ?>
                                <a href="login.php?erro=Faça+login+para+finalizar" class="btn-finalizar" style="background-color: #f39c12;">Fazer Login para Comprar</a>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endif;?>

            </div> </section>

        <footer>
            <p>© 2025 Restaurante — Todos os direitos reservados.</p>
        </footer>
    </div>
</body>
</html>