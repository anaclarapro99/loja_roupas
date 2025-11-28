<?php
require_once __DIR__ . '/../includes/conexao.php';
include __DIR__ . '/../includes/header.php';

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
        $stmt = $conn->prepare("INSERT INTO produtos (nome,categoria,tamanho,preco,quantidade) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssdi", $nome, $categoria, $tamanho, $preco, $quantidade);
        if ($stmt->execute()) {
            set_flash("Produto cadastrado com sucesso.");
            header("Location: produtos_list.php");
            exit;
        } else {
            $errors[] = "Erro ao cadastrar produto.";
        }
    }
}
?>
<div class="card">
  <h2>Novo Produto</h2>
  <?php if(!empty($errors)): ?>
    <div class="flash-danger"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div>
  <?php endif; ?>
  <form method="post">
    <div class="form-row"><label>Nome</label><input class="input" name="nome" required value="<?=htmlspecialchars($_POST['nome'] ?? '')?>"></div>
    <div class="form-row"><label>Categoria</label><input class="input" name="categoria" value="<?=htmlspecialchars($_POST['categoria'] ?? '')?>"></div>
    <div class="form-row"><label>Tamanho</label><input class="input" name="tamanho" value="<?=htmlspecialchars($_POST['tamanho'] ?? '')?>"></div>
    <div class="form-row"><label>Preço (ex: 59.90)</label><input class="input" name="preco" type="number" step="0.01" value="<?=htmlspecialchars($_POST['preco'] ?? '')?>" required></div>
    <div class="form-row"><label>Quantidade</label><input class="input" name="quantidade" type="number" value="<?=htmlspecialchars($_POST['quantidade'] ?? '0')?>" required></div>
    <button class="btn" type="submit">Salvar</button>
    <a class="btn" href="produtos_list.php" style="background:#6c757d">Cancelar</a>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
