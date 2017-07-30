<?php
namespace hbynlsl;

class Config
{
    /**
     * 获取配置项
     *
     * @param string $name 待获取的配置项
     * @param string $default 默认值
     * @return string 配置项所对应的值
     */
    public static function get($name, $default = '')
    {
        // 拆分$name
        $pieces = explode('.', $name);
        $configFile = $pieces[0];
        $config = require_once APP_PATH . '/config/' . $configFile . '.php';
        if (1 == count($pieces)) {
            return $config;
        }
        $configName = $pieces[1];
        // 读取配置
        if (array_key_exists($configName, $config)) {
            return $config[$configName];
        }
        return $default;
    }
}