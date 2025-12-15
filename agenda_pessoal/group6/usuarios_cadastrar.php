<?php
// Inclui a configuração do banco e a sessão
require_once "config/database.php";

// Verifica se o usuário é administrador
verificar_admin();

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
?>

<h1 class="mb-4">➕ Cadastrar Novo Utilizador</h1>

<div class="row justify-content-center">
 <div class="col-md-8">
  <div class="card shadow">
   <div class="card-header bg-success text-white">
    <h4 class="mb-0">Formulário de Cadastro</h4>
   </div>
   <div class="card-body">
    <form action="processa.php?acao=cadastrar_usuario" method="POST">

     <div class="mb-3">
      <label for="usuario" class="form-label">Nome de Utilizador: <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="usuario" name="usuario" required
             placeholder="Digite o nome de utilizador">
      <div class="form-text">Este será o nome usado para fazer login no sistema.</div>
     </div>

     <div class="mb-3">
      <label for="email" class="form-label">Email: <span class="text-danger">*</span></label>
      <input type="email" class="form-control" id="email" name="email" required
             placeholder="email@exemplo.com">
     </div>

     <div class="mb-3">
      <label for="senha" class="form-label">Senha: <span class="text-danger">*</span></label>
      <input type="password" class="form-control" id="senha" name="senha" required
             placeholder="Digite uma senha segura" minlength="6">
      <div class="form-text">A senha deve ter no mínimo 6 caracteres.</div>
     </div>

     <div class="mb-3">
      <label for="nivel" class="form-label">Nível de Acesso: <span class="text-danger">*</span></label>
      <select class="form-select" id="nivel" name="nivel" required>
       <option value="">Selecione o nível</option>
       <option value="Utilizador">Utilizador Comum</option>
       <option value="Admin">Administrador</option>
      </select>
      <div class="form-text">
       <strong>Utilizador Comum:</strong> Pode apenas visualizar comentários.<br>
       <strong>Administrador:</strong> Pode gerenciar utilizadores e excluir comentários.
      </div>
     </div>

     <hr>

     <div class="d-flex justify-content-between">
      <a href="usuarios.php" class="btn btn-secondary">↩️ Voltar</a>
      <button type="submit" class="btn btn-success btn-lg">💾 Cadastrar Utilizador</button>
     </div>

    </form>
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
