<h1><?php echo $this->word; ?></h1>
<h2>Edit this data(file: config/Config.php):</h2>
<code>
Config::INDEX_URL value: <b><?php echo Config::INDEX_URL; ?></b><br />
Config::APP value: <b><?php echo Config::APP; ?></b><br />
Config::DB_ADAPTER<br />
Config::DB_DATABASE<br />
Config::DB_HOST<br />
Config::$DB_USER<br />
Config::$DB_PASSWORD</code>
<?php if(in_array(Config::DB_ADAPTER,array('mysql','postgresql','sqlite'))): ?>
<p>Database adapter selected: <b><?php echo Config::DB_ADAPTER; ?></b>.</p>
<?php else: ?>
<p><b>No database adapter selected</b></p>
<?php endif; ?>
<h2>Development status detected(file: config/config.php):</h2>
<code>
<?php echo (Config::DEVELOPMENT_ENV==true) ? 'On' : 'Off'; ?> 
</code>
<h2>Edit default controller &amp; action(file: ./public/index.php):</h2>
<code>AppRoute::build('hello', 'welcome', 'html');</code>
<h2>Change the routes when you need, look at (file: config/AppRoute.php):</h2>
<code>
:numeric is for integers: posts/read/:numeric:html Framework will check type for you.<br />

:printable is for string and printable characters: posts/read/:numeric:/:printable.html Framework will check type for you.
</code>
<h2>This page is available in more formats(see SiteController.php):</h2>
<code><a href="<?php echo $this->link_to('hello/welcome.json'); ?>"><?php echo $this->link_to('hello/welcome.json'); ?></a><br />
<a href="<?php echo $this->link_to('hello/welcome.xml'); ?>"><?php echo $this->link_to('hello/welcome.xml'); ?></a></code>
<h2>Use a fast redirect with a flash message:</h2>
<code>
<p><a href="<?php echo $this->link_to('hello/redirect_to'); ?>">Redirect!</a></p>
</code>
<h2>$_SERVER print_r</h2>
<code><?php echo H::phpCode('[php]'.print_r($_SERVER,true).'[/php]'); ?></code>

<h2>Simple routes usage:</h2>
<code>hello/view_all/:numeric/:alnum -> <a href="<?php echo $this->link_to('hello/1/view_all'); ?>"><?php echo $this->link_to('hello/1/view_all'); ?></a></code>
</pre>
<h2>=) <a href="http://framework.dashy.it">Dashy! Framework</a></h2>
<p><?php #echo Pagination::links($this->links, $this->queryString[0]); ?></p>
