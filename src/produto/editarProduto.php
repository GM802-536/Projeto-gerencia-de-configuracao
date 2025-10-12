<?php
// Caminho para o JSON
$jsonPath = __DIR__ . '/../../database/produtos.json';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        die('ID do produto não informado.');
    }

    // Lê o JSON existente
    $produtos = json_decode(file_get_contents($jsonPath), true);

    // Busca o produto correspondente
    foreach ($produtos as &$produto) {
        if ($produto['id'] == $id) {
            $produto['nome'] = $_POST['nome'] ?? $produto['nome'];
            $produto['categoria'] = $_POST['categoria'] ?? $produto['categoria'];
            $produto['preco'] = floatval($_POST['preco'] ?? $produto['preco']);
            $produto['quantidade'] = intval($_POST['quantidade'] ?? $produto['quantidade']);
            $produto['status'] = $_POST['status'] ?? $produto['status'];
            $produto['descricao'] = $_POST['descricao'] ?? $produto['descricao'];
            break;
        }
    }

    // Salva o JSON atualizado
    file_put_contents($jsonPath, json_encode($produtos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Redireciona de volta para a listagem
    header('Location: ../../pages/listarProdutos.php');
    exit;
}
?>
