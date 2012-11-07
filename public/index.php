<?php 

define("IN", true);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', str_replace('/public', '', dirname(__FILE__)));


require ROOT . DS . 'config' . DS . 'Config.php';

if (Config::DEVELOPMENT_ENV == true) {
	error_reporting(E_ALL);
    ini_set('display_errors', 'On');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', ROOT . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'error.log');
}

session_start();


function autoload($class) {
    if (is_readable('model' . DS . $class . '.php'))
        require 'model' . DS . $class . '.php';
    if (is_readable('lib' . DS . $class . '.php'))
        require 'lib' . DS . $class . '.php';
}



spl_autoload_register('autoload');

require ROOT . DS . 'helper' . DS . 'Helpers.php';
require ROOT . DS . 'dfw' . DS . 'Swift-4.1.1'. DS . 'lib' . DS .'swift_required.php';
require ROOT . DS . 'dfw' . DS . 'rb.php';
require ROOT . DS . 'dfw' . DS . 'rc3.php';
require ROOT . DS . 'config' . DS . 'AppRoute.php';
require ROOT . DS . 'controller' . DS . 'ApplicationController.php';
#$time_start = microtime(true);
try {
AppRoute::build('hello', 'welcome', 'html');
} catch(RoutesException $e) {
	Redirect::to404($e->getMessage());
} 

#$time_end = microtime(true);
#$time = $time_end - $time_start;
#echo '<br>'.$time.' seconds'; exit;
/*
try {
    RC::configure('hello', 'welcome', 'html');
} catch (Exception $e) {
    die($e->getMessage());
}
*/