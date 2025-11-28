<?php
require_once __DIR__ . '/../includes/conexao.php';
include __DIR__ . '/../includes/header.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { set_flash("Cliente inválido.", "danger"); header("Location: clientes_list.php"); exit; }

$stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->bind_param("i",$id);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();
if (!$cliente) { set_flash("Cliente não encontrado.", "danger"); header("Location: clientes_list.php"); exit; }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');

    if ($nome === '') $errors[] = "Nome é obrigatório.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email inválido.";

    if (empty($errors)) {
        $chk = $conn->prepare("SELECT id FROM clientes WHERE email = ? AND id <> ?");
        $chk->bind_param("si",$email,$id);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) $errors[] = "Outro cliente já usa esse e-mail.";
    }

    if (empty($errors)) {
        $upd = $conn->prepare("UPDATE clientes SET nome=?,email=?,telefone=?,cpf=?,endereco=? WHERE id=?");
        $upd->bind_param("sssssi",$nome,$email,$telefone,$cpf,$endereco,$id);
        if ($upd->execute()) {
            set_flash("Cliente atualizado.");
            header("Location: clientes_list.php");
            exit;
        } else {
            $errors[] = "Erro ao atualizar.";
        }
    }
}
?>
<div class="card">
  <h2>Editar Cliente</h2>
  <?php if(!empty($errors)): ?><div class="flash-danger"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div><?php endif; ?>

  <form method="post">
    <div class="form-row"><label>Nome</label><input class="input" name="nome" required value="<?=htmlspecialchars($_POST['nome'] ?? $cliente['nome'])?>"></div>
    <div class="form-row"><label>Email</label><input class="input" name="email" type="email" required value="<?=htmlspecialchars($_POST['email'] ?? $cliente['email'])?>"></div>
    <div class="form-row"><label>Telefone</label><input class="input" name="telefone" value="<?=htmlspecialchars($_POST['telefone'] ?? $cliente['telefone'])?>"></div>
    <div class="form-row"><label>CPF</label><input class="input" name="cpf" value="<?=htmlspecialchars($_POST['cpf'] ?? $cliente['cpf'])?>"></div>
    <div class="form-row"><label>Endereço</label><textarea class="input" name="endereco"><?=htmlspecialchars($_POST['endereco'] ?? $cliente['endereco'])?></textarea></div>
    <button class="btn" type="submit">Salvar</button>
    <a class="btn" href="clientes_list.php" style="background:#6c757d">Cancelar</a>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
