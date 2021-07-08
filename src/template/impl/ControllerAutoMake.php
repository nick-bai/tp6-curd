<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 10:49 PM
 */
namespace nickbai\tp6curd\template\impl;

use nickbai\tp6curd\template\IAutoMake;
use think\facade\App;

class ControllerAutoMake implements IAutoMake
{
    public function check($controller, $path, $output)
    {
        !defined('DS') && define('DS', DIRECTORY_SEPARATOR);

        $controller = ucfirst($controller);
        $controllerFilePath = App::getAppPath() . $path . DS . 'controller' . DS . $controller . '.php';

        if (!is_dir(App::getAppPath() . $path . DS . 'controller')) {
            mkdir(App::getAppPath() . $path . DS . 'controller', 0755, true);
        }

        if (file_exists($controllerFilePath)) {
            $output->error("$controller.php已经存在");
            exit;
        }
    }

    public function make($controller, $path)
    {
        $controllerTpl = dirname(dirname(__DIR__)) . '/tpl/controller.tpl';
        $tplContent = file_get_contents($controllerTpl);

        $controller = ucfirst($controller);
        $filePath = empty($path) ? '' : DS . $path;
        $namespace = empty($path) ? '\\' : '\\' . $path . '\\';

        $tplContent = str_replace('<namespace>', $namespace, $tplContent);
        $tplContent = str_replace('<controller>', $controller, $tplContent);

        file_put_contents(App::getAppPath() . $filePath . DS . 'controller' . DS . $controller . '.php', $tplContent);
    }
}