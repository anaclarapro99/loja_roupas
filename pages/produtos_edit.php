<?php
require_once __DIR__ . '/../includes/conexao.php';
include __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { set_flash("Produto não encontrado.", "danger"); header("Location: produtos_list.php"); exit; }

$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->bind_param("i",$id);
$stmt->execute();
$produto = $stmt->get_result()->fetch_assoc();
if (!$produto) { set_flash("Produto não encontrado.", "danger"); header("Location: produtos_list.php"); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $tamanho = trim($_POST['tamanho'] ?? '');
    $preco = floatval($_POST['preco'] ?? 0);
    $quantidade = intval($_POST['quantidade'] ?? 0);

    if ($nome === '') $errors[] = "Nome é obrigatório.";
    if ($preco <= 0) $errors[] = "Preço deve ser maior que zero.";
    if ($quantidade < 0) $errors[] = "Quantidade não pode ser negativa.";

    if (empty($errors)) {
        $upd = $conn->prepare("UPDATE produtos SET nome=?,categoria=?,tamanho=?,preco=?,quantidade=? WHERE id=?");
        $upd->bind_param("sssdii", $nome, $categoria, $tamanho, $preco, $quantidade, $id);
        if ($upd->execute()) {
            set_flash("Produto atualizado com sucesso.");
            header("Location: produtos_list.php");
            exit;
        } else {
            $errors[] = "Erro ao atualizar produto.";
        }
    }
}
?>
<div class="card">
  <h2>Editar Produto</h2>
  <?php if(!empty($errors)): ?>
    <div class="flash-danger"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div>
  <?php endif; ?>
  <form method="post">
    <div class="form-row"><label>Nome</label><input class="input" name="nome" required value="<?=htmlspecialchars($_POST['nome'] ?? $produto['nome'])?>"></div>
    <div class="form-row"><label>Categoria</label><input class="input" name="categoria" value="<?=htmlspecialchars($_POST['categoria'] ?? $produto['categoria'])?>"></div>
    <div class="form-row"><label>Tamanho</label><input class="input" name="tamanho" value="<?=htmlspecialchars($_POST['tamanho'] ?? $produto['tamanho'])?>"></div>
    <div class="form-row"><label>Preço</label><input class="input" name="preco" type="number" step="0.01" value="<?=htmlspecialchars($_POST['preco'] ?? $produto['preco'])?>" required></div>
    <div class="form-row"><label>Quantidade</label><input class="input" name="quantidade" type="number" value="<?=htmlspecialchars($_POST['quantidade'] ?? $produto['quantidade'])?>" required></div>
    <button class="btn" type="submit">Salvar</button>
    <a class="btn" href="produtos_list.php" style="background:#6c757d">Cancelar</a>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
