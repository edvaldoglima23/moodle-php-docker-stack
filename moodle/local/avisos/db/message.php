<?php
defined('MOODLE_INTERNAL') || die();

$messageproviders = array(
    'avisos' => array(
        'capability' => 'local/avisos:receiveavisos',
        'defaults' => array(
            'popup' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED,
            'email' => MESSAGE_PERMITTED + MESSAGE_DEFAULT_ENABLED
        ),
    ),
); 