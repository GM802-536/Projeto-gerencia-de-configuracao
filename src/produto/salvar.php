<?php
// Caminho do arquivo JSON
$jsonPath = __DIR__ . '/../../database/produtos.json';
$uploadDir = __DIR__ . '/../../database/uploads';

// Função rápida de redirecionamento
function redirect($ok = true, $msg = '')
{
    $param = $ok ? 'ok=1' : 'erro=' . urlencode($msg);
    header('Location: ../../pages/registroProduto.php?' . $param);
    exit;
}

// Verifica método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(false, 'Método inválido.');
}

// Coleta e valida dados
$nome       = trim($_POST['nome'] ?? '');
$categoria  = trim($_POST['categoria'] ?? '');
$sku        = trim($_POST['sku'] ?? '');
$preco      = floatval(str_replace(',', '.', $_POST['preco'] ?? 0));
$quantidade = intval($_POST['quantidade'] ?? 0);
$status     = trim($_POST['status'] ?? 'ativo');
$descricao  = trim($_POST['descricao'] ?? '');
$imagemPath = null;

// Validação simples
if ($nome === '' || $categoria === '' || $sku === '') {
    redirect(false, 'Preencha todos os campos obrigatórios.');
}

// Cria diretórios, se necessário
if (!is_dir(dirname($jsonPath))) {
    mkdir(dirname($jsonPath), 0777, true);
}
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Lê arquivo JSON existente ou cria vazio
if (!file_exists($jsonPath)) {
    file_put_contents($jsonPath, '[]');
}
$data = json_decode(file_get_contents($jsonPath), true);
if (!is_array($data)) $data = [];

// Upload da imagem (opcional)
if (!empty($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $novoNome = uniqid('img_') . '.' . $ext;
    $destino = $uploadDir . '/' . $novoNome;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
        $imagemPath = 'database/uploads/' . $novoNome; // caminho relativo
    }
}

// Cria novo produto
$novoProduto = [
    'id'         => count($data) + 1,
    'nome'       => $nome,
    'categoria'  => $categoria,
    'sku'        => $sku,
    'preco'      => $preco,
    'quantidade' => $quantidade,
    'status'     => $status,
    'descricao'  => $descricao,
    'imagem'     => $imagemPath,
    'criado_em'  => date('Y-m-d H:i:s')
];

// Adiciona ao array e salva novamente
$data[] = $novoProduto;
if (file_put_contents($jsonPath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    redirect(true);
} else {
    redirect(false, 'Erro ao salvar produto.');
}
