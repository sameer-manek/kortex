<?php

class Input
{
    public static function exists($type = 'post'){
        switch($type){
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;

            case 'get':
                return (!empty($_GET)) ? true : false;
                break;

            default:
                return false;
                break;
        }
    }

    public static function check($type = 'post'){
        switch($type){
            case 'post':
                $key = array_keys($_POST);
                return $key;
                break;
            case 'get':
                $key = array_keys($_GET);
                return $key;
                break;

            default:
                return false;
                break;
        }
    }

    public static function get($item){
        if(isset($_POST[$item])){
            return $_POST[$item];
        } else if(isset($_GET[$item])){
            return $_GET[$item];
        }

        return '';
    }
}