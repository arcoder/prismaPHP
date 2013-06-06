<?php

/*
 * @engineer Alberto Ruffo
 * 
 */

class AppRoute extends Router {

    protected static $routes = array(
        #'posts/view/:phrase',
        #'hello/view_all/:numeric/:alnum',
        #'hello/:phrase',
        #'products/add/:numeric',
        #'products/cart_riepilogue',
        #'products/search',
        #'welcome/:phrase',
        'hello/:phrase',
        ':phrase'
    );
    protected static $aliases = array(
        #array('show/:numeric', 'products/show/:numeric'),
        array('alias', 'hello/welcome'),
        array('hello/:numeric/:phrase', 'hello/view_all/:numeric/:alnum'),
    );
    protected static $default_route = array(
        'controller' => 'hello',
        'action' => 'welcome',
        'args' => array(),
        'format' => 'html'
    );

}