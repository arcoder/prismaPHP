<?php
/*
  Copyright (c) 2011 Alberto Ruffo

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in
  all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
  THE SOFTWARE.
 */


class Lang {

    private static $language;

    public static function configure($lang) {
	    $_SESSION['DFW_lang'] = $lang;
	    #print_r(self::getByClient());
    	#if(!isset($_SESSION['DFW_lang']))
        #	$_SESSION['DFW_lang'] = Config::$default_language;
        
        #if(Config::$http_language_enabled == true)
        #{
        	/*
        	if(Lang::exists($lang))
        		$_SESSION['DFW_lang'] = $lang;
        	else
        	{
            	if(Lang::exists(self::getByClient()))
                 	$_SESSION['DFW_lang'] = self::getByClient();
                 else
                 	$_SESSION['DFW_lang'] = Config::$default_language;
            } */
        #}
       
    }

    /**
     * Checks client language using HTTP_ACCEPT_LANGUAGE
     * 
     * @return string
     */
    public static function getByClient() {
	    $langs = array();

	    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    // break up string into pieces (languages and q factors)
    		preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

    	if (count($lang_parse[1])) {
        // create a list like "en" => 0.8
        $langs = array_combine($lang_parse[1], $lang_parse[4]);
    	
        // set default to 1 for any without q factor
        foreach ($langs as $lang => $val) {
            if ($val === '') $langs[$lang] = 1;
        }

        // sort list based on value	
        arsort($langs, SORT_NUMERIC);
    }
}
	$one = array_keys($langs);
	return $one[0];
/*
// look through sorted list and use first one that matches our languages
foreach ($langs as $lang => $val) {
	if (strpos($lang, 'de') === 0) {
		// show German site
	} else if (strpos($lang, 'en') === 0) {
		// show English site
	} 
}
*/

    }

    /**
     * Load an XML language file to SimpleXMLElement object
     * @param string $file
     * @return SimpleXMLElement
     */
    public static function load($basename) {
        if (isset($_SESSION['DFW_lang']) && self::fileExists($_SESSION['DFW_lang'], $basename))
            return simplexml_load_file('lang' . DIRECTORY_SEPARATOR .  $_SESSION['DFW_lang'] . DIRECTORY_SEPARATOR . $basename . '.xml');
        elseif(self::fileExists(Config::$default_language, $basename)) {
        	return simplexml_load_file('lang' . DIRECTORY_SEPARATOR .  Config::$default_language . DIRECTORY_SEPARATOR . $basename . '.xml');
        }
        else
        	throw new Exception('Required language is not available.');
    }

    public static function fileExists( $language, $basename) {
        if (file_exists('lang' . DIRECTORY_SEPARATOR .  $language . DIRECTORY_SEPARATOR . $basename . '.xml'))
            return true;
        return false;
    }
 
    public static function exists($dirname) {
        if (file_exists('lang' . DIRECTORY_SEPARATOR . $dirname))
            return true;
        return false;
    }

    public static function get() {
        return  $_SESSION['DFW_lang'];
    }

    public static function set($lang) {
         $_SESSION['DFW_lang'] = $lang;
    }

}

class Pagination {

    public static function create($tbl, $from=1, $howmany=10, $where='1', $words=array()) {
        if ($from != 0)
            $page = (int) $from * $howmany - $howmany;
        else
            $page = 1;
        $C_where = $where;
        $C_where .= ' LIMIT ' . ($page) . ',' . ($howmany);
        return array(R::find($tbl, $C_where, $words), $howmany, count(R::find($tbl, $where, $words)));
    }

    public static function createWithRelated($bean, $tbl, $from=1, $howmany=10, $where='1', $words=array()) {
        if ($from != 0)
            $page = (int) $from * $howmany - $howmany;
        else
            $page = 1;

        $where.= ' LIMIT ' . ($page) . ',' . ($howmany);

        return R::related($bean, $tbl, $where, $words);
    }

    public static function createFromSQL($resource, $sql, $tbl, $from=1, $howmany=10) {
        if ($from != 0)
            $page = (int) $from * $howmany - $howmany;
        else
            $page = 1;
        return R::convertToBeans($tbl, $resource->get($sql . ' LIMIT ' . ($page) . ',' . ($howmany)));
    }

