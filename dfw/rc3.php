<?php
/*
  Copyright (c) 2012 Alberto Ruffo

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
	    $_SESSION['rc_lang'] = $lang;
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
        if (isset($_SESSION['rc_lang']) && self::fileExists($_SESSION['rc_lang'], $basename))
            return simplexml_load_file(ROOT.DIRECTORY_SEPARATOR. 'lang' . DIRECTORY_SEPARATOR .  $_SESSION['rc_lang'] . DIRECTORY_SEPARATOR . $basename . '.xml');
        elseif(self::fileExists(Config::LANG_DEFAULT, $basename)) {
        	return simplexml_load_file(ROOT.DIRECTORY_SEPARATOR. 'lang' . DIRECTORY_SEPARATOR .  Config::LANG_DEFAULT . DIRECTORY_SEPARATOR . $basename . '.xml');
        }
        else
        	throw new Exception('Required language is not available.');
    }

    public static function fileExists( $language, $basename) {
        if (file_exists(ROOT.DIRECTORY_SEPARATOR. 'lang' . DIRECTORY_SEPARATOR .  $language . DIRECTORY_SEPARATOR . $basename . '.xml'))
            return true;
        return false;
    }
 
    public static function exists($dirname) {
        if (file_exists(ROOT.DIRECTORY_SEPARATOR. 'lang' . DIRECTORY_SEPARATOR . $dirname))
            return true;
        return false;
    }

    public static function get() {
        return  $_SESSION['rc_lang'];
    }

    public static function set($lang) {
         $_SESSION['rc_lang'] = $lang;
    }
    
    public static function isConfigured() {
	    if(isset($_SESSION['rc_lang'])) 
	    	return true;
	    return false;
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
                $paginazione .= "<a href=\"" . Sequencer::link_to($url, $get) . "\">$i</a> ";
            }
        }
        $paginazione .= "]</p></div>";
        return $paginazione;
    }

}

class DB {

    public static function configure() {
        switch(Config::DB_ADAPTER) {
            case 'mysql':
                    return R::setup("mysql:host=" . Config::DB_HOST . ";dbname=" . Config::DB_DATABASE, Config::DB_USER, Config::DB_PASSWORD);
                break;
            case 'postgresql':
                    return R::setup('pgsql:host='.Config::DB_HOST .';dbname='.Config::DB_DATABASE,Config::DB_USER,Config::DB_PASSWORD); //postgresql
                break;
            case 'sqlite':
                    return R::setup('sqlite:'.Config::DB_HOST,Config::DB_DATABASE,Config::DB_PASSWORD); //sqlite
                break;       
        }
        if(Config::DEVELOPMENT_ENV == false)
        	R::freeze( true );
    }

}

class Mail {

    private static $transport;

    public static function configure() {
        self::$transport = Swift_SmtpTransport::newInstance(Config::SMTP_HOST, Config::SMTP_PORT)
                ->setUsername(Config::SMTP_USERNAME)
                ->setPassword(Config::SMTP_PASSWORD)
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
			Header('Location:' . Sequencer::link_to($params, $get));
        exit;
    }
    public static function to404($message='') {
	    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404); 
	    require './404.php';
        exit;
    }
    public static function to400($message='') {
	    header($_SERVER["SERVER_PROTOCOL"].' 400 Bad Request', true, 400); 
	    require './400.php';
        exit;
    }    
    
    
    
    public static function store($params=null, $get=null) {
    	$_SESSION['rc_store'] = array('params' => $params, 'get' => $get);
        $_SESSION['rc_store_flag'] = 1;
    }
    public static function back() {
    	if(isset($_SESSION['rc_store'])) {
			Header('Location:' . Sequencer::link_to($_SESSION['rc_store']['params'], $_SESSION['rc_store']['get']));
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
        $_SESSION['rc_error'][$owner][] = $msg;
    }

    /**
     * Checks if an error message is set
     *
     * @param string $msg
     *
     * @return void
     */
    public static function isEmpty() {
        if (isset($_SESSION['rc_error']) && count($_SESSION['rc_error']) > 0)
            return false;
        return true;
    }

    /**
     * Print an error alert
     *
     * @return void
     */
    public static function get($owner, $style=true) {
        if (isset($_SESSION['rc_error'][$owner]) && count($_SESSION['rc_error'][$owner]) > 0) {
            if ($style == true)
            {
            	echo '<ul class=\'rc_error\'>';
            	foreach($_SESSION['rc_error'][$owner] as $msg) {
                	echo '<li style=\'color:#A00;\'>' . $msg . '</li>';
                }
                echo '</ul>';
            }
            else
            	print_r($_SESSION['rc_error'][$owner]);
            $_SESSION['rc_error'][$owner] = null;
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
        $_SESSION['rc_flash'][$owner][] = $msg;
    }

    /**
     * Checks if a flash message is set
     *
     * @param string $msg
     *
     * @return void
     */
    public static function isEmpty() {
        if (isset($_SESSION['rc_flash']) && count($_SESSION['rc_flash']) > 0)
            return false;
        return true;
    }

    /**
     * Print a flash alert
     *
     * @return void
     */
    public static function get($owner, $style=true) {
        if (isset($_SESSION['rc_flash'][$owner]) && count($_SESSION['rc_flash'][$owner]) > 0) {
            if ($style == true)
            {
            	echo '<ul class=\'rc_flash\'>';
            	foreach($_SESSION['rc_flash'][$owner] as $msg) {
                	echo '<li style=\'color:#009900;\'>' . $msg . '</li>';
                }
                echo '</ul>';
            }
            else
                print_r($_SESSION['rc_flash']);
            $_SESSION['rc_flash'] = null;
        }
    }

}

