# Contexto do Projeto para IAs - Escola Profissional Bento de Jesus Caraça

## Visão Geral
Este projeto é um site institucional para a Escola Profissional Bento de Jesus Caraça, focado em apresentar informações sobre a escola, sua história, obras e cursos. O sistema inclui uma área administrativa para gerenciamento de conteúdo.

## Objetivos Principais
1. Divulgar informações sobre a escola e sua história
2. Apresentar o catálogo de obras de Bento de Jesus Caraça
3. Fornecer informações sobre cursos disponíveis
4. Facilitar comunicação com alunos e visitantes
5. Permitir gerenciamento de conteúdo via painel administrativo

## Regras de Negócio
- Todo o conteúdo deve estar em português
- Interface deve ser responsiva e acessível
- Sistema deve suportar visualização de PDFs
- Formulários devem validar dados antes do envio
- Conteúdo deve ser facilmente atualizável
- Acesso administrativo requer autenticação
- Apenas administradores podem gerenciar obras
- Upload de PDFs restrito a administradores

## Terminologia
- EPBJC: Escola Profissional Bento de Jesus Caraça
- BJC: Bento de Jesus Caraça
- Obras: Referência aos livros e publicações de Bento de Jesus Caraça
- Cursos: Programas educacionais oferecidos pela escola
- Admin: Usuário com acesso ao painel administrativo
- Dashboard: Painel de controle administrativo

## Decisões de Design
- Uso de Bootstrap 5 para interface responsiva
- PHP para backend e processamento
- MySQL para armazenamento de dados
- Bibliotecas de terceiros para funcionalidades específicas:
  - AOS para animações
  - GLightbox para galerias
  - Swiper para carrosséis
  - Bootstrap Icons para ícones
  - SweetAlert2 para notificações
  - Dropzone.js para uploads

## Estrutura de Dados
- Tabela 'users': Armazena informações dos administradores
  - ID
  - Email
  - Senha (hash)
  - Nome
  - Role (admin/editor)
  - Timestamps

- Tabela 'obras': Armazena informações sobre as obras
  - ID
  - Título
  - Descrição
  - Ano de publicação
  - Link para PDF
  - Imagem de capa
  - Timestamps

## Fluxos de Processamento
1. **Visualização de Obras**:
   - Listagem de obras
   - Visualização detalhada
   - Download de PDFs

2. **Formulários**:
   - Validação de dados
   - Processamento de contato
   - Gestão de newsletter

3. **Navegação**:
   - Menu responsivo
   - Breadcrumbs
   - Links internos

4. **Administração**:
   - Autenticação de usuários
   - Gerenciamento de obras
   - Upload de arquivos
   - Logs de atividades

## Áreas de Automação
1. **Validação de Formulários**:
   - Verificação de campos obrigatórios
   - Validação de formato de email
   - Sanitização de dados

2. **Processamento de PDFs**:
   - Geração de previews
   - Otimização de arquivos
   - Controle de acesso

3. **SEO**:
   - Geração de meta tags
   - Sitemap dinâmico
   - Otimização de imagens

4. **Segurança**:
   - Proteção contra XSS
   - Validação de uploads
   - Controle de sessão

## Análises Necessárias
1. **Performance**:
   - Tempo de carregamento
   - Otimização de recursos
   - Cache de dados

2. **Acessibilidade**:
   - Conformidade com WCAG
   - Compatibilidade com leitores de tela
   - Navegação por teclado

3. **Segurança**:
   - Proteção contra XSS
   - Validação de inputs
   - Sanitização de outputs
   - Proteção contra força bruta

## Sugestões de Melhorias
1. **Interface**:
   - Implementar modo escuro
   - Melhorar contraste
   - Adicionar mais animações

2. **Funcionalidades**:
   - Sistema de busca
   - Filtros avançados
   - Compartilhamento social
   - Editor WYSIWYG para obras

3. **Performance**:
   - Implementar lazy loading
   - Otimizar imagens
   - Melhorar cache

4. **Administração**:
   - Dashboard com estatísticas
   - Sistema de backup
   - Logs detalhados

## Contexto Histórico
- Bento de Jesus Caraça foi um importante matemático e intelectual português
- A escola leva seu nome em homenagem
- O site deve refletir a importância histórica e cultural

## Requisitos Técnicos
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx
- Suporte a PDF
- Compatibilidade com navegadores modernos
- Extensões PHP:
  - GD para manipulação de imagens
  - PDO para conexão com banco
  - Session para autenticação

## Manutenção
- Atualização regular de conteúdo
- Backup de banco de dados
- Monitoramento de logs
- Atualização de bibliotecas
- Verificação de segurança
- Manutenção de sessões 