    public function links($arr, $current_page=1, $url=array(), $get=array()) {
        $tot_pages = ceil($arr[2] / count($arr[0]));
        $paginazione = "<div id='pagination_links'><p>Pagine totali: " . $tot_pages . "
		[";
        for ($i = 1; $i <= $tot_pages; $i++) {
            if ($i == $current_page) {
                $paginazione .= $i . " ";
            } else {
                $url['page'] = $i;
                $paginazione .= "<a href=\"" . RC::link_to($url, $get) . "\">$i</a> ";
            }
        }
        $paginazione .= "]</p></div>";
        return $paginazione;
    }

}

class DB {

    public static function configure() {
        switch(Config::$adapter) {
            case 'mysql':
                    return R::setup("mysql:host=" . Config::$dbhost . ";dbname=" . Config::$dbname, Config::$dbuser, Config::$dbpassword);
                break;
            case 'postgresql':
                    return R::setup('pgsql:host='.Config::$dbhost .';dbname='.Config::$dbname,Config::$dbuser,Config::$dbpassword); //postgresql
                break;
            case 'sqlite':
                    return R::setup('sqlite:'.Config::$dbhost,Config::$dbname,Config::$dbpassword); //sqlite
                break;       
        }
        if(Config::$DEVELOPMENT_ENV == false)
        	R::freeze( true );
    }

}

class Mail {

    private static $transport;

    public static function configure() {
        self::$transport = Swift_SmtpTransport::newInstance(Config::$SMTPhost, Config::$SMTPport)
                ->setUsername(Config::$SMTPusername)
                ->setPassword(Config::$SMTPpassword)
        ;
    }

    public static function getTransport() {
        return self::$transport;
    }

    public static function mailer() {
        return Swift_Mailer::newInstance(self::$transport);
    }

}

class Redirect {

    /**
     * Redirects to another page
     *
     * @param array $params
     *
     * @return void
     */
    public static function to($params=null, $get=null) {
    	if(is_array($params))
			Header('Location:' . RC::link_to($params, $get));
		else
			Header('Location:' . $params);
        exit;
    }
    public static function to404($message='') {
	    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
        echo file_get_contents("./404begin.html");
        echo '<p>'.$message.'</p>';
        echo file_get_contents("./404end.html");
        exit;
    }
    public static function store($params=null, $get=null) {
    	$_SESSION['RC_store'] = array('params' => $params, 'get' => $get);
        $_SESSION['RC_store_flag'] = 1;
    }
    public static function back() {
    	if(isset($_SESSION['RC_store'])) {
			Header('Location:' . RC::link_to($_SESSION['RC_store']['params'], $_SESSION['RC_store']['get']));
        exit;	    
        }
    }

}

class Error {


    private static $messages = array();
    /**
     * Sets an error alert if is set
     *
     * @param string $msg
     *
     * @return void
     */
    public static function set($owner, $msg) {
    	self::$messages[$owner][] = $msg;
        $_SESSION['RC_error'][$owner][] = $msg;
    }

    /**
     * Checks if an error message is set
     *
     * @param string $msg
     *
     * @return void
     */
    public static function isEmpty() {
        if (isset($_SESSION['RC_error']) && count($_SESSION['RC_error']) > 0)
            return false;
        return true;
    }

    /**
     * Print an error alert
     *
     * @return void
     */
    public static function get($owner, $style=true) {
        if (isset($_SESSION['RC_error'][$owner]) && count($_SESSION['RC_error'][$owner]) > 0) {
            if ($style == true)
            {
            	echo '<ul class=\'rc_error\'>';
            	foreach($_SESSION['RC_error'][$owner] as $msg) {
                	echo '<li style=\'color:#A00;\'>' . $msg . '</li>';
                }
                echo '</ul>';
            }
            else
            	print_r($_SESSION['RC_error'][$owner]);
            $_SESSION['RC_error'][$owner] = null;
        }
    }

}

class Flash {


    private static $messages = array();
    /**
     * Sets a flash alert if is set
     *
     * @param string $msg
     *
     * @return void
     */
    public static function set($owner, $msg) {
    	self::$messages[$owner][] = $msg;
        $_SESSION['RC_flash'][$owner][] = $msg;
    }

    /**
     * Checks if a flash message is set
     *
     * @param string $msg
     *
     * @return void
     */
    public static function isEmpty() {
        if (isset($_SESSION['RC_flash']) && count($_SESSION['RC_flash']) > 0)
            return false;
        return true;
    }

