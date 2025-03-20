<?php
/**
 * Testes do Monitor de Documentação
 * Escola Profissional Bento de Jesus Caraça
 */

require_once 'monitor.php';

class DocumentationMonitorTest {
    private $monitor;
    private $testConfig;
    
    public function __construct() {
        // Cria configuração de teste
        $this->testConfig = [
            'logger' => [
                'file' => __DIR__ . '/test.log',
                'level' => 'DEBUG',
                'format' => 'detailed'
            ],
            'monitoring' => [
                'enabled' => true,
                'alerts' => [
                    'size_threshold' => 1024, // 1KB
                    'error_threshold' => 5,
                    'update_threshold' => 300 // 5 minutos
                ]
            ],
            'notifications' => [
                'email' => [
                    'enabled' => false,
                    'recipients' => [],
                    'subject_prefix' => '[TEST]'
                ],
                'slack' => [
                    'enabled' => false,
                    'webhook' => '',
                    'channel' => '#test'
                ]
            ]
        ];
        
        // Salva configuração de teste
        file_put_contents(__DIR__ . '/config.json', json_encode($this->testConfig, JSON_PRETTY_PRINT));
        
        // Inicializa monitor
        $this->monitor = new DocumentationMonitor();
    }
    
    /**
     * Executa todos os testes
     */
    public function runTests() {
        echo "Iniciando testes do monitor...\n\n";
        
        $this->testMetricsCollection();
        $this->testAlerts();
        $this->testReportGeneration();
        $this->testCleanup();
        
        echo "\nTestes concluídos!\n";
    }
    
    /**
     * Testa coleta de métricas
     */
    private function testMetricsCollection() {
        echo "Testando coleta de métricas...\n";
        
        // Cria arquivo de log de teste
        $logFile = $this->testConfig['logger']['file'];
        file_put_contents($logFile, str_repeat('x', 512)); // 512 bytes
        
        // Adiciona algumas mensagens de log
        $logger = new DocumentationLogger($logFile, DocumentationLogger::DEBUG, 'detailed');
        $logger->error('Teste de erro 1');
        $logger->error('Teste de erro 2');
        $logger->warn('Teste de aviso 1');
        $logger->warn('Teste de aviso 2');
        
        // Coleta métricas
        $this->monitor->collectMetrics();
        
        // Verifica métricas
        $metrics = $this->monitor->getMetrics();
        
        if ($metrics['log_size'] !== 512) {
            echo "❌ Falha: Tamanho do log incorreto\n";
            echo "Esperado: 512, Obtido: {$metrics['log_size']}\n";
        } else {
            echo "✓ Tamanho do log correto\n";
        }
        
        if ($metrics['error_count'] !== 2) {
            echo "❌ Falha: Contagem de erros incorreta\n";
            echo "Esperado: 2, Obtido: {$metrics['error_count']}\n";
        } else {
            echo "✓ Contagem de erros correta\n";
        }
        
        if ($metrics['warning_count'] !== 2) {
            echo "❌ Falha: Contagem de avisos incorreta\n";
            echo "Esperado: 2, Obtido: {$metrics['warning_count']}\n";
        } else {
            echo "✓ Contagem de avisos correta\n";
        }
        
        echo "\n";
    }
    
    /**
     * Testa sistema de alertas
     */
    private function testAlerts() {
        echo "Testando sistema de alertas...\n";
        
        // Testa alerta de tamanho
        file_put_contents($this->testConfig['logger']['file'], str_repeat('x', 2048)); // 2KB
        $this->monitor->collectMetrics();
        $this->monitor->checkAlerts();
        
        // Testa alerta de erros
        $logger = new DocumentationLogger($this->testConfig['logger']['file'], DocumentationLogger::DEBUG, 'detailed');
        for ($i = 0; $i < 6; $i++) {
            $logger->error("Teste de erro $i");
        }
        $this->monitor->collectMetrics();
        $this->monitor->checkAlerts();
        
        // Testa alerta de atualização
        sleep(6); // Espera 6 segundos
        $this->monitor->collectMetrics();
        $this->monitor->checkAlerts();
        
        echo "✓ Alertas testados\n\n";
    }
    
    /**
     * Testa geração de relatórios
     */
    private function testReportGeneration() {
        echo "Testando geração de relatórios...\n";
        
        $this->monitor->generateReport();
        
        $reportsDir = __DIR__ . '/reports';
        $files = glob($reportsDir . '/report_*.json');
        
        if (empty($files)) {
            echo "❌ Falha: Nenhum relatório gerado\n";
        } else {
            echo "✓ Relatório gerado: " . basename($files[0]) . "\n";
            
            // Verifica conteúdo do relatório
            $report = json_decode(file_get_contents($files[0]), true);
            
            if (!isset($report['timestamp'])) {
                echo "❌ Falha: Relatório sem timestamp\n";
            } else {
                echo "✓ Relatório com timestamp\n";
            }
            
            if (!isset($report['metrics'])) {
                echo "❌ Falha: Relatório sem métricas\n";
            } else {
                echo "✓ Relatório com métricas\n";
            }
            
            if (!isset($report['status'])) {
                echo "❌ Falha: Relatório sem status\n";
            } else {
                echo "✓ Relatório com status\n";
            }
        }
        
        echo "\n";
    }
    
    /**
     * Testa limpeza de relatórios antigos
     */
    private function testCleanup() {
        echo "Testando limpeza de relatórios...\n";
        
        $reportsDir = __DIR__ . '/reports';
        
        // Cria relatório antigo
        $oldReport = $reportsDir . '/report_old.json';
        file_put_contents($oldReport, json_encode(['timestamp' => '2023-01-01 00:00:00']));
        touch($oldReport, strtotime('-25 hours')); // 25 horas atrás
        
        // Cria relatório recente
        $newReport = $reportsDir . '/report_new.json';
        file_put_contents($newReport, json_encode(['timestamp' => date('Y-m-d H:i:s')]));
        
        $this->monitor->cleanOldReports();
        
        if (file_exists($oldReport)) {
            echo "❌ Falha: Relatório antigo não foi removido\n";
        } else {
            echo "✓ Relatório antigo removido\n";
        }
        
        if (!file_exists($newReport)) {
            echo "❌ Falha: Relatório recente foi removido\n";
        } else {
            echo "✓ Relatório recente mantido\n";
        }
        
        echo "\n";
    }
    
    /**
     * Limpa arquivos de teste
     */
    public function cleanup() {
        // Remove arquivo de configuração de teste
        if (file_exists(__DIR__ . '/config.json')) {
            unlink(__DIR__ . '/config.json');
        }
        
        // Remove arquivo de log de teste
        if (file_exists($this->testConfig['logger']['file'])) {
            unlink($this->testConfig['logger']['file']);
        }
        
        // Remove relatórios de teste
        $reportsDir = __DIR__ . '/reports';
        if (is_dir($reportsDir)) {
            array_map('unlink', glob($reportsDir . '/*'));
            rmdir($reportsDir);
        }
    }
}

// Executa testes
try {
    $test = new DocumentationMonitorTest();
    $test->runTests();
    $test->cleanup();
} catch (Exception $e) {
    echo "Erro nos testes: " . $e->getMessage() . "\n";
    exit(1);
} 