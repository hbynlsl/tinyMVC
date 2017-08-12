<?php
// 辅助函数
use hbynlsl\Config;
use hbynlsl\Session;

// 读取配置项
if (!function_exists('config')) {
    /**
     * 读取配置项
     * @param  string $name    待读取的配置项名称
     * @param  string $default 配置项默认值
     * @return string          配置项结果
     */
    function config($name, $default = '') 
    {
        return Config::get($name, $default);
    }
}

// 页面跳转函数
if (!function_exists('redirect')) {
    /**
     * 页面跳转
     *
     * @param string $url 待跳转的URL，默认为网站根地址
     * @return void
     */
    function redirect($url = '/')
    {
        header("Location: $url");exit;
    }
}

// 页面回退函数
if (!function_exists('back')) {
    /**
     * 后退上一步
     *
     * @return void
     */
    function back()
    {
        echo '
        <script type="text/javascript">
            alert("操作错误，请重试！");
            window.history.go(-1);
        </script>
        ';
    }
}

// 判断用户是否已经登录
if (!function_exists('isUserLogined')) {
    /**
     * 判断用户是否已经登录
     *
     * @return boolean 若当前用户已经登录返回true，否则返回false
     */
    function isUserLogined()
    {
        return Session::has(Config::get('session.LOGINED_KEY'));
    }
}

// 获取登录用户名
if (!function_exists('getLoginedUser')) {
    /**
     * 获取当前在Session中登录的用户名
     *
     * @return string 当前已经登录的用户名或空
     */
    function getLoginedUser() 
    {
        return Session::get(Config::get('session.LOGINED_KEY'));
    }
}

// 数组转换为json数据
if (!function_exists('json')) {
    /**
     * 返回数组、Model类的JSON数据表示形式
     *
     * @param array|Model|Collection $data 数组元素、Model类对象、Collection对象
     * @return string json格式字符串或空字符串
     */
    function json($data = [])
    {
        if (is_array($data)) {
            if ($data[0] instanceof \TORM\Model) {
                $arr = [];
                foreach ($data as $model) {
                    $arr[] = $model->getData();
                }
                return json_encode($arr);
            }
            return json_encode($data);
        } else if ($data instanceof \TORM\Model) {
            return json_encode($data->getData());
        } else if ($data instanceof \TORM\Collection) {
            $arr = [];
            foreach ($data as $model) {
                $arr[] = $model->getData();
            }
            return json_encode($arr);
        } else {
            return "";
        }
    }
}