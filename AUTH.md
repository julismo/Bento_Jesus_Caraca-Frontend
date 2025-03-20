# Sistema de Autenticação - Escola Profissional Bento de Jesus Caraça

## Visão Geral
Sistema de autenticação para administradores do site, permitindo gerenciamento seguro do conteúdo.

## Estrutura de Arquivos
```
projeto/
├── admin/
│   ├── dashboard.php      # Painel principal do administrador
│   ├── obras/
│   │   ├── list.php      # Listagem de obras
│   │   ├── add.php       # Adicionar nova obra
│   │   ├── edit.php      # Editar obra existente
│   │   └── delete.php    # Remover obra
│   └── includes/
│       ├── auth.php      # Funções de autenticação
│       └── header.php    # Cabeçalho do painel
├── assets/
│   └── css/
│       └── admin.css     # Estilos do painel administrativo
└── login.php            # Página de login
```

## Funcionalidades

### 1. Autenticação
- Login seguro com email e senha
- Recuperação de senha
- Sessão persistente
- Proteção contra ataques de força bruta

### 2. Painel Administrativo
- Dashboard com estatísticas
- Gerenciamento de obras
- Upload de PDFs
- Gerenciamento de imagens
- Logs de atividades

### 3. Gerenciamento de Obras
- Adicionar novas obras
- Editar obras existentes
- Remover obras
- Upload de PDFs
- Gerenciamento de imagens de capa

## Implementação

### 1. Banco de Dados
```sql
-- Tabela de usuários
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de obras
CREATE TABLE obras (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    ano_publicacao INT,
    pdf_path VARCHAR(255),
    imagem_capa VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 2. Página de Login
```php
// login.php
<?php
session_start();
require_once 'admin/includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (authenticate($email, $password)) {
        $_SESSION['user_id'] = getUserId($email);
        $_SESSION['role'] = getUserRole($email);
        header('Location: admin/dashboard.php');
        exit;
    }
}
?>
```

### 3. Proteção de Rotas
```php
// admin/includes/auth.php
<?php
function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
}

function requireAdmin() {
    requireAuth();
    if ($_SESSION['role'] !== 'admin') {
        header('Location: /admin/dashboard.php');
        exit;
    }
}
?>
```

## Segurança

### 1. Proteção contra Ataques
- Senhas hasheadas com bcrypt
- Proteção contra SQL Injection
- Proteção contra XSS
- CSRF tokens
- Rate limiting

### 2. Validações
- Validação de email
- Força da senha
- Validação de arquivos
- Sanitização de inputs

### 3. Logs
- Registro de tentativas de login
- Registro de ações administrativas
- Monitoramento de erros

## Interface do Usuário

### 1. Login
- Formulário simples e intuitivo
- Mensagens de erro claras
- Link para recuperação de senha

### 2. Dashboard
- Visão geral das estatísticas
- Ações rápidas
- Menu de navegação

### 3. Gerenciamento de Obras
- Interface intuitiva
- Preview de imagens
- Upload drag-and-drop
- Validação em tempo real

## Boas Práticas

### 1. Código
- Separação de responsabilidades
- Reutilização de código
- Documentação clara
- Tratamento de erros

### 2. Segurança
- Validação de inputs
- Sanitização de outputs
- Proteção contra ataques
- Logs de segurança

### 3. UX
- Feedback visual
- Mensagens claras
- Navegação intuitiva
- Responsividade

## Manutenção

### 1. Backup
- Backup regular do banco
- Backup de arquivos
- Logs de backup

### 2. Monitoramento
- Logs de acesso
- Logs de erros
- Métricas de uso

### 3. Atualizações
- Atualização de dependências
- Correção de bugs
- Melhorias de segurança 