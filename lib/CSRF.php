<?php

class CSRF {

    public static function guard() {
        if (count($_POST))
            if (!self::check())
                die("Security problem.");

        ob_start();
        register_shutdown_function(array('CSRF', 'loadAndParse'));
    }

    public static function loadAndParse() {
        $data = ob_get_clean();
        $data = self::formParsing($data);
        echo $data;
    }

    private static function formParsing($form_data_html) {
        preg_match_all("/<form(.*?)>(.*?)<\\/form>/is", $form_data_html, $matches, PREG_SET_ORDER);
        if (is_array($matches)) {
            foreach ($matches as $m) {
                if (strpos($m[1], "nocsrf") !== false) {
                    continue;
                }
                $token = self::generateToken();
                $_SESSION['authToken'] = $token;
                $form_data_html = str_replace($m[0], "<form{$m[1]}>
<input type='hidden' name='authToken' value='{$token}' />{$m[2]}</form>", $form_data_html);
            }
        }
        return $form_data_html;
    }

    public static function check() {
        if (isset($_SESSION)) {
            if ((!empty($_SESSION['authToken'])) && (!empty($_POST['authToken']))) {
                if ($_SESSION['authToken'] == $_POST['authToken']) {
                    return true;
                }
            }
        }
        return false;
    }

    private static function generateToken() {
        if (function_exists("hash_algos") and in_array("sha512", hash_algos())) {
            $token = hash("sha512", mt_rand(0, mt_getrandmax()));
        } else {
            $token = ' ';
            for ($i = 0; $i < 128; ++$i) {
                $r = mt_rand(0, 35);
                if ($r < 26) {
                    $c = chr(ord('a') + $r);
                } else {
                    $c = chr(ord('0') + $r - 26);
                }
                $token.=$c;
            }
        }
        return $token;
    }

}