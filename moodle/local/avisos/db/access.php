<?php
// Proteção contra acesso direto
defined('MOODLE_INTERNAL') || die();

// Define as permissões do plugin
$capabilities = [
    // Permissão para gerenciar avisos
    'local/avisos:manage' => [
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'archetypes' => [
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ]
    ],
    // Permissão para visualizar avisos
    'local/avisos:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ]
    ],
    // Permissão para receber avisos
    'local/avisos:receiveavisos' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ]
    ]
];
