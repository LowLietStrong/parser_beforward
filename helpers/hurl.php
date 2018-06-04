<?php
class hurl{
    public static function tryLoad($url){
        try {
            @$page = file_get_contents($url);
            if (!$page) throw new Exception('Stock server error');
        } catch (Exception $e) {
            echo  "ERROR: SERVER $url not found<br>\n";
            return false;
        }
        return $page;
    }
}