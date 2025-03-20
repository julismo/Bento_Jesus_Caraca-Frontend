<?php
/**
 * Monitoramento do Logger de Documentação
 * Escola Profissional Bento de Jesus Caraça
 */

require_once 'logger.php';

class DocumentationMonitor {
    private $logger;
    private $config;
    private $metrics;
    
    public function __construct() {
        $this->loadConfig();
        $this->logger = new DocumentationLogger(
            $this->config['logger']['file'],
            $this->getLogLevel($this->config['logger']['level']),
            $this->config['logger']['format']
        );
        $this->metrics = [
            'log_size' => 0,
            'error_count' => 0,
            'warning_count' => 0,
            'update_frequency' => 0
        ];
    }
    
    /**
     * Carrega configurações
     */
    private function loadConfig() {
        $configFile = __DIR__ . '/config.json';
        if (!file_exists($configFile)) {
            throw new Exception('Arquivo de configuração não encontrado');
        }
        
        $this->config = json_decode(file_get_contents($configFile), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Erro ao decodificar arquivo de configuração');
        }
    }
    
    /**
     * Converte nível de log de string para constante
     */
    private function getLogLevel($level) {
        switch (strtoupper($level)) {
            case 'DEBUG':
                return DocumentationLogger::DEBUG;
            case 'INFO':
                return DocumentationLogger::INFO;
            case 'WARN':
                return DocumentationLogger::WARN;
            case 'ERROR':
                return DocumentationLogger::ERROR;
            default:
                return DocumentationLogger::INFO;
        }
    }
    
    /**
     * Inicia monitoramento
     */
    public function start() {
        if (!$this->config['monitoring']['enabled']) {
            echo "Monitoramento desativado\n";
            return;
        }
        
        echo "Iniciando monitoramento...\n";
        
        while (true) {
            $this->collectMetrics();
            $this->checkAlerts();
            $this->generateReport();
            
            sleep(60); // Intervalo de 1 minuto
        }
    }
    
    /**
     * Coleta métricas
     */
    private function collectMetrics() {
        // Tamanho do log
        if (file_exists($this->config['logger']['file'])) {
            $this->metrics['log_size'] = filesize($this->config['logger']['file']);
        }
        
        // Contagem de erros e avisos
        $content = $this->logger->getLogContent();
        $this->metrics['error_count'] = substr_count($content, '[ERROR]');
        $this->metrics['warning_count'] = substr_count($content, '[WARN]');
        
        // Frequência de atualização
        $this->metrics['update_frequency'] = $this->calculateUpdateFrequency();
    }
    
    /**
     * Calcula frequência de atualização
     */
    private function calculateUpdateFrequency() {
        $content = $this->logger->getLogContent();
        $lines = explode("\n", $content);
        $lastUpdate = 0;
        
        foreach ($lines as $line) {
            if (preg_match('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]/', $line, $matches)) {
                $timestamp = strtotime($matches[1]);
                if ($timestamp > $lastUpdate) {
                    $lastUpdate = $timestamp;
                }
            }
        }
        
        return time() - $lastUpdate;
    }
    
    /**
     * Verifica alertas
     */
    private function checkAlerts() {
        $alerts = $this->config['monitoring']['alerts'];
        
        // Verifica tamanho do log
        if ($this->metrics['log_size'] > $alerts['size_threshold']) {
            $this->sendAlert('Tamanho do log excedido', [
                'current_size' => $this->metrics['log_size'],
                'threshold' => $alerts['size_threshold']
            ]);
        }
        
        // Verifica contagem de erros
        if ($this->metrics['error_count'] > $alerts['error_threshold']) {
            $this->sendAlert('Muitos erros detectados', [
                'error_count' => $this->metrics['error_count'],
                'threshold' => $alerts['error_threshold']
            ]);
        }
        
        // Verifica frequência de atualização
        if ($this->metrics['update_frequency'] > $alerts['update_threshold']) {
            $this->sendAlert('Log não atualizado há muito tempo', [
                'last_update' => $this->metrics['update_frequency'],
                'threshold' => $alerts['update_threshold']
            ]);
        }
    }
    
    /**
     * Envia alerta
     */
    private function sendAlert($message, $context = []) {
        // Log do alerta
        $this->logger->warn($message, $context);
        
        // Email
        if ($this->config['notifications']['email']['enabled']) {
            $this->sendEmail($message, $context);
        }
        
        // Slack
        if ($this->config['notifications']['slack']['enabled']) {
            $this->sendSlack($message, $context);
        }
    }
    
    /**
     * Envia email
     */
    private function sendEmail($message, $context) {
        $recipients = $this->config['notifications']['email']['recipients'];
        if (empty($recipients)) {
            return;
        }
        
        $subject = $this->config['notifications']['email']['subject_prefix'] . ' ' . $message;
        $body = "Alerta: $message\n\n";
        $body .= "Contexto:\n" . json_encode($context, JSON_PRETTY_PRINT);
        
        foreach ($recipients as $recipient) {
            mail($recipient, $subject, $body);
        }
    }
    
    /**
     * Envia mensagem para Slack
     */
    private function sendSlack($message, $context) {
        $webhook = $this->config['notifications']['slack']['webhook'];
        if (empty($webhook)) {
            return;
        }
        
        $payload = [
            'channel' => $this->config['notifications']['slack']['channel'],
            'text' => "*Alerta: $message*\n" . json_encode($context, JSON_PRETTY_PRINT),
            'username' => 'Documentation Monitor'
        ];
        
        $ch = curl_init($webhook);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_exec($ch);
        curl_close($ch);
    }
    
    /**
     * Gera relatório
     */
    private function generateReport() {
        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'metrics' => $this->metrics,
            'status' => 'OK'
        ];
        
        // Salva relatório
        $reportFile = __DIR__ . '/reports/report_' . date('Y-m-d_H-i-s') . '.json';
        if (!is_dir(__DIR__ . '/reports')) {
            mkdir(__DIR__ . '/reports', 0755, true);
        }
        
        file_put_contents($reportFile, json_encode($report, JSON_PRETTY_PRINT));
        
        // Remove relatórios antigos
        $this->cleanOldReports();
    }
    
    /**
     * Remove relatórios antigos
     */
    private function cleanOldReports() {
        $reportsDir = __DIR__ . '/reports';
        $files = glob($reportsDir . '/report_*.json');
        
        foreach ($files as $file) {
            if (time() - filemtime($file) > 86400) { // 24 horas
                unlink($file);
            }
        }
    }
}

// Executa monitoramento
try {
    $monitor = new DocumentationMonitor();
    $monitor->start();
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    exit(1);
} 