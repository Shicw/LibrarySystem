<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/15
 * Time: 15:24
 */
namespace app\admin\model;
use think\Model;

class ClassModel extends Model
{
    protected $table = 'class';
    /**
     * 获取班级信息
     * @param $id
     * @return array
     */
    public function getDetail($id){
        $data = $this->field(['id','name','major_id'])->where(['id'=>$id,'delete_time'=>0])->find()->getData();
        return $data;
    }
    /**
     * 添加班级
     * @param $data
     * @return array
     */
    public function doAdd($data){
        $result = $this->insert([
            'major_id' => $data['major_id'],
            'name' => $data['name'],
        ]);

        if ($result) {
            return ['code' => 1, 'msg' => '添加成功'];
        } else {
            return ['code' => 0, 'msg' => '添加失败'];
        }
    }
    /**
     * 删除班级
     * @param $id
     * @return array
     */
    public function doDelete($id){
        $find = $this->field(['id','name'])->where(['id'=>$id,'delete_time'=>0])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '该班级不存在,可能已被删除'];
        }
        $result = $this->where(['id'=>$id,'delete_time'=>0])->update(['delete_time'=>time()]);
        if ($result) {
            return ['code' => 1, 'msg' => '删除成功','data'=>$find['name']];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }
    /**
     * 修改班级
     * @param $data
     * @return array
     */
    public function doEdit($data){
        $find = $this->field(['id','name'])->where(['id'=>$data['id'],'delete_time'=>0])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '该班级不存在,可能已被删除'];
        }
        $result = $this->where(['id'=>$data['id'],'delete_time'=>0])
            ->update([
                'name' => $data['name'],
                'major_id' => $data['major_id']
            ]);
        if ($result) {
            return ['code' => 1, 'msg' => '修改成功','data'=>$find['name']];
        } else {
            return ['code' => 0, 'msg' => '没有新修改的信息'];
        }
    }
}