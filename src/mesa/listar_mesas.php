<?php
$path = __DIR__ . '/../../database/mesas.json';
$mesas = json_decode(file_get_contents($path), true);

header('Content-Type: application/json');
echo json_encode($mesas);
