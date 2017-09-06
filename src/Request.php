<?php
namespace hbynlsl;

class Request 
{
    /**
     * 处理单文件上传
     * @param  string $name 文件上传字段的name属性
     * @return string|boolean       若文件上传成功，返回文件的保存目录；否则返回false
     */
    public static function uploadFile($name = '')
    {
        if (isset($_FILES) && isset($_FILES[$name])) {
            if ($_FILES[$name]['error'] == 0) {
                // 文件上传成功
                $fileName = 'uploads/' . date('Y-m-d');
                if (!file_exists($fileName)) {
                    mkdir(realpath($fileName));
                }
                $fileName .= '/' . $_FILES[$name]['name'];
                // 移动文件
                if (move_uploaded_file($_FILES[$name]['tmp_name'], $fileName)) {
                    return $fileName;
                }
            }
        }
        return false;
    }

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