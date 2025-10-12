<?php
$dados = file_get_contents(__DIR__ . '/../database/produtos.json');
$produtos = json_decode($dados, true);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <link rel="stylesheet" href="../css/listarProdutos.css">
</head>

<body>
    <h1>Produtos Cadastrados</h1>

    <div class="produtos-container">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $p): ?>
                <div class="card">
                    <?php
                    $imagem = !empty($p['imagem']) ? htmlspecialchars($p['imagem']) : 'https://via.placeholder.com/400x200?text=Imagem+Indisponível';
                    ?>
                    <?php
                    $caminhoImagem = "../" . $p['imagem']; // volta uma pasta (de pages/ para raiz)
                    ?>
                    <img src="<?= htmlspecialchars($caminhoImagem) ?>" alt="<?= htmlspecialchars($p['nome']) ?>"
                        class="produto-img"
                        onerror="this.src='https://via.placeholder.com/400x200?text=Imagem+Indispon%C3%ADvel'">


                    <div class="card-content">
                        <h2><?= htmlspecialchars($p['nome']) ?></h2>
                        <p><strong>ID:</strong> <?= htmlspecialchars($p['id']) ?></p>
                        <p><strong>Categoria:</strong> <?= htmlspecialchars($p['categoria']) ?></p>
                        <p><strong>SKU:</strong> <?= htmlspecialchars($p['sku']) ?></p>
                        <p class="preco"><strong>Preço:</strong> R$ <?= number_format($p['preco'], 2, ',', '.') ?></p>
                        <p><strong>Quantidade:</strong> <?= htmlspecialchars($p['quantidade']) ?></p>
                        <p class="<?= $p['status'] === 'ativo' ? 'status-ativo' : 'status-inativo' ?>">
                            <strong>Status:</strong> <?= ucfirst($p['status']) ?>
                        </p>
                        <p><strong>Descrição:</strong> <?= htmlspecialchars($p['descricao']) ?></p>
                        <p><strong>Criado em:</strong> <?= htmlspecialchars($p['criado_em']) ?></p>
                    </div>
                    <div class="acoes">
                        <button class="btn-editar"
                            onclick="abrirEditar(<?= htmlspecialchars(json_encode($p)) ?>)">Editar</button>
                        <form method="POST" action="../src/produto/excluirProduto.php"
                            onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($p['id']) ?>">
                            <button type="submit" class="btn-excluir">Excluir</button>
                        </form>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center;">Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>

    <!-- Modal de Edição -->
    <div id="modalEditar" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharEditar()">&times;</span>
            <h2>Editar Produto</h2>

            <form id="formEditar" method="POST" action="../src/produto/editarProduto.php">
                <input type="hidden" name="id" id="edit-id">

                <div class="form-group">
                    <label for="edit-nome">Nome:</label>
                    <input type="text" name="nome" id="edit-nome" required>
                </div>

                <div class="form-group">
                    <label for="edit-categoria">Categoria:</label>
                    <select name="categoria" id="edit-categoria" required>
                        <option value="">Selecione...</option>
                        <option value="entrada">Entrada</option>
                        <option value="prato-principal">Prato Principal</option>
                        <option value="sobremesa">Sobremesa</option>
                        <option value="bebida">Bebida</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit-preco">Preço:</label>
                    <input type="number" step="0.01" name="preco" id="edit-preco" required>
                </div>

                <div class="form-group">
                    <label for="edit-quantidade">Quantidade:</label>
                    <input type="number" name="quantidade" id="edit-quantidade" required>
                </div>

                <div class="form-group">
                    <label for="edit-status">Status:</label>
                    <select name="status" id="edit-status">
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit-descricao">Descrição:</label>
                    <textarea name="descricao" id="edit-descricao" rows="3"></textarea>
                </div>

                <button type="submit" class="btn-salvar">💾 Salvar Alterações</button>
            </form>
        </div>
    </div>


    <footer>
        © <?= date('Y') ?> - Sistema de Catálogo de Produtos
    </footer>

</body>

</html>

<script>
    function abrirEditar(produto) {
        document.getElementById('edit-id').value = produto.id;
        document.getElementById('edit-nome').value = produto.nome;
        document.getElementById('edit-categoria').value = produto.categoria;
        document.getElementById('edit-preco').value = produto.preco;
        document.getElementById('edit-quantidade').value = produto.quantidade;
        document.getElementById('edit-status').value = produto.status;
        document.getElementById('edit-descricao').value = produto.descricao;
        document.getElementById('modalEditar').style.display = 'flex';
    }

    function fecharEditar() {
        document.getElementById('modalEditar').style.display = 'none';
    }

</script>