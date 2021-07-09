<?php

namespace app<namespace>controller;

use app<namespace>model\<model> as <model>Model;
use app<namespace>validate\<model>Validate;
use think\exception\ValidateException;

class <controller> extends Base
{
    /**
    * 获取列表
    */
    public function getList()
    {
        if (request()->isPost()) {

            $limit  = input('post.limit');
            $where = [];

            $<model>Model = new <model>Model();
            $list = $<model>Model->get<model>List($where, $limit);

            return json(pageReturn($list));
        }
    }

    /**
    * 添加
    */
    public function add()
    {
        if (request()->isPost()) {

            $param = input('post.');

            // 检验完整性
            try {
                validate(<model>Validate::class)->check($param);
            } catch (ValidateException $e) {
                return jsonReturn(-1, $e->getError());
            }

            $<model>Model = new <model>Model();
            $res = $<model>Model->add<model>($param);

            return json($res);
        }
    }

    /**
    * 查询信息
    */
    public function read()
    {
        $id = input('param.<pk>');

        $<model>Model = new <model>Model();
        $info = $<model>Model->get<model>ById($id);

        return json($info);
    }

    /**
    * 编辑
    */
    public function edit()
    {
         if (request()->isPost()) {

            $param = input('post.');

            // 检验完整性
            try {
                validate(<model>Validate::class)->check($param);
            } catch (ValidateException $e) {
                return jsonReturn(-1, $e->getError());
            }

            $<model>Model = new <model>Model();
            $res = $<model>Model->edit<model>($param);

            return json($res);
         }
    }

    /**
    * 删除
    */
    public function del()
    {
        $id = input('param.<pk>');

        $<model>Model = new <model>Model();
        $info = $<model>Model->del<model>ById($id);

        return json($info);
   }
}
