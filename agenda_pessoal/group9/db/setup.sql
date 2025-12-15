-- ================================================
-- SCRIPT DE CONFIGURAÇÃO DO BANCO DE DADOS
-- Sistema: Livro de Visitas / Comentários Simples
-- ================================================

-- 1. Criar o banco de dados (se não existir)
CREATE DATABASE IF NOT EXISTS livro_visitas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2. Selecionar o banco de dados
USE livro_visitas_db;

-- ================================================
-- TABELA: comentarios
-- Armazena os comentários deixados pelos visitantes
-- ================================================
CREATE TABLE IF NOT EXISTS comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    mensagem TEXT NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_data_criacao (data_criacao DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- TABELA: usuarios
-- Armazena os usuários do sistema (administradores)
-- ================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nivel ENUM('Utilizador', 'Admin') DEFAULT 'Utilizador',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================
-- INSERÇÃO DE DADOS INICIAIS
-- ================================================

-- Inserir usuário administrador padrão
-- Utilizador: admin
-- Senha: 123456
INSERT INTO usuarios (usuario, email, senha, nivel) VALUES
('admin', 'admin@livrovisitas.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin');

-- Inserir alguns comentários de exemplo
INSERT INTO comentarios (nome, email, mensagem, data_criacao) VALUES
('João Silva', 'joao@email.com', 'Excelente sistema! Muito fácil de usar e com uma interface limpa. Parabéns aos desenvolvedores!', '2024-01-15 10:30:00'),
('Maria Santos', 'maria@email.com', 'Adorei poder deixar meu comentário aqui. Sistema muito intuitivo!', '2024-01-16 14:45:00'),
('Pedro Costa', NULL, 'Ótima iniciativa! Espero que este projeto continue evoluindo.', '2024-01-17 09:20:00'),
('Ana Paula', 'ana.paula@email.com', 'Sistema simples e funcional. Exatamente o que eu precisava para meu projeto. Obrigada!', '2024-01-18 16:10:00'),
('Carlos Mendes', 'carlos@email.com', 'Muito bom! A organização dos comentários em ordem cronológica reversa facilita muito a visualização.', '2024-01-19 11:55:00');

-- ================================================
-- VERIFICAÇÃO DAS TABELAS CRIADAS
-- ================================================

-- Exibir estrutura da tabela comentarios
DESCRIBE comentarios;

-- Exibir estrutura da tabela usuarios
DESCRIBE usuarios;

-- Contar registros nas tabelas
SELECT COUNT(*) AS total_comentarios FROM comentarios;
SELECT COUNT(*) AS total_usuarios FROM usuarios;

-- ================================================
-- FIM DO SCRIPT
-- ================================================
