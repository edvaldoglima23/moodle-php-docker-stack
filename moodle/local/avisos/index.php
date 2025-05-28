<?php
// Libs necessárias
require_once('../../config.php');
require_once($CFG->libdir.'/tablelib.php');

// Pega ID do curso da URL
$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$context = context_course::instance($courseid);

// Verifica permissões
require_login($course);
require_capability('local/avisos:view', $context);

// Configura a página
$PAGE->set_url(new moodle_url('/local/avisos/index.php', array('courseid' => $courseid)));
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_avisos'));
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add(get_string('pluginname', 'local_avisos'));

// Começa a página
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('pluginname', 'local_avisos'));

// Botão de adicionar (só aparece pra quem pode gerenciar)
if (has_capability('local/avisos:manage', $context)) {
    $addurl = new moodle_url('/local/avisos/add.php', array('courseid' => $courseid));
    echo html_writer::div(
        $OUTPUT->single_button($addurl, get_string('add_aviso', 'local_avisos')),
        'mb-3'
    );
}

// Pega todos os avisos do curso
$avisos = $DB->get_records('local_avisos', array('courseid' => $courseid), 'senddate DESC');

// Se não tem avisos, mostra mensagem
if (empty($avisos)) {
    echo $OUTPUT->notification(get_string('no_avisos', 'local_avisos'), 'info');
} else {
    // Configura a tabela
    $table = new html_table();
    $table->head = array(
        get_string('aviso_title', 'local_avisos'),
        get_string('aviso_date', 'local_avisos'),
        get_string('aviso_status', 'local_avisos'),
        get_string('actions', 'local_avisos')
    );
    $table->attributes['class'] = 'table table-striped';

    // Loop pelos avisos pra montar as linhas
    foreach ($avisos as $aviso) {
        $row = array();
        $row[] = format_string($aviso->title);
        $row[] = userdate($aviso->senddate);
        
        // Monta o status com ícone (publicado/pendente)
        $statusicon = $aviso->sent ? 'i/publish' : 'i/calendar';
        $statustext = $aviso->sent ? 
            get_string('aviso_status_sent', 'local_avisos') : 
            get_string('aviso_status_pending', 'local_avisos');
        $row[] = $OUTPUT->pix_icon($statusicon, $statustext) . ' ' . $statustext;

        // Botões de ação (editar/excluir)
        $actions = '';
        if (has_capability('local/avisos:manage', $context)) {
            $editurl = new moodle_url('/local/avisos/edit.php', 
                array('id' => $aviso->id, 'courseid' => $courseid));
            $deleteurl = new moodle_url('/local/avisos/delete.php', 
                array('id' => $aviso->id, 'courseid' => $courseid));

            // Botão editar
            $actions .= $OUTPUT->action_icon(
                $editurl,
                new pix_icon('t/edit', get_string('edit'), 'moodle', array('class' => 'iconsmall'))
            );

            // Botão excluir
            $actions .= $OUTPUT->action_icon(
                $deleteurl,
                new pix_icon('t/delete', get_string('delete'), 'moodle', array('class' => 'iconsmall'))
            );
        }
        $row[] = $actions;

        $table->data[] = $row;
    }

    // Mostra a tabela
    echo html_writer::table($table);
}

// Fecha a página
echo $OUTPUT->footer(); 