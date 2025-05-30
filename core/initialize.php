<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', DS . 'xampp' . DS . 'htdocs' . DS . 'backendAuto');

//xampp/htdocs/backendAuto
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
