<?php
session_start();

$caminho_json = '../../database/usuarios.json';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../pages/login.php");
    exit;
}

$usuarios = json_decode(file_get_contents($caminho_json), true);
$id_logado = $_SESSION['usuario']['id'];

foreach ($usuarios as &$u) {
    if ($u['id'] == $id_logado) {
        $u['nome'] = $_POST['nome'];
        $u['email'] = $_POST['email'];

        if (!empty($_POST['senha'])) {
            $u['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        }

        $_SESSION['usuario']['nome'] = $u['nome'];
        $_SESSION['usuario']['email'] = $u['email'];

        break;
    }
}

file_put_contents($caminho_json, json_encode($usuarios, JSON_PRETTY_PRINT));

header("Location: ../../pages/editar-perfil.php?sucesso=1");
exit;
