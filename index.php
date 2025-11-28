<?php
require_once __DIR__ . '/includes/conexao.php';
include __DIR__ . '/includes/header.php';
?>

<div class="card">
  <h2>Painel Inicial</h2>
  <p>Bem-vindo(a)! Escolha uma das opções para gerenciar o sistema.</p>

  <div style="display:flex; gap:12px; margin-top:15px;">
      <a class="btn" href="pages/produtos_list.php">Produtos</a>
      <a class="btn" href="pages/clientes_list.php">Clientes</a>
      <a class="btn" href="pages/vendas_list.php">Vendas</a>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
