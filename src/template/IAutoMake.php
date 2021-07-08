<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 10:46 PM
 */
namespace nickbai\tp6curd\template;

interface IAutoMake
{
    public function check($flag, $path, $output);

    public function make($flag, $path);
}