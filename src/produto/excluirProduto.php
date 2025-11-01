<?php
// Caminho do arquivo JSON
$jsonPath = __DIR__ . '/../../database/produtos.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        die('ID do produto não informado.');
    }

    // Lê os produtos
    $produtos = json_decode(file_get_contents($jsonPath), true);

    // Filtra removendo o produto com o ID informado
    $produtos = array_filter($produtos, function($produto) use ($id) {
        return $produto['id'] != $id;
    });

    // Reindexa os IDs (opcional)
    $produtos = array_values($produtos);

    // Salva o novo JSON
    file_put_contents($jsonPath, json_encode($produtos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Redireciona de volta para a lista
    header('Location: ../../pages/listarProdutos.php');
    exit;
}
?>
