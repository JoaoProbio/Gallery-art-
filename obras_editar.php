<?php
require_once "config/database.php";

verificar_login(); // Protege a página

// 1. Validação e obtenção do ID
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    header("Location: index.php?erro=ID de obra inválido para edição.");
    exit();
}

// 2. Busca dos dados da obra usando Prepared Statement
$stmt = mysqli_prepare(
    $conn,
    "SELECT titulo, artista, tecnica, ano_criacao, imagem_obra, descricao, quem_cadastra FROM obras WHERE id = ?",
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) == 0) {
    header("Location: index.php?erro=Obra não encontrado.");
    exit();
}

$obra = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

include "includes/header.php";
?>

<div class="container">
    <div class="">
        <form action="processa.php" method="POST">
            <!-- Campos ocultos para a lógica do processa.php -->
            <input type="hidden" name="acao" value="editar_obra">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="titulo" class="form-label fs-12">Título:</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars(
                        $obra["titulo"],
                    ); ?>" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="artista" class="form-label fs-12">Artista:</label>
                    <input type="text" class="form-control" id="artista" name="artista" value="<?php echo htmlspecialchars(
                        $obra["artista"],
                    ); ?>" required>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="tecnica" class="form-label fs-12">Técnica:</label>
                    <input type="text" class="form-control" id="tecnica" name="tecnica" value="<?php echo htmlspecialchars(
                        $obra["tecnica"],
                    ); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-9 mb-3">
                    <label for="imagem_obra" class="form-label fs-12">Fazer upload de imagem:</label>
                    <input type="text" class="form-control" id="imagem_obra" name="imagem_obra" value="<?php echo htmlspecialchars(
                        $obra["imagem_obra"],
                    ); ?>" required disabled>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="ano_criacao" class="form-label fs-12">Ano de Criação:</label>
                    <input type="number" class="form-control" id="ano_criacao" name="ano_criacao" value="<?php echo htmlspecialchars(
                        $obra["ano_criacao"],
                    ); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label fs-12">Descrição (Opcional):</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="3"><?php echo htmlspecialchars(
                    $obra["descricao"],
                ); ?></textarea>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-entrar">Atualizar Obra</button>
                <a href="index.php" class="btn btn-entrar">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php
include "includes/footer.php";
mysqli_close($conn);


?>
