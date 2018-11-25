<?php
namespace app\admin\controller;
use app\AdminBaseController;
use app\admin\model\UserModel;

class Index extends AdminBaseController
{
    /**
     * 主页iframe框架
     */
    public function index(){
        $admin = getAdmin();
        $this->assign($admin);
        return $this->fetch();
    }
    /**
     * 登录后默认页
     */
    public function center(){
        return $this->fetch();
    }
    /**
     * 修改密码页面
     */
    public function changePassword(){
        return $this->fetch();
    }
    /**
     * 修改密码提交
     */
    public function changePasswordPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $post = $this->request->post()['data'];
        $username = $post['username'];
        $oldPassword = $post['oldPassword'];
        $newPassword = $post['newPassword'];
        if($username=='' || $newPassword=='' || $oldPassword==''){
            $this->error('用户名,旧密码和新密码不能为空');
        }
        $model = new UserModel();
        $result = $model->changePassword($username,$oldPassword,$newPassword);//登录验证
        if($result['code']==1){
            addLog('修改密码',getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
}
