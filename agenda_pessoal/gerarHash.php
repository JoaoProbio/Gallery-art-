<?php
// Script para gerar hash da senha '123456'

$senha = "123456";

// Gerar o hash com PASSWORD_DEFAULT (que é equivalente a PASSWORD_BCRYPT)
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Exibir o hash gerado
echo "=== HASH GERADO PARA A SENHA '123456' ===\n\n";
echo $senhaHash . "\n\n";

// Exibir o comando SQL completo para copiar
echo "=== COPIE ESTE COMANDO PARA SEU setup.sql ===\n\n";
echo "-- Inserir um utilizador Administrador padrão: 'admin' com senha '123456'\n";
echo "-- O hash é gerado com password_hash('123456', PASSWORD_DEFAULT)\n";
echo "INSERT INTO usuarios (usuario, email, senha, nivel) VALUES\n";
echo "('admin', 'admin@agenda.com.br',\n";
echo "'$senhaHash', 'Admin');\n\n";

// Teste para verificar se o hash está correto
echo "=== TESTE DE VERIFICAÇÃO ===\n";
echo "Testando se a senha '123456' funciona com o hash...\n";
if (password_verify($senha, $senhaHash)) {
    echo "✓ SUCESSO! A senha está correta.\n";
} else {
    echo "✗ ERRO! A senha não funciona com o hash.\n";
}
?>
