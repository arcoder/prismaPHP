<h1><?php echo $this->word; ?></h1>

<h2>Edit this data(file: config/Config.php):</h2>
<code>
Config::$SITE value: <b><?php echo Config::$SITE; ?></b><br />
Config::$APP value: <b><?php echo Config::$APP; ?></b><br />
Config::$adapter<br />
Config::$dbname<br />
Config::$dbhost<br />
Config::$dbuser<br />
Config::$dbpassword</code>
<?php if(in_array(Config::$adapter,array('mysql','postgresql','sqlite'))): ?>
<p>Database adapter selected: <b><?php echo Config::$adapter; ?></b>.</p>
<?php else: ?>
<p><b>No database adapter selected</b></p>
<?php endif; ?>
<h2>Development status detected(file: config/config.php):</h2>
<code>
<?php echo (Config::$DEVELOPMENT_ENV==true) ? 'On' : 'Off'; ?> 
</code>
<h2>Edit default controller &amp; action(file: /index.php):</h2>
<code>RC::configure('hello', 'welcome', 'html');</code>
<h2>Change the routes if you need, look at (file: config/Routes.php):</h2>
<code>RC::configure('hello', 'welcome', 'html');</code>
<h2>This page is available in more formats(see SiteController.php):</h2>
<code><a href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>?format=json">http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>?format=json</a><br />
<a href="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>?format=xml">http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>?format=xml</a></code>
<h2>Use a fast redirect with a flash message:</h2>
<code>
<p><a href="<?php echo $this->link_to(array('hello','redirect_to')); ?>">Redirect!</a></p>
</code>
<h2>$_SERVER print_r</h2>
<?php echo H::phpCode('[php]'.print_r($_SERVER,true).'[/php]'); ?></code>

<h2>Dynamic link to this page(see php code)</h2>
<code><?php echo $this->link_to(array('hello','welcome')); ?></code>
</pre>
<h2>=) <a href="http://framework.dashy.it">Dashy! Framework</a></h2>
<p><?php #echo Pagination::links($this->links, $this->queryString[0]); ?></p>