class RoutesException extends Exception {}
class Routes {

		protected static $routes = array();
		protected static $aliases = array();
		
		protected static $status = false;
	
		private static function checkAlias($external_path) {
			foreach(static::$aliases as $id_row => $row) {
				$alias_params   = explode('/', $row[0]);
				$default_params = explode('/', $row[1]);
				if(self::isAllowed($external_path, $alias_params)) {
					static::$status = true;
					return array($row[0], $row[1]);
				}
			}			
		}

		private static function checkDefaultRouting($external_path) {
			foreach(static::$routes as $row) {
				if(!is_string($row))
					throw new RoutesException('In AppRoutes.php \'static routes\' accepts strings');
				$params = explode('/', $row);
				
				if(static::isAllowed($external_path, $params)) {
					#echo 'va bene<br>';
					#echo 'ROUTING SELECTED => "'.$row.'"';
					static::$status = true;
					return $row;
				} 
			}
			static::$status = false;
			return '';			
		}
		
    private static function getPath() {
        if (isset($_SERVER['ORIG_PATH_INFO']))
            $path = $_SERVER['ORIG_PATH_INFO'];
        elseif (isset($_SERVER['PATH_INFO']))
            $path = $_SERVER['PATH_INFO'];
        else
            $path = '/';
        return self::sanitize(mb_substr($path, 1));

    }
	
