<?php
session_start();
$caminho_json = '../database/produtos.json';
$produtos = [];

if (file_exists($caminho_json)) {
    $json_data = file_get_contents($caminho_json);
    $produtos = json_decode($json_data, true);
}
$itens_no_carrinho = isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0;

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
                <h1>Restaurante</h1>
            </div>
            <div class="user-options">
                <a href="carrinho.php" class="cart-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <?php if ($itens_no_carrinho > 0): ?>
                        <span class="cart-counter"><?= $itens_no_carrinho ?></span>
                    <?php endif; ?>
                </a>
                
                <a href="../src/cliente/logout.php" title="Sair">
                   <i class="fa-solid fa-user"></i>
                </a>
            </div>
        </header>

        <section class="menu-section">
            <h2>Cardápio</h2>

            <div class="menu-grid">
                
                <?php
                if (!empty($produtos) && is_array($produtos)):
                    foreach ($produtos as $produto):
                        if (isset($produto['status']) && $produto['status'] == 'ativo'):
                            $preco_formatado = 'R$ ' . number_format((float)$produto['preco'], 2, ',', '.');
                            $caminho_imagem = '../' . htmlspecialchars($produto['imagem']);
                            if (empty($produto['imagem'])) {
                                $caminho_imagem = '../caminho/para/imagem_padrao.png'; // <- Troque se tiver uma
                            }
                ?>
                
                <div class="menu-item">
                    <img src="<?= $caminho_imagem ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <p><?= htmlspecialchars($produto['descricao']) ?></p>
                    <span><?= $preco_formatado ?></span>
                    
                    <form action="../src/carrinho/adicionar.php" method="POST">
                        <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                        <button type="submit"><i class="fa-solid fa-plus"></i> Adicionar</button>
                    </form>
                </div>

                <?php
                        endif;
                    endforeach;
                else:
                ?>
                    <p>Nenhum produto encontrado no cardápio no momento.</p>
                <?php
                endif;
                ?>

            </div> </section>

        <footer>
            <p>© 2025 Restaurante — Todos os direitos reservados.</p>
        </footer>
    </div>
</body>
</html>