<?php
// Inclui a configuração do banco e a sessão
require_once "config/database.php";
// Inclui o cabeçalho (início do HTML, navbar)
include "includes/header.php";

// Variáveis para mensagens (sucesso ou erro)
$mensagem_sucesso = isset($_GET["msg"]) ? htmlspecialchars($_GET["msg"]) : "";
$mensagem_erro = isset($_GET["erro"]) ? htmlspecialchars($_GET["erro"]) : "";

// 1. EXIBIÇÃO DE MENSAGENS (Feedback do sistema)
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

// =======================================================
// ÁREA PÚBLICA: EXIBIR COMENTÁRIOS E FORMULÁRIO
// =======================================================

// Consulta SQL para buscar todos os comentários em ordem cronológica reversa
$sql =
    "SELECT id, nome, email, mensagem, data_criacao FROM comentarios ORDER BY data_criacao DESC";
$resultado = mysqli_query($conn, $sql);
?>

<h1 class="mb-4">📖 Livro de Visitas</h1>

<div class="row">
    <!-- Coluna do Formulário -->
    <div class="col-md-5 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">✍️ Deixe seu Comentário</h4>
            </div>
            <div class="card-body">
                <form action="processa.php?acao=adicionar_comentario" method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nome" name="nome" required
                               placeholder="Digite seu nome">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email: <span class="text-muted">(opcional)</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                               placeholder="seuemail@exemplo.com">
                    </div>
                    <div class="mb-3">
                        <label for="mensagem" class="form-label">Mensagem: <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required
                                  placeholder="Escreva sua mensagem aqui..."></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">📨 Enviar Comentário</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Coluna dos Comentários -->
    <div class="col-md-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-muted mb-0">💬 Comentários Recentes</h4>
            <span class="badge bg-info text-dark fs-6">
                Total: <?php echo mysqli_num_rows($resultado); ?>
            </span>
        </div>

        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <?php while ($comentario = mysqli_fetch_assoc($resultado)): ?>
                <div class="comentario-card">
                    <div class="comentario-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="comentario-autor">
                                    👤 <?php echo htmlspecialchars(
                                        $comentario["nome"],
                                    ); ?>
                                </span>
                                <?php if (!empty($comentario["email"])): ?>
                                    <br>
                                    <small class="text-muted">
                                        📧 <?php echo htmlspecialchars(
                                            $comentario["email"],
                                        ); ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <div class="text-end">
                                <div class="comentario-data">
                                    🕒 <?php echo date(
                                        "d/m/Y H:i",
                                        strtotime($comentario["data_criacao"]),
                                    ); ?>
                                </div>
                                <?php if (
                                    isset($_SESSION["usuario_logado"]) &&
                                    isset($_SESSION["usuario_nivel"]) &&
                                    $_SESSION["usuario_nivel"] == "Admin"
                                ): ?>
                                    <a href="processa.php?acao=excluir_comentario&id=<?php echo $comentario[
                                        "id"
                                    ]; ?>"
                                       class="btn btn-sm btn-danger mt-2"
                                       onclick="return confirm('Tem certeza que deseja excluir este comentário?');">
                                        🗑️ Excluir
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="comentario-mensagem">
                        <?php echo nl2br(
                            htmlspecialchars($comentario["mensagem"]),
                        ); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <strong>Seja o primeiro!</strong>
                Ainda não há comentários no livro de visitas. Deixe sua mensagem! ✨
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Inclui o rodapé
include "includes/footer.php";
// Fecha a conexão com o banco
mysqli_close($conn);

?>
