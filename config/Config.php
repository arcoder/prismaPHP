<?php

class Config {

    /**
     * SITE
     * 
     * (default value: "http://localhost:8888/framework)
     * 
     * @var string
     * @access public
     * @static
     */
    const INDEX_URL = "http://localhost:8888/framework";
    

    const DOCS = "http://phprisma.org/docs.html";
    
    /**
     * APP
     * 
     * (default value: "FEDOM.it")
     * 
     * @var string
     * @access public
     * @static
     */
    const APP = "Frameworkv0.7";
    
    /**
     * SMTPhost
     * 
     * (default value: "smtp.fedom.it")
     * 
     * @var string
     * @access public
     * @static
     */
    const SMTP_HOST = "smtp.host.it";
   
     
    /**
     * SMTPusername
     * 
     * (default value: "info@fedom.it")
     * 
     * @var string
     * @access public
     * @static
     */
    const SMTP_USERNAME = "smtpuser";
    
    /**
     * SMTPpassword
     * 
     * (default value: "ciao7777")
     * 
     * @var string
     * @access public
     * @static
     */
    const SMTP_PASSWORD = "smtppassword";
    
    /**
     * SMTPport
     * 
     * (default value: '25')
     * 
     * @var string
     * @access public
     * @static
     */
    const SMTP_PORT = '25';
    
    /**
     * adapter
     * 
     * (default value: 'mysql')
     * 
     * @var string
     * @access public
     * @static
     */
    const DB_ADAPTER = 'mysql'; //mysql, postgresql, sqlite, no
    
    /**
     * dbname
     * 
     * (default value: 'prova')
     * 
     * @var string
     * @access public
     * @static
     */
    const DB_DATABASE = 'framework';
    
    /**
     * dbhost
     * 
     * (default value: 'localhost')
     * 
     * @var string
     * @access public
     * @static
     */
    const DB_HOST = 'localhost'; //host or sqlite db file
    
    /**
     * dbuser
     * 
     * (default value: 'root')
     * 
     * @var string
     * @access public
     * @static
     */
    const DB_USER = 'root';
    
    /**
     * dbpassword
     * 
     * (default value: 'root')
     * 
     * @var string
     * @access public
     * @static
     */
    const DB_PASSWORD = 'root';
    
    /**
     * default_language
     * 
     * (default value: 'it')
     * 
     * @var string
     * @access public
     * @static
     */
    const LANG_DEFAULT = 'it-IT';
    
    /**
     * http_language_enabled
     * 
     * (default value: false)
     * 
     * @var bool
     * @access public
     * @static
     */
    const LANG_MULTI_LANGUAGE = false;
    

	 /**
	  * DEVELOPMENT_ENV
	  * 
	  * (default value: true)
	  * 
	  * @var bool
	  * @access public
	  * @static
	  */
	 const DEVELOPMENT_ENV = true;

}

