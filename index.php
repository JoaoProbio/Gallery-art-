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

<div class="alert alert-dismissible fade show mx-auto d-flex align-items-center no-hover-btn" role="alert">
    <strong class="block me-2"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48" fill="#000000"><g fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="4"><path d="M25.5 36H21l-10 5v-5H4V6h40v11m-32-3h6m-6 6h12"/><path d="m29 30l6 5l9-11"/></g></svg></strong> <?php echo $mensagem_sucesso; ?>
    <button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>
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
    // ÁREA RESTRITA: UTILIZADOR LOGADO - EXIBIR OBRAS (READ)
    // =======================================================

    // Consulta SQL para buscar todas as obras


    $sql =
        "SELECT id, titulo, artista, tecnica, ano_criacao, imagem_obra, descricao, quem_cadastra FROM obras";
    $resultado = mysqli_query($conn, $sql);
    ?>

<div class="header-obras">
    <h1 class="fs-14">Obras cadastradas</h1>
    <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
        <a href="obras_cadastrar.php" class="btn-cadastrar d-flex align-items-center justify-content-center">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48" fill="currentColor"><g fill="none" stroke="#000000" stroke-linejoin="round" stroke-width="4"><rect width="36" height="36" x="6" y="6" rx="3"/><path stroke-linecap="round" d="M24 16v16m-8-8h16"/></g></svg>
            </span>
        </a>
    </div>
</div>

<?php if (mysqli_num_rows($resultado) > 0): ?>
    <div class="grid-obras">
        <?php while ($obra = mysqli_fetch_assoc($resultado)): ?>
            <div class="obra-card" onclick="abrirModal(this)">
                <div class="obra-imagem">
                    <img src="<?php echo $obra[
                        "imagem_obra"
                    ]; ?>" alt="Obra <?php echo htmlspecialchars(
    $obra["id"],
); ?>">
                    <div class="obra-overlay">
                        <a href="obras_editar.php?id=<?php echo $obra[
                            "id"
                        ]; ?>" title="Editar" onclick="event.stopPropagation();">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48"><path fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M16 6H8a2 2 0 0 0-2 2v8m10 26H8a2 2 0 0 1-2-2v-8m26 10h8a2 2 0 0 0 2-2v-8M32 6h8a2 2 0 0 1 2 2v8m-10 8H16m8 8V16"/></svg>
                            </span>
                        </a>
                        <a href="processa.php?acao=excluir_obra&id=<?php echo $obra[
                            "id"
                        ]; ?>" title="Excluir" onclick="event.stopPropagation(); return confirm('Tem certeza que deseja EXCLUIR a obra: <?php echo htmlspecialchars(
    $obra["titulo"],
); ?>?');">
    <span>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 48 48" fill="#000000"><g fill="none" stroke="#9b1919" stroke-linejoin="round" stroke-width="4"><path d="M9 10v34h30V10H9Z"/><path stroke-linecap="round" d="M20 20v13m8-13v13M4 10h40"/><path d="m16 10l3.289-6h9.488L32 10H16Z"/></g></svg>
    </span>
</a>
                    </div>
                </div>

                <div class="obra-info">
                    <div class="obra-titulo"><?php echo htmlspecialchars(
                        $obra["titulo"],
                    ); ?></div>
                    <span class="obra-artista"><?php echo htmlspecialchars(
                        $obra["artista"],
                    ); ?></span>
                    <div class="obra-meta">Técnica: <?php echo htmlspecialchars(
                        $obra["tecnica"],
                    ); ?></div>
                    <div class="obra-meta">Ano: <?php echo htmlspecialchars(
                        $obra["ano_criacao"],
                    ); ?></div>

                </div>


                <!-- Dados do modal em data attributes -->
                <div style="display: none;">
                    <span class="obra-modal-titulo fs-14">Cadastrado por <?php echo htmlspecialchars(
                        $obra["quem_cadastra"],
                    ); ?></span>
                    <span class="obra-modal-tecnica"><?php echo htmlspecialchars(
                        $obra["tecnica"],
                    ); ?></span>
                    <span class="obra-modal-ano"><?php echo htmlspecialchars(
                        $obra["ano_criacao"],
                    ); ?></span>
                    <span class="obra-modal-descricao"><?php echo htmlspecialchars(
                        $obra["descricao"],
                    ); ?></span>
                    <span class="obra-modal-imagem"><?php echo $obra[
                        "imagem_obra"
                    ]; ?></span>
                    <span class="obra-modal-id"><?php echo $obra[
                        "id"
                    ]; ?></span>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

