<?php
require_once __DIR__ . '/../includes/conexao.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { set_flash("Produto inválido.", "danger"); header("Location: produtos_list.php"); exit; }

$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM vendas WHERE produto_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
if ($res['cnt'] > 0) {
    set_flash("Não é possível excluir: existem vendas vinculadas a este produto.", "danger");
    header("Location: produtos_list.php");
    exit;
}

$del = $conn->prepare("DELETE FROM produtos WHERE id = ?");
$del->bind_param("i",$id);
$del->execute();
set_flash("Produto excluído.");
header("Location: produtos_list.php");
exit;
