<?php
namespace hbynlsl;

require_once realpath(APP_PATH . '../vendor') . '/autoload.php';

use Clio\Console as Cli;
use hbynlsl\Config;

class Console 
{
    public static function run()
    {
        static::init();
        // 获取请求参数
        $params = $GLOBALS['argv'];
        if (count($params) <= 1) {
            static::output('请输入命令参数');
            exit;
        }
        // 处理请求参数
        switch (strtolower($params[1])) {
            case 'list':    // 列出所有命令
                static::output('命令列表');
                static::output('---------------------');
                static::output('make:app 创建应用程序目录结构');
                static::output('make:controller 创建控制器类');
                static::output('make:model 创建模型类');
                static::output('route:clear 清空路由');
                break;
            case 'make:controller': // 创建控制器类
                static::makeController($params[2], $params[3]);
                break;
            case 'make:model':  // 创建模型类
                static::makeModel($params[2]);
                break;
            case 'make:app':    // 创建应用程序目录结构
                static::makeApplication();
                break;
            case 'route:clear':  // 清空路由文件
                file_put_contents(APP_PATH . '/router.php', file_get_contents(dirname(__FILE__) . '/console/router.tpl'));
                break;
            default:            // 用户自定义命令
                break;
        }

    }

    protected static function makeApplication()
    {
        // 创建目录结构
        if (!file_exists(APP_PATH)) {
            mkdir(APP_PATH);
        }
        if (!file_exists(APP_PATH . '/config')) {
            mkdir(APP_PATH . '/config');
        }
        if (!file_exists(APP_PATH . '/controllers')) {
            mkdir(APP_PATH . '/controllers');
        }
        if (!file_exists(APP_PATH . '/models')) {
            mkdir(APP_PATH . '/models');
        }
        if (!file_exists(APP_PATH . '/views')) {
            mkdir(APP_PATH . '/views');
        }
        if (!file_exists(ROOT_PATH . '/public')) {
            mkdir(ROOT_PATH . '/public');
        }
        if (!file_exists(ROOT_PATH . '/public/images')) {
            mkdir(ROOT_PATH . '/public/images');
        }
        if (!file_exists(ROOT_PATH . '/public/css')) {
            mkdir(ROOT_PATH . '/public/css');
        }
        if (!file_exists(ROOT_PATH . '/public/js')) {
            mkdir(ROOT_PATH . '/public/js');
        }
        if (!file_exists(ROOT_PATH . '/public/uploads')) {
            mkdir(ROOT_PATH . '/public/uploads');
        }
        // 创建配置文件
        file_put_contents(APP_PATH . '/router.php', file_get_contents(dirname(__FILE__) . '/console/router.tpl'));
        file_put_contents(APP_PATH . '/config/db.php', file_get_contents(dirname(__FILE__) . '/console/db.tpl'));
        file_put_contents(APP_PATH . '/config/app.php', file_get_contents(dirname(__FILE__) . '/console/app.tpl'));
        file_put_contents(APP_PATH . '/config/page.php', file_get_contents(dirname(__FILE__) . '/console/page.tpl'));
        file_put_contents(APP_PATH . '/config/file.php', file_get_contents(dirname(__FILE__) . '/console/file.tpl'));
        file_put_contents(APP_PATH . '/config/session.php', file_get_contents(dirname(__FILE__) . '/console/session.tpl'));
        file_put_contents(APP_PATH . '/Bootstrap.php', static::buildTemplate(dirname(__FILE__) . '/console/bootstrap.tpl', 'Bootstrap'));
        // 创建入口文件
        file_put_contents(ROOT_PATH . '/public/index.php', file_get_contents(dirname(__FILE__) . '/console/index.tpl'));
        file_put_contents(ROOT_PATH . '/public/.htaccess', file_get_contents(dirname(__FILE__) . '/console/htaccess.tpl'));
    }

    protected static function makeModel($modelName)
    {
        $file = APP_PATH . '/models/' . $modelName . '.php';
        $stubName = dirname(__FILE__) . '/console/model.tpl';
        $data = static::buildTemplate($stubName, $modelName);
        if (false !== file_put_contents($file, $data)) {
            static::output($modelName . '创建成功');
        } else {
            static::output($modelName . '创建失败');
        }
    }

    protected static function makeController($controllerName, $param = '')
    {
        $file = APP_PATH . '/controllers/' . $controllerName . '.php';
        if ($param == '-r') {
            $stubName = dirname(__FILE__) . '/console/controller.resource.tpl';
        } else {
            $stubName = dirname(__FILE__) . '/console/controller.plain.tpl';
        }
        $data = static::buildTemplate($stubName, $controllerName);
        if (false !== file_put_contents($file, $data)) {
            static::output($controllerName . '创建成功');
        } else {
            static::output($controllerName . '创建失败');
        }
    }

    protected static function buildTemplate($fileName, $class)
    {
        return str_replace(['{%className%}', '{%namespace%}'], [
            $class,
            Config::get('app.namespace', 'App'),
        ], file_get_contents($fileName));
    }

    protected static function output($str)
    {
        Cli::output(iconv('UTF-8', 'gb2312', $str));
    }

    protected static function init()
    {
        if (php_sapi_name() != 'cli') {
            die('Must run from command line');
        }

        error_reporting(E_ALL ^ E_NOTICE);
        ini_set('display_errors', 1);
        ini_set('log_errors', 0);
        ini_set('html_errors', 0);
    }
}