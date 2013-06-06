<?php

/*
 * @engineer Alberto Ruffo
 * 
 */

class H {
    
    function __construct() {}
    
    public static function fromWinToUTF8($str) {
         #return html_entity_decode(mb_convert_encoding($str, 'Windows-1252', 'UTF-8'), ENT_NOQUOTES, 'Windows-1252');
        return html_entity_decode($str, ENT_NOQUOTES);
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

    public static function h($str, $codifica = 'UTF-8') {
        return htmlspecialchars($str, ENT_QUOTES, $codifica);
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

    public static function generateHash($plainText ='', $salt_length = 10,  $salt = null)
    {
        if ($salt === null)
        {
            $salt = substr(md5(uniqid(rand(), true)), 0, $salt_length);
        }
        else
        {
            $salt = substr($salt, 0, $salt_length);
        }

        return $salt . sha1($salt . $plainText);
    }

    public function generateCode($length=4)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length)
        {
            $code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
    }

    public static function generateUniqueRandomCode($type, $len, $table, $field) {
        $code = H::random($type, $len);
        $f = R::findOne($table, $field.' = ?', array($code));
        if($f != null) {
           return H::generateUniqueRandomCode($type, $len, $table, $field);
        }
        return $code;
    }

    public static function random($type = 'sha1', $len = 20)
    {
        if (phpversion() >= 4.2) mt_srand();
        else
            mt_srand(hexdec(substr(md5(microtime()), - $len)) & 0x7fffffff);

        switch ($type) {
            case 'basic':
                return mt_rand();
                break;
            case 'alpha':
            case 'numeric':
            case 'nozero':
                switch ($type) {
                    case 'alpha':
                        $param = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric':
                        $param = '0123456789';
                        break;
                    case 'nozero':
                        $param = '123456789';
                        break;
                }
                $str = '';
                for ($i = 0; $i < $len; $i ++) {
                    $str .= substr($param, mt_rand(0, strlen($param) - 1), 1);
                }
                return $str;
                break;
            case 'md5':
                return md5(uniqid(mt_rand(), TRUE));
                break;
            case 'sha1':
                return sha1(uniqid(mt_rand(), TRUE));
                break;
        }
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