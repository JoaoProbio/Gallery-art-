<?php
require_once "config/database.php";

verificar_login(); // Protege a página: só acessa se estiver logado

include "includes/header.php";
?>

<div class="container">
    <div class="card-body">
        <form action="processa.php" method="POST" enctype="multipart/form-data">
            <!-- Ação para o processa.php -->
            <input type="hidden" name="acao" value="cadastrar_obra">
            <input type="hidden" name="quem_cadastra" value="<?php echo $_SESSION[
                "usuario_logado"
            ]; ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="titulo" class="form-label fs-12">Título:</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="artista" class="form-label fs-12">Artista:</label>
                    <input type="text" class="form-control" id="artista" name="artista" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="tecnica" class="form-label fs-12">Técnica:</label>
                    <select class="form-control" id="tecnica" name="tecnica" required>
                        <option value="">Selecione uma técnica</option>
                        <option value="Desenho">Desenho</option>
                        <option value="Pintura">Pintura</option>
                        <option value="Escultura">Escultura</option>
                        <option value="Gravura">Gravura</option>
                        <option value="Fotografia">Fotografia</option>
                        <option value="Cerâmica">Cerâmica</option>
                        <option value="Mosaico">Mosaico</option>
                        <option value="Colagem">Colagem</option>
                        <option value="Arte digital">Arte digital</option>
                        <option value="Performance">Performance</option>
                        <option value="Instalação">Instalação</option>
                        <option value="Arquitetura">Arquitetura</option>
                        <option value="Cinema / Vídeo">Cinema / Vídeo</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-9 mb-3">
                    <label for="imagem_obra" class="form-label fs-12">Fazer upload de imagem:</label>
                    <input type="file" class="form-control" id="imagem_obra" name="imagem_obra" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="ano_criacao" class="form-label fs-12">Ano de Criação:</label>
                    <input type="number" class="form-control" id="ano_criacao" name="ano_criacao" value="2025" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label fs-12">Descrição (Opcional):</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3"></textarea>
            </div>
            <div class="mt-4">
                 <button type="submit" class="btn btn-entrar" name="submit" value="enviado">Cadastrar</button>
                <a href="index.php" class="btn btn-entrar">Voltar</a>
            </div>
        </form>
    </div>
</div>

<?php include "includes/footer.php";
?>
