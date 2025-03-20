# Estrutura do Projeto - Escola Profissional Bento de Jesus Caraça

## Visão Geral
Este documento descreve a organização e estrutura de pastas do projeto frontend da Escola Profissional Bento de Jesus Caraça.

## Estrutura de Diretórios

```
Bento_Jesus_Caraca-Frontend/
├── assets/                     # Recursos estáticos
│   ├── css/                   # Arquivos CSS
│   │   ├── main.css          # Estilos principais
│   │   └── pdf-viewer.css    # Estilos do visualizador PDF
│   ├── img/                  # Imagens do site
│   │   ├── BJC_logo.png     # Logo da escola
│   │   ├── about-2.jpg      # Imagens institucionais
│   │   └── ...              # Outras imagens
│   ├── js/                   # Scripts JavaScript
│   │   ├── main.js          # JavaScript principal
│   │   └── pdf-viewer.js    # Script do visualizador PDF
│   ├── pdf/                  # Arquivos PDF
│   │   └── Obras/           # PDFs das obras
│   ├── scss/                # Arquivos SCSS
│   └── vendor/              # Bibliotecas de terceiros
│       ├── aos/             # Animate On Scroll Library
│       ├── bootstrap/       # Framework Bootstrap
│       ├── bootstrap-icons/ # Ícones do Bootstrap
│       ├── glightbox/      # Lightbox para galerias
│       ├── swiper/         # Slider/Carrossel
│       └── php-email-form/ # Formulário de email PHP
│
├── forms/                    # Processamento de formulários
│   ├── contact.php          # Processamento do contato
│   └── newsletter.php       # Processamento da newsletter
│
├── .git/                     # Repositório Git
├── .gitignore               # Arquivos ignorados pelo Git
├── .hintrc                  # Configurações do hint
├── ConfigBD.php             # Configuração do banco de dados
├── Menu.php                 # Componente do menu
├── README.md               # Documentação principal
├── contact.php             # Página de contato
├── course-details.php      # Detalhes dos cursos
├── footer.php              # Componente do rodapé
├── index.php              # Página inicial
├── legado.php            # Página sobre o legado
├── login.php             # Página de login
├── obras.php             # Página de obras
├── package.json          # Dependências NPM
├── starter-page.php      # Template de página inicial
└── vida.php             # Página sobre a vida

```

## Descrição dos Componentes Principais

### 1. Arquivos de Configuração
- `ConfigBD.php`: Configurações de conexão com o banco de dados
- `.env`: Variáveis de ambiente (não versionado)
- `package.json`: Gerenciamento de dependências NPM
- `.gitignore`: Configuração de arquivos ignorados pelo Git
- `.hintrc`: Configurações de linting/hints

### 2. Componentes do Layout
- `Menu.php`: Navegação principal do site
- `footer.php`: Rodapé padrão
- `starter-page.php`: Template base para novas páginas

### 3. Páginas Principais
- `index.php`: Página inicial
- `vida.php`: Biografia e história
- `obras.php`: Catálogo de obras
- `legado.php`: Informações sobre o legado
- `course-details.php`: Detalhes dos cursos
- `contact.php`: Página de contato
- `login.php`: Sistema de autenticação

### 4. Processamento de Formulários
- `forms/contact.php`: Processamento do formulário de contato
- `forms/newsletter.php`: Gestão de inscrições na newsletter

### 5. Assets
- **CSS**: Estilos e temas
- **JavaScript**: Scripts de interatividade
- **Imagens**: Recursos visuais
- **PDFs**: Documentos e obras
- **Vendor**: Bibliotecas externas

## Padrões de Desenvolvimento

### Organização de Código
1. **Componentes**: Separados em arquivos PHP individuais
2. **Assets**: Organizados por tipo em subdiretórios
3. **Formulários**: Processamento isolado na pasta forms
4. **Bibliotecas**: Gerenciadas na pasta vendor

### Convenções de Nomenclatura
1. **Arquivos PHP**: 
   - Páginas: `nome-da-pagina.php`
   - Componentes: `NomeComponente.php`
2. **Assets**:
   - CSS: `nome-do-estilo.css`
   - JS: `nome-do-script.js`
   - Imagens: `nome-descritivo.extensao`

### Boas Práticas
1. Manter separação de responsabilidades
2. Usar includes para componentes reutilizáveis
3. Organizar assets por tipo e função
4. Manter bibliotecas de terceiros separadas
5. Documentar alterações significativas

## Fluxo de Desenvolvimento

1. **Criação de Novas Páginas**:
   - Copiar `starter-page.php`
   - Incluir componentes necessários
   - Adicionar conteúdo específico

2. **Adição de Recursos**:
   - Colocar em pasta apropriada em assets/
   - Seguir convenções de nomenclatura
   - Atualizar documentação se necessário

3. **Modificações no Layout**:
   - Atualizar componentes relevantes
   - Testar em todas as páginas afetadas
   - Documentar mudanças significativas

## Manutenção

1. **Backups**:
   - Banco de dados: regularmente
   - Arquivos: versionados no Git
   - Assets: backup separado

2. **Atualizações**:
   - Bibliotecas: via package.json
   - Componentes: individualmente
   - Documentação: manter atualizada

3. **Monitoramento**:
   - Logs de erro
   - Performance
   - Segurança 