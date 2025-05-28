<?php
define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/clilib.php');

// Cria uma instância da tarefa
$task = new \local_avisos\task\send_avisos();

// Executa a tarefa
try {
    mtrace("Iniciando teste da tarefa de envio de avisos...");
    $task->execute();
    mtrace("Teste concluído com sucesso!");
} catch (Exception $e) {
    mtrace("Erro ao executar a tarefa: " . $e->getMessage());
    exit(1);
} 