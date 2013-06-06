<?php

/*
 * @engineer Alberto Ruffo
 * 
 */

date_default_timezone_set('Europe/Rome');
setlocale(LC_ALL, 'it_IT');

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
     * (default value: "prismaPHP")
     * 
     * @var string
     * @access public
     * @static
     */
    const APP = "prismaPHP";
    
    /**
     * SMTPhost
     * 
     * (default value: "smtp.site.it")
     * 
     * @var string
     * @access public
     * @static
     */
    const SMTP_HOST = "mail.site.it";
   
     
    /**
     * SMTPusername
     * 
     * (default value: "email@site.it")
     * 
     * @var string
     * @access public
     * @static
     */
    const SMTP_USERNAME = "email@site.it";
    
    /**
     * SMTPpassword
     * 
     * (default value: "password")
     * 
     * @var string
     * @access public
     * @static
     */
    const SMTP_PASSWORD = "password";
    
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
    const LANG_DEFAULT = 'it-it';
    
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
