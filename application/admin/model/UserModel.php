<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/15
 * Time: 10:52
 */
namespace app\admin\model;
use think\Db;
use think\Model;

class UserModel extends Model
{
    protected $table = 'user';
    /**
     * 验证用户名密码
     * @param $username
     * @param $password
     * @param $type
     * @return array
     */
    public function doLogin($username, $password, $type){
        $find = $this->field(['id', 'username','name'])
            ->where(['username' => $username, 'password' => md5($password)])
            ->find();
        if ($find) {
            $find = $find->toArray();
            session($type, $find);//保存session
            //更新最后登录时间
            $this->where('id', $find['id'])->update(['last_login_time' => time()]);
            return ['code' => 1, 'msg' => '登录成功', 'data' => $find];
        } else {
            return ['code' => 0, 'msg' => '用户名或密码错误'];
        }
    }

    /**
     * 修改密码
     * @param $username
     * @param $oldPassword
     * @param $newPassword
     * @return array
     */
    public function changePassword($username, $oldPassword, $newPassword){
        //判断旧密码
        $find = $this->where(['username'=>$username,'password'=>md5($oldPassword)])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '旧密码错误'];
        }
        $update = $this->where('id',$find['id'])->update(['password'=>md5($newPassword)]);
        if ($update) {
            return ['code' => 1, 'msg' => '修改成功'];
        } else {
            return ['code' => 0, 'msg' => '修改失败'];
        }
    }

    /**
     * 获取用户信息
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
     * 添加用户
     * @param $data
     * @return array
     */
    public function doAdd($data){
        $result = $this->insert([
            'id' => $data['id'],
            'username' => $data['id'],
            'password' => md5('123456'),
            'name' => $data['name'],
            'sex' => $data['sex'],
            'type' => $data['type'],
            'mobile' => $data['mobile'],
            'major_id' => $data['major_id'],
            'class_id' => $data['class_id'],
            'status' => 1,
            'create_time' => time()
        ]);
        if ($result) {
            return ['code' => 1, 'msg' => '添加成功'];
        } else {
            return ['code' => 0, 'msg' => '添加失败'];
        }
    }
    /**
     * 删除用户
     * @param $id
     * @return array
     */
    public function doDelete($id){
        $find = $this->field(['id'])->where(['id'=>$id,'delete_time'=>0])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '该用户不存在,可能已被删除'];
        }
        $borrow = Db::name('book_borrow')->where(['user_id'=>$id,'real_end_time'=>0])->find();
        if($borrow){
            return ['code' => 0, 'msg' => '该用户有未归还的图书,不能删除'];
        }
        $result = $this->where('id',$id)->update(['delete_time'=>time()]);
        if ($result) {
            return ['code' => 1, 'msg' => '删除成功'];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }
    /**
     * 禁用用户
     * @param $id
     * @return array
     */
    public function doBan($id){
        $find = $this->field(['id'])->where(['id'=>$id,'delete_time'=>0])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '该用户不存在,可能已被删除'];
        }
        $result = $this->where('id',$id)->update(['status'=>0]);
        if ($result) {
            return ['code' => 1, 'msg' => '禁用成功'];
        } else {
            return ['code' => 0, 'msg' => '禁用失败'];
        }
    }
    /**
     * 启用用户
     * @param $id
     * @return array
     */
    public function doCancelBan($id){
        $find = $this->field(['id'])->where(['id'=>$id,'delete_time'=>0])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '该用户不存在,可能已被删除'];
        }
        $result = $this->where('id',$id)->update(['status'=>1]);
        if ($result) {
            return ['code' => 1, 'msg' => '删除成功'];
        } else {
            return ['code' => 0, 'msg' => '删除失败'];
        }
    }

    /**
     * 修改用户
     * @param $post
     * @return array
     */
    public function doEdit($post){
        $find = $this->field(['id'])->where(['id'=>$post['id'],'delete_time'=>0])->find();
        if(!$find){
            return ['code' => 0, 'msg' => '该用户不存在,可能已被删除'];
        }
        $result = $this->where('id',$post['id'])->update($post);
        if ($result) {
            return ['code' => 1, 'msg' => '修改成功'];
        } else {
            return ['code' => 0, 'msg' => '没有新的修改信息'];
        }
    }

    /**
     * 更新用户上一次借阅时间
     * @param $userId
     * @param $time
     * @return array
     */
    public function updateLastBorrowTime($userId,$time){
        $result = $this->where('id',$userId)->update(['last_borrow_time'=>$time]);
        if ($result) {
            return ['code' => 1];
        } else {
            return ['code' => 0];
        }
    }
}