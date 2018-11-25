<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/15
 * Time: 14:21
 */
namespace app\admin\model;
use think\Model;

class MajorModel extends Model
{
    protected $table = 'major';
    /**
     * 获取专业信息
     * @param $id
     * @return array
     */
    public function getDetail($id){
        $data = $this->field(['id','name','description'])->where(['id'=>$id,'delete_time'=>0])->find()->getData();
        return $data;
    }
    /**
     * 添加专业
     * @param $data
     * @return array
     */
    public function doAdd($data){
        $result = $this->insert([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        if ($result) {
            return ['code' => 1, 'msg' => '添加成功'];
        } else {
            return ['code' => 0, 'msg' => '添加失败'];
        }
    }
    /**
     * 删除专业
     * @param $id
     * @return array
     */
    public function doDelete($id){
        $find = $this->field(['id','name'])->where(['id'=>$id,'delete_time'=>0])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '该专业不存在,可能已被删除'];
        }
        $result = $this->where(['id'=>$id,'delete_time'=>0])->update(['delete_time'=>time()]);
        if ($result) {
            return ['code' => 1, 'msg' => '删除成功','data'=>$find['name']];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }
    /**
     * 修改专业
     * @param $data
     * @return array
     */
    public function doEdit($data){
        $find = $this->field(['id','name'])->where(['id'=>$data['id'],'delete_time'=>0])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '该专业不存在,可能已被删除'];
        }
        $result = $this->where(['id'=>$data['id'],'delete_time'=>0])
            ->update([
                'name' => $data['name'],
                'description' => $data['description']
            ]);
        if ($result) {
            return ['code' => 1, 'msg' => '修改成功','data'=>$find['name']];
        } else {
            return ['code' => 0, 'msg' => '没有新修改的信息'];
        }
    }
}