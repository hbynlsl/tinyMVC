<?php
// 定义应用常量
define('ROOT_PATH', realpath(dirname(dirname(__FILE__))));
define('APP_PATH', ROOT_PATH . '/Application/');

// 引入Composer自动加载文件
require_once ROOT_PATH . '/vendor/autoload.php';

// 启动应用程序
$app = new App\Bootstrap();
$app->run();