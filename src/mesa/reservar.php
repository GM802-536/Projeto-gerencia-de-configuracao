<?php
$path = __DIR__ . '/../../database/mesas.json';
$mesas = json_decode(file_get_contents($path), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['mesa_id']);
    $cliente = trim($_POST['cliente']);

    foreach ($mesas as &$mesa) {
        if ($mesa['id'] == $id) {
            $mesa['status'] = 'reservada';
            $mesa['cliente'] = $cliente;
            break;
        }
    }

    file_put_contents($path, json_encode($mesas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: ../../pages/mesas.php');
    exit;
}
