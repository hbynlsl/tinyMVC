<?php
namespace hbynlsl;

class Session
{
    public static function get($name, $default = '')
    {
        if (array_key_exists($name, $_SESSION)) {
            return $_SESSION[$name];
        }
        return $default;
    }

    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function has($name)
    {
        return array_key_exists($name, $_SESSION);
    }

    public static function destroy()
    {
        session_destroy();
    }
}