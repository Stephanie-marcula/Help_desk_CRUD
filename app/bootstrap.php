<?php


session_start();

define('BASE_PATH', dirname(__DIR__));
define('APP_NAME', 'HelpDesk Pro');
define('APP_VERSION', '1.0.0');

require_once BASE_PATH . '/app/helpers.php';
require_once BASE_PATH . '/app/CSRF.php';
require_once BASE_PATH . '/app/Chamado.php';

$configPath = BASE_PATH . '/app/config.php';
$examplePath = BASE_PATH . '/app/config.example.php';

if (!file_exists($configPath)) {
    http_response_code(500);
    $erroDeploy = 'Arquivo app/config.php não encontrado. Copie app/config.example.php para app/config.php e preencha os dados do banco.';
    require BASE_PATH . '/views/erro.php';
    exit;
}

$config = require $configPath;

$host    = $config['db_host'] ?? 'localhost';
$dbname  = $config['db_name'] ?? 'sistema_chamados';
$user    = $config['db_user'] ?? 'root';
$pass    = $config['db_pass'] ?? '';
$charset = $config['db_charset'] ?? 'utf8mb4';
$debug   = (bool)($config['app_debug'] ?? false);

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset={$charset}",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    $erroDeploy = $debug
        ? 'Erro de conexão com o banco: ' . $e->getMessage()
        : 'Não foi possível conectar ao banco de dados. Confira as credenciais em app/config.php.';
    require BASE_PATH . '/views/erro.php';
    exit;
}
