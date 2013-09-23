<?php

/*
 * @engineer Alberto Ruffo
 * 
 */

class OException extends Exception
{

    public function __toString()
    {
        if (Config::DEVELOPMENT_ENV == true)
            echo '<pre>' . parent::__toString() . '</pre>';
        else {
            $where = URI::getInstance();

            error_log(str_repeat("-", 50) . "\n" . $where->getPath() . "\n GET: " . print_r($_GET, true) . "\n" . parent::__toString() . "\n", 3, ROOT . DS . 'logs' . DS . 'exceptions.log');
            Redirect::to404("Si è verificato un problema interno (01).");
        }
        return "";
    }

    public function __toString_No404()
    {
        if (Config::DEVELOPMENT_ENV == true)
            echo '<pre>' . parent::__toString() . '</pre>';
        else {
            $where = URI::getInstance();
            echo 'Si è verificato un problema interno (02).';
            error_log(str_repeat("-", 50) . "\n" . $where->getPath() . "\n GET: " . print_r($_GET, true) . "\n" . parent::__toString() . "\n", 3, ROOT . DS . 'logs' . DS . 'exceptions.log');
            exit;
        }
    }

}

class DB
{

    public static function close()
    {
        R::close();
    }

    public static function configure()
    {
        switch (Config::DB_ADAPTER) {
            case 'mysql':
                R::setup("mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_DATABASE, Config::DB_USER, Config::DB_PASSWORD);
                break;
            case 'postgresql':
                R::setup('pgsql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_DATABASE, Config::DB_USER, Config::DB_PASSWORD); //postgresql
                break;
            case 'sqlite':
                R::setup('sqlite:' . Config::DB_HOST, Config::DB_DATABASE, Config::DB_PASSWORD); //sqlite
                break;
        }
        if (Config::DEVELOPMENT_ENV == false)
            R::freeze(true);
        else
            R::freeze(false);
    }

}

class Redirect
{

    /**
     * Redirects to another page
     *
     * @param array $params
     *
     * @return void
     */
    public static function to($params = null, $get = null)
    {
        Header('Location:' . Utility::link_to($params, $get));
        exit;
    }

    public static function to404($message = '')
    {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
        require ROOT . DS . 'public' . DS . '404.php';
        exit;
    }

    public static function to400($message = '')
    {
        header($_SERVER["SERVER_PROTOCOL"] . ' 400 Bad Request', true, 400);
        require ROOT . DS . 'public' . DS . '400.php';
    }

}

class Error
{

    private static $messages = array();

    /**
     * Sets an error alert if is set
     *
     * @param string $msg
     *
     * @return void
     */
    public static function set($owner, $msg)
    {
        self::$messages[$owner][] = $msg;
        $_SESSION['rc_error'][$owner][] = $msg;
    }

    /**
     * Checks if an error message is set
     *
     * @param string $msg
     *
     * @return bool
     */
    public static function isEmpty()
    {
        if (isset($_SESSION['rc_error']) && count($_SESSION['rc_error']) > 0)
            return false;
        return true;
    }

    /**
     * Print an error alert
     *
     * @return void
     */
    public static function get($owner, $style = true)
    {
        if (isset($_SESSION['rc_error'][$owner]) && count($_SESSION['rc_error'][$owner]) > 0) {
            if ($style == true) {
                echo '<div class=\'alert alert-error alert-block\'>';
                echo '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
                echo '<h4>Errore!</h4>';
                echo '<ul class=\'rc_error\'>';
                foreach ($_SESSION['rc_error'][$owner] as $msg) {
                    if (is_array($msg)) {
                        foreach ($msg as $one) {
                            echo '<li>' . $one . '</li>';
                        }
                    } else {
                        echo '<li>' . $msg . '</li>';
                    }
                }
                echo '</ul>';
                echo '</div>';
            } else
                print_r($_SESSION['rc_error'][$owner]);
            $_SESSION['rc_error'][$owner] = null;
        }
    }

}

class Success
{

    private static $messages = array();

    /**
     * Sets a flash alert if is set
     *
     * @param string $msg
     *
     * @return void
     */
    public static function set($owner, $msg)
    {
        self::$messages[$owner][] = $msg;
        $_SESSION['rc_success'][$owner][] = $msg;
    }

    /**
     * Checks if a flash message is set
     *
     * @param string $msg
     *
     * @return bool
     */
    public static function isEmpty()
    {
        if (isset($_SESSION['rc_success']) && count($_SESSION['rc_success']) > 0)
            return false;
        return true;
    }

    /**
     * Print a flash alert
     *
     * @return void
     */
    public static function get($owner, $style = true)
    {
        if (isset($_SESSION['rc_success'][$owner]) && count($_SESSION['rc_success'][$owner]) > 0) {
            if ($style == true) {
                echo '<div class=\'alert alert-success alert-block\'>';
                echo '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
                echo '<h4>Ben fatto!</h4>';
                echo '<ul class=\'rc_success\'>';
                foreach ($_SESSION['rc_success'][$owner] as $msg) {
                    if (is_array($msg)) {
                        foreach ($msg as $one) {
                            echo '<li>' . $one . '</li>';
                        }
                    } else {
                        echo '<li>' . $msg . '</li>';
                    }
                }
                echo '</ul>';
                echo '</div>';
            } else
                print_r($_SESSION['rc_success']);
            $_SESSION['rc_success'] = null;
        }
    }

}


class Warning
{

    private static $messages = array();

    /**
     * Sets a flash alert if is set
     *
     * @param string $msg
     *
     * @return void
     */
    public static function set($owner, $msg)
    {
        self::$messages[$owner][] = $msg;
        $_SESSION['rc_warning'][$owner][] = $msg;
    }

    /**
     * Checks if a flash message is set
     *
     * @param string $msg
     *
     * @return bool
     */
    public static function isEmpty()
    {
        if (isset($_SESSION['rc_warning']) && count($_SESSION['rc_warning']) > 0)
            return false;
        return true;
    }

    /**
     * Print a flash alert
     *
     * @return void
     */
    public static function get($owner, $style = true)
    {
        if (isset($_SESSION['rc_warning'][$owner]) && count($_SESSION['rc_warning'][$owner]) > 0) {
            if ($style == true) {
                echo '<div class=\'alert alert-block\'>';
                echo '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
                echo '<h4>Attenzione!</h4>';
                echo '<ul class=\'rc_warning\'>';
                foreach ($_SESSION['rc_warning'][$owner] as $msg) {
                    if (is_array($msg)) {
                        foreach ($msg as $one) {
                            echo '<li>' . $one . '</li>';
                        }
                    } else {
                        echo '<li>' . $msg . '</li>';
                    }
                }
                echo '</ul>';
                echo '</div>';
            } else
                print_r($_SESSION['rc_warning']);
            $_SESSION['rc_warning'] = null;
        }
    }

}

class FormatHandlerException extends OException
{

}

abstract class FormatHandler
{

    public static function getInstance($type, Controller $controllerObj, InternalPath $path)
    {
        $class_name = mb_strtoupper($type);
        if (class_exists($class_name)) {
            $format = new ReflectionClass($class_name);
            if ($format->hasMethod('view') and $format->isSubclassOf(__CLASS__)) {
                return new $class_name($controllerObj, $path);
            } else {
                throw new FormatHandlerException('Class ' . $class_name . ' not found or method view not overridden');
            }
        } else {
            throw new FormatHandlerException('Format not available, cannot create view');
        }
        #return null; trigger_error???
    }

    abstract function view();
}

class ControllerException extends OException
{

}

abstract class Controller extends stdClass
{

    protected static $path;

    public function __construct(InternalPath $path)
    {
        self::$path = $path;
    }

    public function before_filter()
    {

    }

    public function after_filter()
    {

    }

    /**
     * Filters actions through protected methods
     * EX.: $this->filter('checkUserLogin',array('except' => array('login','search','register','new_from_facebook')));
     * EX.: $this->filter('checkUserLogin',array('all'));
     * EX.: $this->filter('checkUserLogin',array('only' => array('login','search'));
     *
     * @param string $method
     * @param array $actions
     * @param array $args (protected method args)
     *
     * @return void
     */
    final protected function filter($method, $actions = array(), $args = array())
    {
        $ref = new ReflectionObject($this);

        if (!$ref->getMethod($method)->isProtected())
            throw new ControllerException(' \'' . $method . '\' METHOD must be protected');


        if (is_array($actions) && count($actions) == 1) {
            foreach ($actions as $key => $value) {
                switch ($key) {
                    case 'all':
                        if (count($args) > 0)
                            call_user_func_array(array($this, $method), $args);
                        else
                            call_user_func(array($this, $method));

                        break;
                    case 'except':
                        $flag = 1;
                        foreach ($actions[$key] as $action) {
                            if (self::$path->getAction(true) == $action)
                                $flag = 0;
                        }
                        if ($flag == 1) {
                            if (count($args) > 0)
                                call_user_func_array(array($this, $method), $args);
                            else
                                call_user_func(array($this, $method));
                        }
                        break;
                    case 'only':
                        $flag = 0;
                        foreach ($actions[$key] as $action) {
                            if (self::$path->getAction(true) == $action)
                                $flag = 1;
                        }
                        if ($flag == 1) {
                            if (count($args) > 0)
                                call_user_func_array(array($this, $method), $args);
                            else
                                call_user_func(array($this, $method));
                        }
                        break;
                }
            }
        } else {
            throw new ControllerException("Filter method accepts an array with a key(all, except, only), ex: array('all' => 'method1', 'method2')");
        }
    }

}

class GeneratorException extends OException
{

}

class Generator
{

    private $internalPath;
    private static $instance;

    private function __construct(InternalPath $p)
    {
        $this->internalPath = $p;
    }

    public static function create(InternalPath $p)
    {
        if (!is_null($p) and empty(self::$instance)) {
            self::$instance = new Generator($p);
            return self::$instance;
        }
        throw new GeneratorException('There\'s a problem, i cannot generate anything');
        #return null; trigger_error???
    }

    public function execute()
    {
        $this->phpSettings();
        $this->setController();
    }

    private function setController()
    {
        $controller = $this->internalPath->getController(true);
        $action = $this->internalPath->getAction(true);
        if ($this->controllerExists($controller)) {

            require ROOT . DS . 'controller' . DS . $controller . '.php';
            # if (method_exists($this->internalPath->getController(true), $this->internalPath->getAction(true)) && !in_array($this->internalPath->getAction(true), array('beforeFilter', 'afterFilter', 'configureVars'))) {
            #Mail::configure();
            #Format::configure($this->controller, $this->action, $this->format);

            $controllerR = new ReflectionClass($controller);
            if ($controllerR->hasMethod($action)) {
                if ($controllerR->isSubclassOf('ApplicationController')) {
                    if ($controllerR->getMethod($action)->getNumberOfRequiredParameters() <= count($this->internalPath->getArgs())) {
                        if ($controllerR->getMethod($action)->isPublic() and !in_array($action, array('beforeFilter', 'afterFilter'))) {
                            try {
                                $controllerObj = new $controller($this->internalPath);
                                $controllerObj->before_filter();
                                call_user_func_array(array($controllerObj, $action), $this->internalPath->getArgs());
                                $controllerObj->after_filter();
                                $format = FormatHandler::getInstance($this->internalPath->getFormat(), $controllerObj, $this->internalPath);
                                $format->view();
                            } catch (ControllerException $e) {
                                throw $e;
                            } catch (FormatHandlerException $e) {
                                throw $e;
                            }
                        } else {
                            throw new GeneratorException('Cannot load a private/protected such as action \'' . $action . '\'');
                        }
                    } else {
                        throw new GeneratorException($action . ' action needs more parameters.');
                    }
                } else {
                    throw new GeneratorException($controller . ' must extend ApplicationController');
                }
            } else {
                throw new GeneratorException('Action \'' . $action . '\' not found or controller doesn\'t extend ApplicationController');
            }


            #$controller = new $this->classController();
            #$controller->__load_vars($this->controller, $this->action);
            #} else {
            #    throw new GeneratorException('Controller class found, but i cannot use ' . $this->internalPath->getController(true) . '#' . $this->internalPath->getAction(true));
            #}
        }
    }

    private function controllerExists($classController)
    {

        if (file_exists(ROOT . DS . 'controller' . DS . $classController . '.php'))
            return true;
        else {
            throw new GeneratorException('I cannot load controller \'' . $classController . '.php\', file doesn\'t exist.');
            #return false; trigger_error??
        }
    }

    private function phpSettings()
    {
        if (get_magic_quotes_gpc()) {
            $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
            while (list($key, $val) = each($process)) {
                foreach ($val as $k => $v) {
                    unset($process[$key][$k]);
                    if (is_array($v)) {
                        $process[$key][stripslashes($k)] = $v;
                        $process[] = & $process[$key][stripslashes($k)];
                    } else {
                        $process[$key][stripslashes($k)] = stripslashes($v);
                    }
                }
            }
            unset($process);
        }
        if (ini_get('register_globals')) {
            $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

}

class InternalPathException extends OException
{

}

class InternalPath
{

    private $controller;
    private $action;
    private $args = array();
    private $format;

    public function __construct($defcontroller, $defaction, $defargs, $defformat)
    {
        $this->controller = Utility::separatorToUnderscore($defcontroller);
        $this->action = Utility::separatorToUnderscore($defaction);
        $this->args = $defargs;
        $this->format = $defformat;
    }

    public function import(array $values)
    {
        if (in_array(array('controller', 'action', 'args', 'format'), array_keys($values))) {

            $this->controller = Utility::separatorToUnderscore($values['controller']);
            $this->action = Utility::separatorToUnderscore($values['action']);
            $this->args = $values['args'];
            $this->format = $values['format'];
        } else
            throw new InternalPathException('i cannot load data into internal path.');
    }

    public function getController($camelled = false)
    {
        if ($camelled == false)
            return $this->controller;
        else
            return Utility::separatorToCamel($this->controller, '_', true) . 'Controller';
    }

    public function getAction($camelled = false)
    {
        if ($camelled == false)
            return $this->action;
        else
            return Utility::separatorToCamel($this->action, '_', false);
    }

    public function getArgs()
    {
        return $this->args;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function export()
    {
        return array(
            'controller' => $this->getController(),
            'action' => $this->getAction(),
            'args' => $this->getArgs(),
            'format' => $this->getFormat()
        );
    }

    public function get()
    {
        return $this->getController() . '/' . $this->getAction() . '/' . implode('/', $this->getArgs()) . '/' . $this->getFormat();
    }

}

class Utility
{

    public static function separatorToUnderscore($str, $from = '-')
    {
        return str_replace($from, '_', $str);
    }

    public static function separatorToCamel($str, $separator = '-', $ucfirst = false)
    {
        $parts = explode($separator, $str);
        $parts = $parts ? array_map('ucfirst', $parts) : array($str);
        $parts[0] = $ucfirst ? ucfirst($parts[0]) : lcfirst($parts[0]);
        return implode('', $parts);
    }

    public static function link_to($params = null, $get = null)
    {
        $url = Config::INDEX_URL . '/';
        #if (Config::LANG_MULTI_LANGUAGE == true)
        #    $url .= Lang::get() . '/';

        if (is_string($params)) {
            if (filter_var($params, FILTER_VALIDATE_URL) !== false) {
                $url = $params;
            } else {
                if ($params != '') {
                    $file = pathinfo($params);
                    $ext = 'html';
                    if (isset($file['extension']))
                        $ext = $file['extension'];
                    if ($file['dirname'] != '.')
                        $str = $file['dirname'] . '/' . $file['filename'];
                    else
                        $str = $file['filename'];
                    $url_ar = explode('/', $str);
                    $url = $url . implode('/', array_map(array('Utility', 'toAscii'), $url_ar)) . '.' . $ext;
                }
            }
        }
        if (is_array($get) && $get != null)
            $url .= '?' . http_build_query($get);

        return $url;
    }

    public static function toAscii($str, $replace = array(), $delimiter = '-', $maxLength = 200)
    {
        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("%[^-/+|\w ]%", '', $clean);
        $clean = strtolower(trim(substr($clean, 0, $maxLength), '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

}

class URI
{

    private $path;
    public static $constArray = array(
        ':phrase' => '[[:lower:]0-9_]+',
        ':numeric' => '[0-9]+',
        ':alpha' => '[a-z]+',
        ':alnum' => '[a-z0-9]+'
    );

    private function __construct($url = null)
    {
        if (is_null($url)) {
            if (isset($_GET['__PHURL__'])) {
                $this->path = $_GET['__PHURL__'];
            } else
                $this->path = '';
        } else {
            $this->path = $url;
        }
    }

    public static function getInstance($url = null)
    {
        return new self($url);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getPathLessFormat()
    {
        return preg_replace("/\\.[^.\\s]{3,4}$/", "", $this->path);
    }

    public function getParts()
    {
// delete first backslash / for exploding and remove extension
        $i = substr_count($this->getPathLessFormat(), '/');
        if ($i > 0)
            return explode('/', $this->getPathLessFormat(), $i + 1);
        elseif ($i == 0) {
            $arr = explode('/', $this->getPathLessFormat());
            if ($arr[$i] != '')
                return $arr;
            else
                return array();
        } else
            return array();
    }

    public function getExt()
    {
        $path = parse_url($this->path, PHP_URL_PATH);
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public function hasExt()
    {
        if ($this->getExt() == '') {
            return false;
        }
        return true;
    }

    public function compareWith(URI $op)
    {
        $regExpPath1 = str_replace(array_keys(static::$constArray), static::$constArray, str_replace('/', '\/', $this->path));
        #echo '<b>' . $regExpPath1 . '</b> paragonato con <b>' . $op->getPathLessFormat() . '</b> ';
        if (preg_match('/^' . $regExpPath1 . '$/', Utility::separatorToUnderscore($op->getPathLessFormat()), $e)) {
            return true;
        }

        #echo $regExpPath1.' '.Utility::separatorToUnderscore($op->getPathLessFormat()).'NO!!!<br>';
        return false;
    }

}

class RouterException extends OException
{

}

class Router
{

    private $uri;
    protected static $routes = array();
    protected static $aliases = array();
    protected static $default_route = array(
        'controller' => 'welcome',
        'action' => 'index',
        'args' => array(),
        'format' => 'html'
    );
    private $destination = array();

    private function __construct(URI $uriobj)
    {
        $this->uri = $uriobj;
        if (preg_match('/[^[:lower:]0-9-\/]+/', $this->uri->getPathLessFormat(), $e) or ($this->uri->hasExt() == false and count($this->uri->getParts()) > 0)) {
            throw new RouterException('Url format is not valid', 0);
        }
    }

    public function getRoute()
    {
        return $this->destination;
    }

    public function resolveURI()
    {
        $parts = $this->uri->getParts();
        foreach (static::$aliases as $id_row => $row) {
            # $col0 is the first column of $aliases array
            $col0 = URI::getInstance($row[0]);

            if ($col0->compareWith($this->uri)) {
                # $col1 is the second column of $aliases array
                $col1 = URI::getInstance($row[1]);
                $params = $col1->getParts();
                $this->destination = array(
                    'controller' => $params[0],
                    'action' => $params[1],
                    'args' => array(),
                    'format' => $this->uri->getExt()
                );
                $a = 0;
                foreach ($col0->getParts() as $piece) {
                    if (in_array($piece, array_keys(URI::$constArray))) {
                        $this->destination['args'][] = $parts[$a];
                    }
                    $a++;
                }
                return new InternalPath(
                    $this->destination['controller'], $this->destination['action'], $this->destination['args'], $this->destination['format']
                );
            }
        }
        if (count($parts) >= 2) {
            $action = $parts[1];
        } elseif (count($parts) == '0') {
            $this->destination = array(
                'controller' => static::$default_route['controller'],
                'action' => static::$default_route['action'],
                'args' => static::$default_route['args'],
                'format' => static::$default_route['format']
            );
            return new InternalPath(
                $this->destination['controller'], $this->destination['action'], $this->destination['args'], $this->destination['format']
            );
        } else {
            $action = 'index';
        }
        foreach (static::$routes as $row) {
            $col0 = URI::getInstance($row);
            if ($col0->compareWith($this->uri)) {
                $this->destination = array(
                    'controller' => $parts[0],
                    'action' => $action,
                    'args' => array_slice($parts, 2),
                    'format' => $this->uri->getExt()
                );
                return new InternalPath(
                    $this->destination['controller'], $this->destination['action'], $this->destination['args'], $this->destination['format']
                );
            }
        }
        throw new RouterException('i cannot find a route', 1);
        #return null; trigger_error???
    }

    public static function getInstance(URI $uriobj)
    {
        return new static($uriobj);
    }

}

class ModelException extends Exception
{

}

abstract class Model extends RedBean_SimpleModel
{

    protected $errors = array();
    private $table;

    function __construct($tbl)
    {
        $this->table = $tbl;
    }

    abstract public function validationOnUpdate();

    abstract public function validationOnCreate();

    abstract public function validationOnDelete();

    protected function validate()
    {
        $this->throwModelException();
    }

    private function throwModelException()
    {
        if (count($this->errors) > 0) {
            throw new ModelException('Model throws exception, you must validate data before.');
        }
    }

    public function validates_presence_of($word, $label, $message)
    {
        if (trim($this->$word) == '') {
            $this->errors[$word][] = array($label, $message);
        }
    }

    public function validates_presence_for(array $words, array $labels, $message)
    {
        if (count($words) > 0) {
            $a = 0;
            foreach ($words as $field) {
                if (trim($this->$field) == '') {
                    $this->errors[$field][] = array($labels[$a], $message);
                }
                $a++;
            }
        }
    }

    public function validates_ctype($word, $label, $ctype, $message)
    {
        if (!call_user_func_array('ctype_' . $ctype, array($this->$word))) {
            $this->errors[$word][] = array($label, $message);
        }
    }

    public function validates_ctype_for(array $words, array $labels, $ctype, $message)
    {
        if (count($words) > 0) {
            $a = 0;
            foreach ($words as $field) {
                if (!call_user_func_array('ctype_' . $ctype, array($this->$field))) {
                    $this->errors[$field][] = array($labels[$a], $message);
                }
                $a++;
            }
        }
    }

    public function validates_email_of($word, $label, $message)
    {
        if (!filter_var($this->$word, FILTER_VALIDATE_EMAIL))
            $this->errors[$word][] = array($label, $message);
    }

    public function validates_length_of($word, $label, $options, $message)
    {
        if (count($options) == 0)
            throw new ModelException('validates_length_of, you must define an option');
        else {
            foreach ($options as $type => $value) {
                switch ($type) {
                    case 'maximum':
                        if (mb_strlen($this->$word) > $value)
                            $this->errors[$word][] = array($label, $message);
                        break;
                    case 'minimum':
                        if (mb_strlen($this->$word) < $value)
                            $this->errors[$word][] = array($label, $message);

                        break;
                    case 'is':
                        if (mb_strlen($this->$word) == $value)
                            $this->errors[$word][] = array($label, $message);
                        break;
                    default:
                        throw new ModelException('validates_length_of, you must define an option');
                }
            }
        }
    }

    public function validates_length_for(array $words, array $labels, $options, $message)
    {
        if (count($words) > 0) {
            if (count($options) == 0)
                throw new ModelException('validates_length_of, you must define an option');
            else {
                $a = 0;
                foreach ($words as $field) {
                    foreach ($options as $type => $value) {
                        switch ($type) {
                            case 'maximum':
                                if (mb_strlen($this->$field) > $value)
                                    $this->errors[$field][] = array($labels[$a], $message);
                                break;
                            case 'minimum':
                                if (mb_strlen($this->$field) < $value)
                                    $this->errors[$field][] = array($labels[$a], $message);

                                break;
                            case 'is':
                                if (mb_strlen($this->$field) != $value)
                                    $this->errors[$field][] = array($labels[$a], $message);
                                break;
                            default:
                                throw new ModelException('validates_length_for, you must define an option');
                        }
                    }
                    $a++;
                }
            }
        }
    }

    public function validates_confirmed_field($field, $label, $external_data, $message)
    {
        # $cfield = 'confirmed_' . $field;
        if ($this->$field != $external_data)
            # if ($this->$field != $this->$cfield)
        $this->errors[$field][] = array($label, $message);
        #$this->$cfield = null;
        #unset($this->$cfield);
    }

    public function validates_date($field, $label, $message)
    {
        list($y, $m, $d) = explode("-", $this->$field);
        if (!checkdate($m, $d, $y)) {
            $this->errors[$field][] = array($label, $message);
        }
    }

    public function validates_uniqueness_of($field, $label, $message)
    {
        if (R::findOne($this->table, $field . '=?', array($this->$field)) != null)
            $this->errors[$field][] = array($label, $message);
    }


    public function validates_uniqueness_for(array $fields, array $labels, $message)
    {
        if (count($fields) > 0) {
            $a = 0;
            foreach ($fields as $field) {
                if (R::findOne($this->table, $field . '=?', array($this->$field)) != null)
                    $this->errors[$field][] = array($labels[$a], $message);
                $a++;
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function isValid()
    {
        if (count($this->errors) == 0)
            return true;
        return false;
    }

    public function isValidField($word)
    {
        if (array_key_exists($word, $this->errors))
            return false;
        return true;
    }

    public function viewFieldErrors($field)
    {
        if ($this->isValidField($field) == false) {
            echo '<div class=\'model_error_div\'>';
            echo '<ul class=\'model_error _' . $field . '\'>';
            foreach ($this->errors[$field] as $error) {
                echo '<li class=\'model_error_li\'>\'' . $error[0] . '\' ' . $error[1] . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }

    public function viewModelErrors()
    {
        if (!$this->isValid()) {
            echo '<div class=\'alert alert-error alert-block\'>';
            echo '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';
            echo '<h4>Si sono verificati i seguenti errori:</h4>';
            echo '<div class=\'model_errors_div\'>';
            echo '<ul class=\'model_errors\'>';
            foreach ($this->errors as $field => $messages) {
                echo '<li class=\'model_errors_field_li\'>' . $this->errors[$field][0][0];
                echo '<ul class=\'model_errors_messages_li\'>';
                foreach ($messages as $error) {
                    echo '<li class=\'model_errors_error_li\'>' . $error[1] . '</li>';
                }
                echo '</ul></li>';
            }
            echo '</ul>';
            echo '</div>';
            echo '</div>';
        }
    }

}