<?php
namespace app\user\controller;
use app\admin\model\BookBorrowModel;
use app\admin\model\UserModel;
use app\UserBaseController;
use think\Db;

class Index extends UserBaseController
{
    /**
     * 主页
     */
    public function index(){
        $user = getUser();
        $this->assign($user);
        return $this->fetch();
    }
    /**
     * 获取用户借阅中的图书
     */
    public function getBorrowingList(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $userId = getUserId();
        $list = Db::name('book_borrow bb')
            ->join('book b','bb.book_id=b.id')
            ->field(['bb.renew_times','FROM_UNIXTIME(bb.begin_time,\'%Y-%m-%d\') begin_time',
                'FROM_UNIXTIME(bb.end_time,\'%Y-%m-%d\') end_time','b.name book_name','bb.id'])
            ->where(['bb.user_id'=>$userId,'real_end_time'=>0])
            ->select();
        if(count($list)>0){
            return [
                'code'=>0,
                'msg'=>'请求成功',
                'count'=>count($list),
                'data'=>$list
            ];
        }else{
            return [
                'code' => 1,
                'msg' => '无借阅中的图书',
                'count' => 0,
                'data' => ''
            ];
        }
    }
    /**
     * 主页
     */
    public function history(){
        $user = getUser();
        $this->assign($user);
        return $this->fetch();
    }
    /**
     * 获取用户借阅中的图书
     */
    public function getBorrowHistory(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $userId = getUserId();
        $list = Db::name('book_borrow bb')
            ->join('book b','bb.book_id=b.id')
            ->field(['FROM_UNIXTIME(bb.begin_time,\'%Y-%m-%d\') begin_time',
                'FROM_UNIXTIME(bb.real_end_time,\'%Y-%m-%d\') real_end_time','b.name book_name'])
            ->where(['bb.user_id'=>$userId,'real_end_time'=>['>',0]])
            ->select();
        if(count($list)>0){
            return [
                'code'=>0,
                'msg'=>'请求成功',
                'count'=>count($list),
                'data'=>$list
            ];
        }else{
            return [
                'code' => 1,
                'msg' => '无借阅历史',
                'count' => 0,
                'data' => ''
            ];
        }
    }
    /**
     * 办理续借
     */
    public function renew(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');

        $model = new BookBorrowModel();
        $result = $model->doRenew($id);

        if($result['code']==1){
            addLog('办理续借:'.$result['data']['book'].';'.$result['data']['user'],getUserId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
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
            addLog('修改密码',getuserId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
}
