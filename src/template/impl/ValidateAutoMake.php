<?php

namespace nickbai\tp6curd\template\impl;

use nickbai\tp6curd\extend\Utils;
use nickbai\tp6curd\template\IAutoMake;
use Symfony\Component\VarExporter\VarExporter;
use think\console\Output;
use think\facade\App;
use think\facade\Db;

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
        $validateTpl = dirname(dirname(__DIR__)) . '/tpl/validate.tpl';
        $tplContent = file_get_contents($validateTpl);

        $model = ucfirst(Utils::camelize($table));
        $filePath = empty($path) ? '' : DS . $path;
        $namespace = empty($path) ? '\\' : '\\' . $path . '\\';

        $prefix = config('database.connections.mysql.prefix');
        $column = Db::query('SHOW FULL COLUMNS FROM `' . $prefix . $table . '`');
        $rule = [];
        $attributes = [];
        //以下列不在校验之列.这些都由系统装填
        $notValidateField = ['add_time','update_time','delete_time'];
        foreach ($column as $vo) {
            if(in_array($vo['Field'], $notValidateField)){
                continue;
            }
            //not null为必填的
            //null为选填的
            //主键的类型是自增的为:number
            if($vo['Key']=='PRI'){ //主键
                if($vo['Extra']=='auto_increment'){
                    $rule[$vo['Field']] = 'number';
                }else{
                    $rule[$vo['Field']] = 'require';
                }
            }else{ //非主键
                if($vo['Null']=='NO'){
                    $rule[$vo['Field']] = 'require';
                }else{
                    $rule[$vo['Field']] = '';
                }
            }
            //$rule[$vo['Field']] = 'require';
            $attributes[$vo['Field']] = $vo['Comment'];
        }

        $ruleArr = VarExporter::export($rule);
        $attributesArr = VarExporter::export($attributes);

        $tplContent = str_replace('<namespace>', $namespace, $tplContent);
        $tplContent = str_replace('<model>', $model, $tplContent);
        $tplContent = str_replace('<rule>', '' . $ruleArr, $tplContent);
        $tplContent = str_replace('<attributes>', $attributesArr, $tplContent);

        file_put_contents(App::getAppPath() . $filePath . DS . 'validate' . DS . $model . 'Validate.php', $tplContent);
    }
}
