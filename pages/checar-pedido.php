<?php
session_start();

$caminho_pedidos = __DIR__ . '/../database/pedidos.json';
$caminho_usuarios = __DIR__ . '/../database/usuarios.json';

if (!isset($_SESSION['usuario']['id'])) {
    header('Location: login.php');
    exit;
}

// Carrega todos os pedidos
$pedidos = file_exists($caminho_pedidos) ? json_decode(file_get_contents($caminho_pedidos), true) : [];
$usuario_id = $_SESSION['usuario']['id'];

// Carrega o usuário atual para saber seus pedidos ativos
$usuarios = file_exists($caminho_usuarios) ? json_decode(file_get_contents($caminho_usuarios), true) : [];
$pedidos_em_andamento = [];

foreach ($usuarios as $u) {
    if ($u['id'] === $usuario_id && !empty($u['pedidos_em_andamento'])) {
        $pedidos_em_andamento = $u['pedidos_em_andamento'];
        break;
    }
}

// Filtra pedidos do usuário
$meus_pedidos = array_filter($pedidos, fn($p) => $p['id_cliente'] === $usuario_id);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checar Pedido - Lababadi</title>
    <link rel="stylesheet" href="../css/menu-clientes.css">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8c889e6dd8.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../css/checar-pedido.css">
</head>

<body>
    <div class="container-pedidos">
        <h1>📦 Meus Pedidos</h1>

        <?php if (!empty($meus_pedidos)): ?>
            <?php foreach (array_reverse($meus_pedidos) as $pedido): ?>
                <?php if (in_array($pedido['id'], $pedidos_em_andamento)): ?>
                    <p style="color:#c62828;font-weight:600;">⏳ Em andamento</p>
                <?php endif; ?>

                <div class="pedido-card">
                    <h2>Pedido #<?= $pedido['id']; ?> —
                        <span class="status <?= strtolower($pedido['status']); ?>">
                            <?= ucfirst($pedido['status']); ?>
                        </span>

                    </h2>
                    <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?></p>
                    <p><strong>Total:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.'); ?></p>

                    <div class="itens">
                        <strong>Itens:</strong>
                        <?php foreach ($pedido['itens'] as $item): ?>
                            <div class="item">
                                <span><?= $item['nome_produto']; ?> (x<?= $item['quantidade']; ?>)</span>
                                <span>R$ <?= number_format($item['preco_unitario'] * $item['quantidade'], 2, ',', '.'); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="sem-pedidos">
                <p>Você ainda não possui pedidos registrados.</p>
            </div>
        <?php endif; ?>

        <a href="menu-clientes.php" class="voltar"><i class="fa-solid fa-arrow-left"></i> Voltar ao Menu</a>
    </div>
</body>

</html>