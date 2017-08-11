<?php
namespace hbynlsl;

use terranc\Blade\Compilers\BladeCompiler;
use terranc\Blade\Engines\CompilerEngine;
use terranc\Blade\Factory;
use terranc\Blade\FileViewFinder;

class Controller
{
    protected $props = [];

    public function __construct()
    {
        if (method_exists($this, '__initialize')) {
            $this->__initialize();
        }
    }

    public function assign($name, $value)
    {
        $this->props[$name] = $value;
    }

    public function __set($name, $value)
    {
        $this->assign($name, $value);
    }

    public function display($file = '')
    {
        // 若未传入视图文件名，则使用控制器名和动作名表示视图
        if (!$file) {
            // 1. 获取当前控制器类的类名称（不含Controller后缀）
            // 创建反射类
            $reflection = new \ReflectionClass($this);
            // 通过反射类获取类名称
            $className = $reflection->getShortName();
            // 去除Controller后缀，并调整为小写字符
            $className = strtolower(basename($className, 'Controller'));
            $reflection = null;
            // 2. 获取当前方法名称
            // 2.1 获取当前方法的调用堆栈
            $methods = debug_backtrace();
            // 2.2 获取调用当前方法的方法名
            $methodName = $methods[1]['function'];
            // 2.3 去除Action后缀，并调整为小写字符
            $methodName = strtolower(basename($methodName, 'Action'));
            // 3. 构造视图文件
            $file = $className . '.' . $methodName;
        }
        // 4. 配置视图引擎参数
        $path = [APP_PATH . '/views'];  // 默认视图文件目录
        $cachePath = APP_PATH . '/views/cache'; // 编译后的视图目录
        $compiler = new BladeCompiler($cachePath);
        $enginer = new CompilerEngine($compiler);
        $finder = new FileViewFinder($path);
        $finder->addExtension('php');
        $finder->addExtension('tpl');
        // 5. 创建视图对象
        $view = new Factory(
            $enginer,   // 模块编译引擎
            $finder    // 模板文件查找引擎
        );
        // 6. 显示视图
        echo $view->make($file, $this->props)->render();
    }
}