<?php

namespace app<namespace>model;

use think\model\concern\SoftDelete;
use think\Model;

class <model> extends Model
{
    //支持软删除
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $pk = '<pk>';
    
    /**
    * 获取分页列表
    * @param $where
    * @param $limit
    * @return array
    */
    public function get<model>List($where, $limit)
    {
        try {

            $list = $this->where($where)->order('<pk>', 'desc')->paginate($limit);
        } catch(\Exception $e) {
            return dataReturn(-1, $e->getMessage());
        }

        return dataReturn(0, 'success', $list);
    }

    /**
    * 添加信息
    * @param $param
    * @return $array
    */
    public function add<model>($param)
    {
        try {

           // TODO 去重校验

           $param['add_time'] = date('Y-m-d H:i:s');
           $this->insert($param);
        } catch(\Exception $e) {

           return dataReturn(-1, $e->getMessage());
        }

        return dataReturn(0, 'success');
    }

    /**
    * 根据id获取信息
    * @param $id
    * @return array
    */
    public function get<model>ById($id)
    {
        try {

            $info = $this->where('<pk>', $id)->find();
        } catch(\Exception $e) {

            return dataReturn(-1, $e->getMessage());
        }

        return dataReturn(0, 'success', $info);
    }

    /**
    * 编辑信息
    * @param $param
    * @return array
    */
    public function edit<model>($param)
    {
        try {

            // TODO 去重校验

            $param['update_time'] = date('Y-m-d H:i:s');
            $this->where('<pk>', $param['<pk>'])->update($param);
        } catch(\Exception $e) {

            return dataReturn(-1, $e->getMessage());
        }

        return dataReturn(0, 'success');
    }

    /**
    * 软删除信息
    * @param $id
    * @return array
    */
    public function del<model>ById($id)
    {
        try {

            // TODO 不可删除校验
            <model>::destroy($id);
         } catch(\Exception $e) {

            return dataReturn(-1, $e->getMessage());
         }

        return dataReturn(0, 'success');
    }
    
    /**
    * 物理删除信息
    * @param $id
    * @return array
    */
    public function physicalDel<model>ById($id)
    {
        try {

            // TODO 不可删除校验
            // 物理删除
            <model>::destroy($id, true);
         } catch(\Exception $e) {

            return dataReturn(-1, $e->getMessage());
         }

        return dataReturn(0, 'success');
    }
}

