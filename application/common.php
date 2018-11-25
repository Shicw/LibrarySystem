<?php
// 应用公共文件
//获取登录管理员的id
function getAdminId(){
    $sessionAdminId = session('admin.id');
    if (empty($sessionAdminId)) {
        return 0;
    }
    return $sessionAdminId;
}
//获取管理员信息
function getAdmin(){
    $sessionAdmin = session('admin');
    if (!empty($sessionAdmin)) {
        return $sessionAdmin;
    } else {
        return false;
    }
}
//获取用户id
function getUserId(){
    $sessionUserId = session('user.id');
    if (empty($sessionUserId)) {
        return 0;
    }
    return $sessionUserId;
}
//获取用户信息
function getUser(){
    $sessionUser = session('user');
    if (!empty($sessionUser)) {
        return $sessionUser;
    } else {
        return false;
    }
}
//记录操作日志
function addLog($action,$userId){
    \think\Db::name('log')->insert([
        'action'=>$action,
        'user_id'=>$userId,
        'create_time'=>time()
    ]);
}