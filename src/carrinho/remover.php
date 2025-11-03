<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['produto_id'])) {
        $produto_id = $_POST['produto_id'];
        if (isset($_SESSION['carrinho']) && isset($_SESSION['carrinho'][$produto_id])) {
            unset($_SESSION['carrinho'][$produto_id]);
        }
    }
}
header('Location: ../../pages/carrinho.php');
exit;