<?php
require_once __DIR__ . '/../includes/conexao.php';
include __DIR__ . '/../includes/header.php';

$res = $conn->query("SELECT * FROM produtos ORDER BY id DESC");
?>
<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Produtos</h2>
    <a class="btn" href="<?= $BASE_URL ?>/pages/produtos_create.php">Novo Produto</a>
  </div>

  <table class="table">
    <thead><tr><th>ID</th><th>Nome</th><th>Categoria</th><th>Tamanho</th><th>Preço</th><th>Estoque</th><th>Ações</th></tr></thead>
    <tbody>
      <?php while($p = $res->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($p['id'])?></td>
        <td><?=htmlspecialchars($p['nome'])?></td>
        <td><?=htmlspecialchars($p['categoria'])?></td>
        <td><?=htmlspecialchars($p['tamanho'])?></td>
        <td>R$ <?=number_format($p['preco'],2,',','.')?></td>
        <td><?=intval($p['quantidade'])?></td>
        <td>
          <a class="btn small" href="<?= $BASE_URL ?>/pages/produtos_edit.php?id=<?=$p['id']?>">Editar</a>
          <a class="btn small danger" href="<?= $BASE_URL ?>/pages/produtos_delete.php?id=<?=$p['id']?>" onclick="return confirm('Confirma exclusão?')">Excluir</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
