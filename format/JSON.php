<?php

/*
 * @engineer Alberto Ruffo
 * 
 */

class JSON extends FormatHandler {

    private static $enable = 0;
    private static $data = array();

    public static function load($params) {
        self::$enable = 1;
        self::$data = $params;
    }

    public function view() {
        if (self::$enable == 1) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(self::$data, JSON_HEX_TAG);
        } else {
            echo '404 JSON';
            throw new FormatHandlerException('JSON output is not available');
        }
    }

}