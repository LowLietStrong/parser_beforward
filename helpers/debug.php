<?php
class debug{
    public static function print_r($obj){
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }
    public static function var_dump($obj){
        echo "<pre>";
        var_dump($obj);
        echo "</pre>";
    }
}
