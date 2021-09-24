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
            $isR=$this->isRequireField($vo);
            $lenF=$this->getFieldLength($vo);
            $typeF=$this->getFieldType($vo);
            $ruleF = [];
            if($isR){
                array_push($ruleF, 'require');
            }
            if($typeF){
                array_push($ruleF, $typeF);
            }
            if($lenF!=-1){
                array_push($ruleF, 'max:'.$lenF);
            }
            if(count($ruleF)==0){
                continue;
            }
            $rule[$vo['Field']] = implode('|', $ruleF);
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
    
    //是否是必填项
    private function isRequireField($vo){
        //主键
        if($vo['Key']=='PRI'){
            if($vo['Extra']!='auto_increment'){
                return true;
            }
        }else{ //非主键
            if($vo['Null']=='NO'){
                return true;
            }
        }
        return false;
    }
    //长度
    private function getFieldLength($vo){
        preg_match('/\d+/', $vo['Type'], $matches);
        if(count($matches)>0){
            return $matches[0];
        }
        return -1;
    }
    //列的类型
    private function getFieldType($vo){
        $mysqlType = $vo['Type'];
        $pos = strpos($vo['Type'],"(");
        if($pos !== false){
            //没有找到
            $mysqlType = substr($vo['Type'], 0, $pos);
        }
        return $this->getTpRoles(strtolower($mysqlType));
    }
    //返回tp6中的规则定义
    private function getTpRoles($mysqlType){
        //映射定义(key=mysql中的类型,全小写. val=tp6验证规则中的定义)
        $mp = array(
            'varchar'  => 'chsDash', 
            'int'  => 'integer', 
            'datetime'  => 'date', 
            'timestamp' => 'number',
            'char' => 'chsDash',
            'tinyint' => 'integer',
            'text' => 'chsDash',
            'float' => 'float',
            'double' => 'float',
            'decimal' => 'float');
        if(!array_key_exists($mysqlType, $mp)){ //mp中没有定义
            return false;
        }
        return $mp[$mysqlType];
    }
}
