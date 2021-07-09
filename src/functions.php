<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/9
 * Time: 12:11 AM
 */
function autoWriteFunctions() {

    $tpl = file_get_contents(dirname(__FILE__) . '/tpl/functions.tpl');

    file_put_contents(dirname(__FILE__) . '/../../../../app/common.php', $tpl, FILE_APPEND);
}

function autoWriteCommand() {

    $command = include \think\facade\App::getAppPath() . 'config/console.php';
    $command['commands'][] = [
        'curd' => 'nickbai\\tp6curd\\command\Curd'
    ];

    $commandConf = \Symfony\Component\VarExporter\VarExporter::export($command);
    file_put_contents(dirname(__FILE__) . '/../../../../config/console.php', $commandConf);
}

autoWriteFunctions();
autoWriteCommand();
