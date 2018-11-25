<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/16
 * Time: 10:43
 */
namespace app\admin\controller;
use app\admin\model\UserModel;
use app\AdminBaseController;
use think\Db;
use think\Validate;

class User extends AdminBaseController
{
    /**
     * 用户管理页
     */
    public function index(){
        $majorList = Db::name('major')->field(['id','name'])->where(['id'=>['>',1],'delete_time'=>0])->select();
        $this->assign('majorList',$majorList);
        return $this->fetch();
    }
    /**
     * 获取用户列表
     */
    public function getList(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $limit = $this->request->get('limit');
        $keyword = $this->request->param('keyword');//获取搜索关键词
        $conditions = [];//查询条件
        //关键字搜索
        if($keyword!=null){
            $conditions['u.id|u.name|u.mobile|m.name|c.name'] = ['like', "%$keyword%"];
        }

        $rows = Db::name('user u')
            ->field(['u.id','u.name','u.username','u.sex','u.type','u.status','u.mobile',
                "IF(u.last_borrow_time>0, FROM_UNIXTIME(u.last_borrow_time,'%Y-%m-%d %H:%i:%s'), '无') last_borrow_time",
                "IF(u.last_login_time>0, FROM_UNIXTIME(u.last_login_time,'%Y-%m-%d %H:%i:%s'), '无') last_login_time",
                "FROM_UNIXTIME(u.create_time,'%Y-%m-%d %H:%i:%s') create_time",
                'm.name major','c.name class'
            ])
            ->join([
                ['major m','m.id=u.major_id'],
                ['class c','c.id=u.class_id']
            ])
            ->where($conditions)
            ->where(['u.type'=>['>',0],'u.delete_time'=>0])
            ->order('u.create_time desc')
            ->paginate($limit)
            ->toArray();
        if(count($rows['data'])>0){
            return [
                'code'=>0,
                'msg'=>'请求成功',
                'count'=>$rows['total'],
                'data'=>$rows['data']
            ];
        }else{
            return [
                'code' => 1,
                'msg' => '无数据',
                'count' => 0,
                'data' => ''
            ];
        }
    }
    /**
     * 添加用户
     */
    public function addPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $validate = new Validate([
            'id'       => 'require',
            'name'     => 'require',
            'sex'      => 'require',
            'mobile'   => 'require',
            'type'     => 'require',
            'major_id' => 'require',
            'class_id' => 'require',
        ]);
        $validate->message([
            'id.require'       => '用户编号不能为空',
            'name.require'     => '姓名不能为空',
            'sex.require'      => '性别不能为空',
            'mobile.require'   => '手机号不能为空',
            'type.require'     => '用户类型不能为空',
            'major_id.require' => '专业不能为空',
            'class_id.require' => '班级不能为空',
        ]);
        $post = $this->request->post()['data'];
        if (!$validate->check($post)) {
            $this->error($validate->getError());
        }

        $model = new UserModel();
        $result = $model->doAdd($post);//添加

        if($result['code']==1){
            addLog('添加用户:'.$post['id'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 修改用户页
     */
    public function edit(){
        $id = $this->request->param('id');
        $model = new UserModel();
        $result = $model->getDetail($id);//获取用户信息
        //获取专业列表
        $majorList = Db::name('major')->field(['id','name'])
            ->where(['id'=>['>',1],'delete_time'=>0])
            ->select();
        //获取对应班级列表
        $classList = Db::name('class')->field(['id','name'])
            ->where(['major_id'=>$result['major_id'],'delete_time'=>0])
            ->select();
        $this->assign($result);
        $this->assign([
            'majorList' => $majorList,
            'classList' => $classList,
        ]);
        return $this->fetch();
    }
    /**
     * 修改用户提交
     */
    public function editPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $validate = new Validate([
            'id'       => 'require',
            'name'     => 'require',
            'sex'      => 'require',
            'mobile'   => 'require',
            'type'     => 'require',
            'major_id' => 'require',
            'class_id' => 'require',
        ]);
        $validate->message([
            'id.require'       => '用户编号不能为空',
            'name.require'     => '姓名不能为空',
            'sex.require'      => '性别不能为空',
            'mobile.require'   => '手机号不能为空',
            'type.require'     => '用户类型不能为空',
            'major_id.require' => '专业不能为空',
            'class_id.require' => '班级不能为空',
        ]);
        $post = $this->request->post()['data'];
        if (!$validate->check($post)) {
            $this->error($validate->getError());
        }

        $model = new UserModel();
        $result = $model->doEdit($post);

        if($result['code']==1){
            addLog('修改用户信息:'.$post['id'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 删除用户
     */
    public function delete(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');

        $model = new UserModel();
        $result = $model->doDelete($id);//删除

        if($result['code']==1){
            addLog('删除用户:'.$id,getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 根据输入的用户编号加载姓名
     */
    public function showUserName(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $userId = $this->request->post('userId');
        $model = new UserModel();
        $result = $model->getDetail($userId);
        if($result){
            $this->success('请求成功','',$result['name']);
        }else{
            $this->error('输入的用户编号有误');
        }
    }

    /**
     * 禁用用户
     */
    public function ban(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');

        $model = new UserModel();
        $result = $model->doBan($id);

        if($result['code']==1){
            addLog('禁用用户:'.$id,getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 启用用户
     */
    public function cancelBan(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');

        $model = new UserModel();
        $result = $model->doCancelBan($id);

        if($result['code']==1){
            addLog('启用用户:'.$id,getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
}