    /**
     * Print a flash alert
     *
     * @return void
     */
    public static function get($owner, $style=true) {
        if (isset($_SESSION['RC_flash'][$owner]) && count($_SESSION['RC_flash'][$owner]) > 0) {
            if ($style == true)
            {
            	echo '<ul class=\'rc_flash\'>';
            	foreach($_SESSION['RC_flash'][$owner] as $msg) {
                	echo '<li style=\'color:#009900;\'>' . $msg . '</li>';
                }
                echo '</ul>';
            }
            else
                print_r($_SESSION['RC_flash']);
            $_SESSION['RC_flash'] = null;
        }
    }

}

/*
 * RC
 * @file 		rc.php
 * @description		RC parses and manages GET requests, then it dinamically creates a controller object and invokes the action method
 *                      wanted by query string
 * @author              Alberto Ruffo
 * @license		MIT
 *
 *
 * (c) Dashy (arcoder)
 * This source file is subject to the MIT License that is bundled
 * with this source code in the file LICENSE.
 */
 
class RCException extends Exception {}

class RC {
    /**
     * @var string
     * Contains the controller name.
     *
     */
    private $controller;

    /**
     * @var string
     * Contains the action name.
     *
     */
    private $action;

    /**
     * @var string
     * Contains the type of format required.
     *
     */
    private $format;

    /**
     * @var string
     * Contains Camel-case controller class to invoke.
     *
     */
    private $classController;

    /**
     * @var string
     * Contains Camel-case action method to invoke.
     *
     */
    private $methodClass;

    /**
     * @var Controller
     * Contains the object created dinamically.
     *
     */
    private $objController;

    /**
     * @var string
     * Contains the query string without GET params(ex. controller/action/)
     *
     */
    private $url;

    /**
     * @var array
     * Contains the array created from $url without GET params(ex. controller/action/)
     *
     */
    protected $queryString;

    /**
     * This constructor parses the query string and gets controller and action.
     * EX.: index.php/blog/read/27/11/1990?page=1,
     * Controller: blog
     * Action: read
     * Querystring: array(0 => 27, 1 => 11, 2 => 1990);
     * $_GET['page'] = 1
     * @property queryString
     * @param string $defController
     * @param integer $defAction
     * @param string $defFormat
     */
    function __construct($defController, $defAction, $defFormat) {
        // IMPORTANT CONTROL, PANIC!!!
        if (isset($_SERVER['ORIG_PATH_INFO']))
            $path_info = $_SERVER['ORIG_PATH_INFO'];
        elseif (isset($_SERVER['PATH_INFO']))
            $path_info = $_SERVER['PATH_INFO'];
        else
            $path_info = '/';

        if (!isset($_GET['format']))
            $this->format = $defFormat;
        else
            $this->format = $_GET['format'];

        #$this->objController = null;
        $this->url = substr($path_info, 1);

        $params = explode("/", $this->url);
        $numParams = count($params); 
        //SECURITY!!!
        if(!isset($_SESSION['timezone']))
        	date_default_timezone_set('Europe/Rome');
        if(Config::$http_language_enabled == true) {
	        if(!Lang::exists($params[0])) 
		 		Redirect::to404('Pagina non disponibile in questa lingua.');	
		 	Lang::configure($params[0]);
		 	$numParams = $numParams -1; // tolgo la lingua con -1
		 	setlocale(LC_ALL, str_replace('-','_', $params[0]));
		 	array_shift($params); // tolgo lang
		 	$this->url = implode('/', $params); //ricostruisco la querystring
        } else {
	        #setlocale(LC_ALL, Config::$default_language);
	        setlocale(LC_ALL, str_replace('-','_', Config::$default_language));
        }
  
        if ($numParams < 2 || ($numParams == 2 && trim($params[1]) == '')) {
	        if (isset($params[0]) && strlen(trim($params[0])) > 0)
	        	$this->url = $params[0].'/index';
	        else
	        	$this->url = $defController . '/' . $defAction;
	        
        }
     
        $urlArray = explode("/", $this->url);

        ###############################################
        ###### ROUTING - IMPORTANT ####################
        if(Routing::isAllowed($urlArray) == false)
        	Redirect::to404('code#000');
        ###############################################
        ###############################################
        #$this->controller = (Config::$http_language_enabled==true) ? $urlArray[1] : $urlArray[0];
        $this->controller = $urlArray[0];
        $this->classController = ucfirst($this->controller) . 'Controller';       
        array_shift($urlArray);
        $this->action = RC::dashedToScores($urlArray[0]);  //se è un numero, uso read e il numero va in QS
        array_shift($urlArray); //tolgo la action
        
        $this->methodClass = RC::scoresToCamel($this->action);
        
        $this->queryString = $urlArray;
        $this->generateResponse();
        
    }

