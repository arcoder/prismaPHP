<?php
#register_shutdown_function('session_write_close');
#session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');

session_start();

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

function autoload($classname)
{
    if (is_readable(ROOT . DS . 'format' . DS . $classname . '.php'))
        require_once(ROOT . DS . "format" . DS . $classname . '.php');
    if (is_readable(ROOT . DS . 'model' . DS . $classname . '.php'))
        require_once(ROOT . DS . "model" . DS . $classname . '.php');
    if (is_readable(ROOT . DS . 'lib' . DS . $classname . '.php'))
        require_once(ROOT . DS . 'lib' . DS . $classname . '.php');
}

spl_autoload_register('autoload');

require_once ROOT . DS . 'config/Config.php';
require_once ROOT . DS . 'pf' . DS . 'rb.php';
require_once ROOT . DS . 'pf' . DS . 'rc.php';
require_once ROOT . DS . 'config/AppRoute.php';
DB::configure();


############### Helper ###############
require_once ROOT . DS . 'helper' . DS . 'H.php';
############### ApplicationController ###############
require_once ROOT . DS . 'controller' . DS . 'ApplicationController.php';


############### VENDOR LIBS ################

## Swiftmailer
require_once ROOT . DS . 'vendor' . DS . 'swiftmailer5.0.0' . DS . 'swift_required.php';

function swiftmailer_configurator()
{
    // configure Swift Mailer
    Swift_Preferences::getInstance()->setCharset('utf-8');
}

Swift::init('swiftmailer_configurator');
Mail::setTransport();


/*
$handler = new SessionDbManager();
session_set_save_handler(
        array($handler, '_open'), array($handler, '_close'), array($handler, '_read'), array($handler, '_write'), array($handler, '_destroy'), array($handler, '_clean')
);

// the following prevents unexpected effects when using objects as save handlers
register_shutdown_function('session_write_close');
session_start();
*/


try {
    $app = AppRoute::getInstance(URI::getInstance());
    $intpath = $app->resolveUri();
} catch (RouterException $e) {
    die($e->__toString());
} catch (InternalPathException $e) {
    die($e->__toString());
} catch (GeneratorException $e) {
    die($e->__toString());
}


$gen = Generator::create($intpath);

try {
    $gen->execute();
} catch (ReflectionException $e) {
    $e->__toString();
} catch (GeneratorException $e) {
    $e->__toString();
} catch (ControllerException $e) {
    $e->__toString();
} catch (FormatHandlerException $e) {
    $e->__toString_No404();;
}

DB::close();