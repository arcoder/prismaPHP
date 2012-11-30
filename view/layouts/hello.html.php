<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>

        <title>prismaPHP Framework page</title>
        <link href="<?php echo Config::INDEX_URL; ?>/public/css/main.css" media="screen" rel="stylesheet" type="text/css" />
        <style>

        </style>
    </head>
    <body>
        <div id="intestazione">
            <div id="logo"><a href="<?php echo Config::INDEX_URL; ?>"><span>prismaPHP Framework</span></a></div>
            <div id="urls">
                <ul>
                    <li><a href="<?php echo Config::DOCS; ?>/site/contacts">contacts</a></li>
                    <li><a href="https://github.com/arcoder/Dashy--Framework">github</a></li>
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
                	<?php Flash::get('general'); ?>
                	<?php Error::get('general'); ?>
                    <?php $this->partial(self::$routes['controller'], self::$routes['action']); ?>
                    <?php $this->renderTemplate('layouts/tpl/twitter'); ?>
                    <?php $this->renderTemplate('layouts/tpl/about'); ?>
                </div>

            </div>
        </div>
        <div id="pie-di-pagina"><p>prismaPHP <?php echo date('Y', time()); ?></p></div>
    </body>
</html>