    /**
     * Converts dashed string to scores
     *
     * @param string $msg
     * @param bool $ucfirst
     * 
     * @return string
     */
    public static function dashedToScores($str) {
        return str_replace('-','_',$str);
    }
    /**
     * Converts string to camel notation
     *
     * @param string $msg
     * @param bool $ucfirst
     * 
     * @return string
     */
    public static function scoresToCamel($str, $ucfirst = false) {
        $parts = explode('_', $str);
        $parts = $parts ? array_map('ucfirst', $parts) : array($str);
        $parts[0] = $ucfirst ? ucfirst($parts[0]) : lcfirst($parts[0]);
        return implode('', $parts);
    }

    /**
     * Converts string to underscore notation
     *
     * @param string $str
     *
     * @return string
     */
    public static function camelToUnderscore($str) {
        $string = preg_replace("/(?<=\\w)(?=[A-Z])/", "_$1", $str);
        return strtolower($string);
    }

    /**
     * This method creates an instance of Controller class and invokes the action method.
     * Then, it calls 'html' method if the format is html.
     */
    private function generateResponse() {
        if ($this->controllerExists()) {
            require 'controller' . DIRECTORY_SEPARATOR . $this->classController . '.php';
            try {
            if (class_exists($this->classController) && method_exists($this->classController, $this->methodClass) && !in_array($this->methodClass, array('beforeFilter', 'afterFilter', 'configureVars'))) {
                Format::configure($this->controller, $this->action, $this->format);

               # Lang::configure();
                Mail::configure();
                DB::configure();

                $this->objController = new $this->classController();
                $this->objController->__load_vars($this->controller, $this->action,  $this->queryString);
                $this->objController->before_filter();
                #$this->objController->queryString = $this->queryString;
                $this->objController->{$this->methodClass}();
                $this->objController->after_filter();

                if ($this->format == 'html') {
                    //$this->objController->html();
                    $view = new View($this->objController, array('controller' => $this->controller, 'action' => $this->action));
                    {
                    	header('Content-type: text/html; charset=UTF-8');
                        $view->html();
                    }
                }
            }
            else
                throw new RCException('Controller found, but i cannot use ' . $this->classController . '#' . $this->methodClass);
        	} catch(RCException $e) {
            	if (Config::$DEVELOPMENT_ENV == true) 
        			Redirect::to404($e->getMessage());
        		else
        			Redirect::to404('code#001');
        	}
        }
    }

    /**
     * Returns true if controller file exists.
     * @return bool
     */
    private function controllerExists() {
    	  try {
        if (file_exists('controller' . DIRECTORY_SEPARATOR . $this->classController . '.php'))
            return true;
        else {
            throw new RCException('I cannot load \'' . $this->classController . '.php\', file doesn\'t exist.');
            return false;
        }
        } catch(RCException $e) {
            	if (Config::$DEVELOPMENT_ENV == true) 
        			Redirect::to404($e->getMessage());
        		else
        		   	Redirect::to404('code#002');

        }
    }

    /**
     * Fast object creation function
     *
     * @param string $defController    Default controller (underscore-case)
     * @param string $defAction        Default action (underscore-case
     * @param string $defFormat        Default format(html, json, xml)
     *
     * @return void
     */
    public static function configure($defController, $defAction, $defFormat) {
        self::removeMagicQuotes();
        self::unregisterGlobals();
        new RC($defController, $defAction, $defFormat);
    }

    /**
     * Returns streepslashed array/string
     *
     * @param string/array $value    Default controller (underscore-case)
     *
     * @return void
     */
    private static function stripSlashesDeep($value) {
        $value = is_array($value) ? array_map(array('RC', 'stripSlashesDeep'), $value) : stripslashes($value);
        return $value;
    }

