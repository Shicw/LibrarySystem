<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/14
 * Time: 16:42
 */
namespace app;
use think\Db;
use think\Controller;
class AdminBaseController extends Controller
{
    public function _initialize(){
        $this->checkAdminLogin();
    }
    //判断用户是否登录
    public function checkAdminLogin()
    {
        $adminId = getAdminId();
        if (empty($adminId)) {
            $this->redirect('admin/Login/index');
        }
    }
}