<?php else: ?>

<div class="alert alert-secondary" style="text-align: center; padding: 40px 20px; border-radius: 8px;">
    <div class="d-flex align-items-center justify-content-center" style="margin-bottom: 10px;">
        <span class="me-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 512 512"><path fill="#000000" d="M367 0H145q-14 0-25.5 9T105 32L0 299v170q0 18 12.5 30.5T43 512h426q18 0 30.5-12.5T512 469V299L407 32q-3-14-14.5-23T367 0zm102 469H43V341h85l43 64h170l43-64h85v128zm-81-170q-29 0-38 23l-30 41H192l-30-41q-9-23-38-23H51l98-256h214l98 256h-73z"/></svg>
        </span>
        <h5 class="mb-0 fs-14">Galeria Vazia</h5>
    </div>
    <p style="margin: 0; color: #666;" class="mb-0 fs-12">Sua galeria está vazia. Comece cadastrando sua primeira obra!</p>
</div>

<?php endif; ?>

<!-- Modal -->
<div class="modal-overlay" id="modalObra">
    <div class="modal-content">
        <button class="modal-close" onclick="fecharModal()">&times;</button>

        <div class="modal-imagem">
            <img id="modalImagem" src="" alt="Obra">
        </div>

        <div class="modal-info">
            <div class="modal-header-info">
                <h1 class="modal-title" id="modalTitulo"></h1>
            </div>

            <div>
                <div class="modal-detail-item">
                    <div class="modal-detail-label fs-12">Técnica</div>
                    <div class="modal-detail-value fs-14" id="modalTecnica"></div>
                </div>

                <div class="modal-detail-item">
                    <div class="modal-detail-label fs-12">Ano de Criação</div>
                    <div class="modal-detail-value fs-14" id="modalAno"></div>
                </div>

                <div class="modal-detail-item">
                    <div class="modal-detail-label fs-12">Descrição</div>
                    <p class="modal-descricao fs-14" id="modalDescricao"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
else:
     ?>

<!--
===============================================================
ÁREA PÚBLICA: UTILIZADOR DESLOGADO - EXIBIR FORMULÁRIO DE LOGIN
===============================================================
-->
<div class="row d-flex justify-content-center container align-items-center" style="height: 70vh;">
    <div class="col-md-6">
        <div class="mx-auto">
            <div class="">
                <h4 class="d-flex align-items-center mb-0 fs-6 tracking-tighter">
                    <span class="me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-lock-icon lucide-user-lock"><circle cx="10" cy="7" r="4"/><path d="M10.3 15H7a4 4 0 0 0-4 4v2"/><path d="M15 15.5V14a2 2 0 0 1 4 0v1.5"/><rect width="8" height="5" x="13" y="16" rx=".899"/></svg>
                    </span>
                    Acesso à Galeria de Artes
                </h4>
            </div>

            <div class="card-body">
                <p class="text-muted" style="font-size: 11px;">Credencial de teste: admin / 123456</p>

                <form action="processa.php?acao=login" method="POST">
                    <div class="mb-1">
                        <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Utilizador" required>
                    </div>

                    <div class="mb-1">
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="Senha" required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn-entrar" style="font-size: 14px;">Entrar</button>
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
