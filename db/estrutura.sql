-- Criação do banco de dados (se necessário, execute no phpMyAdmin)
CREATE DATABASE IF NOT EXISTS galeria_arte;
USE galeria_arte;
-- Tabela de Utilizadores (Para Login/Área Restrita)
CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
usuario VARCHAR(50) NOT NULL UNIQUE,
email VARCHAR(100) NOT NULL,
senha VARCHAR(255) NOT NULL, -- Armazena a senha criptografada (hash)
nivel ENUM('Admin', 'Comum') NOT NULL DEFAULT 'Comum'
);

-- Tabela de Contatos (CRUD Principal da Aplicação)

CREATE TABLE obras (
id INT AUTO_INCREMENT PRIMARY KEY,
titulo VARCHAR(100) NOT NULL,
artista VARCHAR(100) NOT NULL,
tecnica VARCHAR(50) NOT NULL,
ano_criacao INT NOT NULL,
imagem_obra VARCHAR(250),
descricao TEXT,
quem_cadastra VARCHAR(50) NOT NULL,
data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Inserir um utilizador Administrador padrão: 'admin' com senha '123456'
-- O hash é gerado com password_hash('123456', PASSWORD_DEFAULT)
INSERT INTO usuarios (usuario, email, senha, nivel) VALUES
('admin', 'admin@galeria.com.br',
'$2y$10$YiBpez0RPaoPnQM6oH8hTOJ/jehOg7k4NFGMpABJj5HtoOlcHC9G.', 'Admin');