		public static function build($default_controller, $default_action, $format) {
			#print_r(parent::$routes);
			$path = str_replace('-', '_',self::getPath());
			$external_path = explode('/',$path);
			$alias = array();
			
			if(Config::LANG_MULTI_LANGUAGE == true) {
				if(!Lang::isConfigured()) { 
					$client_lang = strtolower(Lang::getByClient());
					if(Lang::exists($client_lang) && trim($client_lang) !='')
						Lang::configure($client_lang); 
					else {
						Lang::configure(Config::LANG_DEFAULT);
						Redirect::to(Config::INDEX_URL);
					}
				}	else {
					$lang = ($external_path[0] == '') ? Lang::get() : str_replace('_', '-', $external_path[0]);
					if(Lang::exists($lang) && $lang != '') {
						Lang::configure($lang);
					} else {
						Redirect::to404('Cannot find the page, the selected language is not available :-(');
					}			
				}
				array_shift($external_path);
			} else {
				Lang::configure(Config::LANG_DEFAULT);
			}
			$path = implode('/', $external_path);	
	

			
			## RICORDA DA FARE UNA FUNZIONE PER IL TIMEZONE
			date_default_timezone_set('Europe/Rome');
			#if(Config::LANG_MULTI_LANGUAGE == true && Lang::isConfigured()) {

			#} else {
			#	Redirect::to404('Cannot find the page, this language is not available :-(');	
			#}

			setlocale(LC_ALL, str_replace('-','_', Lang::get()));

			if($path != '') {
				$alias_and_route = self::checkAlias($external_path);
				# COUNT CORRECTS EXPLODE PROBLEM WHEN IT HAS AN EMPTY ARRAY
				if($i = substr_count($alias_and_route[0],'/'))
					$alias    = explode('/',$alias_and_route[0], $i+1);
				$strroute = $alias_and_route[1];
				if(self::$status == false) {
					$strroute = self::checkDefaultRouting($external_path);						
				}
				$route = explode('/', $strroute);
			} else {
				self::$status = true;
				$route = array($default_controller, $default_action);
			}
			if(self::$status == false)
				throw new RoutesException('I cannot routing this request. Sorry :-(');
			else
				#echo '<br>ok, require: <b>'.$route.'</b> generated from: <b>'.$path.'</b>';
			# FILE EXTENSION hello/welcome.html
			$ext = parse_url(pathinfo($_SERVER['REQUEST_URI'], PATHINFO_EXTENSION), PHP_URL_PATH);
			
			# CORRECTION FOR DEFAULT APP PAGE hello/welcome -> domain.ext <- without extension
			if ($ext != $format && $ext != '') $format = $ext;
			try {
			$sequencer = Sequencer::getInstance($external_path, $route, $format, $alias);
			$sequencer->phpSettings();
			$sequencer->setController();
			$sequencer->setBeforefilter();
			$sequencer->setAction();
			$sequencer->setAfterfilter();
			$sequencer->render(); 
			} catch(SequencerException $e) {
            	if (Config::DEVELOPMENT_ENV == true) 
        			Redirect::to404($e->getMessage());
        		else
        			Redirect::to404('Cannot find the page :-(');			
        	}
		}
		

    
    private static function sanitize($str) {
		if(preg_match('/[^[:lower:]0-9-\/]+/',$str, $e)) {
				Redirect::to400('Parameters are not valid');
		}
		return $str;
    }
 

	
	public static $constArray = array(':all', ':numeric', ':alnum', ':printable');
	   
	public static function isAllowed($op1, $op2, $first_secure = true) {
		$numOp1 = count($op1);
		$numOp2 = count($op2);
		$good = true;

			// ESEGUO SOLO SE LE DIMENSIONI DEI VETTORI SONO LE STESSE
		if($numOp1 == $numOp2) {
				// Scorro le colonne
				for($i = 0; $i < $numOp1; $i++) {	
						if(!self::verify($op1[$i], $op2[$i])) {
							$good = false;
							break;
						}
				}
			} else
				$good = false;
		return $good;
	}
	
	private static function verify($val1, $val2) {
		if($val1 != $val2) {
			if($val1[0] == ":") {
				$type = $val1;
				$val  = $val2; 
			}
			elseif($val2[0] == ':') {
				$type = $val2;
			 	$val = $val1; 
			 }
			else
				return false;
		} else { 
			if(!in_array($val1, self::$constArray) && !in_array($val2, self::$constArray)) {
				return true;
			}
			return false;
		}
			if($type == ':numeric') { 
				if(ctype_digit($val)) { 
					return true; 
				} else 
					return false;
			} elseif($type == ':alnum') { 
				if(ctype_alnum($val)) {
					return true;
				} else 
				return false;		
			} elseif($type == ':printable') { 
				if(ctype_print($val)) {
					return true;
				} else 
					return false;
			} elseif($type == ':all') { 
				if(ctype_print($val)) {
					return true;
				} else 
					return false;
			} else
			{
				return false;
			}
		}

}

class SequencerException extends Exception {}

class Sequencer {
	private static $instance = null;
	
