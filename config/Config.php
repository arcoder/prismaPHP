<?php

class Config {

    /**
     * SITE
     * 
     * (default value: "http://localhost:8888/fedom")
     * 
     * @var string
     * @access public
     * @static
     */
    public static $SITE = "http://localhost:8888/framework";
    
    /**
     * DOCS
     * 
     * (default value: "http://framework.dashy.it")
     * 
     * @var string
     * @access public
     * @static
     */
    public static $DOCS = "http://framework.dashy.it";
    
    /**
     * APP
     * 
     * (default value: "FEDOM.it")
     * 
     * @var string
     * @access public
     * @static
     */
    public static $APP = "Framework";
    
    /**
     * SMTPhost
     * 
     * (default value: "smtp.fedom.it")
     * 
     * @var string
     * @access public
     * @static
     */
    public static $SMTPhost = "smtp.host.it";
   
    /**
     * SMTPusername
     * 
     * (default value: "info@fedom.it")
     * 
     * @var string
     * @access public
     * @static
     */
    public static $INFOemail = "infomyemail@host.it";
     
    /**
     * SMTPusername
     * 
     * (default value: "info@fedom.it")
     * 
     * @var string
     * @access public
     * @static
     */
    public static $SMTPusername = "smtpuser";
    
    /**
     * SMTPpassword
     * 
     * (default value: "ciao7777")
     * 
     * @var string
     * @access public
     * @static
     */
    public static $SMTPpassword = "smtppassword";
    
    /**
     * SMTPport
     * 
     * (default value: '25')
     * 
     * @var string
     * @access public
     * @static
     */
    public static $SMTPport = '25';
    
    /**
     * adapter
     * 
     * (default value: 'mysql')
     * 
     * @var string
     * @access public
     * @static
     */
    public static $adapter = 'mysql'; //mysql, postgresql, sqlite, no
    
    /**
     * dbname
     * 
     * (default value: 'prova')
     * 
     * @var string
     * @access public
     * @static
     */
    public static $dbname = 'framework';
    
    /**
     * dbhost
     * 
     * (default value: 'localhost')
     * 
     * @var string
     * @access public
     * @static
     */
    public static $dbhost = 'localhost'; //host or sqlite db file
    
    /**
     * dbuser
     * 
     * (default value: 'root')
     * 
     * @var string
     * @access public
     * @static
     */
    public static $dbuser = 'root';
    
    /**
     * dbpassword
     * 
     * (default value: 'root')
     * 
     * @var string
     * @access public
     * @static
     */
    public static $dbpassword = 'root';
    
    /**
     * default_language
     * 
     * (default value: 'it')
     * 
     * @var string
     * @access public
     * @static
     */
    public static $default_language = 'it-IT';
    
    /**
     * http_language_enabled
     * 
     * (default value: false)
     * 
     * @var bool
     * @access public
     * @static
     */
    public static $http_language_enabled = false;
    

	 /**
	  * DEVELOPMENT_ENV
	  * 
	  * (default value: true)
	  * 
	  * @var bool
	  * @access public
	  * @static
	  */
	 public static $DEVELOPMENT_ENV = true;
}

