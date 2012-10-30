<?php

class Routing  {

	private static $routes = array(
							array('hello','__ALL__'),
					
							#array('shop','__ALL__'),
							#array('shop', 'product','__ALL__'), 
							array('__ALL__')
						);
						
	private static $aliases = array(
						array('__ALL__' => array('shop','product','__ALL__'))
	);
	
	
	
	const __ALL__              = '__ALL__';
	const __ALL_NUMERIC__       = '__ALL_NUMERIC__';
	const __ALL_ALPHANUMERIC__  = '__ALL_ALPHANUMERIC__';
	
	private static $constArray = array('__ALL__', '__ALL_NUMERIC__', '__ALL_ALPHANUMERIC__');
	
	public static function isAllowed($querystring) {
		$layers = count($querystring);
	
		$good = false;
		#echo "<br><b>inizio</b>";
		#$a = 0;
		foreach(self::$routes as $route) {
		#echo '<br><b>RIGA '.$a.' ';
			// ESEGUO SOLO SE LE DIMENSIONI DEI VETTORI SONO LE STESSE
			if($layers == count($route) && $good == false) {
				// Scorro le colonne
				for($i = 0; $i < $layers; $i++) {	
					if(!in_array($route[$i],self::$constArray) && $route[$i] != $querystring[$i]) {
		#					echo 'diversi: '.$route[$i].' - '.$querystring[$i];
							$good = false;
							break 1;
					} elseif($route[$i] == self::__ALL_NUMERIC__) {
						if(is_numeric($querystring[$i])) 
							$good = true;
						else
							$good = false;				
					} elseif($route[$i] == self::__ALL_ALPHANUMERIC__) {
						if(ctype_alnum($querystring[$i]))
							$good = true;
						else
							$good = false;				
					} else {
		#				echo 'uguali: '.$route[$i].' - '.$querystring[$i];

						$good = true;
					}
		#			echo ' - ';
				}
			}
		#	$a++; 
		}
		return $good;
	}
}