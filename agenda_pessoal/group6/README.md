# 📦 Sistema de Controle de Estoque Básico

Sistema web desenvolvido em PHP procedural para gerenciar estoque de produtos com controle de custos, preços de venda e cálculo automático de margens de lucro.

## 📋 Características

- ✅ Cadastro completo de produtos (nome, quantidade, preço custo/venda, descrição)
- ✅ Listagem de produtos com informações detalhadas
- ✅ Edição e exclusão de produtos
- ✅ Dashboard com estatísticas do estoque
- ✅ Cálculo automático de margem de lucro
- ✅ Alertas de estoque baixo e crítico
- ✅ Sistema de autenticação para segurança
- ✅ Calculadora de lucro em tempo real
- ✅ Interface responsiva com Bootstrap 5
- ✅ Painel administrativo para gerenciar usuários

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 7.4+ (Procedural)
- **Banco de Dados**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
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

1. Certifique-se de que a pasta está em: `C:\xampp\htdocs\group6\`

### Passo 3: Criar o Banco de Dados

1. Acesse o phpMyAdmin: http://localhost/phpmyadmin
2. Clique em **"SQL"** no menu superior
3. Abra o arquivo `group6/db/setup.sql` e copie todo o conteúdo
4. Cole o conteúdo na área SQL do phpMyAdmin
5. Clique em **"Executar"**

**Ou manualmente via linha de comando:**

```bash
mysql -u root -p < C:\xampp\htdocs\group6\db\setup.sql
```

### Passo 4: Verificar a Configuração do Banco

Abra o arquivo `config/database.php` e verifique se as credenciais estão corretas:

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estoque_db";
```

### Passo 5: Acessar o Sistema

Abra seu navegador e acesse: **http://localhost/group6/**

## 👤 Credenciais de Teste

### Administrador
- **Utilizador**: `admin`
- **Senha**: `123456`

## 📖 Como Usar

### 1. Fazer Login

1. Acesse: http://localhost/group6/
2. Digite as credenciais: `admin` / `123456`
3. Clique em "Entrar no Sistema"

### 2. Visualizar o Dashboard

Após o login, você verá:
- **Total de Produtos**: Quantidade de produtos cadastrados
- **Itens em Estoque**: Soma total de unidades
- **Valor em Custo**: Valor total investido no estoque
- **Lucro Potencial**: Lucro se vender todo o estoque

### 3. Cadastrar Produtos

1. Clique em "➕ Adicionar Novo Produto" ou use o menu superior
2. Preencha os campos obrigatórios:
   - **Nome**: Nome do produto
   - **Quantidade**: Unidades em estoque
   - **Preço de Custo**: Quanto você pagou ao fornecedor
   - **Preço de Venda**: Quanto você venderá ao cliente
   - **Descrição**: Detalhes do produto (opcional)
3. Use a calculadora de margem para visualizar o lucro
4. Clique em "💾 Cadastrar Produto"

### 4. Editar Produtos

1. Na lista de produtos, clique no botão "✏️" (Editar)
2. Modifique os campos desejados
3. Clique em "💾 Salvar Alterações"

### 5. Excluir Produtos

1. Na lista de produtos, clique no botão "🗑️" (Excluir)
2. Confirme a exclusão

### 6. Gerenciar Usuários (Admin)

1. Acesse o menu "👥 Gerenciar Usuários"
2. Você pode:
   - Ver todos os usuários do sistema
   - Cadastrar novos usuários
   - Excluir usuários (exceto você mesmo)

## 📁 Estrutura de Arquivos

```
group6/
│
├── assets/              # Recursos estáticos
│   └── style.css        # CSS customizado
├── config/              # Configurações
│   └── database.php     # Configuração de banco e funções
├── db/                  # Scripts de banco de dados
│   └── setup.sql        # Script de criação e dados iniciais
├── includes/            # Arquivos de inclusão
│   ├── header.php       # Cabeçalho HTML e navegação
│   └── footer.php       # Rodapé HTML
│
├── index.php            # Página principal (lista de produtos)
├── processa.php         # Processa todas as ações
├── produtos_cadastrar.php  # Cadastro de produtos
├── produtos_editar.php     # Edição de produtos
├── usuarios.php            # Gerenciamento de usuários (admin)
├── usuarios_cadastrar.php  # Cadastro de usuários (admin)
└── README.md               # Este arquivo
```

## 🗄️ Estrutura do Banco de Dados

