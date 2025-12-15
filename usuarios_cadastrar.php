<?php
require_once "config/database.php";

verificar_admin(); // APENAS ADMIN PODE ACESSAR ESTA PÁGINA

include "includes/header.php";

// Variáveis para mensagens
$mensagem_erro = isset($_GET["erro"]) ? htmlspecialchars($_GET["erro"]) : "";
?>

<?php if (!empty($mensagem_erro)): ?>

<div class="alert alert-danger" role="alert">
    <strong>Erro!</strong> <?php echo $mensagem_erro; ?>
</div>

<?php endif; ?>

<div class="container">
    <div class="card-body">
        <form action="processa.php" method="POST">
            <input type="hidden" name="acao" value="cadastrar_usuario">

            <div class="mb-3">
                <label for="usuario" class="form-label fs-12">Nome de Utilizador (Login):</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required maxlength="50">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fs-12">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required maxlength="100">
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label fs-12">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required minlength="6">
                <small class="form-text text-muted">A senha será criptografada (hash) no banco de dados.</small>
            </div>

            <div class="mb-3">
                <label for="nivel" class="form-label fs-12">Nível de Acesso:</label>
                <select class="form-select" id="nivel" name="nivel" required>
                    <option value="Comum">Comum (Apenas gerencia obras)</option>
                    <option value="Admin">Admin (Gerencia obras e utilizadores)</option>
                </select>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-entrar">Cadastrar Utilizador</button>
                <a href="usuarios.php" class="btn btn-entrar">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include "includes/footer.php";
?>
