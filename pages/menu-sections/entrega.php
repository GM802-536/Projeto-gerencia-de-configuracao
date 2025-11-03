<section class="menu-section">
    <h2>Cardápio</h2>

    <div class="menu-grid">

        <?php
        if (!empty($produtos) && is_array($produtos)):
            foreach ($produtos as $produto):
                if (isset($produto['status']) && $produto['status'] == 'ativo'):
                    $preco_formatado = 'R$ ' . number_format((float) $produto['preco'], 2, ',', '.');
                    if (empty($produto['imagem'])) {
                        $caminho_imagem = '../database/uploads/produto_sem_foto.jpeg'; // <- Troque se tiver uma
                    } else {
                        $caminho_imagem = '../' . htmlspecialchars($produto['imagem']);
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

    </div>
</section>