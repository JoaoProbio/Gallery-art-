<?php
// processa.php
// Lógica central: autenticação, CRUD de produtos e usuários

// Inclui configuração do banco (conexão + session + funções de verificação)
require_once "config/database.php";

// Recebe a ação (pode vir via GET ou POST)
$acao = isset($_REQUEST["acao"]) ? $_REQUEST["acao"] : "";

/**
 * Helper para redirecionar com mensagem (usa urlencode para evitar problemas)
 */
function redirect_with($location, $paramName, $message)
{
    $sep = strpos($location, "?") === false ? "?" : "&";
    header(
        "Location: " .
            $location .
            $sep .
            $paramName .
            "=" .
            urlencode($message),
    );
    exit();
}

switch ($acao) {
    // =======================================================
    // AUTENTICAÇÃO
    // =======================================================
    case "login":
        // Recebe e sanitiza dados
        $usuario = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING);
        $senha = filter_input(INPUT_POST, "senha", FILTER_UNSAFE_RAW); // senha não deve ser alterada

        if (empty($usuario) || empty($senha)) {
            redirect_with("index.php", "erro", "Preencha utilizador e senha.");
        }

        // Prepara e executa a query
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, usuario, senha, nivel FROM usuarios WHERE usuario = ?",
        );
        if (!$stmt) {
            redirect_with(
                "index.php",
                "erro",
                "Erro na consulta de autenticação.",
            );
        }
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if ($resultado && mysqli_num_rows($resultado) === 1) {
            $linha = mysqli_fetch_assoc($resultado);
            if (password_verify($senha, $linha["senha"])) {
                // Sucesso: grava na sessão
                $_SESSION["usuario_logado"] = $linha["usuario"];
                $_SESSION["usuario_nivel"] = $linha["nivel"];
                $_SESSION["usuario_id"] = $linha["id"];
                mysqli_stmt_close($stmt);
                redirect_with(
                    "index.php",
                    "msg",
                    "Bem-vindo(a), {$linha["usuario"]}! Login realizado com sucesso.",
                );
            }
        }

        // Falha no login
        if ($stmt) {
            mysqli_stmt_close($stmt);
        }
        redirect_with(
            "index.php",
            "erro",
            "Credenciais inválidas. Verifique utilizador e senha.",
        );
        break;

    case "logout":
        session_unset();
        session_destroy();
        redirect_with(
            "index.php",
            "msg",
            "Você foi desconectado(a) do sistema.",
        );
        break;

    // =======================================================
    // CRUD DE PRODUTOS
    // =======================================================
    case "cadastrar_produto":
        verificar_login();

        $nome = trim(filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING));
        $quantidade = filter_input(INPUT_POST, "quantidade", FILTER_VALIDATE_INT);
        $preco_custo = filter_input(
            INPUT_POST,
            "preco_custo",
            FILTER_VALIDATE_FLOAT,
        );
        $preco_venda = filter_input(
            INPUT_POST,
            "preco_venda",
            FILTER_VALIDATE_FLOAT,
        );
        $descricao = trim(
            filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING),
        );

        if (empty($nome)) {
            redirect_with(
                "produtos_cadastrar.php",
                "erro",
                "O nome do produto é obrigatório.",
            );
        }

        if ($quantidade === false || $quantidade < 0) {
            redirect_with(
                "produtos_cadastrar.php",
                "erro",
                "A quantidade deve ser um número válido maior ou igual a zero.",
            );
        }

        if ($preco_custo === false || $preco_custo < 0) {
            redirect_with(
                "produtos_cadastrar.php",
                "erro",
                "O preço de custo deve ser um valor válido maior ou igual a zero.",
            );
        }

        if ($preco_venda === false || $preco_venda < 0) {
            redirect_with(
                "produtos_cadastrar.php",
                "erro",
                "O preço de venda deve ser um valor válido maior ou igual a zero.",
            );
        }

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO produtos (nome, quantidade, preco_custo, preco_venda, descricao) VALUES (?, ?, ?, ?, ?)",
        );
        if (!$stmt) {
            redirect_with(
                "produtos_cadastrar.php",
                "erro",
                "Erro ao preparar cadastro: " . mysqli_error($conn),
            );
        }
        mysqli_stmt_bind_param(
            $stmt,
            "sidds",
            $nome,
            $quantidade,
            $preco_custo,
            $preco_venda,
            $descricao,
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            redirect_with(
                "index.php",
                "msg",
                "Produto '{$nome}' cadastrado com sucesso!",
            );
        } else {
            $erro = mysqli_error($conn);
            mysqli_stmt_close($stmt);
            redirect_with(
                "produtos_cadastrar.php",
                "erro",
                "Erro ao cadastrar: " . $erro,
            );
        }
        break;

    case "editar_produto":
        verificar_login();

        $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
        $nome = trim(filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING));
        $quantidade = filter_input(INPUT_POST, "quantidade", FILTER_VALIDATE_INT);
        $preco_custo = filter_input(
            INPUT_POST,
            "preco_custo",
            FILTER_VALIDATE_FLOAT,
        );
        $preco_venda = filter_input(
            INPUT_POST,
            "preco_venda",
            FILTER_VALIDATE_FLOAT,
        );
        $descricao = trim(
            filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING),
        );

        if (!$id || empty($nome)) {
            redirect_with(
                "index.php",
                "erro",
                "Dados inválidos para atualização do produto.",
            );
        }

        if ($quantidade === false || $quantidade < 0) {
            redirect_with(
                "produtos_editar.php?id={$id}",
                "erro",
                "A quantidade deve ser um número válido maior ou igual a zero.",
            );
        }

        if ($preco_custo === false || $preco_custo < 0) {
            redirect_with(
                "produtos_editar.php?id={$id}",
                "erro",
                "O preço de custo deve ser um valor válido.",
            );
        }

        if ($preco_venda === false || $preco_venda < 0) {
            redirect_with(
                "produtos_editar.php?id={$id}",
                "erro",
                "O preço de venda deve ser um valor válido.",
            );
        }

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE produtos SET nome = ?, quantidade = ?, preco_custo = ?, preco_venda = ?, descricao = ? WHERE id = ?",
        );
        if (!$stmt) {
            redirect_with(
                "produtos_editar.php?id={$id}",
                "erro",
                "Erro ao preparar atualização: " . mysqli_error($conn),
            );
        }
        mysqli_stmt_bind_param(
            $stmt,
            "siddsi",
            $nome,
            $quantidade,
            $preco_custo,
            $preco_venda,
            $descricao,
            $id,
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            redirect_with(
                "index.php",
                "msg",
                "Produto '{$nome}' atualizado com sucesso!",
            );
        } else {
            $erro = mysqli_error($conn);
            mysqli_stmt_close($stmt);
            redirect_with(
                "produtos_editar.php?id={$id}",
                "erro",
                "Erro ao atualizar: " . $erro,
            );
        }
        break;

    case "excluir_produto":
        verificar_login();

        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if (!$id) {
            redirect_with("index.php", "erro", "ID de produto inválido.");
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM produtos WHERE id = ?");
        if (!$stmt) {
            redirect_with(
                "index.php",
                "erro",
                "Erro ao preparar exclusão: " . mysqli_error($conn),
            );
        }
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            redirect_with("index.php", "msg", "Produto excluído com sucesso!");
        } else {
            $erro = mysqli_error($conn);
            mysqli_stmt_close($stmt);
            redirect_with("index.php", "erro", "Erro ao excluir: " . $erro);
        }
        break;

    // =======================================================
    // CRUD DE UTILIZADORES (apenas Admin)
    // =======================================================
    case "cadastrar_usuario":
        verificar_admin();

        $usuario = trim(
            filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING),
        );
        $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
        $senha_original = filter_input(INPUT_POST, "senha", FILTER_UNSAFE_RAW);
        $nivel = trim(
            filter_input(INPUT_POST, "nivel", FILTER_SANITIZE_STRING),
        );

        if (empty($usuario) || empty($senha_original) || empty($nivel)) {
            redirect_with(
                "usuarios_cadastrar.php",
                "erro",
                "Preencha todos os campos obrigatórios.",
            );
        }

        $senha_hash = password_hash($senha_original, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO usuarios (usuario, email, senha, nivel) VALUES (?, ?, ?, ?)",
        );
        if (!$stmt) {
            redirect_with(
                "usuarios_cadastrar.php",
                "erro",
                "Erro ao preparar cadastro de utilizador: " .
                    mysqli_error($conn),
            );
        }
        mysqli_stmt_bind_param(
            $stmt,
            "ssss",
            $usuario,
            $email,
            $senha_hash,
            $nivel,
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            redirect_with(
                "usuarios.php",
                "msg",
                "Utilizador '{$usuario}' cadastrado com sucesso!",
            );
        } else {
            $errno = mysqli_errno($conn);
            $erro_msg =
                $errno == 1062
                    ? "Utilizador já existe no banco de dados."
                    : mysqli_error($conn);
            mysqli_stmt_close($stmt);
            redirect_with(
                "usuarios_cadastrar.php",
                "erro",
                "Erro ao cadastrar: " . $erro_msg,
            );
        }
        break;

    case "excluir_usuario":
        verificar_admin();

        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if (!$id) {
            redirect_with("usuarios.php", "erro", "ID de utilizador inválido.");
        }

        // Não permite excluir o próprio utilizador
        if (
            isset($_SESSION["usuario_id"]) &&
            intval($_SESSION["usuario_id"]) === intval($id)
        ) {
            redirect_with(
                "usuarios.php",
                "erro",
                "Você não pode excluir o seu próprio utilizador enquanto logado.",
            );
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM usuarios WHERE id = ?");
        if (!$stmt) {
            redirect_with(
                "usuarios.php",
                "erro",
                "Erro ao preparar exclusão: " . mysqli_error($conn),
            );
        }
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            redirect_with(
                "usuarios.php",
                "msg",
                "Utilizador excluído com sucesso!",
            );
        } else {
            $erro = mysqli_error($conn);
            mysqli_stmt_close($stmt);
            redirect_with("usuarios.php", "erro", "Erro ao excluir: " . $erro);
        }
        break;

    // =======================================================
    // Ação inválida
    // =======================================================
    default:
        redirect_with(
            "index.php",
            "erro",
            "Ação inválida ou não especificada.",
        );
        break;
}

// Fecha a conexão por segurança (caso caia fora dos redirects)
if (isset($conn) && $conn) {
    mysqli_close($conn);
}
exit();
?>
