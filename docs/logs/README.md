# Sistema de Logs e Monitoramento

Este diretório contém o sistema de logs e monitoramento da documentação do projeto.

## Estrutura

```
logs/
├── config.json           # Configurações do sistema
├── logger.php           # Classe principal de logging
├── monitor.php          # Sistema de monitoramento
├── test_logger.php      # Testes do logger
├── test_monitor.php     # Testes do monitor
├── reports/             # Relatórios gerados
└── backups/             # Backups dos logs
```

## Componentes

### Logger (`logger.php`)

Classe responsável por gerenciar os logs da documentação. Principais funcionalidades:

- Diferentes níveis de log (DEBUG, INFO, WARN, ERROR)
- Múltiplos formatos de log (simples, detalhado, JSON)
- Rotação automática de logs
- Limpeza de logs antigos

### Monitor (`monitor.php`)

Sistema de monitoramento que coleta métricas e gera alertas. Funcionalidades:

- Coleta de métricas (tamanho, erros, avisos)
- Sistema de alertas configurável
- Geração de relatórios
- Notificações por email e Slack

## Configuração

O arquivo `config.json` permite configurar:

- Níveis e formatos de log
- Limites para alertas
- Configurações de notificação
- Parâmetros de monitoramento

## Testes

Para executar os testes:

```bash
# Testar logger
php test_logger.php

# Testar monitor
php test_monitor.php
```

## Uso

### Iniciar Monitoramento

```bash
php monitor.php
```

### Ver Logs

```bash
# Ver último log
tail -f logs/documentation.log

# Ver relatórios
ls reports/
```

## Manutenção

### Limpeza de Logs

Os logs são automaticamente:
- Rotacionados quando atingem o tamanho máximo
- Limpos após o período de retenção
- Compactados antes da remoção

### Backup

Backups são gerados:
- Diariamente
- Antes de operações críticas
- Manualmente quando necessário

## Segurança

- Logs sensíveis são criptografados
- Permissões de arquivo restritas
- Validação de integridade
- Monitoramento de acesso

## Monitoramento

O sistema monitora:
- Tamanho dos logs
- Taxa de erros
- Frequência de atualização
- Uso de recursos

## Alertas

Alertas são gerados para:
- Logs muito grandes
- Muitos erros
- Falta de atualização
- Problemas de segurança

## Relatórios

Relatórios incluem:
- Métricas coletadas
- Status do sistema
- Tendências
- Recomendações

## Contribuição

Para contribuir:
1. Siga os padrões de código
2. Adicione testes
3. Atualize documentação
4. Faça pull request

## Suporte

Em caso de problemas:
1. Verifique os logs
2. Consulte a documentação
3. Abra uma issue
4. Contate o suporte 