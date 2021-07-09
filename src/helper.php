<?php

\think\Console::starting(function (\think\Console $console) {
    $console->addCommands([
        'curd' => '\\nickbai\\tp6curd\\command\\Curd'
    ]);
});

/**
 * 模型内统一数据返回
 * @param $code
 * @param string $msg
 * @param array $data
 * @return array
 */
if (!function_exists('dataReturn')) {
    function dataReturn($code, $msg = 'success', $data = []) {

        return ['code' => $code, 'data' => $data, 'msg' => $msg];
    }
}

/**
 * 统一返回json数据
 * @param $code
 * @param string $msg
 * @param array $data
 * @return \think\response\Json
 */
if (!function_exists('jsonReturn')) {
    function jsonReturn($code, $msg = 'success', $data = []) {

        return json(['code' => $code, 'data' => $data, 'msg' => $msg]);
    }
}