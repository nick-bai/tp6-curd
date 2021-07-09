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

/**
 * 统一分页返回
 * @param $list
 * @return array
 */
if (!function_exists('pageReturn')) {

    function pageReturn($list) {
        if (0 == $list['code']) {
            return ['code' => 0, 'msg' => 'ok', 'count' => $list['data']->total(), 'data' => $list['data']->all()];
        }

        return ['code' => 0, 'msg' => 'ok', 'count' => 0, 'data' => []];
    }
}
