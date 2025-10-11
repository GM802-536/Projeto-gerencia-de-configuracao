<?php

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
                    <form action="" method="post">
                        <input type="text" name="nome" placeholder="Nome">
                        <input type="email" name="email" placeholder="E-mail">
                        <input type="password" name="senha" placeholder="Senha">
                        <input type="password" name="confirma" placeholder="Confirme a sua senha">
                        <button type="submit">Cadastrar</button>
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