# tinyMVC
轻量级的MVC框架，支持模型、视图、控制器分层，支持路由机制等。
## 安装方法
复制vendor/hbynlsl/tiny文件到应用程序根目录，即可执行tiny命令
### tiny命令
php tiny list   显示所有命令  
php tiny make:app   初始化应用程序目录结构  
php tiny make:controller    创建Controller类  
      
      php tiny make:controller PostController  `
      php tiny make:controller PostController -r  `

php tiny make:model 创建Model类  
      
      php tiny make:model Post  `

php tiny make:curd  创建CURD操作（控制器、模型、视图）  
      
      php tiny make:curd Post name=名称;image=图片  `

### composer.json
在composer.json文件中，给出psr-4形式的自动加载规范
      
      "autoload": {  
        "psr-4": {
            "App\\": "Application"
        }
    }  `

## 路由定义
### 路由文件
tinyMVC默认路由文件为 Application/router.php文件，该文件已经定义好不同HTTP请求所支持的路由列表，只需在该数组中能 key-value 形式给出 url 和 处理动作即可。  
处理动作，既可以使用闭包函数，也可以使用控制器动作来处理。
### 资源控制器路由
tinyMVC支持定义资源控制器路由，只需要在"resource"段中定义 key-value 形式即可（url-控制器名称）。
## 配置文件
### 配置文件目录
配置文件默认位于 Application/config 目录下，默认已经准备了一些的基本的配置文件。用户完全可以在此目录下创建自己的配置文件。
### 读取配置项
可以使用 Config::get(配置项名称) 读取配置项的值，配置项的命名规范是 “配置文件名.配置项”，如db.dbhost表示读取db.php文件中dbhost配置项。
## 定义控制器
### 控制器类
控制器类要继承 hbynlsl\Controller类，以实现视图显示等内置方法。
### 控制器方法
assign($name, $value)：为视图变量赋值  
display()：显示视图界面 
## 视图
视图采用blade模板语法，支持视图继承，具体文档请查看[Laravel5.4-blade](https://laravel.com/docs/5.4/blade)。

