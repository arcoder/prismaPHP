<?php

/*
 * @engineer Alberto Ruffo
 * 

 *  */

class HTMLException extends FormatHandlerException {
    
}

class HTML extends FormatHandler {

    private $objs = array();
    private static $data = array();
    private static $routes = array();
    public static $forceLayout = true;
    public static $layout;

    function __construct(Controller $controlObjs, InternalPath $path) {
        $this->objs = get_object_vars($controlObjs);
        self::$routes = $path->export();
    }

    public function __get($name) {
        if (!isset($this->objs[$name])) {
            throw new HTMLException('HTML class exception: \'' . $name . '\' variable is not available to the view.');
            return null;
        }

        if (is_array($this->objs[$name])) {
            return (array) $this->objs[$name];
        }
        return $this->objs[$name];
    }

    public function __set($name, $value) {
        $this->objs[$name] = $value;
    }

    public function __isset($att) {
        return array_key_exists($att, $this->objs);
    }

    public static function load($params) {
        self::$data = get_object_vars($params);
    }

    public function view() {

        header('Content-type: text/html; charset=UTF-8');
        try {
            $ctrl = self::$routes['controller'];
            if (self::$forceLayout) {
                if (self::$layout != '')
                    $ctrl = self::$layout;
                if (!file_exists(ROOT . DS . 'view' . DS . 'layouts' . DS . $ctrl . '.html.php'))
                    throw new HTMLException('I cannot load the HTML layout ' . $ctrl . '#' . self::$routes['action']);
                require(ROOT . DS . 'view' . DS . 'layouts' . DS . $ctrl . '.html.php');
            }
            else {
                if (!file_exists(ROOT . DS . 'view' . DS . $ctrl . DS . self::$routes['action'] . '.php'))
                    throw new HTMLException('I cannot load the HTML view ' . $ctrl . '#' . self::$routes['action']);
                require( ROOT . DS . 'view' . DS . $ctrl . DS . self::$routes['action'] . '.php');
            }
        } catch (HTMLException $e) {
            $e->__toString_No404();
        }
    }

    private function render($controller, $action) {
            if (!file_exists(ROOT . DS . 'view' . DS . $controller . DS . $action . '.php'))
                throw new HTMLException("I cannot render action $action");
            require(ROOT . DS . 'view' . DS . $controller . DS . $action . '.php');
    }

    private function partial($file) {
        if (!file_exists(ROOT . DS . 'view' . DS . $file . '.php'))
            throw new HTMLException('I cannot load tha partial HTML template file ' . $file);
        else
            require(ROOT . DS . 'view' . DS . $file . '.php');
    }

    private function link_to($params = null, $get = null) {
        return Utility::link_to($params, $get);
    }

}