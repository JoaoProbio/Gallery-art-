<?php
// Inclui a configuração do banco e a sessão
require_once "config/database.php";

// Verifica se o usuário está logado
verificar_login();

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
<?php endif; ?>

<h1 class="mb-4">➕ Cadastrar Novo Produto</h1>

<div class="row justify-content-center">
 <div class="col-md-10">
  <div class="card shadow">
   <div class="card-header bg-success text-white">
    <h4 class="mb-0">📦 Formulário de Cadastro de Produto</h4>
   </div>
   <div class="card-body">
    <form action="processa.php?acao=cadastrar_produto" method="POST">

     <div class="row">
      <div class="col-md-8 mb-3">
       <label for="nome" class="form-label">Nome do Produto: <span class="text-danger">*</span></label>
       <input type="text" class="form-control" id="nome" name="nome" required
              placeholder="Ex: Notebook Dell Inspiron 15">
      </div>

      <div class="col-md-4 mb-3">
       <label for="quantidade" class="form-label">Quantidade: <span class="text-danger">*</span></label>
       <input type="number" class="form-control" id="quantidade" name="quantidade"
              required min="0" value="0" placeholder="0">
       <div class="form-text">Unidades em estoque</div>
      </div>
     </div>

     <div class="row">
      <div class="col-md-6 mb-3">
       <label for="preco_custo" class="form-label">Preço de Custo (R$): <span class="text-danger">*</span></label>
       <input type="number" class="form-control" id="preco_custo" name="preco_custo"
              required min="0" step="0.01" placeholder="0.00">
       <div class="form-text">Valor pago ao fornecedor</div>
      </div>

      <div class="col-md-6 mb-3">
       <label for="preco_venda" class="form-label">Preço de Venda (R$): <span class="text-danger">*</span></label>
       <input type="number" class="form-control" id="preco_venda" name="preco_venda"
              required min="0" step="0.01" placeholder="0.00">
       <div class="form-text">Valor de venda ao cliente</div>
      </div>
     </div>

     <div class="mb-3">
      <label for="descricao" class="form-label">Descrição: <span class="text-muted">(opcional)</span></label>
      <textarea class="form-control" id="descricao" name="descricao" rows="4"
                placeholder="Digite detalhes sobre o produto (características, modelo, especificações, etc.)"></textarea>
     </div>

     <div class="alert alert-info">
      <strong>💡 Dica:</strong> Preencha todos os campos obrigatórios (*). O preço de venda deve ser maior que o preço de custo para garantir lucro.
     </div>

     <hr>

     <div class="d-flex justify-content-between">
      <a href="index.php" class="btn btn-secondary">↩️ Voltar para Estoque</a>
      <button type="submit" class="btn btn-success btn-lg">💾 Cadastrar Produto</button>
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
</script>

<?php
// Inclui o rodapé
include "includes/footer.php";
// Fecha a conexão com o banco
mysqli_close($conn);
?>
