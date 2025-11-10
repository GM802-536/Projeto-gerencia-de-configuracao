<?php
session_start();

// 1. Verifica se o usuário está logado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    // Se não estiver logado, redireciona para o login com uma mensagem
    header('Location: ../../pages/login.php?erro=' . urlencode('Você precisa estar logado para finalizar o pedido.'));
    exit;
}

// 2. Verifica se o carrinho não está vazio
if (empty($_SESSION['carrinho'])) {
    header('Location: ../../pages/menu-clientes.php');
    exit;
}

// Caminhos dos arquivos JSON
$caminho_produtos = __DIR__ . '/../../database/produtos.json';
$caminho_pedidos = __DIR__ . '/../../database/pedidos.json';

// 3. Carrega os produtos para pegar os preços atualizados
if (!file_exists($caminho_produtos)) {
    die('Erro fatal: Arquivo de produtos não encontrado.');
}
$produtos_json = json_decode(file_get_contents($caminho_produtos), true);
$produtos_map = [];
foreach ($produtos_json as $p) {
    $produtos_map[$p['id']] = $p;
}

// 4. Monta a lista de itens do pedido e calcula o total
$itens_pedido = [];
$total_pedido = 0;

foreach ($_SESSION['carrinho'] as $produto_id => $item_carrinho) {
    if (isset($produtos_map[$produto_id])) {
        $produto_real = $produtos_map[$produto_id];
        $quantidade = $item_carrinho['quantidade'];
        $preco_unitario = (float) $produto_real['preco'];

        $total_pedido += ($preco_unitario * $quantidade);

        // Adiciona ao array de itens que será salvo no pedido
        $itens_pedido[] = [
            'id_produto' => $produto_id,
            'nome_produto' => $produto_real['nome'],
            'quantidade' => $quantidade,
            'preco_unitario' => $preco_unitario
        ];
    }
}

// Tratemento de erro
if ($total_pedido <= 0 || empty($itens_pedido)) {
    die('Erro ao processar itens do pedido. Tente novamente.');
}

// 5. Carrega os pedidos existentes para gerar o próximo ID
$pedidos = [];
if (file_exists($caminho_pedidos)) {
    $pedidos = json_decode(file_get_contents($caminho_pedidos), true);
    if (!is_array($pedidos))
        $pedidos = [];
}

// Gera novo ID (pega o maior ID atual e soma 1, ou começa do 1)
$novo_id_pedido = 1;
if (!empty($pedidos)) {
    $maior_id = 0;
    foreach ($pedidos as $ped) {
        if ($ped['id'] > $maior_id) {
            $maior_id = $ped['id'];
        }
    }
    $novo_id_pedido = $maior_id + 1;
}

// Define o fuso horário para a data ficar correta (Brasília)
date_default_timezone_set('America/Sao_Paulo');

// 6. Cria a estrutura do novo pedido json
$novo_pedido = [
    'id' => $novo_id_pedido,
    'id_cliente' => $_SESSION['usuario']['id'],
    'nome_cliente' => $_SESSION['usuario']['nome'],
    'data_pedido' => date('Y-m-d H:i:s'),
    'status' => 'recebido',
    'total' => $total_pedido,
    'itens' => $itens_pedido
];

// 7. Adiciona ao array principal e salva no arquivo JSON
$pedidos[] = $novo_pedido;
if (file_put_contents($caminho_pedidos, json_encode($pedidos, JSON_PRETTY_PRINT))) {
    // SUCESSO!

    // ✅ Salva o ID do pedido no JSON do usuário
    $caminho_usuarios = __DIR__ . '/../../database/usuarios.json';

    // Lê o arquivo JSON dos usuários
    if (file_exists($caminho_usuarios)) {
        $usuarios = json_decode(file_get_contents($caminho_usuarios), true);

        foreach ($usuarios as &$usuario) {
            if ($usuario['id'] === $_SESSION['usuario']['id']) {
                // Se ainda não existir o campo, cria como array
                if (!isset($usuario['pedidos_em_andamento']) || !is_array($usuario['pedidos_em_andamento'])) {
                    $usuario['pedidos_em_andamento'] = [];
                }

                // Adiciona o novo pedido no array, se ainda não existir
                if (!in_array($novo_id_pedido, $usuario['pedidos_em_andamento'])) {
                    $usuario['pedidos_em_andamento'][] = $novo_id_pedido;
                }

                break;
            }
        }

        // Salva de volta no arquivo
        file_put_contents($caminho_usuarios, json_encode($usuarios, JSON_PRETTY_PRINT));
    }

    // ✅ Também mantém na sessão (pra interface)
    if (!isset($_SESSION['pedidos_em_andamento']) || !is_array($_SESSION['pedidos_em_andamento'])) {
        $_SESSION['pedidos_em_andamento'] = [];
    }
    $_SESSION['pedidos_em_andamento'][] = $novo_id_pedido;


    // 8. Limpa o carrinho
    unset($_SESSION['carrinho']);
    // 9. Redireciona para o menu com mensagem de sucesso
    header('Location: ../../pages/menu-clientes.php?sucesso_pedido=' . $novo_id_pedido);
    exit;

} else {
    die('Erro ao salvar o arquivo de pedidos. Verifique as permissões da pasta database.');
}