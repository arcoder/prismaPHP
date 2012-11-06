<?php

class AppRoute extends Routes {
	
		protected static $routes = array(
							':all/:all',
							'posts/view/:all',
							'hello/view_all/:numeric/:alnum',
							#array('shop','_ALL_'),
							#array('shop', 'product','_ALL_'), 
							':all'
		);
		protected static $aliases = array(
			array('alias','hello/welcome'),
			array('hello/:numeric/:printable','hello/view_all/:numeric/:printable')
		);
		
}