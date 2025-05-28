<?php
namespace local_avisos\task;

/**
 * Tarefa agendada para envio de avisos
 */
class send_avisos extends \core\task\scheduled_task {
    
    /**
     * Nome da tarefa
     */
    public function get_name() {
        return get_string('task_send_avisos', 'local_avisos');
    }

    /**
     * Executa o envio dos avisos
     */
    public function execute() {
        global $DB, $CFG;
        require_once($CFG->libdir . '/moodlelib.php');

        mtrace('Iniciando envio de avisos agendados...');
        
        try {
            $now = time();
            $avisos = $this->get_pending_avisos($now);
            
            foreach ($avisos as $aviso) {
                mtrace("Processando aviso {$aviso->id} para o curso {$aviso->coursename}");
                
                try {
                    $users = $this->get_course_users($aviso->courseid);
                    $this->send_notifications($aviso, $users);
                    $this->mark_as_sent($aviso);
                    
                    mtrace("Aviso {$aviso->id} processado com sucesso");
                } catch (\Exception $e) {
                    mtrace("Erro ao processar aviso {$aviso->id}: " . $e->getMessage());
                    // Registra o erro mas continua processando outros avisos
                    continue;
                }
            }
            
            mtrace('Finalizado envio de avisos agendados');
        } catch (\Exception $e) {
            mtrace('Erro fatal no processamento de avisos: ' . $e->getMessage());
            return;
        }
    }

    /**
     * Busca avisos pendentes
     */
    private function get_pending_avisos($now) {
        global $DB;
        
        $sql = "SELECT a.*, c.fullname as coursename 
                FROM {local_avisos} a 
                JOIN {course} c ON c.id = a.courseid 
                WHERE a.senddate <= ? AND a.sent = 0";
                
        return $DB->get_records_sql($sql, [$now]);
    }

    /**
     * Busca usuários do curso
     */
    private function get_course_users($courseid) {
        $context = \context_course::instance($courseid);
        return get_enrolled_users($context);
    }

    /**
     * Envia notificações para os usuários
     */
    private function send_notifications($aviso, $users) {
        foreach ($users as $user) {
            $this->send_single_notification($aviso, $user);
        }
    }

    /**
     * Envia uma notificação individual
     */
    private function send_single_notification($aviso, $user) {
        $message = new \core\message\message();
        $message->courseid = $aviso->courseid;
        $message->component = 'local_avisos';
        $message->name = 'avisos';
        $message->userfrom = \core_user::get_noreply_user();
        $message->userto = $user;
        $message->subject = $aviso->title;
        $message->fullmessage = $aviso->message;
        $message->fullmessageformat = FORMAT_HTML;
        $message->fullmessagehtml = $aviso->message;
        $message->smallmessage = $aviso->title;
        $message->notification = true;
        $message->contexturl = new \moodle_url('/local/avisos/view.php', ['id' => $aviso->id]);
        $message->contexturlname = get_string('view_aviso', 'local_avisos');
        
        $messageid = message_send($message);
        mtrace("Enviado aviso {$messageid} para usuário {$user->id}");
    }

    /**
     * Marca aviso como enviado
     */
    private function mark_as_sent($aviso) {
        global $DB;
        
        $aviso->sent = 1;
        $aviso->timemodified = time();
        $DB->update_record('local_avisos', $aviso);
    }
} 