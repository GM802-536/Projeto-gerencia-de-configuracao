<?php
$path = __DIR__ . '/../../database/mesas.json';
$mesas = json_decode(file_get_contents($path), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['mesa_id']);
    $novoStatus = $_POST['status'];

    foreach ($mesas as &$mesa) {
        if ($mesa['id'] == $id) {
            $mesa['status'] = $novoStatus;
            if ($novoStatus === 'livre') {
                $mesa['cliente'] = '';
            }
            break;
        }
    }

    file_put_contents($path, json_encode($mesas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(['sucesso' => true]);
}
