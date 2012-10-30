<?php

class H {

    function __construct() {
        
    }
    
    public static function toAscii($str, $replace=array(), $delimiter='-', $maxLength=200) {

		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}

		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("%[^-/+|\w ]%", '', $clean);
		$clean = strtolower(trim(substr($clean, 0, $maxLength), '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;
	}

    public static function h($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    public static function decimal($num) {
        return number_format($num, 2, '.', '');
    }
    
	public static function base64_url_decode($input) {
    	return base64_decode(strtr($input, '-_', '+/'));
	}
	
	public static function orderCode($number) {
		$year = date("y", time());
		return $year.sprintf("%08d", $number);
	}

    public static function timestampToAgo($ts) {

        $qty = 0;
        $unit = 0;
        $now = time();
        $life = $now - $ts;
        $seconds = $life;
        $minutes = floor($life / 60);
        $hours = floor($life / (60 * 60));
        $days = floor($life / (60 * 60 * 24));
        if ($life == 0)
            return 'poco fa';

        if ($seconds && !$minutes) {
            $qty = $seconds;
            $unit = $seconds == 1 ? 'secondo' : 'secondi';
        } elseif ($seconds && $minutes && !$hours) {
            $qty = $minutes;
            $unit = $minutes == 1 ? 'minuto' : 'minuti';
        } elseif ($seconds && $minutes && $hours && !$days) {
            $qty = $hours;
            $unit = $hours == 1 ? 'ora' : 'ore';
        } elseif ($seconds && $minutes && $hours && $days) {
            if ($days < 30) {
                $qty = $days;
                $unit = $days == 1 ? 'giorno' : 'giorni';
            } elseif ($days >= 30 && $days < 365) {
                $qty = floor($days / 30);
                $unit = $qty == 1 ? 'mese' : 'mesi';
            } elseif ($days >= 365) {
                $qty = floor($days / 365);
                $unit = $qty == 1 ? 'anno' : 'anni';
            }
        }
        $time = "{$qty} {$unit} fa";
        return $time;
    }

    public static function dateToAgo($dt) {
        return H::timestampToAgo(strtotime($dt));
    }

    public static function getPartitionedDirById($id, $root) {
        preg_match_all('/.../', sprintf("%09d", $id), $matches);
        return $root . DIRECTORY_SEPARATOR . implode('/', $matches[0]);
    }

    public static function createPartitionedDirById($id, $root) {
        if (!file_exists(H::getPartitionedDirById($id, $root)))
            mkdir(H::getPartitionedDirById($id, $root), 0777, true);
    }

    public static function truncate($str, $length=61) {
        return preg_replace('/\s+?(\S+)?$/', '', substr($str, 0, $length)) . '...';
    }

    public static function phpCode($s) {
        $s = str_replace("]\n", "]", $s);
        $match = array('#\[php\](.*?)\[\/php\]#se');
        $replace = array("'<div>'.highlight_string(stripslashes('$1'), true).'</div>'");
        return preg_replace($match, $replace, $s);
    }

}

?>