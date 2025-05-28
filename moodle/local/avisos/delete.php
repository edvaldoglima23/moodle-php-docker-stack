<?php
// arquivos necessários
require_once('../../config.php');

// pega os parâmetros da url
$id = required_param('id', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// carrega o aviso e o curso
$aviso = $DB->get_record('local_avisos', array('id' => $id), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$context = context_course::instance($courseid);

// verifica permissões
require_login($course);
require_capability('local/avisos:manage', $context);

// configura a página
$PAGE->set_url(new moodle_url('/local/avisos/delete.php', array('id' => $id, 'courseid' => $courseid)));
$PAGE->set_context($context);
$PAGE->set_title(get_string('delete_aviso', 'local_avisos'));
$PAGE->set_heading($course->fullname);

// breadcrumb
$PAGE->navbar->add(get_string('pluginname', 'local_avisos'), 
    new moodle_url('/local/avisos/index.php', array('courseid' => $courseid)));
$PAGE->navbar->add(get_string('delete_aviso', 'local_avisos'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('delete_aviso', 'local_avisos'));

// confirmação de exclusão
if (optional_param('confirm', 0, PARAM_INT)) {
    // exclui o aviso
    $DB->delete_records('local_avisos', array('id' => $id));
    
    // mensagem de sucesso e volta pra lista
    redirect(
        new moodle_url('/local/avisos/index.php', array('courseid' => $courseid)),
        get_string('aviso_deleted', 'local_avisos'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
} else {
    // mostra mensagem de confirmação
    echo $OUTPUT->confirm(
        get_string('delete_aviso_confirm', 'local_avisos', format_string($aviso->title)),
        new moodle_url('/local/avisos/delete.php', array('id' => $id, 'courseid' => $courseid, 'confirm' => 1)),
        new moodle_url('/local/avisos/index.php', array('courseid' => $courseid))
    );
}

echo $OUTPUT->footer(); 