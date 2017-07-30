<?php
// 引入Composer自动加载文件
require_once 'vendor/autoload.php';

// 定义应用常量
define('APP_PATH', realpath('Application'));

// 启动应用程序
$app = new App\Bootstrap();
$app->run();