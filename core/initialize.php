<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', dirname(__DIR__));

defined('INC_PATH') ? null : define('INC_PATH', SITE_ROOT . DS . 'includes');
defined('CORE_PATH') ? null : define('CORE_PATH', SITE_ROOT . DS . 'core');

// Load config file first
require_once(INC_PATH . DS . 'config.php');

//core classes
require_once(CORE_PATH . DS . 'clients.php');
require_once(CORE_PATH . DS . 'message.php');
require_once(CORE_PATH . DS . 'users.php');
require_once(CORE_PATH . DS . 'cars.php');
require_once(CORE_PATH . DS . 'sales.php');

if (!defined('BASE_URL')) {
    //Prod
    //define('BASE_URL', 'https://www.alexcg.de/autozone');

    //Dev
    define('BASE_URL', 'http://localhost/autozone/backendAutozone');
}

// Limitar la cantidad de peticiones por hora por IP
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$rate_limit_file = sys_get_temp_dir() . DS . 'rate_limit_' . md5($ip) . '.json';
$limit = 100; // Cambia este valor para ajustar el límite por hora
$window = 3600; // 1 hora en segundos
$now = time();

if (file_exists($rate_limit_file)) {
    $data = json_decode(file_get_contents($rate_limit_file), true);
    if (!$data) $data = ['count' => 0, 'start' => $now];
    if ($now - $data['start'] < $window) {
        if ($data['count'] >= $limit) {
            http_response_code(429);
            header('Retry-After: ' . ($window - ($now - $data['start'])));
            echo json_encode(['message' => 'Demasiadas peticiones. Intenta de nuevo más tarde.']);
            exit;
        } else {
            $data['count']++;
        }
    } else {
        $data = ['count' => 1, 'start' => $now];
    }
} else {
    $data = ['count' => 1, 'start' => $now];
}
file_put_contents($rate_limit_file, json_encode($data));
