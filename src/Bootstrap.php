<?php
namespace hbynlsl;

use hbynlsl\Config;
use hbynlsl\Route;
use TORM\Connection;

class Bootstrap
{
    protected $router = null;

    public function __construct()
    {
        // 建立数据库连接
        $this->_initDbConnection();
        // 开启Session
        session_start();
        // 创建路由对象
        $this->router = new Route();
        // 用户自定义的启动项
        if (method_exists($this, '_initialize')) {
            $this->_initialize();
        }
    }

    public function run()
    {
        // 加载路由
        $this->loadRouters();
        // 启动应用程序
        $this->router->run();
    }

    protected function loadRouters()
    {
        $routers = require_once APP_PATH . '/router.php';
        foreach ($routers as $method => $router) {
            foreach ($router as $url => $action) {
                $this->router->$method($url, $action);
            }
        }
    }

    protected function _initDbConnection()
    {
        $dbconfig = Config::get('db');
        Connection::setConnection(new \PDO("{$dbconfig['dbdriver']}:dbname={$dbconfig['dbname']};host={$dbconfig['dbhost']}", $dbconfig['dbuser'], $dbconfig['dbpswd']));
        Connection::setDriver($dbconfig['dbdriver']);
    }
}
