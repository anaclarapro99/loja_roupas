<?php
require_once __DIR__ . '/../includes/conexao.php';
include __DIR__ . '/../includes/header.php';

$clientes = $conn->query("SELECT id,nome FROM clientes ORDER BY nome")->fetch_all(MYSQLI_ASSOC);
$produtos = $conn->query("SELECT id,nome,preco,quantidade FROM produtos ORDER BY nome")->fetch_all(MYSQLI_ASSOC);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = intval($_POST['cliente'] ?? 0);
    $produto_id = intval($_POST['produto'] ?? 0);
    $quantidade = intval($_POST['quantidade'] ?? 0);

    if ($cliente_id <= 0) $errors[] = "Escolha um cliente.";
    if ($produto_id <= 0) $errors[] = "Escolha um produto.";
    if ($quantidade <= 0) $errors[] = "Quantidade deve ser maior que zero.";

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT preco,quantidade FROM produtos WHERE id = ?");
        $stmt->bind_param("i",$produto_id);
        $stmt->execute();
        $prod = $stmt->get_result()->fetch_assoc();
        if (!$prod) $errors[] = "Produto inválido.";
        else {
            $preco = floatval($prod['preco']);
            $estoque_atual = intval($prod['quantidade']);
            if ($quantidade > $estoque_atual) $errors[] = "Quantidade solicitada maior que o estoque disponível ({$estoque_atual}).";
        }
    }

    if (empty($errors)) {
        $total = $preco * $quantidade;
        $conn->begin_transaction();
        try {
            $ins = $conn->prepare("INSERT INTO vendas (cliente,produto,quantidade,total) VALUES (?,?,?,?)");
            $ins->bind_param("iiid", $cliente_id, $produto_id, $quantidade, $total);
            $ins->execute();

            $novo_estoque = $estoque_atual - $quantidade;
            $upd = $conn->prepare("UPDATE produtos SET quantidade = ? WHERE id = ?");
            $upd->bind_param("ii",$novo_estoque,$produto_id);
            $upd->execute();

            $conn->commit();
            set_flash("Venda registrada com sucesso. Total: R$ " . number_format($total,2,',','.'));
            header("Location: vendas_list.php");
            exit;
        } catch(Exception $e) {
            $conn->rollback();
            $errors[] = "Erro ao registrar venda: " . $e->getMessage();
        }
    }
}
?>

<div class="page-container">
    <div class="card">
        <h2>Registrar Venda</h2>

        <?php if(!empty($errors)): ?>
            <div class="flash-danger">
                <ul>
                    <?php foreach($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-row">
                <label>Cliente</label>
                <select name="cliente" class="input" required>
                    <option value="">-- selecione --</option>
                    <?php foreach($clientes as $c): ?>
                    <option value="<?=$c['id']?>" <?= (isset($_POST['cliente']) && $_POST['cliente']==$c['id']) ? 'selected' : '' ?>>
                        <?=htmlspecialchars($c['nome'])?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label>Produto</label>
                <select name="produto" class="input" required>
                    <option value="">-- selecione --</option>
                    <?php foreach($produtos as $p): ?>
                    <option value="<?=$p['id']?>" <?= (isset($_POST['produto']) && $_POST['produto']==$p['id']) ? 'selected' : '' ?>>
                        <?=htmlspecialchars($p['nome'])?> — R$ <?=number_format($p['preco'],2,',','.')?> — Estoque: <?=$p['quantidade']?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label>Quantidade</label>
                <input class="input" name="quantidade" type="number" min="1" value="<?=htmlspecialchars($_POST['quantidade'] ?? '1')?>" required>
            </div>

            <button class="btn" type="submit">Registrar Venda</button>
            <a class="btn" href="vendas_list.php" style="background:#6c757d">Cancelar</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
