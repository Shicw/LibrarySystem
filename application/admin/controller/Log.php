<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/15
 * Time: 11:59
 */
namespace app\admin\controller;
use app\AdminBaseController;
use think\Db;

class Log extends AdminBaseController
{
    /**
     * 系统日志页
     */
    public function index(){
        return $this->fetch();
    }
    /**
     * 获取日志列表
     */
    public function getLogs(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $limit = $this->request->get('limit');
        $rows = Db::name('log')
            ->field(['id','action','user_id','FROM_UNIXTIME(create_time,\'%Y-%m-%d %H:%i:%s\') create_time'])
            ->order('id desc')
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
}