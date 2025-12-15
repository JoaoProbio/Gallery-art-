<?php
// Inclui a configuração do banco e a sessão
require_once "config/database.php";

// Inclui o cabeçalho
include "includes/header.php";

// Variáveis para mensagens
$mensagem_sucesso = isset($_GET["msg"]) ? htmlspecialchars($_GET["msg"]) : "";
$mensagem_erro = isset($_GET["erro"]) ? htmlspecialchars($_GET["erro"]) : "";

// Exibir mensagens
if (!empty($mensagem_sucesso)): ?>
 <div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Sucesso!</strong> <?php echo $mensagem_sucesso; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 </div>
<?php endif;

if (!empty($mensagem_erro)): ?>
 <div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Erro!</strong> <?php echo $mensagem_erro; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 </div>
<?php endif;

// Verifica se o usuário está logado e é admin
if (
    isset($_SESSION["usuario_logado"]) &&
    isset($_SESSION["usuario_nivel"]) &&
    $_SESSION["usuario_nivel"] == "Admin"
):
    // ÁREA ADMINISTRATIVA - Usuário logado como admin

    // Consulta para buscar todos os usuários

    $sql =
        "SELECT id, usuario, email, nivel FROM usuarios ORDER BY usuario ASC";
    $resultado = mysqli_query($conn, $sql);
    ?>

<h1 class="mb-4">👥 Gerenciamento de Utilizadores (Admin)</h1>

<div class="d-flex justify-content-between mb-3">
 <p class="h5 text-muted">Total de utilizadores: <strong><?php echo mysqli_num_rows(
     $resultado,
 ); ?></strong></p>
 <a href="usuarios_cadastrar.php" class="btn btn-success">➕ Cadastrar Novo Utilizador</a>
</div>

<?php if (mysqli_num_rows($resultado) > 0): ?>
<div class="table-responsive">
 <table class="table table-striped table-hover shadow-sm">
  <thead class="table-dark">
   <tr>
    <th>Utilizador</th>
    <th>Email</th>
    <th>Nível</th>
    <th>Ações</th>
   </tr>
  </thead>
  <tbody>
   <?php while ($usuario = mysqli_fetch_assoc($resultado)): ?>
   <tr>
    <td><?php echo htmlspecialchars($usuario["usuario"]); ?></td>
    <td><?php echo htmlspecialchars($usuario["email"]); ?></td>
    <td>
     <?php if ($usuario["nivel"] == "Admin"): ?>
      <span class="badge bg-danger">Admin</span>
     <?php else: ?>
      <span class="badge bg-secondary">Utilizador</span>
     <?php endif; ?>
    </td>
    <td>
     <?php if (
         isset($_SESSION["usuario_id"]) &&
         intval($_SESSION["usuario_id"]) === intval($usuario["id"])
     ): ?>
      <span class="text-muted">Você está logado</span>
     <?php else: ?>
      <a href="processa.php?acao=excluir_usuario&id=<?php echo $usuario[
          "id"
      ]; ?>"
         class="btn btn-sm btn-danger"
         onclick="return confirm('Tem certeza que deseja EXCLUIR o utilizador: <?php echo htmlspecialchars(
             $usuario["usuario"],
         ); ?>?');">
       🗑️ Excluir
      </a>
     <?php endif; ?>
    </td>
   </tr>
   <?php endwhile; ?>
  </tbody>
 </table>
</div>
<?php else: ?>
<div class="alert alert-info">
 Não há utilizadores cadastrados no sistema.
</div>
<?php endif; ?>

<?php
else:
     ?>
    <!-- ÁREA PÚBLICA - Sem login -->
    <h1 class="mb-4">🔐 Acesso Administrativo</h1>
    <p class="lead text-muted">Esta é uma área restrita. Faça login para acessar o painel administrativo.</p>
<?php
endif;
?>

<!-- Formulário de Login (sempre visível) -->
<div class="row justify-content-center mt-5">
 <div class="col-md-6">
  <div class="card shadow-lg">
   <div class="card-header bg-primary text-white">
    <h4 class="mb-0">🔐 <?php echo isset($_SESSION["usuario_logado"])
        ? "Trocar de Conta"
        : "Login de Administrador"; ?></h4>
   </div>
   <div class="card-body">
    <?php if (!isset($_SESSION["usuario_logado"])): ?>
    <p class="text-muted">Use suas credenciais para acessar o painel administrativo.</p>
    <?php endif; ?>

    <form action="processa.php?acao=login" method="POST">
     <div class="mb-3">
      <label for="usuario" class="form-label">Utilizador:</label>
      <input type="text" class="form-control" id="usuario" name="usuario" required
             placeholder="Digite seu usuário">
     </div>
     <div class="mb-3">
      <label for="senha" class="form-label">Senha:</label>
      <input type="password" class="form-control" id="senha" name="senha" required
             placeholder="Digite sua senha">
     </div>
     <div class="d-grid">
      <button type="submit" class="btn btn-success btn-lg">Entrar</button>
     </div>
    </form>

    <hr>

    <div class="alert alert-warning mb-0">
     <strong>⚠️ Credencial de teste:</strong><br>
     <strong>Usuário:</strong> <code>admin</code><br>
     <strong>Senha:</strong> <code>123456</code>
    </div>

    <div class="text-center mt-3">
     <a href="index.php" class="btn btn-secondary">← Voltar para Comentários</a>
    </div>
   </div>
  </div>
 </div>
</div>

<?php
// Inclui o rodapé
include "includes/footer.php";
// Fecha a conexão com o banco
mysqli_close($conn);

?>
