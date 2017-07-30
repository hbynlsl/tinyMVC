<?php
namespace hbynlsl;

use hbynlsl\Config;
use hbynlsl\Route;
use TORM\Connection;

class Application
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

    /**
     * 启动用户程序
     */
    public function run()
    {
        // 加载路由
        $this->loadRouters();
        // 启动应用程序
        $this->router->run();
    }

    /**
     * 加载路由配置
     */
    protected function loadRouters()
    {
        $routers = require_once APP_PATH . '/router.php';
        foreach ($routers as $method => $router) {
            switch (strtoupper($method)) {
                case 'RESOURCE':    // 资源控制器路由
                    foreach ($router as $url => $action) {
                        $this->router->get($url, $action . '@index');    // GET index
                        $this->router->get($url . '/create', $action . '@create');  // GET create
                        $this->router->post($url, $action . '@store');  // POST
                        $this->router->get($url . '/(\d+)', $action . '@show'); // GET id
                        $this->router->get($url . '/(\d+)/edit', $action . '@edit'); // GET id edit
                        $this->router->put($url . '/(\d+)', $action . '@update'); // PUT id
                        $this->router->delete($url . '/(\d+)', $action . '@destroy');  // DELETE id
                    }
                    break;
                case 'GET':
                case 'POST':
                case 'PUT':
                case 'DELETE':
                    foreach ($router as $url => $action) {
                        $this->router->$method($url, $action);
                    }
                    break;
                default:
                    foreach ($router as $url => $action) {
                        $this->router->get($url, $action);
                    }
                    break;
            }
        }
    }

    /**
     * 初始化数据库连接
     */
    protected function _initDbConnection()
    {
        $dbconfig = Config::get('db');
        Connection::setConnection(new \PDO("{$dbconfig['dbdriver']}:dbname={$dbconfig['dbname']};host={$dbconfig['dbhost']}", $dbconfig['dbuser'], $dbconfig['dbpswd']));
        Connection::setDriver($dbconfig['dbdriver']);
    }
}
