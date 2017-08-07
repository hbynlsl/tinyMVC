<?php
namespace hbynlsl;

use hbynlsl\View\TemplateView;

class Controller
{
    protected $props = [];

    public function __construct()
    {
        if (\method_exists($this, '__initialize')) {
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
        // 3. 处理待显示的视图文件
        if (!$file) {
            $controllerName = $className;
            $actionName = $methodName;
        } else {
            $pieces = explode('/', $file);
            if (count($pieces) > 1) {
                $controllerName = strtolower($pieces[0]);
                $actionName = strtolower($pieces[1]);
            } else {
                $controllerName = $className;
                $actionName = strtolower($pieces[0]);
            }
        }
        $file = APP_PATH . '/views/' . $controllerName . '/' . $actionName;
        // 4. 处理视图文件扩展名
        if (file_exists($file . '.php')) {
            $fileName = $file . '.php';
        } else if (file_exists($file . '.tpl')) {
            $fileName = $file . '.tpl';
        } else if (file_exists($file . '.html')) {
            $fileName = $file . '.html';
        } else {
            $fileName = $file;
        }
        // 5. 创建视图对象
        $view = new TemplateView($fileName, $this->props);
        // 6. 显示视图
        echo $view->render();
    }
}