    /**
     * Unsets $GLOBALS array elements
     *
     * @return void
     */
    private static function unregisterGlobals() {
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

    /**
     * Recursive strip tags
     *
     * @return bool
     */
    protected static function stripTags($value) {
        $value = is_array($value) ? array_map(array('Controller', 'stripTags'), $value) : strip_tags($value);
        return $value;
    }

    /**
     * Removes quotes if get_magic_quotes_gpc is on
     *
     * @return void
     */
    private static function removeMagicQuotes() {
        if (get_magic_quotes_gpc()) {
            $_GET = RC::stripSlashesDeep($_GET);
            $_POST = RC::stripSlashesDeep($_POST);
            $_COOKIE = RC::stripSlashesDeep($_COOKIE);
        }
    }

    public static function link_to($params=null, $get=null) {
        if (!is_null($params)) {
            $url = Config::$SITE . '/';
            foreach ($params as $key => $value) {
                $url .= rawurlencode($value) . '/';
            }
            $url = substr($url, 0, -1).'.html';
        }
        if ($get != null)
            $url .= '?' . http_build_query($get);
        return str_replace('_','-',$url);
    }

}

/**
 * Controller
 * @file			rc.php
 * @description                 Controller extends stdClass to dinamically create attributes for views
 * @author			Alberto Ruffo
 * @license			MIT
 *
 *
 * (c) Dashy (arcoder)
 * This source file is subject to the MIT License that is bundled
 * with this source code in the file LICENSE.
 *
 */
class ControllerException extends Exception {}

abstract class Controller extends stdClass {


	/**
	 * controller
	 * 
	 * @var mixed
	 * @access private
	 * @static
	 */
	protected static $controller;
	/**
	 * action
	 * 
	 * @var mixed
	 * @access private
	 * @static
	 */
	protected static $action;
    /**
     * @var object
     * Contains (object)$_POST
     *
     */
    public $post;

    /**
     * @var object
     * Contains (object)$_GET
     *
     */
    public $get;

    /**
     * @var object
     * Contains (object)$_REQUEST
     *
     */
    public $request;

    /**
     * @var array
     * Contains query string data controller/action/[ciao/hola]
     *
     */
    public $queryString;

    function __construct() {
        
    }


    /**
     * __load_vars function.
     * 
     * @access public
     * @final
     * @param mixed $RCaction
     * @return void
     */
    final public function __load_vars($RCcontroller, $RCaction, $RCqueryString) {
        $this->post = (object) $_POST;
        $this->get = (object) $_GET;
        $this->request = (object) $_REQUEST;
        self::$controller = $RCcontroller;
        self::$action = $RCaction;
        $this->queryString = $RCqueryString;
    }

    /**
     * Commands before Action call
     *
     * @return void
     */
    public function before_filter() {
        
    }

    /**
     * Commands after Action call
     *
     * @return void
     */
    public function after_filter() {
        
    }

    /**
     * Print model errors if they are thrown
     *
     * @param string $msg
     *
     * @return void
     */
    /*
      final protected function modelErrors($msg='') {
      if ($msg == '') {
      if (isset($_SESSION['model_errors']) && strlen(trim($_SESSION['model_errors'])) > 0) {
      echo $_SESSION['model_errors'];
      $_SESSION['model_errors'] = null;
      }
      }
      else
      $this->model_errors = $_SESSION['model_errors'] = $msg;
      }
     */

