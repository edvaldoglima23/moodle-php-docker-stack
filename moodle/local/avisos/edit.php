<?php
// Carrega as libs do Moodle
require_once('../../config.php');
require_once($CFG->libdir . '/formslib.php');

// Formulário de edição do aviso
class aviso_edit_form extends moodleform {
    public function definition() {
        global $DB;
        
        $mform = $this->_form;

        // Título do aviso
        $mform->addElement('text', 'title', get_string('aviso_title', 'local_avisos'), array('size' => '64'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required', null, 'client');

        // Conteúdo do aviso
        $mform->addElement('editor', 'message', get_string('aviso_content', 'local_avisos'));
        $mform->setType('message', PARAM_RAW);
        $mform->addRule('message', null, 'required', null, 'client');

        // Data e hora para envio
        $mform->addElement('date_time_selector', 'senddate', get_string('aviso_date', 'local_avisos'));
        $mform->addRule('senddate', null, 'required', null, 'client');

        // Campos ocultos
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $this->add_action_buttons();
    }
}

// Pega os IDs necessários da URL
$id = required_param('id', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);

// Carrega dados do curso e do aviso
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$aviso = $DB->get_record('local_avisos', array('id' => $id), '*', MUST_EXIST);
$context = context_course::instance($courseid);

// Checa permissões
require_login($course);
require_capability('local/avisos:manage', $context);

// Setup da página
$PAGE->set_url(new moodle_url('/local/avisos/edit.php', array('id' => $id, 'courseid' => $courseid)));
$PAGE->set_context($context);
$PAGE->set_title(get_string('edit_aviso', 'local_avisos'));
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add(get_string('pluginname', 'local_avisos'), new moodle_url('/local/avisos/index.php', array('courseid' => $courseid)));
$PAGE->navbar->add(get_string('edit_aviso', 'local_avisos'));

// Prepara o formulário
$mform = new aviso_edit_form();

// Se selecionou um template, carrega os dados dele
if (!empty($aviso->templateid)) {
    $template = $DB->get_record('local_avisos_templates', array('id' => $aviso->templateid));
    if ($template) {
        $aviso->title = $template->subject;
        $aviso->message = $template->content;
    }
}

// Formata a mensagem pro editor
$aviso->message = array('text' => $aviso->message, 'format' => FORMAT_HTML);
$mform->set_data($aviso);

// Processa o form
if ($mform->is_cancelled()) {
    // Volta pra lista se cancelou
    redirect(new moodle_url('/local/avisos/index.php', array('courseid' => $courseid)));
} else if ($data = $mform->get_data()) {
    $aviso = new stdClass();
    $aviso->id = $data->id;
    $aviso->title = $data->title;
    $aviso->message = $data->message['text'];
    $aviso->senddate = $data->senddate;

    // Salva e redireciona
    $DB->update_record('local_avisos', $aviso);
    redirect(
        new moodle_url('/local/avisos/index.php', array('courseid' => $courseid)),
        get_string('aviso_saved', 'local_avisos'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

// Mostra o formulário
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('edit_aviso', 'local_avisos'));
$mform->display();
echo $OUTPUT->footer(); 