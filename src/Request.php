<?php
namespace hbynlsl;

class Request 
{
    public static function param($name, $default = '')
    {
        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        } else if (array_key_exists($name, $_POST)) {
            return $_POST[$name];
        } else {
            return static::put($name, $default);
        }

    }

    public static function get($name, $default = '')
    {
        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        } 
        return $default;
    }

    public static function post($name, $default = '')
    {
        if (array_key_exists($name, $_POST)) {
            return $_POST[$name];
        } 
        return $default;
    }

    public static function put($name, $default = '')
    {
        $_POST = array();
        if ('PUT' == $_SERVER['REQUEST_METHOD']) {
             parse_str(file_get_contents('php://input'), $_POST);
         }
         return static::post($name, $default);
    }

    public static function all()
    {
        if ('PUT' == $_SERVER['REQUEST_METHOD']) {
             parse_str(file_get_contents('php://input'), $_POST);
         }
        return $_REQUEST;
    }

}