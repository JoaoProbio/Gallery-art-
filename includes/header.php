<?php
// Define o caminho base para que os links funcionem corretamente, independente da pasta
$caminho = basename(getcwd()) == "galeria_arte" ? "" : "../"; ?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Galeria de Arte | PHP Procedural</title>
        <!-- Incluindo Bootstrap CSS via CDN para estilização e responsividade -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo $caminho; ?>assets/style.css">
    </head>
    <body class="d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-lg navbar-light  text-black">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center tracking-tight" href="<?php echo $caminho; ?>index.php"><span class="me-2"><svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="#000000"><g fill="none" stroke="#000000" stroke-linejoin="round"><path d="M7 7.5c1.6 0 4.333 2 5.5 3c1.333-1 4.023-2.735 5.5-3c1.178-.211 4.5 0 4.5 4.5s-4 5.5-6.5 5.5c-2 0-3.5-2-4-3c-1.167 1-4 3-6 3c-2.5 0-4.5-1.5-4.5-4.5s3-5.5 5.5-5.5Z"/><path d="M10.253 12.633c-.936 1.078-3.281 1.577-4.248 1.577c-.153.356.18.802.486 1.311c-2.213 0-3.003-1.447-3.003-2.77c0-1.321.427-2.596 2.288-2.79c1.46-.152 3.655 1.57 4.477 2.672Zm3.867-.051c.936 1.078 2.29.458 3.256.458c.153.356.409 1.366-.244 2.237c2.213 0 3.526-1.807 3.526-2.9c0-1.323-.353-2.478-2.213-2.672c-1.461-.152-3.502 1.774-4.325 2.877Z"/><path d="M18.19 11.707a.25.25 0 1 1 0-.5m0 .5a.25.25 0 0 0 0-.5m-12.083 1a.25.25 0 1 1 0-.5m0 .5a.25.25 0 1 0 0-.5"/></g></svg></span> Galeria</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <!-- Links visíveis apenas se o utilizador estiver logado -->
                        <?php if (isset($_SESSION["usuario_logado"])): ?>
                        <li class="nav-item">
                            <a class="nav-link tracking-tight" href="<?php echo $caminho; ?>obras_cadastrar.php">Cadastrar Obra</a>
                        </li>

                        <!-- Link visível apenas para Administradores -->
                        <?php if (
                            isset($_SESSION["usuario_nivel"]) &&
                            $_SESSION["usuario_nivel"] == "Admin"
                        ): ?>
                        <li class="nav-item">
                            <a class="nav-link tracking-tight" href="<?php echo $caminho; ?>usuarios.php">Gerenciar Utilizadores</a>
                        </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav">
                        <!-- Informações e botão de Sair -->
                        <?php if (isset($_SESSION["usuario_logado"])): ?>
                        <li class="nav-item me-3">
                            <span class="nav-link tracking-tight btn btn-entrar"> Olá, <?php echo htmlspecialchars(
                                $_SESSION["usuario_logado"],
                            ); ?>
                            </span>
                        </li>

                        <li class="nav-item my-auto">
                            <a href="<?php echo $caminho; ?>processa.php?acao=logout" class="btn btn-entrar">Sair</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-4 flex-grow-1">
