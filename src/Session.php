<?php
namespace hbynlsl;

class Session
{
    /**
     * 获取Session信息
     * @param  string $name    待获取的session数据下标
     * @param  string $default 默认值
     * @return string          session数据
     */
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
        $_SESSION = array();
        session_destroy();
    }
}