    /**
     * Filters actions through protected methods
     * EX.: $this->filter('checkUserLogin',array('except' => array('login','search','register','new_from_facebook')));
     * EX.: $this->filter('checkUserLogin',array('all'));
     * EX.: $this->filter('checkUserLogin',array('only' => array('login','search'));
     *
     * @param string $method
     * @param array  $actions
     * @param array  $args (protected method args)
     * 
     * @return void
     */
    final protected function filter($method, $actions = array(), $args=array()) {
        $ref = new ReflectionObject($this);
        try {
            if (!$ref->getMethod($method)->isProtected())
                throw new ControllerException(' \'' . $method . '\' METHOD must be protected');
        } catch (ControllerException $e) {
            die($e->getMessage());
        }

        if (is_array($actions) && count($actions) == 1) {
            foreach ($actions as $key => $value) {
                switch ($key) {
                    case 'all':
                        if (count($args) > 0)
                            call_user_func(array($this, $method), $args);
                        else
                            call_user_func(array($this, $method));

                        break;
                    case 'except':
                        $flag = 1;
                        foreach ($actions[$key] as $action) {
                            if (RC::scoresToCamel(self::$action) == $action)
                                $flag = 0;
                        }
                        if ($flag == 1) {
                            if (count($args) > 0)
                                call_user_func(array($this, $method), $args);
                            else
                                call_user_func(array($this, $method));
                        }
                        break;
                    case 'only':
                        $flag = 0;
                        foreach ($actions[$key] as $action) {
                            if (RC::scoresToCamel(self::$action) == $action)
                                $flag = 1;
                        }
                        if ($flag == 1) {
                            if (count($args) > 0)
                                call_user_func(array($this, $method), $args);
                            else
                                call_user_func(array($this, $method));
                        }
                        break;
                }
            }
        }
        else {
            throw new ControllerException("Filter method accepts an array with a key(all, except, only), ex: array('all' => 'method1', 'method2')");
        }
    }
    
    protected function get_this_url() {
    	#if(count($this->queryString)>0) {
    	$params = array(self::$controller, self::$action);
	    #	return array_merge($params, $this->queryString, $this->get);
    	#}
	    #else
	    	return array_merge($params, $this->queryString);
    }

    function __destruct() {
        $this->post = null;
        $this->get = null;
        $this->request = null;
        unset($this->post);
        unset($this->get);
        unset($this->request);
    }

}

class Format {

    private static $FORMAT;
    private static $CONTROLLER;
    private static $ACTION;

    public static function json($obj) {
        if (self::$FORMAT == 'json') {
            echo json_encode(self::jsonize($obj), JSON_HEX_TAG);
            exit;
        }
    }

    private static function jsonize($arr) {
        $good = array();
        if (is_array($arr)) {
            foreach ($arr as $key => $o) {
                if (is_object($o) && !($o instanceof RedBean_OODBBean))
                    $good[$key] = get_object_vars($o);
                elseif (is_object($o) && $o instanceof RedBean_OODBBean)
                    $good[$key] = $o->export();
                elseif (is_array($o)) {
                    $good[$key] = self::jsonize($o);
                }
                else
                    $good[$key] = $o;
            }
        }
        else {
            if (is_object($arr) && !($arr instanceof RedBean_OODBBean))
                $good[] = get_object_vars($arr);
            elseif (is_object($arr) && $arr instanceof RedBean_OODBBean)
                $good[] = $arr->export();
            else
                $good[] = $arr;
        }
        return $good;
    }

    public static function configure($controller, $action, $format) {
        self::$FORMAT = $format;
        self::$CONTROLLER = $controller;
        self::$ACTION = $action;
    }

    public static function xml($obj) {
        if (self::$FORMAT == 'xml') {
            $vars = array();
            
            if (is_array($obj))
                $vars = $obj;
            elseif (is_object($obj) && !($obj instanceof RedBean_OODBBean))
                $vars = get_object_vars($obj);
            elseif (is_object($obj) && $obj instanceof RedBean_OODBBean)
                $vars = $obj->export();
            else
                $vars = array($obj);
            
			header("Content-type: text/xml; charset=utf-8");

            
            $xml = new XmlWriter();
            $xml->openMemory();
            $xml->startDocument('1.0', 'UTF-8');
            $xml->startElement('root');
            self::writeXML($xml, $vars);

            $xml->endElement();
            echo $xml->outputMemory(true);
            exit;
            
        }
    }
    

    public static function writeXML(XMLWriter $xml, $data) {
        if (is_object($data))
            $data = get_object_vars($data);
        foreach ($data as $key => $value) {
            if (is_integer($key))
                $key = 'row';
            if (is_object($value) && !($value instanceof RedBean_OODBBean)) {
                $data[$key] = get_object_vars($value);
                $xml->startElement($key);
                self::writeXML($xml, $data[$key]);
                $xml->endElement();
                continue;
            } elseif (is_object($value) && $value instanceof RedBean_OODBBean) {
                $xml->startElement($key);
                self::writeXML($xml, $value->export());
                $xml->endElement();
                continue;
                //$data[$key] = get_object_vars($value);
            }

            if (is_array($value)) {

                $xml->startElement($key);
                self::writeXML($xml, $value);
                $xml->endElement();
                continue;
            }
            $xml->writeElement($key, $value);
        }
    }

}

/**
 * Controller
 * @file			rc.php
 * @description                 Model extends RedBean_SimpleModel to validate data
 * @author			Alberto Ruffo
 * @license			MIT
 *
 *
 * (c) Dashy (arcoder)
 * This source file is subject to the MIT License that is bundled
 * with this source code in the file LICENSE.
 *
 */
 class ModelException extends Exception {}
 
class Model extends RedBean_SimpleModel {

