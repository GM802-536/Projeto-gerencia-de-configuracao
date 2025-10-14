<?php
    $erro = isset($_GET['erro']) ? htmlspecialchars(urldecode($_GET['erro'])) : null;
    $sucesso = isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Restaurante</title>
    <link rel="stylesheet" href="../css/index.css">
</head>

<body>
    <section class="container-pai">
        <input type="checkbox" id="toggle" hidden>

        <div class="card">
            <div class="esquerda">
                <div class="formLogin">
                    <h2>Fazer Login</h2>
                    <form action="" method="post">
                        <input type="text" name="email" placeholder="E-mail" required>
                        <input type="password" name="senha" placeholder="Senha" required>
                        <button type="submit">Entrar</button>
                    </form>
                </div>

                <div class="facaLogin">
                    <h2>Já tem <br> uma conta?</h2>
                    <p>Faça login para acessar o sistema.</p>
                    <label for="toggle" class="loginButton">Faça Login</label>
                </div>
            </div>

            <div class="direita">

                <div class="formCadastro">
                    <h2>Cadastro</h2>
                    <?php if ($erro): ?>
                        <p style="color: #ffcccc; background: rgba(255,0,0,0.2); padding: 10px; border-radius: 8px; width: 100%; text-align: center;"><?= $erro ?></p>
                    <?php endif; ?>
                    <?php if ($sucesso): ?>
                        <p style="color: #ccffcc; background: rgba(0,255,0,0.2); padding: 10px; border-radius: 8px; width: 100%; text-align: center;">Cadastro realizado com sucesso! Faça o login.</p>
                    <?php endif; ?>
                    
                    <form action="../src/cliente/cadastrarCliente.php" method="post">
                        <input type="text" name="nome" placeholder="Nome" required>
                        <input type="email" name="email" placeholder="E-mail" required>
                        <input type="password" name="senha" placeholder="Senha" required>
                        <input type="password" name="confirma_senha" placeholder="Confirme a sua senha" required>
                        <button type="submit" name="acao" value="cadastrar">Cadastrar</button>
                    </form>
                </div>

                <div class="facaCadastro">
                    <h2>Não tem <br> uma conta?</h2>
                    <p>Cadastre-se para aproveitar todos os recursos.</p>
                    <label for="toggle" class="cadastroButton">Cadastre-se</label>
                </div>
            </div>
        </div>
    </section>
</body>

</html>