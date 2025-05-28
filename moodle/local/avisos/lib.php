<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Estende a navegação do curso para incluir o plugin de avisos
 */
function local_avisos_extend_navigation_course($navigation, $course, $context) {
    // Só mostra o menu para quem tem permissão de gerenciar
    if (has_capability('local/avisos:manage', $context)) {
        $url = new moodle_url('/local/avisos/index.php', array('courseid' => $course->id));
        $navigation->add(
            get_string('pluginname', 'local_avisos'),
            $url,
            navigation_node::TYPE_CUSTOM,
            null,
            'avisos',
            new pix_icon('i/announcement', '')
        );
    }
}

/**
 * Gerencia o acesso aos arquivos da área de avisos
 * 
 * Esta função controla como os arquivos anexados aos avisos
 * são disponibilizados para download
 */
function local_avisos_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // Verifica se estamos no contexto correto
    if ($context->contextlevel != CONTEXT_COURSE) {
        return false;
    }

    // Garante que o usuário está logado no curso
    require_login($course);

    // Só permite acesso à área de anexos
    if ($filearea !== 'attachment') {
        return false;
    }

    // Processa os argumentos da URL para localizar o arquivo
    $itemid = array_shift($args);
    $filename = array_pop($args);
    $filepath = $args ? '/'.implode('/', $args).'/' : '/';

    // Busca e envia o arquivo solicitado
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_avisos', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false;
    }

    send_stored_file($file, 0, 0, true, $options);
}

/**
 * Registra as tarefas agendadas
 */
function local_avisos_cron_scheduled_tasks() {
    $tasks = array(
        array(
            'classname' => 'local_avisos\task\send_avisos',
            'blocking' => 0,
            'minute' => '*/5',
            'hour' => '*',
            'day' => '*',
            'month' => '*',
            'dayofweek' => '*',
            'disabled' => 0
        )
    );
    return $tasks;
}

/**
 * Exibe aviso no topo do curso 'eu eu mesmo e irene' (ID 3) usando o hook de navegação
 */
function local_avisos_extend_navigation($nav) {
    global $COURSE, $DB, $OUTPUT, $PAGE;
    if (isset($COURSE) && $COURSE->id == 3 && $PAGE->pagetype === 'course-view') {
        $aviso = $DB->get_record('local_avisos', ['courseid' => 3, 'sent' => 1], 'id, title, message, senddate', IGNORE_MULTIPLE, 'senddate DESC');
        if ($aviso) {
            // Mostra o aviso no topo da página do curso
            echo $OUTPUT->notification("<b>{$aviso->title}</b><br>{$aviso->message}", 'success');
        }
    }
} 