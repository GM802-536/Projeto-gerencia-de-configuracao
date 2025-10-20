<?php
session_start();

// Garante que o usuário esteja logado
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../index.php?erro=Faça login para reservar uma mesa.');
    exit;
}

$path = __DIR__ . '/../../database/mesas.json';
$mesas = json_decode(file_get_contents($path), true);
$usuarioAtual = $_SESSION['usuario']['email']; // usa o e-mail como identificador

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['mesa_id']);
    $cliente = trim($_POST['cliente']);

    // 1️⃣ verifica se o usuário já reservou alguma mesa
    foreach ($mesas as $mesa) {
        if (isset($mesa['cliente_email']) && $mesa['cliente_email'] === $usuarioAtual && $mesa['status'] === 'reservada') {
            // Já possui reserva
            header('Location: ../../pages/mesas.php?erro=Você já possui uma mesa reservada.');
            exit;
        }
    }

    // 2️⃣ faz a reserva normalmente
    foreach ($mesas as &$mesa) {
        if ($mesa['id'] == $id) {
            if ($mesa['status'] === 'livre') {
                $mesa['status'] = 'reservada';
                $mesa['cliente'] = $cliente;
                $mesa['cliente_email'] = $usuarioAtual;
            } else {
                header('Location: ../../pages/mesas.php?erro=Mesa já está ocupada ou reservada.');
                exit;
            }
            break;
        }
    }

    file_put_contents($path, json_encode($mesas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: ../../pages/mesas.php?ok=Reserva realizada com sucesso!');
    exit;
}