	private $controller;
	private $action;
	private $params = array();
	private $format;
	
	private $objController;
	
	private $classController;
	private $methodClass;
	
	private function __construct($path, $route, $format, $alias) {
		if(count($route) < 2) {
			$route[] = 'index';
		}
		if(in_array($route[0], Routes::$constArray)) {
			$this->controller = $path[0];
		} else $this->controller = $route[0];
		if(in_array($route[1], Routes::$constArray)) {
			$this->action     = $path[1];		
		} else $this->action     = $route[1];

		$i = 0;
		if(count($alias) > 1) {
			foreach($alias as $piece) {
				if(in_array($piece, Routes::$constArray)) {
					$this->params[] = $path[$i];
				}
				$i++;
			}
		} else {
			array_shift($route);
			array_shift($route);
			foreach($route as $piece) {	
				if(in_array($piece, Routes::$constArray)) {
					$this->params[] = $path[$i+2];
				}
				$i++;
			}			
		}

		$this->format     = $format;
		
		$this->classController = self::separatorToCamel($this->controller,'_', true).'Controller';
		$this->methodClass     = self::separatorToCamel($this->action,'_'. false);
	}
	
	public function setController() {
			if($this->controllerExists($this->classController)) {
				require ROOT.DIRECTORY_SEPARATOR. 'controller' . DIRECTORY_SEPARATOR . $this->classController . '.php';
					if (class_exists($this->classController) && method_exists($this->classController, $this->methodClass) && !in_array($this->methodClass, array('beforeFilter', 'afterFilter', 'configureVars'))) {
						
						Mail::configure();
						DB::configure();
						Format::configure($this->controller, $this->action, $this->format);
						$this->objController = new $this->classController();
						$this->objController->__load_vars($this->controller, $this->action);

					}  else {
                		throw new SequencerException('Controller class found, but i cannot use ' . $this->classController . '#' . $this->methodClass);
                	}
                } 
	}
	public function setBeforeFilter() {
		$this->objController->before_filter();		
	}	
	public function setAction() {
		call_user_func_array(array($this->objController, $this->methodClass), str_replace('_','-',$this->params));
	}
	public function setAfterFilter() {
		$this->objController->after_filter();			
	}	
	
	public function render() {
        if ($this->format == 'html') {
        //$this->objController->html();
	        $view = new View($this->objController, array('controller' => $this->controller, 'action' => $this->action));
	        {
	        	header('Content-type: text/html; charset=UTF-8');
	            $view->html();
	        }
        } 
	}
	
