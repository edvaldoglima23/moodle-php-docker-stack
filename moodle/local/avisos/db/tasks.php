<?php
defined('MOODLE_INTERNAL') || die();

$tasks = array(
    array(
        // classe que vai executar o envio
        'classname' => '\local_avisos\task\send_avisos',
        'blocking' => 0, 
        'minute' => '*/5',  
        'hour' => '*', 
        'day' => '*', 
        'month' => '*', 
        'dayofweek' => '*', 
        'disabled' => 0 
    )
); 