    private $errors = array();
    private $table;

    function __construct($tbl) {
        $this->table = $tbl;
    }
    protected function update() {
	    $this->throwModelException();

    }
     protected function after_update() {
	    $this->throwModelException();

    }   
    protected function open() {
	    $this->throwModelException();

    } 
    protected function dispense() {
	    $this->throwModelException();
    } 
    
    private function throwModelException() {
	    if(count($this->errors)>0){
		    throw new ModelException();
	    }
    }

    protected function validates_presence_of($word, $message) {
        if (trim($this->$word) == '') {
            $this->errors[$word][] = $message;
        }
    }
    
    protected function validates_presence_for($words, $message) {
    	if(count($words)>0) {
    		foreach($words as $field) {
        		if (trim($this->$field) == '') {
            		$this->errors[$field][] = $message;
            	}
            }
        }
    }

    protected function validates_ctype($word, $ctype, $message) {
        if (!call_user_func_array('ctype_' . $ctype, array($this->$word))) {
            $this->errors[$word][] = $message;
        }
    }

    protected function validates_ctype_for($words, $ctype, $message) {
    	if(count($words)>0) {
    		foreach($words as $field) {
	    		if (!call_user_func_array('ctype_' . $ctype, array($this->$field))) {
            		$this->errors[$field][] = $message;
            	}
            }
        }
    }

    protected function validates_email_of($word, $message) {
        if (!filter_var($this->$word, FILTER_VALIDATE_EMAIL))
            $this->errors[$word][] = $message;
    }

    protected function validates_length_of($word, $options, $message) {
        if (count($options) == 0)
            throw new ModelException('validates_length_of, you must define an option');
        else {
            foreach ($options as $type => $value) {
                switch ($type) {
                    case 'maximum': 
                        if (mb_strlen($this->$word) > $value) 
                            $this->errors[$word][] = $message;             
                        break;
                    case 'minimum':
                        if (mb_strlen($this->$word) < $value)
                            $this->errors[$word][] = $message;

                        break;
                    case 'is':
                        if (mb_strlen($this->$word) == $value)
                            $this->errors[$word][] = $message;
                        break;
                    default: throw new ModelException('validates_length_of, you must define an option');

                }
            }
        }
    }
    protected function validates_length_for($words, $options, $message) {
    	if(count($words)>0) {
	        if (count($options) == 0)
	            throw new ModelException('validates_length_of, you must define an option');
	        else {
	        	foreach($words as $field) {
		            foreach ($options as $type => $value) {
		                switch ($type) {
		                    case 'maximum': 
		                        if (mb_strlen($this->$field) > $value) 
		                            $this->errors[$field][] = $message;             
		                        break;
		                    case 'minimum':
		                        if (mb_strlen($this->$field) < $value)
		                            $this->errors[$field][] = $message;
		
		                        break;
		                    case 'is':
		                        if (mb_strlen($this->$field) == $value)
		                            $this->errors[$field][] = $message;
		                        break;
		                    default: throw new ModelException('validates_length_for, you must define an option');
		
		                }
		            }
		        }
	        }
	    }
    }    
    protected function validates_confirmed_field($field, $message) {
    	$cfield = 'confirmed_'.$field;
	    if($this->$field != $this->$cfield)
	    	$this->errors[$field][] = $message;
	    $this->$cfield = null;
    }


    protected function validates_uniqueness_of($field, $message) {
        if (R::findOne($this->table, $field . '=?', array($this->$field)) != null)
            $this->errors[$field][] = $message;
    }
    
        protected function validates_uniqueness_for($fields, $message) {
        if(count($fields)>0) {
        	foreach($fields as $field) {
	        	if (R::findOne($this->table, $field . '=?', array($this->$field)) != null)
	        		$this->errors[$field][] = $message;
	        }
        }
    }

    public function getErrors() {
        return $this->errors;
        
    }

