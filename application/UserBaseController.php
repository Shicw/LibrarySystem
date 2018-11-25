<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/18
 * Time: 14:44
 */
namespace app;
use think\Db;
use think\Controller;
class UserBaseController extends Controller
{
    public function _initialize(){
        $this->checkUserLogin();
    }
    //判断用户是否登录
    public function checkUserLogin()
    {
        $adminId = getUserId();
        if (empty($adminId)) {
            $this->redirect('user/Login/index');
        }
    }
}