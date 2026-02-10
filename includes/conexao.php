<?php
// includes/conexao.php
session_start();

// ATENÇÃO: para desenvolvimento, habilitar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ajuste se suas credenciais MySQL forem diferentes
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'loja_roupas';

// Ajuste o BASE_URL se a pasta no htdocs tiver outro nome (ex: '/LOJA_ROUPAS')
$BASE_URL = '/LOJA_ROUPAS';

$conn = new mysqli  ($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Flash messages (simples)
function set_flash($msg, $type = 'success') {
    $_SESSION['flash'] = ['msg'=>$msg,'type'=>$type];
}
function show_flash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        $cls = ($f['type'] === 'danger') ? 'flash-danger' : 'flash-success';
        echo "<div class='{$cls}'>".htmlspecialchars($f['msg'])."</div>";
        unset($_SESSION['flash']);
    }
}
?>
