<?php
session_start();

if (!isset($_SESSION['usuario']['id'])) {
    header('Location: ../../pages/login.php');
    exit;
}

if (!isset($_POST['pedido_id'])) {
    header('Location: ../../pages/checar-pedido.php?erro=pedido_invalido');
    exit;
}

$pedido_id = (int) $_POST['pedido_id'];

$caminho_pedidos = __DIR__ . '/../../database/pedidos.json';
$caminho_usuarios = __DIR__ . '/../../database/usuarios.json';

// --- Atualiza o status no pedidos.json ---
if (file_exists($caminho_pedidos)) {
    $pedidos = json_decode(file_get_contents($caminho_pedidos), true);

    foreach ($pedidos as &$pedido) {
        if ($pedido['id'] === $pedido_id && $pedido['id_cliente'] === $_SESSION['usuario']['id']) {
            $pedido['status'] = 'entregue';
            $pedido['data_entrega'] = date('Y-m-d H:i:s');
            break;
        }
    }

    file_put_contents($caminho_pedidos, json_encode($pedidos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// --- Remove o pedido dos pedidos_em_andamento no usuarios.json ---
if (file_exists($caminho_usuarios)) {
    $usuarios = json_decode(file_get_contents($caminho_usuarios), true);

    foreach ($usuarios as &$usuario) {
        if ($usuario['id'] === $_SESSION['usuario']['id'] && isset($usuario['pedidos_em_andamento'])) {
            $usuario['pedidos_em_andamento'] = array_filter(
                $usuario['pedidos_em_andamento'],
                fn($id) => $id != $pedido_id
            );
            break;
        }
    }

    file_put_contents($caminho_usuarios, json_encode($usuarios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Atualiza a sessão
$_SESSION['pedidos_em_andamento'] = false;

// Redireciona com sucesso
header('Location: ../../pages/checar-pedido.php?sucesso=Pedido%20entregue%20com%20sucesso!');
exit;
