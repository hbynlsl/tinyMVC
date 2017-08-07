<?php
namespace hbynlsl;

class Request 
{
    public static function get($name, $default = '')
    {
        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        } else if (array_key_exists($name, $_POST)) {
            return $_POST[$name];
        } else {
            return $default;
        }
    }

    public static function post($name, $default = '')
    {
        if (array_key_exists($name, $_POST)) {
            return $_POST[$name];
        } 
        return $default;
    }

    public static function all()
    {
        return $_REQUEST;
    }

}