    public function isValid() {
        if (count($this->errors) == 0)
            return true;
        return false;
    }

    public function isValidField($word) {
        if (array_key_exists($word, $this->errors))
            return false;
        return true;
    }
    
    public function viewFieldErrors($field) {
	    if(!$this->isValidField($field)) {
	    	echo '<div class=\'model_error_div\'>';
		    echo '<ul class=\'model_error '.$field.'\'>';
		    foreach($this->errors[$field] as $error) {
			    echo '<li class=\'model_error_li\'>'.$error.'</li>';
		    }
		    echo '</ul>';
		    echo '</div>';
	    }
    }
    
    public function viewModelErrors() {
		if(!$this->isValid()) {
			echo '<h3 class=\'model_errors_h3\'>Si sono verificati degli errori</h3>';
			echo '<div class=\'model_errors_div\'>';
			echo '<ul class=\'model_errors\'>';
			foreach($this->errors as $field => $messages) {
				echo '<li class=\'model_errors_field_li\'>'.str_replace("_"," ",$field);
				echo '<ul class=\'model_errors_messages_li\'>';
				foreach($messages as $error) {
					echo '<li class=\'model_errors_error_li\'>'.$error.'</li>';
				}
				echo '</ul></li>';
				}
				echo '</ul>';
				echo '</div>';
			}
			
		}
}



/**
 * View
 * @file			rc.php
 * @description                 View class outputs html layouts
 * @author			Alberto Ruffo
 * @license			MIT
 *
 *
 * (c) Dashy (arcoder)
 * This source file is subject to the MIT License that is bundled
 * with this source code in the file LICENSE.
 *
 */
class View {

    private $objs = array();
    private static $routes = array();
    public static $layout;
    private $cobjs;

    /**
     * @var bool
     * Contains true to load layouts/controller.html.php, else false
     *
     */
    public static $forceLayout = true;

    function __construct($controlObjs, $controlRoutes) {
        $this->objs = get_object_vars($controlObjs);
        $this->cobjs = $controlObjs;
        self::$routes = $controlRoutes;
    }

    public function __get($name) {
        /*
          if (array_key_exists($name, $this->objs)) {
          return $this->objs[$name];
          }
         */
        if (is_array($this->objs[$name])) {
            return (array) $this->objs[$name];
        }

        return $this->objs[$name];
    }

    public function __set($name, $value) {
        $this->objs[$name] = $value;
    }

    public function __isset($att) {
        return array_key_exists($att, get_object_vars($this->cobjs));
    }

    /**
     * Requires an html layout in layouts/ or an action view in view/controller
     *
     * @return void
     */
    public function html() {
        $ctrl = self::$routes['controller'];
        if (self::$forceLayout) {
            if (self::$layout != '')
                $ctrl = self::$layout;
            if (!file_exists('view' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $ctrl . '.html.php'))
                throw new Exception('I cannot load the HTML layout ' . $ctrl . '#' . self::$routes['action']);
            require('view' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $ctrl . '.html.php');
        }
        else {
            if (!file_exists('view' . DIRECTORY_SEPARATOR . $ctrl . DIRECTORY_SEPARATOR . self::$routes['action'] . '.php'))
                throw new Exception('I cannot load the HTML view ' . $ctrl . '#' . self::$routes['action']);
            require( 'view' . DIRECTORY_SEPARATOR . $ctrl . DIRECTORY_SEPARATOR . self::$routes['action'] . '.php');
        }
    }

    /**
     * Dynamically loads the action required
     *
     * @return void
     */
    private function partial($controller, $action) {
        try {
            if (!file_exists('view' . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $action . '.php'))
                throw new Exception("I cannot load the partial of the action $action");
            require('view' . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $action . '.php');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function link_to($params=null, $get=null) {
        return RC::link_to($params, $get);
    }

    /**
     * Loads the required template file
     *
     * @return void
     */
    private function renderTemplate($file) {
        try {
            if (!file_exists('view' . DIRECTORY_SEPARATOR . $file . '.php'))
                throw new Exception('I cannot load tha partial template file ' . $file);
            require('view' . DIRECTORY_SEPARATOR . $file . '.php');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}

if (function_exists('lcfirst') === false) {

    function lcfirst($str) {
        $str[0] = strtolower($str[0]);
        return $str;
    }
}