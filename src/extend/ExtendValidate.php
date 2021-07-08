<?php
/**
 * Created by PhpStorm.
 * Date: 2021/7/8
 * Time: 8:18 PM
 */
namespace nickbai\tp6curd\extend;

use think\exception\ValidateException;
use think\Validate;
use think\validate\ValidateRule;

class ExtendValidate extends Validate
{
    /**
     * 自定义字段属性
     * @var array
     */
    protected $attributes = [];

    /**
     * 数据自动验证
     * @access public
     * @param array $data  数据
     * @param array $rules 验证规则
     * @return bool
     */
    public function check(array $data, array $rules = []): bool
    {
        $this->error = [];

        if ($this->currentScene) {
            $this->getScene($this->currentScene);
        }

        if (empty($rules)) {
            // 读取验证规则
            $rules = $this->rule;
        }

        foreach ($this->append as $key => $rule) {
            if (!isset($rules[$key])) {
                $rules[$key] = $rule;
                unset($this->append[$key]);
            }
        }

        foreach ($rules as $key => $rule) {
            // field => 'rule1|rule2...' field => ['rule1','rule2',...]
            if (strpos($key, '|')) {
                // 字段|描述 用于指定属性名称
                [$key, $title] = explode('|', $key);
            } else {
                $title = $this->field[$key] ?? $key;
            }

            // 场景检测
            if (!empty($this->only) && !in_array($key, $this->only)) {
                continue;
            }

            // 获取数据 支持二维数组
            $value = $this->getDataValue($data, $key);

            // 字段验证
            if ($rule instanceof Closure) {
                $result = call_user_func_array($rule, [$value, $data]);
            } elseif ($rule instanceof ValidateRule) {
                //  验证因子
                $result = $this->checkItem($key, $value, $rule->getRule(), $data, $rule->getTitle() ?: $title, $rule->getMsg());
            } else {
                $result = $this->checkItem($key, $value, $rule, $data, $title);
            }

            if (true !== $result) {
                // 自定义 attributes
                if (!empty($this->attributes) && isset($this->attributes[$title])) {
                    $result = str_replace($title, $this->attributes[$title], $result);
                }
                // 没有返回true 则表示验证失败
                if (!empty($this->batch)) {
                    // 批量验证
                    $this->error[$key] = $result;
                } elseif ($this->failException) {
                    throw new ValidateException($result);
                } else {
                    $this->error = $result;
                    return false;
                }
            }
        }

        if (!empty($this->error)) {
            if ($this->failException) {
                throw new ValidateException($this->error);
            }
            return false;
        }

        return true;
    }
}