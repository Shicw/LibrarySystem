<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/16
 * Time: 15:16
 */
namespace app\admin\model;
use think\Model;

class BookModel extends Model
{
    protected $table = 'book';
    /**
     * 获取图书信息
     * @param $id
     * @return array
     */
    public function getDetail($id){
        $result = $this->where('id',$id)->find();
        if($result){
            return $result->getData();
        }else{
            return $result;
        }

    }
    /**
     * 添加图书
     * @param $data
     * @return array
     */
    public function doAdd($data){
        $result = $this->insert([
            'id'       => $data['id'],
            'name'     => $data['name'],
            'price'    => $data['price'],
            'publisher'=> $data['publisher'],
            'type_id'  => $data['type_id'],
            'location' => $data['location'],
            'description' => $data['description'],
            'status'   => 0,
            'create_time' => time()
        ]);
        if ($result) {
            return ['code' => 1, 'msg' => '添加成功'];
        } else {
            return ['code' => 0, 'msg' => '添加失败'];
        }
    }
    /**
     * 删除图书
     * @param $id
     * @return array
     */
    public function doDelete($id){
        $find = $this->field(['id','status'])->where(['id'=>$id,'delete_time'=>0])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '该图书不存在,可能已被删除'];
        }
        //判断该图书是否已被借出
        if($find['status']==1){
            return ['code' => 0, 'msg' => '该图书已被借出,暂不能删除'];
        }
        $result = $this->where(['id'=>$id,'delete_time'=>0])->update(['delete_time'=>time()]);
        if ($result) {
            return ['code' => 1, 'msg' => '删除成功'];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }
    /**
     * 修改图书信息
     * @param $post
     * @return array
     */
    public function doEdit($post){
        $find = $this->field(['id'])->where(['id'=>$post['id'],'delete_time'=>0])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '该图书不存在,可能已被删除'];
        }
        $result = $this->where('id',$post['id'])->update($post);
        if ($result) {
            return ['code' => 1, 'msg' => '修改成功'];
        } else {
            return ['code' => 0, 'msg' => '没有新的修改信息'];
        }
    }

    /**
     * 将图书设置为已借出
     * @param $bookId
     * @return array
     */
    public function setBorrowed($bookId){
        $result = $this->where('id',$bookId)->update(['status'=>1]);
        if ($result) {
            return ['code' => 1];
        } else {
            return ['code' => 0];
        }
    }
    /**
     * 将图书设置为暂停借阅
     * @param $bookId
     * @return array
     */
    public function setCannotBorrow($bookId){
        $result = $this->where('id',$bookId)->update(['status'=>2]);
        if ($result) {
            return ['code' => 1];
        } else {
            return ['code' => 0];
        }
    }
    /**
     * 将图书设置为可借阅
     * @param $bookId
     * @return array
     */
    public function setCanBorrow($bookId){
        $result = $this->where('id',$bookId)->update(['status'=>0]);
        if ($result) {
            return ['code' => 1];
        } else {
            return ['code' => 0];
        }
    }
}