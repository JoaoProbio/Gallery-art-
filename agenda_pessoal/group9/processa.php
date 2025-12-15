<?php
// processa.php
// Lógica central: autenticação e gerenciamento de comentários

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
            redirect_with(
                "usuarios.php",
                "erro",
                "Preencha utilizador e senha.",
            );
        }

        // Prepara e executa a query
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, usuario, senha, nivel FROM usuarios WHERE usuario = ?",
        );
        if (!$stmt) {
            redirect_with(
                "usuarios.php",
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
                    "usuarios.php",
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
            "usuarios.php",
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
    // GERENCIAMENTO DE COMENTÁRIOS
    // =======================================================
    case "adicionar_comentario":
        // Recebe e sanitiza dados do formulário
        $nome = trim(filter_input(INPUT_POST, "nome", FILTER_SANITIZE_STRING));
        $email = trim(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
        $mensagem = trim(
            filter_input(INPUT_POST, "mensagem", FILTER_SANITIZE_STRING),
        );

        // Validação
        if (empty($nome)) {
            redirect_with("index.php", "erro", "O nome é obrigatório.");
        }

        if (empty($mensagem)) {
            redirect_with("index.php", "erro", "A mensagem é obrigatória.");
        }

        // Limita o tamanho da mensagem
        if (strlen($mensagem) > 1000) {
            redirect_with(
                "index.php",
                "erro",
                "A mensagem não pode ter mais de 1000 caracteres.",
            );
        }

        // Prepara a query para inserir o comentário
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO comentarios (nome, email, mensagem, data_criacao) VALUES (?, ?, ?, NOW())",
        );

        if (!$stmt) {
            redirect_with(
                "index.php",
                "erro",
                "Erro ao preparar cadastro: " . mysqli_error($conn),
            );
        }

        mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $mensagem);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            redirect_with(
                "index.php",
                "msg",
                "Comentário adicionado com sucesso! Obrigado pela sua mensagem.",
            );
        } else {
            $erro = mysqli_error($conn);
            mysqli_stmt_close($stmt);
            redirect_with(
                "index.php",
                "erro",
                "Erro ao adicionar comentário: " . $erro,
            );
        }
        break;

    case "excluir_comentario":
        verificar_admin();

        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
        if (!$id) {
            redirect_with("index.php", "erro", "ID de comentário inválido.");
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM comentarios WHERE id = ?");
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
            redirect_with(
                "index.php",
                "msg",
                "Comentário excluído com sucesso!",
            );
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
