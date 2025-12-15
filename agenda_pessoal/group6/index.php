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

// 2. LÓGICA CONDICIONAL: SE LOGADO OU NÃO
if (isset($_SESSION["usuario_logado"])):
    // =======================================================
    // ÁREA RESTRITA: UTILIZADOR LOGADO - EXIBIR ESTOQUE (READ)
    // =======================================================

    // Consulta SQL para buscar todos os produtos
    $sql =
        "SELECT id, nome, quantidade, preco_custo, preco_venda, descricao FROM produtos ORDER BY nome ASC";
    $resultado = mysqli_query($conn, $sql);

    // Calcular estatísticas do estoque
    $sql_stats =
        "SELECT
        COUNT(*) as total_produtos,
        SUM(quantidade) as total_itens,
        SUM(preco_custo * quantidade) as valor_custo_total,
        SUM(preco_venda * quantidade) as valor_venda_total,
        SUM((preco_venda - preco_custo) * quantidade) as lucro_potencial
    FROM produtos";
    $resultado_stats = mysqli_query($conn, $sql_stats);
    $stats = mysqli_fetch_assoc($resultado_stats);
    ?>

<h1 class="mb-4">📦 Controle de Estoque</h1>

<!-- Cards de Estatísticas -->
<div class="row mb-4">
 <div class="col-md-3">
  <div class="card bg-primary text-white shadow">
   <div class="card-body">
    <h6 class="card-subtitle mb-2">Total de Produtos</h6>
    <h2 class="card-title mb-0"><?php echo $stats["total_produtos"]; ?></h2>
   </div>
  </div>
 </div>
 <div class="col-md-3">
  <div class="card bg-info text-white shadow">
   <div class="card-body">
    <h6 class="card-subtitle mb-2">Itens em Estoque</h6>
    <h2 class="card-title mb-0"><?php echo number_format(
        $stats["total_itens"],
        0,
        ",",
        ".",
    ); ?></h2>
   </div>
  </div>
 </div>
 <div class="col-md-3">
  <div class="card bg-warning text-dark shadow">
   <div class="card-body">
    <h6 class="card-subtitle mb-2">Valor em Custo</h6>
    <h2 class="card-title mb-0"><?php echo formatar_moeda(
        $stats["valor_custo_total"],
    ); ?></h2>
   </div>
  </div>
 </div>
 <div class="col-md-3">
  <div class="card bg-success text-white shadow">
   <div class="card-body">
    <h6 class="card-subtitle mb-2">Lucro Potencial</h6>
    <h2 class="card-title mb-0"><?php echo formatar_moeda(
        $stats["lucro_potencial"],
    ); ?></h2>
   </div>
  </div>
 </div>
</div>

 <div class="d-flex justify-content-between mb-3">
 <p class="h5 text-muted">Lista de Produtos em Estoque</p>
 <a href="produtos_cadastrar.php" class="btn btn-success">➕ Adicionar Novo Produto</a>
 </div>

 <?php if (mysqli_num_rows($resultado) > 0): ?>
 <div class="table-responsive">
 <table class="table table-striped table-hover shadow-sm">
 <thead class="table-dark">
 <tr>
 <th>Produto</th>
 <th class="text-center">Quantidade</th>
 <th class="text-end">Preço Custo</th>
 <th class="text-end">Preço Venda</th>
 <th class="text-end">Margem</th>
 <th class="text-end">Valor Total</th>
 <th class="text-center">Ações</th>
 </tr>
 </thead>
 <tbody>
 <?php while ($produto = mysqli_fetch_assoc($resultado)):
     $margem = calcular_margem_lucro(
         $produto["preco_custo"],
         $produto["preco_venda"],
     );
     $valor_total = $produto["preco_venda"] * $produto["quantidade"];
     $classe_estoque =
         $produto["quantidade"] < 10
             ? "text-danger fw-bold"
             : ($produto["quantidade"] < 20
                 ? "text-warning fw-bold"
                 : "");
     ?>
 <tr>
 <td>
  <strong><?php echo htmlspecialchars($produto["nome"]); ?></strong>
  <?php if (!empty($produto["descricao"])): ?>
   <br><small class="text-muted"><?php echo htmlspecialchars(
       substr($produto["descricao"], 0, 60),
   ); ?><?php echo strlen($produto["descricao"]) > 60
     ? "..."
     : ""; ?></small>
  <?php endif; ?>
 </td>
 <td class="text-center <?php echo $classe_estoque; ?>">
  <?php echo $produto["quantidade"]; ?>
  <?php if ($produto["quantidade"] < 10): ?>
   <span class="badge bg-danger">Crítico</span>
  <?php elseif ($produto["quantidade"] < 20): ?>
   <span class="badge bg-warning text-dark">Baixo</span>
  <?php endif; ?>
 </td>
 <td class="text-end"><?php echo formatar_moeda(
     $produto["preco_custo"],
 ); ?></td>
 <td class="text-end"><?php echo formatar_moeda(
     $produto["preco_venda"],
 ); ?></td>
 <td class="text-end">
  <span class="badge <?php echo $margem >= 30
      ? "bg-success"
      : ($margem >= 15
          ? "bg-warning text-dark"
          : "bg-danger"); ?>">
   <?php echo number_format($margem, 1); ?>%
  </span>
 </td>
 <td class="text-end fw-bold"><?php echo formatar_moeda(
     $valor_total,
 ); ?></td>
 <td class="text-center">
 <a href="produtos_editar.php?id=<?php echo $produto[
     "id"
 ]; ?>" class="btn btn-sm btn-warning me-1" title="Editar">✏️</a>
 <a
 href="processa.php?acao=excluir_produto&id=<?php echo $produto["id"]; ?>"
 class="btn btn-sm btn-danger"
 title="Excluir"
 onclick="return confirm('Tem certeza que deseja EXCLUIR o produto: <?php echo htmlspecialchars(
     $produto["nome"],
 ); ?>?');">
 🗑️
 </a>
 </td>
 </tr>
 <?php endwhile; ?>
 </tbody>
 </table>
 </div>
 <?php else: ?>
 <div class="alert alert-info">
 O estoque está vazio. Comece cadastrando seu primeiro produto!
 </div>
 <?php endif; ?>

<?php
else:
     ?>
 <div class="row justify-content-center">
 <div class="col-md-7">
 <div class="card card-login shadow-lg">
 <div class="card-header bg-success text-white text-center">
 <h2 class="mb-0">🔒 Acesso ao Sistema de Estoque</h2>
 </div>
 <div class="card-body">
 <p class="text-center text-muted mb-4">
  <strong>Credencial de teste:</strong> admin / 123456
 </p>
 <form action="processa.php?acao=login" method="POST">
 <div class="mb-3">
 <label for="usuario" class="form-label">Utilizador:</label>
 <input type="text" class="form-control" id="usuario" name="usuario" required placeholder="Digite seu usuário">
 </div>
 <div class="mb-3">
 <label for="senha" class="form-label">Senha:</label>
 <input type="password" class="form-control" id="senha" name="senha" required placeholder="Digite sua senha">
 </div>
 <div class="d-grid mt-4">
 <button type="submit" class="btn btn-success btn-lg">Entrar no Sistema</button>
 </div>
 </form>
 </div>
 </div>
 </div>
 </div>

<?php
endif;

// Inclui o rodapé
include "includes/footer.php";
// Fecha a conexão com o banco
mysqli_close($conn);
?>
