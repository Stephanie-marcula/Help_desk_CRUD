<?php
/**
 * Copie este arquivo para app/config.php e preencha com os dados do seu banco.
 *
 * No XAMPP/local, normalmente fica:
 * DB_HOST = localhost
 * DB_NAME = sistema_chamados
 * DB_USER = root
 * DB_PASS = ''
 */

return [
    'db_host' => 'localhost',
    'db_name' => 'sistema_chamados',
    'db_user' => 'root',
    'db_pass' => '',
    'db_charset' => 'utf8mb4',
    'app_debug' => false,

    // Proteções para deploy demonstrativo/portfólio
    'demo_mode' => true,
    'max_chamados' => 50,
    'max_titulo' => 100,
    'max_descricao' => 1000,
];
