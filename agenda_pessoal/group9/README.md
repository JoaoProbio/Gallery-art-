# 📖 Livro de Visitas / Comentários Simples

Sistema web simples desenvolvido em PHP procedural para permitir que visitantes deixem comentários com nome, email opcional e mensagem. Os comentários são exibidos em ordem cronológica reversa (mais recentes primeiro).

## 📋 Características

- ✅ Sistema público de comentários (qualquer visitante pode deixar comentários)
- ✅ Campos: Nome (obrigatório), Email (opcional), Mensagem (obrigatória)
- ✅ Exibição em ordem cronológica reversa
- ✅ Painel administrativo para gerenciar usuários e excluir comentários
- ✅ Sistema de autenticação para administradores
- ✅ Interface responsiva com Bootstrap 5
- ✅ Validação e sanitização de dados
- ✅ Proteção contra SQL Injection usando prepared statements

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 7.4+ (Procedural)
- **Banco de Dados**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Servidor**: Apache (XAMPP)

## 📦 Requisitos do Sistema

- XAMPP (ou similar) com:
  - PHP 7.4 ou superior
  - MySQL 5.7 ou superior
  - Apache Web Server
- Navegador web moderno

## 🚀 Instalação

### Passo 1: Preparar o Ambiente

1. Instale o XAMPP em seu computador
2. Inicie os serviços **Apache** e **MySQL** no painel de controle do XAMPP

### Passo 2: Configurar o Projeto

1. Copie a pasta `group9` para o diretório `C:\xampp\htdocs\`
2. A estrutura final deve ser: `C:\xampp\htdocs\group9\`

### Passo 3: Criar o Banco de Dados

1. Acesse o phpMyAdmin: http://localhost/phpmyadmin
2. Clique em **"SQL"** no menu superior
3. Abra o arquivo `group9/db/setup.sql` e copie todo o conteúdo
4. Cole o conteúdo na área SQL do phpMyAdmin
5. Clique em **"Executar"**

**Ou manualmente via linha de comando:**

```bash
mysql -u root -p < C:\xampp\htdocs\group9\db\setup.sql
```

### Passo 4: Verificar a Configuração do Banco

Abra o arquivo `config/database.php` e verifique se as credenciais estão corretas:

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "livro_visitas_db";
```

### Passo 5: Acessar o Sistema

Abra seu navegador e acesse: **http://localhost/group9/**

## 👤 Credenciais de Teste

### Administrador
- **Utilizador**: `admin`
- **Senha**: `123456`

> Para fazer login como administrador, acesse:
  "http://localhost/group9/usuarios.php"

## 📖 Como Usar

### Para Visitantes (Público)

1. Acesse a página principal: http://localhost/group9/
2. Preencha o formulário:
   - **Nome**: Campo obrigatório
   - **Email**: Campo opcional
   - **Mensagem**: Campo obrigatório (máximo 1000 caracteres)
3. Clique em **"📨 Enviar Comentário"**
4. Seu comentário aparecerá na lista à direita

### Para Administradores

1. Acesse: http://localhost/group9/usuarios.php
2. Faça login com as credenciais de administrador
3. Você poderá:
   - Ver todos os comentários
   - Excluir comentários inapropriados (botão 🗑️ Excluir aparece em cada comentário)
   - Gerenciar usuários do sistema
   - Cadastrar novos administradores

## 📁 Estrutura de Arquivos

```
group9/
│
├── assets/              # Recursos estáticos (imagens, CSS, JS personalizados)
├── config/              # Configurações
│   └── database.php     # Configuração de banco de dados e sessões
├── db/                  # Scripts de banco de dados
│   └── setup.sql        # Script de criação do banco e tabelas
├── includes/            # Arquivos de inclusão
│   ├── header.php       # Cabeçalho HTML e navegação
│   └── footer.php       # Rodapé HTML
│
├── index.php            # Página principal (exibe comentários e formulário)
├── processa.php         # Processa todas as ações (login, comentários, etc)
├── usuarios.php         # Gerenciamento de usuários (apenas admin)
├── usuarios_cadastrar.php  # Cadastro de novos usuários (apenas admin)
└── README.md            # Este arquivo
```

## 🗄️ Estrutura do Banco de Dados

### Tabela: `comentarios`
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT (PK) | Identificador único |
| nome | VARCHAR(100) | Nome do visitante |
| email | VARCHAR(100) | Email do visitante (opcional) |
| mensagem | TEXT | Mensagem/comentário |
| data_criacao | DATETIME | Data e hora do comentário |

### Tabela: `usuarios`
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT (PK) | Identificador único |
| usuario | VARCHAR(50) | Nome de usuário (único) |
| email | VARCHAR(100) | Email do usuário |
| senha | VARCHAR(255) | Senha criptografada (hash) |
| nivel | ENUM | 'Utilizador' ou 'Admin' |
| data_criacao | DATETIME | Data de criação da conta |

## 🔒 Segurança

- ✅ Senhas armazenadas com hash (bcrypt)
- ✅ Prepared statements para prevenir SQL Injection
- ✅ Sanitização de entradas do usuário
- ✅ Validação de dados no backend
- ✅ Proteção de páginas administrativas
- ✅ Escape de HTML para prevenir XSS

## 🐛 Solução de Problemas

### Erro: "Falha na conexão com o banco de dados"
- Verifique se o MySQL está rodando no XAMPP
- Confirme as credenciais em `config/database.php`
- Certifique-se de que o banco `livro_visitas_db` foi criado

### Erro: "Call to undefined function mysqli_connect()"
- Verifique se a extensão `php_mysqli` está habilitada no `php.ini`
- Reinicie o Apache após alterar o `php.ini`

### Comentários não aparecem
- Verifique se há dados na tabela `comentarios` no phpMyAdmin
- Confira se não há erros no console do navegador (F12)

### Não consigo fazer login
- Verifique se a tabela `usuarios` tem o usuário `admin`
- Use as credenciais: admin / 123456
- Limpe os cookies do navegador

## 👨‍💻 Desenvolvimento

Este projeto foi desenvolvido seguindo boas práticas de programação:
- Código organizado e comentado
- Separação de responsabilidades
- Reutilização de código
- Validação em múltiplas camadas

## 📄 Licença

Este é um projeto educacional desenvolvido para fins de aprendizado.

## 📧 Suporte

Para dúvidas ou problemas, consulte:
- Documentação do PHP: https://www.php.net/
- Documentação do MySQL: https://dev.mysql.com/doc/
- Bootstrap: https://getbootstrap.com/

---

**Desenvolvido com ❤️ para aprendizado**
