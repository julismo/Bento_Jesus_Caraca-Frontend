<?php
/**
 * Logger para Documentação
 * Escola Profissional Bento de Jesus Caraça
 */

class DocumentationLogger {
    private $logFile;
    private $logLevel;
    private $logFormat;
    
    // Níveis de log
    const DEBUG = 0;
    const INFO = 1;
    const WARN = 2;
    const ERROR = 3;
    
    // Formatos de log
    const FORMAT_SIMPLE = 'simple';
    const FORMAT_DETAILED = 'detailed';
    const FORMAT_JSON = 'json';
    
    public function __construct(
        $logFile = 'logs/documentation.log',
        $logLevel = self::INFO,
        $logFormat = self::FORMAT_DETAILED
    ) {
        $this->logFile = $logFile;
        $this->logLevel = $logLevel;
        $this->logFormat = $logFormat;
        
        // Cria diretório de logs se não existir
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    /**
     * Registra uma mensagem de debug
     */
    public function debug($message, $context = []) {
        if ($this->logLevel <= self::DEBUG) {
            $this->log('DEBUG', $message, $context);
        }
    }
    
    /**
     * Registra uma mensagem de informação
     */
    public function info($message, $context = []) {
        if ($this->logLevel <= self::INFO) {
            $this->log('INFO', $message, $context);
        }
    }
    
    /**
     * Registra uma mensagem de aviso
     */
    public function warn($message, $context = []) {
        if ($this->logLevel <= self::WARN) {
            $this->log('WARN', $message, $context);
        }
    }
    
    /**
     * Registra uma mensagem de erro
     */
    public function error($message, $context = []) {
        if ($this->logLevel <= self::ERROR) {
            $this->log('ERROR', $message, $context);
        }
    }
    
    /**
     * Registra uma mensagem de log
     */
    private function log($level, $message, $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = $this->formatLogEntry($level, $message, $context, $timestamp);
        
        file_put_contents(
            $this->logFile,
            $logEntry . PHP_EOL,
            FILE_APPEND
        );
    }
    
    /**
     * Formata a entrada de log
     */
    private function formatLogEntry($level, $message, $context, $timestamp) {
        switch ($this->logFormat) {
            case self::FORMAT_SIMPLE:
                return "[$timestamp] $level: $message";
                
            case self::FORMAT_DETAILED:
                $entry = "[$timestamp] $level: $message";
                if (!empty($context)) {
                    $entry .= "\nContext: " . json_encode($context, JSON_PRETTY_PRINT);
                }
                return $entry;
                
            case self::FORMAT_JSON:
                return json_encode([
                    'timestamp' => $timestamp,
                    'level' => $level,
                    'message' => $message,
                    'context' => $context
                ]);
                
            default:
                return "[$timestamp] $level: $message";
        }
    }
    
    /**
     * Define o nível de log
     */
    public function setLogLevel($level) {
        $this->logLevel = $level;
    }
    
    /**
     * Define o formato de log
     */
    public function setLogFormat($format) {
        $this->logFormat = $format;
    }
    
    /**
     * Define o arquivo de log
     */
    public function setLogFile($file) {
        $this->logFile = $file;
    }
    
    /**
     * Obtém o conteúdo do log
     */
    public function getLogContent($lines = null) {
        if (!file_exists($this->logFile)) {
            return '';
        }
        
        if ($lines === null) {
            return file_get_contents($this->logFile);
        }
        
        $content = file($this->logFile);
        return implode('', array_slice($content, -$lines));
    }
    
    /**
     * Limpa o arquivo de log
     */
    public function clearLog() {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
    }
    
    /**
     * Rotaciona o arquivo de log
     */
    public function rotateLog($maxSize = 5242880) { // 5MB
        if (!file_exists($this->logFile)) {
            return;
        }
        
        if (filesize($this->logFile) > $maxSize) {
            $info = pathinfo($this->logFile);
            $rotatedFile = $info['dirname'] . '/' . 
                          $info['filename'] . '_' . 
                          date('Y-m-d_H-i-s') . '.' . 
                          $info['extension'];
            
            rename($this->logFile, $rotatedFile);
        }
    }
}

// Exemplo de uso
/*
$logger = new DocumentationLogger(
    'logs/documentation.log',
    DocumentationLogger::DEBUG,
    DocumentationLogger::FORMAT_DETAILED
);

$logger->info('Iniciando atualização da documentação');
$logger->debug('Verificando arquivos', ['path' => 'docs/']);
$logger->warn('Arquivo não encontrado', ['file' => 'config.json']);
$logger->error('Falha ao atualizar', ['error' => 'Permissão negada']);
*/ 