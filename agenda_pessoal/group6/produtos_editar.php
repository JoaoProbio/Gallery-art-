<?php
// Inclui a configuração do banco e a sessão
require_once "config/database.php";

// Verifica se o usuário está logado
verificar_login();

// 1. Validação e obtenção do ID
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php?erro=ID de produto inválido para edição.");
    exit();
}

// 2. Busca dos dados do produto usando Prepared Statement
$stmt = mysqli_prepare(
    $conn,
    "SELECT nome, quantidade, preco_custo, preco_venda, descricao FROM produtos WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) == 0) {
    header("Location: index.php?erro=Produto não encontrado.");
    exit();
}

$produto = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

// Inclui o cabeçalho
include "includes/header.php";

// Variáveis para mensagens
$mensagem_erro = isset($_GET["erro"]) ? htmlspecialchars($_GET["erro"]) : "";

// Exibir mensagens
if (!empty($mensagem_erro)): ?>
 <div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Erro!</strong> <?php echo $mensagem_erro; ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 </div>
<?php endif; ?>

<h1 class="mb-4">✏️ Editar Produto: <?php echo htmlspecialchars($produto["nome"]); ?></h1>

<div class="row justify-content-center">
 <div class="col-md-10">
  <div class="card shadow">
   <div class="card-header bg-warning text-dark">
    <h4 class="mb-0">📦 Formulário de Edição de Produto</h4>
   </div>
   <div class="card-body">
    <form action="processa.php?acao=editar_produto" method="POST">
     <input type="hidden" name="id" value="<?php echo $id; ?>">

     <div class="row">
      <div class="col-md-8 mb-3">
       <label for="nome" class="form-label">Nome do Produto: <span class="text-danger">*</span></label>
       <input type="text" class="form-control" id="nome" name="nome" required
              value="<?php echo htmlspecialchars($produto["nome"]); ?>"
              placeholder="Ex: Notebook Dell Inspiron 15">
      </div>

      <div class="col-md-4 mb-3">
       <label for="quantidade" class="form-label">Quantidade: <span class="text-danger">*</span></label>
       <input type="number" class="form-control" id="quantidade" name="quantidade"
              required min="0" value="<?php echo $produto["quantidade"]; ?>" placeholder="0">
       <div class="form-text">Unidades em estoque</div>
      </div>
     </div>

     <div class="row">
      <div class="col-md-6 mb-3">
       <label for="preco_custo" class="form-label">Preço de Custo (R$): <span class="text-danger">*</span></label>
       <input type="number" class="form-control" id="preco_custo" name="preco_custo"
              required min="0" step="0.01" value="<?php echo number_format(
                  $produto["preco_custo"],
                  2,
                  ".",
                  ""
              ); ?>" placeholder="0.00">
       <div class="form-text">Valor pago ao fornecedor</div>
      </div>

      <div class="col-md-6 mb-3">
       <label for="preco_venda" class="form-label">Preço de Venda (R$): <span class="text-danger">*</span></label>
       <input type="number" class="form-control" id="preco_venda" name="preco_venda"
              required min="0" step="0.01" value="<?php echo number_format(
                  $produto["preco_venda"],
                  2,
                  ".",
                  ""
              ); ?>" placeholder="0.00">
       <div class="form-text">Valor de venda ao cliente</div>
      </div>
     </div>

     <div class="mb-3">
      <label for="descricao" class="form-label">Descrição: <span class="text-muted">(opcional)</span></label>
      <textarea class="form-control" id="descricao" name="descricao" rows="4"
                placeholder="Digite detalhes sobre o produto"><?php echo htmlspecialchars(
                    $produto["descricao"]
                ); ?></textarea>
     </div>

     <div class="alert alert-warning">
      <strong>⚠️ Atenção:</strong> As alterações serão salvas imediatamente após clicar em "Salvar Alterações".
     </div>

     <hr>

     <div class="d-flex justify-content-between">
      <a href="index.php" class="btn btn-secondary">↩️ Cancelar</a>
      <button type="submit" class="btn btn-warning btn-lg text-dark">💾 Salvar Alterações</button>
     </div>

    </form>
   </div>
  </div>

  <!-- Card com cálculo automático de margem -->
  <div class="card mt-4 shadow">
   <div class="card-header bg-info text-white">
    <h5 class="mb-0">🧮 Calculadora de Margem de Lucro</h5>
   </div>
   <div class="card-body">
    <div class="row">
     <div class="col-md-4">
      <p class="mb-1"><strong>Preço de Custo:</strong></p>
      <h4 id="calc_custo" class="text-primary">R$ 0,00</h4>
     </div>
     <div class="col-md-4">
      <p class="mb-1"><strong>Preço de Venda:</strong></p>
      <h4 id="calc_venda" class="text-success">R$ 0,00</h4>
     </div>
     <div class="col-md-4">
      <p class="mb-1"><strong>Margem de Lucro:</strong></p>
      <h4 id="calc_margem" class="text-warning">0%</h4>
     </div>
    </div>
    <hr>
    <div class="row">
     <div class="col-md-6">
      <p class="mb-1"><strong>Lucro por Unidade:</strong></p>
      <h4 id="calc_lucro_unit" class="text-info">R$ 0,00</h4>
     </div>
     <div class="col-md-6">
      <p class="mb-1"><strong>Lucro Total (estoque):</strong></p>
      <h4 id="calc_lucro_total" class="text-success">R$ 0,00</h4>
     </div>
    </div>
   </div>
  </div>

 </div>
</div>

<script>
// Calculadora de margem em tempo real
function formatarMoeda(valor) {
    return 'R$ ' + valor.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function calcularMargem() {
    const custo = parseFloat(document.getElementById('preco_custo').value) || 0;
    const venda = parseFloat(document.getElementById('preco_venda').value) || 0;
    const quantidade = parseInt(document.getElementById('quantidade').value) || 0;

    const lucroUnit = venda - custo;
    const lucroTotal = lucroUnit * quantidade;
    const margem = custo > 0 ? ((venda - custo) / custo * 100) : 0;

    document.getElementById('calc_custo').textContent = formatarMoeda(custo);
    document.getElementById('calc_venda').textContent = formatarMoeda(venda);
    document.getElementById('calc_margem').textContent = margem.toFixed(1) + '%';
    document.getElementById('calc_lucro_unit').textContent = formatarMoeda(lucroUnit);
    document.getElementById('calc_lucro_total').textContent = formatarMoeda(lucroTotal);

    // Mudar cor da margem baseado no valor
    const margemElement = document.getElementById('calc_margem');
    if (margem >= 30) {
        margemElement.className = 'text-success';
    } else if (margem >= 15) {
        margemElement.className = 'text-warning';
    } else {
        margemElement.className = 'text-danger';
    }
}

// Adicionar event listeners
document.getElementById('preco_custo').addEventListener('input', calcularMargem);
document.getElementById('preco_venda').addEventListener('input', calcularMargem);
document.getElementById('quantidade').addEventListener('input', calcularMargem);

// Calcular ao carregar a página
window.addEventListener('load', calcularMargem);
</script>

<?php
// Inclui o rodapé
include "includes/footer.php";
// Fecha a conexão com o banco
mysqli_close($conn);
?>
