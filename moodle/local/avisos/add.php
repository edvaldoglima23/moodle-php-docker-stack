<?php
// Arquivos necessários do Moodle
require_once('../../config.php');
require_once($CFG->libdir . '/formslib.php');

// Formulário para criar um novo aviso
class aviso_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        
        $mform->addElement('text', 'title', get_string('aviso_title', 'local_avisos'), array('size' => '64'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required', null, 'client');

        
        $mform->addElement('editor', 'message', get_string('aviso_content', 'local_avisos'));
        $mform->setType('message', PARAM_RAW);
        $mform->addRule('message', null, 'required', null, 'client');

        
        $mform->addElement('date_time_selector', 'senddate', get_string('aviso_date', 'local_avisos'));
        $mform->addRule('senddate', null, 'required', null, 'client');

        
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);

        $this->add_action_buttons();
    }
}

// Pega o ID do curso da URL
$courseid = required_param('courseid', PARAM_INT);
$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
$context = context_course::instance($courseid);

// Verifica permissões do usuário
require_login($course);
require_capability('local/avisos:manage', $context);

// Configura a página
$PAGE->set_url(new moodle_url('/local/avisos/add.php', array('courseid' => $courseid)));
$PAGE->set_context($context);
$PAGE->set_title(get_string('add_aviso', 'local_avisos'));
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add(get_string('pluginname', 'local_avisos'), new moodle_url('/local/avisos/index.php', array('courseid' => $courseid)));
$PAGE->navbar->add(get_string('add_aviso', 'local_avisos'));

// Cria e configura o formulário
$mform = new aviso_form();
$mform->set_data(array('courseid' => $courseid));

// Processa o formulário
if ($mform->is_cancelled()) {
    // Se cancelou, volta pra lista
    redirect(new moodle_url('/local/avisos/index.php', array('courseid' => $courseid)));
} else if ($data = $mform->get_data()) {
    // Se enviou, salva o aviso
    $aviso = new stdClass();
    $aviso->courseid = $data->courseid;
    $aviso->title = $data->title;
    $aviso->message = $data->message['text'];
    $aviso->senddate = $data->senddate;
    
    $aviso->sent = 0;  // Começa como não enviado
    $aviso->timecreated = time();
    $aviso->timemodified = time();
    $aviso->createdby = $USER->id;

    // Salva no banco e redireciona com mensagem de sucesso
    $DB->insert_record('local_avisos', $aviso);
    redirect(
        new moodle_url('/local/avisos/index.php', array('courseid' => $courseid)),
        get_string('aviso_saved', 'local_avisos'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

// Mostra o formulário
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('add_aviso', 'local_avisos'));
$mform->display();
echo $OUTPUT->footer(); 