	public function phpSettings() {
		if (get_magic_quotes_gpc()) {
		    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
		    while (list($key, $val) = each($process)) {
		        foreach ($val as $k => $v) {
		            unset($process[$key][$k]);
		            if (is_array($v)) {
		                $process[$key][stripslashes($k)] = $v;
		                $process[] = &$process[$key][stripslashes($k)];
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
	
	public static function getInstance($path, $route, $format, $alias) {
		if(self::$instance == null)
			self::$instance = new Sequencer($path, $route, $format, $alias);
		return self::$instance;
	}
	
    /**
     * Returns true if controller file exists.
     * @return bool
     */
    private function controllerExists($classController) {
    	try {
	        if (file_exists(ROOT.DIRECTORY_SEPARATOR. 'controller' . DIRECTORY_SEPARATOR . $classController . '.php'))
	            return true;
	        else {
	            throw new SequencerException('I cannot load \'' . $classController . '.php\', file doesn\'t exist.');
	            return false;
	        }
        } catch(SequencerException $e) {
            	if (Config::DEVELOPMENT_ENV == true) 
        			Redirect::to404($e->getMessage());
        		else
        		   	Redirect::to404('Cannot find the page.');

        }
    }
    /**
     * Converts string to camel notation
     *
     * @param string $msg
     * @param bool $ucfirst
     * 
     * @return string
     */ 
    public static function separatorToCamel($str, $separator='-', $ucfirst = false) {
        $parts = explode($separator, $str);
        $parts = $parts ? array_map('ucfirst', $parts) : array($str);
        $parts[0] = $ucfirst ? ucfirst($parts[0]) : lcfirst($parts[0]);
        return implode('', $parts);
    }
    
    /**
     * link_to function.
     * 
     * @access public
     * @static
     * @param mixed $params (default: null)
     * @param mixed $get (default: null)
     * @return void
     */
    public static function link_to($params=null, $get=null) {
    	$url = Config::INDEX_URL . '/';
    	if(Config::LANG_MULTI_LANGUAGE == true) 
    		$url .= Lang::get(). '/';

    	if(is_string($params)) {
	    	if (filter_var($params, FILTER_VALIDATE_URL) !== false) {
	    		$url = $params;
	    	} else {
	    		if($params!= '') {
	    			$file = pathinfo($params);
	    			#print_r($file);
	    			if(!isset($file['extension'])) $ext = 'html'; else $ext = $file['extension'];
	    			#$ext = ($file != '') ? $file['extension'] : 'html';
	    			#echo self::toAscii($params);
	    			$url_ar = explode('/', $file['dirname'].'/'.$file['filename']);
	    			$url = $url.implode('/', array_map(array('Sequencer', 'toAscii'), $url_ar)).'.'.$ext;
		    	}
	    	}	
    	} 
	    if (is_array($get) && $get != null)
	    	$url .= '?' . http_build_query($get);
	    return $url; 
    }
    
    public static function toAscii($str, $replace=array(), $delimiter='-', $maxLength=200) {
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}

		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("%[^-/+|\w ]%", '', $clean);
		$clean = strtolower(trim(substr($clean, 0, $maxLength), '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;
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
    final public function __load_vars($RCcontroller, $RCaction) {
        $this->post = (object) $_POST;
        $this->get = (object) $_GET;
        $this->request = (object) $_REQUEST;
        self::$controller = $RCcontroller;
        self::$action = $RCaction;
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
                            if (Sequencer::separatorToCamel(self::$action, '_') == $action)
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
                            if (Sequencer::separatorToCamel(self::$action,'_') == $action)
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
        	header('Content-Type: application/json');
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
            if (!file_exists(ROOT.DIRECTORY_SEPARATOR. 'view' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $ctrl . '.html.php'))
                throw new Exception('I cannot load the HTML layout ' . $ctrl . '#' . self::$routes['action']);
            require(ROOT.DIRECTORY_SEPARATOR. 'view' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $ctrl . '.html.php');
        }
        else {
            if (!file_exists(ROOT.DIRECTORY_SEPARATOR. 'view' . DIRECTORY_SEPARATOR . $ctrl . DIRECTORY_SEPARATOR . self::$routes['action'] . '.php'))
                throw new Exception('I cannot load the HTML view ' . $ctrl . '#' . self::$routes['action']);
            require( ROOT.DIRECTORY_SEPARATOR. 'view' . DIRECTORY_SEPARATOR . $ctrl . DIRECTORY_SEPARATOR . self::$routes['action'] . '.php');
        }
    }

    /**
     * Dynamically loads the action required
     *
     * @return void
     */
    private function partial($controller, $action) {
        try {
            if (!file_exists(ROOT.DIRECTORY_SEPARATOR. 'view' . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $action . '.php'))
                throw new Exception("I cannot load the partial of the action $action");
            require(ROOT.DIRECTORY_SEPARATOR. 'view' . DIRECTORY_SEPARATOR . $controller . DIRECTORY_SEPARATOR . $action . '.php');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function link_to($params=null, $get=null) {
        return Sequencer::link_to($params, $get);
    }

    /**
     * Loads the required template file
     *
     * @return void
     */
    private function renderTemplate($file) {
        try {
            if (!file_exists(ROOT.DIRECTORY_SEPARATOR. 'view' . DIRECTORY_SEPARATOR . $file . '.php'))
                throw new Exception('I cannot load tha partial template file ' . $file);
            require(ROOT.DIRECTORY_SEPARATOR. 'view' . DIRECTORY_SEPARATOR . $file . '.php');
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