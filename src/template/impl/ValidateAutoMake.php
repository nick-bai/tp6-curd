<?php

namespace nickbai\tp6curd\template\impl;

use nickbai\tp6curd\extend\Utils;
use nickbai\tp6curd\template\IAutoMake;
use think\console\Output;
use think\facade\App;

class ValidateAutoMake implements IAutoMake
{
    public function check($table, $path)
    {
        $validateName = Utils::camelize($table) . 'Validate';
        $validateFilePath = App::getAppPath() . $path . DS . 'validate' . DS . $validateName . '.php';

        if (!is_dir(App::getAppPath() . $path . DS . 'validate')) {
            mkdir(App::getAppPath() . $path . DS . 'validate', 0755, true);
        }

        if (file_exists($validateFilePath)) {
            $output = new Output();
            $output->error("$validateName.php已经存在");
            exit;
        }
    }
    
    public function make($table, $path, $other)
    {
        // TODO: Implement make() method.
    }
}