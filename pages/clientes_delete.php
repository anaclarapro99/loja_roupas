<?php
require_once __DIR__ . '/../includes/conexao.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { set_flash("Cliente inválido.", "danger"); header("Location: clientes_list.php"); exit; }

$chk = $conn->prepare("SELECT COUNT(*) as cnt FROM vendas WHERE cliente_id = ?");
$chk->bind_param("i",$id);
$chk->execute();
$res = $chk->get_result()->fetch_assoc();
if ($res['cnt'] > 0) {
    set_flash("Não é possível excluir: cliente possui vendas.", "danger");
    header("Location: clientes_list.php");
    exit;
}

$del = $conn->prepare("DELETE FROM clientes WHERE id = ?");
$del->bind_param("i",$id);
$del->execute();
set_flash("Cliente excluído.");
header("Location: clientes_list.php");
exit;
