<?php
namespace local_avisos\form;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class aviso_form extends \moodleform {
    public function definition() {
        global $DB;
        
        $mform = $this->_form;
        
        // Título
        $mform->addElement('text', 'title', get_string('title', 'local_avisos'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('required'), 'required');
        
        // Mensagem
        $mform->addElement('editor', 'message', get_string('message', 'local_avisos'));
        $mform->setType('message', PARAM_RAW);
        $mform->addRule('message', get_string('required'), 'required');
        
        // Data de envio
        $mform->addElement('date_time_selector', 'senddate', get_string('senddate', 'local_avisos'));
        $mform->addRule('senddate', get_string('required'), 'required');
        
        // ID do aviso (hidden)
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        
        // ID do curso (hidden)
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        
        $this->add_action_buttons();
    }
} 