<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/15
 * Time: 15:25
 */
namespace app\admin\controller;
use app\admin\model\ClassModel;
use app\AdminBaseController;
use think\Db;

class Classes extends AdminBaseController
{
    /**
     * 班级管理页
     */
    public function index(){
        $majorList = Db::name('major')->field(['id','name'])->where(['id'=>['>',1],'delete_time'=>0])->select();
        $this->assign('majorList',$majorList);
        return $this->fetch();
    }
    /**
     * 获取班级列表
     */
    public function getList(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $limit = $this->request->get('limit');
        $rows = Db::name('class c')
            ->field(['c.id','m.name major','c.name class'])
            ->join('major m','m.id=c.major_id')
            ->where(['c.id'=>['>',1],'c.delete_time'=>0])
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
     * 添加班级
     */
    public function addPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $post = $this->request->post()['data'];
        if($post['name']==''){
            $this->error('班级名称不能为空');
        }
        if($post['major_id']==''){
            $this->error('专业不能为空');
        }

        $model = new ClassModel();
        $result = $model->doAdd($post);//添加

        if($result['code']==1){
            addLog('添加班级:'.$post['name'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 修改班级页
     */
    public function edit(){
        $id = $this->request->param('id');
        //获取班级信息
        $model = new ClassModel();
        $result = $model->getDetail($id);
        //获取专业列表
        $majorList = Db::name('major')->field(['id','name'])->where(['id'=>['>',1],'delete_time'=>0])->select();
        $this->assign('majorList',$majorList);
        $this->assign($result);
        return $this->fetch();
    }
    /**
     * 修改班级提交
     */
    public function editPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $post = $this->request->post()['data'];

        $model = new ClassModel();
        $result = $model->doEdit($post);

        if($result['code']==1){
            addLog('修改班级:'.$result['data'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 删除班级
     */
    public function delete(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');

        $model = new ClassModel();
        $result = $model->doDelete($id);//删除

        if($result['code']==1){
            addLog('删除班级:'.$result['data'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 获取对应专业下的班级
     */
    public function loadClass(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $majorId = $this->request->post('majorId');

        $result = Db::name('class')->field(['id','name'])
            ->where(['major_id'=>$majorId,'delete_time'=>0])
            ->select();
        if(count($result)>0){
            $this->success('请求成功','',$result);
        }else{
            $this->error('当前选择的专业下无班级信息');
        }
    }
}