### Tabela: `produtos`
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT (PK) | Identificador único |
| nome | VARCHAR(200) | Nome do produto |
| quantidade | INT | Unidades em estoque |
| preco_custo | DECIMAL(10,2) | Preço de custo |
| preco_venda | DECIMAL(10,2) | Preço de venda |
| descricao | TEXT | Descrição do produto |
| data_cadastro | DATETIME | Data de cadastro |
| data_atualizacao | DATETIME | Data da última atualização |

### Tabela: `usuarios`
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT (PK) | Identificador único |
| usuario | VARCHAR(50) | Nome de usuário (único) |
| email | VARCHAR(100) | Email do usuário |
| senha | VARCHAR(255) | Senha criptografada (hash) |
| nivel | ENUM | 'Utilizador' ou 'Admin' |
| data_criacao | DATETIME | Data de criação da conta |

### Views Disponíveis

#### `vw_produtos_lucro`
View que calcula automaticamente:
- Lucro unitário
- Margem de lucro percentual
- Lucro total do estoque
- Valor total em custo
- Valor total em venda

#### `vw_estoque_baixo`
View que lista produtos com estoque menor que 20 unidades.

## 🎨 Funcionalidades Especiais

### 📊 Dashboard Inteligente
- Cards coloridos com estatísticas em tempo real
- Valores formatados em moeda brasileira (R$)
- Cálculos automáticos de lucro potencial

### 🚨 Alertas de Estoque
- **Crítico**: Menos de 10 unidades (badge vermelho)
- **Baixo**: Entre 10 e 19 unidades (badge amarelo)
- **Normal**: 20 ou mais unidades

### 🧮 Calculadora de Margem
- Cálculo em tempo real enquanto digita
- Mostra lucro unitário e total
- Código de cores baseado na margem:
  - Verde: ≥30% (ótimo)
  - Amarelo: 15-29% (bom)
  - Vermelho: <15% (baixo)

### 💰 Formatação Monetária
- Todos os valores em formato brasileiro (R$ 1.234,56)
- Cálculos precisos com 2 casas decimais

## 🐛 Solução de Problemas

### Erro: "Falha na conexão com o banco de dados"
- Verifique se o MySQL está rodando no XAMPP
- Confirme as credenciais em `config/database.php`
- Certifique-se de que o banco `estoque_db` foi criado

### Erro: "Call to undefined function mysqli_connect()"
- Abra: `C:\xampp\php\php.ini`
- Procure: `;extension=mysqli`
- Remova o ";" para: `extension=mysqli`
- Salve e reinicie o Apache no XAMPP

### Produtos não aparecem
- Verifique se há dados na tabela `produtos` no phpMyAdmin
- Execute o script `db/setup.sql` que inclui produtos de exemplo
- Aperte F12 no navegador e veja se há erros no Console

### Não consigo fazer login
- Verifique se existe o usuário "admin" na tabela "usuarios"
- Use: admin / 123456
- Limpe cookies do navegador (Ctrl+Shift+Del)
- Tente em modo anônimo

### Calculadora não funciona
- Verifique se o JavaScript está habilitado no navegador
- Abra o Console (F12) e veja se há erros
- Certifique-se de que jQuery está carregando

## 💡 Dicas de Uso

### Cadastro de Produtos
- Mantenha nomes descritivos e claros
- Sempre preencha a descrição para referência futura
- Use preços com 2 casas decimais (ex: 99.90)
- Atualize a quantidade regularmente

### Precificação
- **Margem ideal**: 30% ou mais
- **Margem mínima**: 15%
- Considere custos operacionais na margem
- Pesquise preços da concorrência

### Gestão de Estoque
- Monitore produtos com estoque crítico
- Faça pedidos antes do estoque zerar
- Mantenha produtos mais vendidos sempre disponíveis
- Revise periodicamente produtos parados

## 🌐 URLs do Sistema

- **Página Principal**: http://localhost/group6/
- **Cadastrar Produto**: http://localhost/group6/produtos_cadastrar.php
- **Gerenciar Usuários**: http://localhost/group6/usuarios.php

## 📧 Suporte

Para dúvidas ou problemas, consulte:
- Documentação do PHP: https://www.php.net/
- Documentação do MySQL: https://dev.mysql.com/doc/
- Bootstrap: https://getbootstrap.com/

## 📄 Licença

Este é um projeto educacional desenvolvido para fins de aprendizado.

## 👨‍💻 Desenvolvimento

Desenvolvido seguindo boas práticas:
- Código organizado e comentado
- Separação de responsabilidades
- Reutilização de código
- Validação em múltiplas camadas
- Funções auxiliares para formatação
- Views no banco para consultas complexas

---

**Desenvolvido com ❤️ para aprendizado**
