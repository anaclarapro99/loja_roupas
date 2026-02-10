<?php
require_once __DIR__ . '/../includes/conexao.php';
include __DIR__ . '/../includes/header.php';

$sql = "
  SELECT v.id, v.quantidade, v.total, v.data,
         c.nome as cliente_nome, p.nome as produto_nome
  FROM vendas v
  JOIN clientes c ON cliente = c.id
  JOIN produtos p ON produto = p.id
  ORDER BY v.id DESC
";
$res = $conn->query($sql);
?>

<div class="card">
  <div style="display:flex;justify-content:space-between;align-items:center;">
    <h2>Vendas</h2>
    <a class="btn" href="<?= $BASE_URL ?>/pages/vendas_create.php">Registrar Venda</a>
</div>

  <table class="table">
    <thead><tr><th>ID</th><th>Cliente</th><th>Produto</th><th>Quantidade</th><th>Total</th><th>Data</th></tr></thead>
    <tbody>
      <?php while($v = $res->fetch_assoc()): ?>
      <tr>
        <td><?=htmlspecialchars($v['id'])?></td>
        <td><?=htmlspecialchars($v['cliente_nome'])?></td>
        <td><?=htmlspecialchars($v['produto_nome'])?></td>
        <td><?=intval($v['quantidade'])?></td>
        <td>R$ <?=number_format($v['total'],2,',','.')?></td>
        <td><?=htmlspecialchars($v['data'])?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
