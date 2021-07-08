<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 11:44 PM
 */
namespace nickbai\tp6curd\extend;

class Utils
{
    /**
     * 下划线转驼峰
     * @param $unCamelizedWords
     * @param string $separator
     * @return string
     */
    public static function camelize($unCamelizedWords, $separator='_')
    {
        $unCamelizedWords = $separator. str_replace($separator, " ", strtolower($unCamelizedWords));
        return ltrim(str_replace(" ", "", ucwords($unCamelizedWords)), $separator);
    }
}