<?php
require_once __DIR__ . '/../includes/conexao.php';
include __DIR__ . '/../includes/header.php';

$res = $conn->query("SELECT * FROM clientes ORDER BY id DESC");
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Clientes</h2>
    <a class="btn" href="<?= $BASE_URL ?>/pages/clientes_create.php">Novo Cliente</a>
  </div>

  <table class="table">
    <thead><tr><th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th><th>CPF</th><th>Ações</th></tr></thead>
    <tbody>
      <?php while($c = $res->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($c['id'])?></td>
        <td><?=htmlspecialchars($c['nome'])?></td>
        <td><?=htmlspecialchars($c['email'])?></td>
        <td><?=htmlspecialchars($c['telefone'])?></td>
        <td><?=htmlspecialchars($c['cpf'])?></td>
        <td>
          <a class="btn small" href="<?= $BASE_URL ?>/pages/clientes_edit.php?id=<?=$c['id']?>">Editar</a>
          <a class="btn small danger" href="<?= $BASE_URL ?>/pages/clientes_delete.php?id=<?=$c['id']?>" onclick="return confirm('Confirma exclusão?')">Excluir</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
