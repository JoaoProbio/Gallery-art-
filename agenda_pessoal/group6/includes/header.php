<?php
// Define o caminho base para que os links funcionem corretamente, independente da pasta
$caminho = basename(getcwd()) == "group6" ? "" : "../"; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Controle de Estoque | PHP Procedural</title>
 <link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="<?php echo $caminho; ?>assets/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
 <div class="container">
  <a class="navbar-brand" href="<?php echo $caminho; ?>index.php">📦 Controle de Estoque</a>
  <button class="navbar-toggler" type="button"
   data-bs-toggle="collapse" data-bs-target="#navbarNav"
   aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
   <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
  <ul class="navbar-nav me-auto">
   <?php if (isset($_SESSION["usuario_logado"])): ?>
   <li class="nav-item">
    <a class="nav-link" href="<?php echo $caminho; ?>index.php">📋 Estoque</a>
   </li>
   <li class="nav-item">
    <a class="nav-link" href="<?php echo $caminho; ?>produtos_cadastrar.php">➕ Adicionar Produto</a>
   </li>
   <?php if (
       isset($_SESSION["usuario_nivel"]) &&
       $_SESSION["usuario_nivel"] == "Admin"
   ): ?>
    <li class="nav-item">
    <a class="nav-link text-warning" href="<?php echo $caminho; ?>usuarios.php">👥 Gerenciar Usuários</a>
    </li>
   <?php endif; ?>
   <?php endif; ?>
  </ul>
  <ul class="navbar-nav">
   <?php if (isset($_SESSION["usuario_logado"])): ?>
   <li class="nav-item me-3">
    <span class="nav-link text-light">
    👤 <?php echo htmlspecialchars($_SESSION["usuario_logado"]); ?>
    </span>
   </li>
   <li class="nav-item">
    <a href="<?php echo $caminho; ?>processa.php?acao=logout" class="btn btn-sm btn-danger">Sair</a>
   </li>
   <?php endif; ?>
  </ul>
  </div>
 </div>
</nav>
<div class="container mt-4">
