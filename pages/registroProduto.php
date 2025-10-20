<?php
$ok   = isset($_GET['ok']);
$erro = isset($_GET['erro']) ? urldecode($_GET['erro']) : null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastro de Produto</title>
  <link rel="stylesheet" href="../css/registroProduto.css">
</head>

<script>
  // Esconde a mensagem após 3 segundos
  setTimeout(() => {
    const toast = document.querySelector('.toast');
    if (toast) {
      toast.style.opacity = '0';
      toast.style.transform = 'translate(-50%, -20px)';
      setTimeout(() => toast.remove(), 600);
    }
  }, 3000);

  // Remove o ?ok=1 ou ?erro=... da URL sem recarregar
  if (window.history.replaceState) {
    const url = new URL(window.location);
    url.search = '';
    window.history.replaceState({}, document.title, url);
  }
</script>

<body>
  <section class="container-pai">
    <div class="card">

      <div class="card-header">
        Gerenciador de Produtos
      </div>

      <div class="card-body">
        <!-- mensagens -->
        <?php if ($ok): ?>
          <div class="toast toast-ok">Produto cadastrado com sucesso!</div>
        <?php elseif ($erro): ?>
          <div class="toast toast-erro"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>

        <!-- lado esquerdo: dicas -->
        <div class="esquerda">
          <div class="formLogin" style="opacity:1;pointer-events:auto;">
            <h2>Dicas de Cadastro</h2>
            <div class="info-lista">
              <p>• Use um <strong>SKU</strong> único para identificar o produto.</p>
              <p>• Preço em formato decimal: <strong>12.50</strong></p>
              <p>• O campo <strong>Categoria</strong> ajuda na organização do cardápio.</p>
              <p>• A <strong>imagem</strong> é opcional, mas melhora a visualização.</p>
            </div>
          </div>
        </div>

        <!-- lado direito: formulário -->
        <div class="direita">
          <div class="formCadastro" style="opacity:1;pointer-events:auto;">
            <h2>Cadastro de Produto</h2>
            <form action="../src/produto/salvar.php" method="post" enctype="multipart/form-data">
              <input type="text" name="nome" placeholder="Nome do produto" required>

              <select name="categoria" required>
                <option value="" disabled selected>Categoria</option>
                <option value="entrada">Entrada</option>
                <option value="prato-principal">Prato Principal</option>
                <option value="sobremesa">Sobremesa</option>
                <option value="bebida">Bebida</option>
                <option value="outros">Outros</option>
              </select>

              <div class="duas-colunas">
                <input type="text" name="sku" placeholder="SKU / Código" required>
                <input type="number" step="0.01" min="0" name="preco" placeholder="Preço (R$)" required>
              </div>

              <div class="duas-colunas">
                <input type="number" min="0" name="quantidade" placeholder="Quantidade em estoque" required>
                <select name="status" required>
                  <option value="" disabled selected>Status</option>
                  <option value="ativo">Ativo</option>
                  <option value="inativo">Inativo</option>
                </select>
              </div>

              <textarea name="descricao" rows="4" placeholder="Descrição do produto (ingredientes, observações)"></textarea>

              <label class="fileLabel">
                <input type="file" name="imagem" accept="image/*">
                Anexar imagem do produto (opcional)
              </label>

              <button type="submit">Cadastrar Produto</button>
            </form>
          </div>
        </div>
      </div>

    </div>

    <!-- botão fixo no canto inferior esquerdo -->
    <a href="painelAdministrativo.php" class="voltar-painel">← Voltar ao Painel</a>

  </section>
</body>
</html>
