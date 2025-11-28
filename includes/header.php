<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VesteLux</title>

    <!-- CSS principal -->
    <link rel="stylesheet" href="/LOJA_ROUPAS/public/style.css">

    <!-- Fonte moderna -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

<header class="navbar">
    <div class="navbar-logo">
        <img src="public\logo.png" alt="Logo VesteLux">
        <h1>VesteLux</h1>
    </div>

    <nav class="navbar-links">
        <a href="/LOJA_ROUPAS/index.php">Início</a>
        <a href="/LOJA_ROUPAS/pages/produtos_list.php">Produtos</a>
        <a href="/LOJA_ROUPAS/pages/vendas_list.php">Vendas</a>
        <a href="/LOJA_ROUPAS/pages/clientes_list.php">Clientes</a>
        <a href="/LOJA_ROUPAS/pages/relatorios.php">Relatórios</a>
    </nav>
</header>

<main class="content">

