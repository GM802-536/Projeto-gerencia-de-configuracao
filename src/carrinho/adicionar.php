<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['produto_id'])) {
        $produto_id = $_POST['produto_id'];
        
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        
        if (isset($_SESSION['carrinho'][$produto_id])) {
            $_SESSION['carrinho'][$produto_id]['quantidade']++;
        } else {
            $_SESSION['carrinho'][$produto_id] = [
                'id' => $produto_id,
                'quantidade' => 1
            ];
        }
    }
    
}
header('Location: ../../pages/menu-clientes.php');
exit;