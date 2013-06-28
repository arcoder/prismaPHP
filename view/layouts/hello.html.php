<!DOCTYPE html>
<!--[if IE 8]>
<html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width"/>

    <title>prismaPHP Framework page</title>
    <!-- YOU CAN USE Foundation 4 OR only normalize.css
    	<link href="<?php echo Config::INDEX_URL; ?>/public/css/foundation.css" rel="stylesheet" media="screen">   
    	-->
    <link href="<?php echo Config::INDEX_URL; ?>/public/css/normalize.css" media="screen" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo Config::INDEX_URL; ?>/public/css/main.css" media="screen" rel="stylesheet" type="text/css"/>

    <script src="<?php echo Config::INDEX_URL; ?>/public/js/vendor/custom.modernizr.js"></script>
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
<!-- YOU CAN USE FOUNDATION
<script>
document.write('<script src=' +
('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
'.js><\/script>')
</script>

<script src="js/foundation.min.js"></script>

<script src="js/foundation/foundation.js"></script>

<script src="js/foundation/foundation.alerts.js"></script>

<script src="js/foundation/foundation.clearing.js"></script>

<script src="js/foundation/foundation.cookie.js"></script>

<script src="js/foundation/foundation.dropdown.js"></script>

<script src="js/foundation/foundation.forms.js"></script>

<script src="js/foundation/foundation.joyride.js"></script>

<script src="js/foundation/foundation.magellan.js"></script>

<script src="js/foundation/foundation.orbit.js"></script>

<script src="js/foundation/foundation.reveal.js"></script>

<script src="js/foundation/foundation.section.js"></script>

<script src="js/foundation/foundation.tooltips.js"></script>

<script src="js/foundation/foundation.topbar.js"></script>

<script src="js/foundation/foundation.interchange.js"></script>

<script src="js/foundation/foundation.placeholder.js"></script>



<script>
$(document).foundation();
</script>
-->
</body>
</html>