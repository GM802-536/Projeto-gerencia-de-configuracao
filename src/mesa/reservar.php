<?php
$caminho = __DIR__ . '/../../database/mesas.json';
$mesas = json_decode(file_get_contents($caminho), true);

$mesaId = $_POST['mesa_id'] ?? null;
$dataReserva = $_POST['data'] ?? null;
$cliente = trim($_POST['cliente'] ?? '');

if (!$mesaId || !$dataReserva || !$cliente) {
    header("Location: /pages/menu-sections/mesas.php?erro=Dados inválidos");
    exit;
}

$encontrou = false;
foreach ($mesas as &$mesa) {
    if ($mesa['id'] == $mesaId) {
        $encontrou = true;

        if (!isset($mesa['reservas'])) {
            $mesa['reservas'] = [];
        }

        $jaReservada = false;
        foreach ($mesa['reservas'] as $reserva) {
            if ($reserva['data_reserva'] === $dataReserva) {
                $jaReservada = true;
                break;
            }
        }

        if ($jaReservada) {
            header("Location: /pages/menu-sections/mesas.php?data=$dataReserva&erro=Mesa já reservada nesta data!");
            exit;
        }

        $mesa['reservas'][] = [
            'data_reserva' => $dataReserva,
            'cliente' => $cliente,
            'status' => 'reservada'
        ];

        break;
    }
}

if (!$encontrou) {
    header("Location: /pages/menu-sections/mesas.php?erro=Mesa não encontrada");
    exit;
}

file_put_contents($caminho, json_encode($mesas, JSON_PRETTY_PRINT));

header("Location: /pages/menu-sections/mesas.php?data=$dataReserva&ok=Reserva confirmada com sucesso!");
exit;
