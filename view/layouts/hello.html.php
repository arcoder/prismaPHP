<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>

        <title>prismaPHP Framework page</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<!-- YOU CAN USE Bootstrap OR only normalize.css
    	<link href="<?php echo Config::INDEX_URL; ?>/public/css/bootstrap.min.css" rel="stylesheet" media="screen">   
    	-->     
    	<link href="<?php echo Config::INDEX_URL; ?>/public/css/normalize.css" media="screen" rel="stylesheet" type="text/css" />    			
    	<link href="<?php echo Config::INDEX_URL; ?>/public/css/main.css" media="screen" rel="stylesheet" type="text/css" />        
    </head>
    <body>
        <div id="intestazione">
            <div id="logo"><a href="<?php echo Config::INDEX_URL; ?>"><span>prismaPHP Framework</span></a></div>
            <div id="urls">
                <ul>
                    <li><a href="<?php echo Config::DOCS; ?>/contacts">contacts</a></li>
                    <li><a href="https://github.com/arcoder/prismaPHP">github</a></li>
                </ul>
            </div>
        </div>
        <div id="manifest">
            <div id="what"><a href="<?php echo Config::DOCS; ?>"><h1>prismaPHP Framewodsrk</h1></a></div>
            <div id="download"><a href="<?php echo Config::DOCS; ?>"><h2>prismaPHP Framewodsrk</h2></a></div>
            <div id="menu">
                <ul class="menuNavigazione">
                    <li><a href="<?php echo Config::DOCS; ?>/docs/page/installation?id=1">install</a></li>
                    <li class="voceCorrente"><a href="<?php echo Config::DOCS; ?>/docs/index">documentation</a></li>
                    <li><a href="#">community</a></li>
                    <li><a href="#">screencasts</a></li>
                </ul>
            </div>
        </div>
        <div id="corpo">
            <div id="corpo-sub">
                <div id="colonna-1">
                	<?php Success::get('general'); ?>
                	<?php Error::get('general'); ?>
                    <?php $this->render(self::$routes['controller'], self::$routes['action']); ?>
                    <?php $this->partial('layouts/tpl/twitter'); ?>
                    <?php $this->partial('layouts/tpl/about'); ?>
                </div>

            </div>
        </div>
        <div id="pie-di-pagina"><p>prismaPHP <?php echo date('Y', time()); ?></p></div>
    	<script src="http://code.jquery.com/jquery.js"></script>
    	<!-- YOU CAN USE BOOTSTRAP
    	<script src="<?php echo Config::INDEX_URL; ?>/public/js/bootstrap.min.js"></script>
    	-->
    </body>
</html>