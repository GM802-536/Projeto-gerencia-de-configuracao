<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Caminho do JSON de usuários
$caminho_usuarios = __DIR__ . '/../../database/usuarios.json';

$tem_pedidos = false;

// Garante que o usuário esteja logado
if (isset($_SESSION['usuario']['id']) && file_exists($caminho_usuarios)) {
    $usuarios = json_decode(file_get_contents($caminho_usuarios), true);

    foreach ($usuarios as $usuario) {
        if ($usuario['id'] === $_SESSION['usuario']['id']) {

            // Atualiza a flag da sessão sempre que o menu for carregado
            $_SESSION['pedidos_em_andamento'] = isset($usuario['pedidos_em_andamento']) ? !empty($usuario['pedidos_em_andamento']): false;

            // Verifica se há pedidos em andamento no JSON
            if (!empty($usuario['pedidos_em_andamento']) && is_array($usuario['pedidos_em_andamento'])) {
                $tem_pedidos = true;
                $quantidade_pedidos = count($usuario['pedidos_em_andamento']);
            }
            break;
        }
    }
}
?>

<?php if ($tem_pedidos): ?>
    <div id="popup-pedido" class="popup-pedido">
        <div class="popup-content">
            <i class="fa-solid fa-bell-concierge"></i>
            <span>🍽️ Você possui <?= $quantidade_pedidos; ?> pedido<?= $quantidade_pedidos > 1 ? 's' : ''; ?> em
                andamento!</span>
            <a href="checar-pedido.php" class="botao-ver">Ver pedidos</a>
            <button id="fecharPopup" class="fechar-popup">&times;</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const popup = document.getElementById("popup-pedido");
            const fecharBtn = document.getElementById("fecharPopup");

            // Mostra o popup
            setTimeout(() => popup.classList.add("visivel"), 400);

            // Fica permanente até clicar em fechar
            fecharBtn.addEventListener("click", () => {
                popup.classList.remove("visivel");
            });
        });
    </script>
<?php endif; ?>