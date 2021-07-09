<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 10:49 PM
 */
namespace nickbai\tp6curd\template\impl;

use nickbai\tp6curd\extend\Utils;
use nickbai\tp6curd\template\IAutoMake;
use think\facade\App;
use think\facade\Db;
use think\console\Output;

class ControllerAutoMake implements IAutoMake
{
    public function check($controller, $path)
    {
        !defined('DS') && define('DS', DIRECTORY_SEPARATOR);

        $controller = ucfirst($controller);
        $controllerFilePath = App::getAppPath() . $path . DS . 'controller' . DS . $controller . '.php';

        if (!is_dir(App::getAppPath() . $path . DS . 'controller')) {
            mkdir(App::getAppPath() . $path . DS . 'controller', 0755, true);
        }
        
        if (file_exists($controllerFilePath)) {
            $output = new Output();
            $output->error("$controller.php已经存在");
            exit;
        }
    }

    public function make($controller, $path, $table)
    {
        $controllerTpl = dirname(dirname(__DIR__)) . '/tpl/controller.tpl';
        $tplContent = file_get_contents($controllerTpl);

        $controller = ucfirst($controller);
        $model = ucfirst(Utils::camelize($table));
        $filePath = empty($path) ? '' : DS . $path;
        $namespace = empty($path) ? '\\' : '\\' . $path . '\\';

        $prefix = config('database.connections.mysql.prefix');
        $column = Db::query('SHOW FULL COLUMNS FROM `' . $prefix . $table . '`');
        $pk = '';
        foreach ($column as $vo) {
            if ($vo['Key'] == 'PRI') {
                $pk = $vo['Field'];
                break;
            }
        }

        $tplContent = str_replace('<namespace>', $namespace, $tplContent);
        $tplContent = str_replace('<controller>', $controller, $tplContent);
        $tplContent = str_replace('<model>', $model, $tplContent);
        $tplContent = str_replace('<pk>', $pk, $tplContent);

        file_put_contents(App::getAppPath() . $filePath . DS . 'controller' . DS . $controller . '.php', $tplContent);

        // 检测base是否存在
        if (!file_exists(App::getAppPath() . $filePath . DS . 'controller' . DS . 'Base.php')) {

            $controllerTpl = dirname(dirname(__DIR__)) . '/tpl/baseController.tpl';
            $tplContent = file_get_contents($controllerTpl);

            $tplContent = str_replace('<namespace>', $namespace, $tplContent);

            file_put_contents(App::getAppPath() . $filePath . DS . 'controller' . DS . 'Base.php', $tplContent);
        }
    }
}