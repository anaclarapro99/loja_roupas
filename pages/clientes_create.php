<?php
require_once __DIR__ . '/../includes/conexao.php';
include __DIR__ . '/../includes/header.php';

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
        $chk = $conn->prepare("SELECT id FROM clientes WHERE email = ?");
        $chk->bind_param("s",$email);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) $errors[] = "Já existe cliente com esse e-mail.";
    }

    if (empty($errors)) {
        $ins = $conn->prepare("INSERT INTO clientes (nome,email,telefone,cpf,endereco) VALUES (?,?,?,?,?)");
        $ins->bind_param("sssss",$nome,$email,$telefone,$cpf,$endereco);
        if ($ins->execute()) {
            set_flash("Cliente cadastrado com sucesso.");
            header("Location: clientes_list.php");
            exit;
        } else {
            $errors[] = "Erro ao cadastrar cliente.";
        }
    }
}
?>
<div class="card">
  <h2>Novo Cliente</h2>
  <?php if(!empty($errors)): ?><div class="flash-danger"><ul><?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul></div><?php endif; ?>

  <form method="post">
    <div class="form-row"><label>Nome</label><input class="input" name="nome" required value="<?=htmlspecialchars($_POST['nome'] ?? '')?>"></div>
    <div class="form-row"><label>Email</label><input class="input" name="email" type="email" required value="<?=htmlspecialchars($_POST['email'] ?? '')?>"></div>
    <div class="form-row"><label>Telefone</label><input class="input" name="telefone" value="<?=htmlspecialchars($_POST['telefone'] ?? '')?>"></div>
    <div class="form-row"><label>CPF</label><input class="input" name="cpf" value="<?=htmlspecialchars($_POST['cpf'] ?? '')?>"></div>
    <div class="form-row"><label>Endereço</label><textarea class="input" name="endereco"><?=htmlspecialchars($_POST['endereco'] ?? '')?></textarea></div>
    <button class="btn" type="submit">Salvar</button>
    <a class="btn" href="clientes_list.php" style="background:#6c757d">Cancelar</a>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
