<?php
/**
 * Testes do Logger de Documentação
 * Escola Profissional Bento de Jesus Caraça
 */

require_once 'logger.php';

class DocumentationLoggerTest {
    private $logger;
    private $testFile;
    
    public function __construct() {
        $this->testFile = 'logs/test.log';
        $this->logger = new DocumentationLogger(
            $this->testFile,
            DocumentationLogger::DEBUG,
            DocumentationLogger::FORMAT_DETAILED
        );
    }
    
    public function runTests() {
        echo "Iniciando testes do Logger...\n\n";
        
        $this->testLogLevels();
        $this->testLogFormats();
        $this->testLogRotation();
        $this->testLogContent();
        $this->testLogClear();
        
        echo "\nTestes concluídos!\n";
    }
    
    private function testLogLevels() {
        echo "Testando níveis de log...\n";
        
        // Teste DEBUG
        $this->logger->setLogLevel(DocumentationLogger::DEBUG);
        $this->logger->debug('Mensagem de debug');
        $this->logger->info('Mensagem de info');
        $this->logger->warn('Mensagem de warn');
        $this->logger->error('Mensagem de error');
        
        // Teste INFO
        $this->logger->setLogLevel(DocumentationLogger::INFO);
        $this->logger->debug('Mensagem de debug (não deve aparecer)');
        $this->logger->info('Mensagem de info');
        $this->logger->warn('Mensagem de warn');
        $this->logger->error('Mensagem de error');
        
        // Teste WARN
        $this->logger->setLogLevel(DocumentationLogger::WARN);
        $this->logger->debug('Mensagem de debug (não deve aparecer)');
        $this->logger->info('Mensagem de info (não deve aparecer)');
        $this->logger->warn('Mensagem de warn');
        $this->logger->error('Mensagem de error');
        
        // Teste ERROR
        $this->logger->setLogLevel(DocumentationLogger::ERROR);
        $this->logger->debug('Mensagem de debug (não deve aparecer)');
        $this->logger->info('Mensagem de info (não deve aparecer)');
        $this->logger->warn('Mensagem de warn (não deve aparecer)');
        $this->logger->error('Mensagem de error');
        
        echo "✓ Teste de níveis concluído\n";
    }
    
    private function testLogFormats() {
        echo "\nTestando formatos de log...\n";
        
        // Teste FORMAT_SIMPLE
        $this->logger->setLogFormat(DocumentationLogger::FORMAT_SIMPLE);
        $this->logger->info('Mensagem simples');
        
        // Teste FORMAT_DETAILED
        $this->logger->setLogFormat(DocumentationLogger::FORMAT_DETAILED);
        $this->logger->info('Mensagem detalhada', ['context' => 'teste']);
        
        // Teste FORMAT_JSON
        $this->logger->setLogFormat(DocumentationLogger::FORMAT_JSON);
        $this->logger->info('Mensagem JSON', ['context' => 'teste']);
        
        echo "✓ Teste de formatos concluído\n";
    }
    
    private function testLogRotation() {
        echo "\nTestando rotação de log...\n";
        
        // Cria um arquivo de log grande
        $this->logger->setLogFormat(DocumentationLogger::FORMAT_SIMPLE);
        for ($i = 0; $i < 1000; $i++) {
            $this->logger->info("Linha de teste $i");
        }
        
        // Testa rotação
        $this->logger->rotateLog(1024); // 1KB
        
        if (file_exists($this->testFile . '_' . date('Y-m-d_H-i-s'))) {
            echo "✓ Rotação de log concluída\n";
        } else {
            echo "✗ Falha na rotação de log\n";
        }
    }
    
    private function testLogContent() {
        echo "\nTestando conteúdo do log...\n";
        
        // Limpa o log
        $this->logger->clearLog();
        
        // Adiciona algumas mensagens
        $this->logger->info('Mensagem 1');
        $this->logger->info('Mensagem 2');
        $this->logger->info('Mensagem 3');
        
        // Testa obtenção de conteúdo
        $content = $this->logger->getLogContent();
        if (strpos($content, 'Mensagem 1') !== false &&
            strpos($content, 'Mensagem 2') !== false &&
            strpos($content, 'Mensagem 3') !== false) {
            echo "✓ Obtenção de conteúdo concluída\n";
        } else {
            echo "✗ Falha na obtenção de conteúdo\n";
        }
        
        // Testa obtenção de últimas linhas
        $lastLines = $this->logger->getLogContent(2);
        if (substr_count($lastLines, 'Mensagem') === 2) {
            echo "✓ Obtenção de últimas linhas concluída\n";
        } else {
            echo "✗ Falha na obtenção de últimas linhas\n";
        }
    }
    
    private function testLogClear() {
        echo "\nTestando limpeza de log...\n";
        
        // Adiciona algumas mensagens
        $this->logger->info('Mensagem para limpar');
        
        // Limpa o log
        $this->logger->clearLog();
        
        // Verifica se está vazio
        if (!file_exists($this->testFile) || filesize($this->testFile) === 0) {
            echo "✓ Limpeza de log concluída\n";
        } else {
            echo "✗ Falha na limpeza de log\n";
        }
    }
}

// Executa os testes
$test = new DocumentationLoggerTest();
$test->runTests(); 