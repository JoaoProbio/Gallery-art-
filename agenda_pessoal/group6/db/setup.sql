-- ================================================
-- SCRIPT DE CONFIGURAÇÃO DO BANCO DE DADOS
-- Sistema: Painel de Controle de Estoque Básico
-- ================================================

-- 1. Criar o banco de dados (se não existir)
CREATE DATABASE IF NOT EXISTS estoque_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2. Selecionar o banco de dados
USE estoque_db;

-- ================================================
-- TABELA: produtos
-- Armazena os produtos do estoque
-- ================================================
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(200) NOT NULL,
    quantidade INT NOT NULL DEFAULT 0,
    preco_custo DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    preco_venda DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    descricao TEXT,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nome (nome),
    INDEX idx_quantidade (quantidade)
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
    ('admin', 'admin@estoque.com', '$2y$10$N9qo8uLOickgx2ZMRZoMye1IVI5bV7yW/ZLWQ7cJLLZK3WLkKtHSK', 'Admin');

-- Inserir alguns produtos de exemplo
INSERT INTO produtos (nome, quantidade, preco_custo, preco_venda, descricao) VALUES
('Notebook Dell Inspiron 15', 15, 2500.00, 3200.00, 'Notebook com processador Intel Core i5, 8GB RAM, 256GB SSD'),
('Mouse Logitech MX Master', 45, 180.00, 280.00, 'Mouse ergonômico sem fio com bateria recarregável'),
('Teclado Mecânico Redragon', 30, 150.00, 250.00, 'Teclado mecânico RGB com switches blue'),
('Monitor LG 24 Polegadas', 20, 600.00, 850.00, 'Monitor Full HD IPS 24" com suporte VESA'),
('Webcam Logitech C920', 25, 350.00, 520.00, 'Webcam Full HD 1080p com microfone estéreo'),
('Headset HyperX Cloud II', 35, 280.00, 420.00, 'Headset gamer com som surround 7.1'),
('SSD Kingston 480GB', 50, 250.00, 380.00, 'SSD SATA III 2.5" com velocidade de leitura 550MB/s'),
('Memória RAM DDR4 8GB', 60, 150.00, 220.00, 'Memória RAM DDR4 8GB 3200MHz'),
('HD Externo Seagate 1TB', 40, 250.00, 350.00, 'HD externo portátil USB 3.0 1TB'),
('Cabo HDMI 2.0 2m', 100, 15.00, 35.00, 'Cabo HDMI 2.0 com suporte a 4K 60Hz');

-- ================================================
-- VIEWS ÚTEIS
-- ================================================

-- View para calcular margem de lucro
CREATE OR REPLACE VIEW vw_produtos_lucro AS
SELECT
    id,
    nome,
    quantidade,
    preco_custo,
    preco_venda,
    (preco_venda - preco_custo) AS lucro_unitario,
    ROUND(((preco_venda - preco_custo) / preco_custo * 100), 2) AS margem_lucro_percentual,
    (preco_venda - preco_custo) * quantidade AS lucro_total_estoque,
    preco_custo * quantidade AS valor_total_custo,
    preco_venda * quantidade AS valor_total_venda
FROM produtos
ORDER BY nome;

-- View para produtos com estoque baixo (menos de 20 unidades)
CREATE OR REPLACE VIEW vw_estoque_baixo AS
SELECT
    id,
    nome,
    quantidade,
    preco_custo,
    preco_venda
FROM produtos
WHERE quantidade < 20
ORDER BY quantidade ASC, nome ASC;

-- ================================================
-- VERIFICAÇÃO DAS TABELAS CRIADAS
-- ================================================

-- Exibir estrutura da tabela produtos
DESCRIBE produtos;

-- Exibir estrutura da tabela usuarios
DESCRIBE usuarios;

-- Contar registros nas tabelas
SELECT COUNT(*) AS total_produtos FROM produtos;
SELECT COUNT(*) AS total_usuarios FROM usuarios;

-- Estatísticas do estoque
SELECT
    COUNT(*) AS total_produtos,
    SUM(quantidade) AS total_itens_estoque,
    SUM(preco_custo * quantidade) AS valor_total_custo,
    SUM(preco_venda * quantidade) AS valor_total_venda,
    SUM((preco_venda - preco_custo) * quantidade) AS lucro_potencial
FROM produtos;

-- ================================================
-- FIM DO SCRIPT
-- ================================================
