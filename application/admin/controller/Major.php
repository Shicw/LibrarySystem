<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/15
 * Time: 14:18
 */
namespace app\admin\controller;
use app\admin\model\MajorModel;
use app\AdminBaseController;
use think\Db;

class Major extends AdminBaseController
{
    /**
     * 专业管理页
     */
    public function index(){
        return $this->fetch();
    }
    /**
     * 获取专业列表
     */
    public function getList(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $limit = $this->request->get('limit');
        $rows = Db::name('major')
            ->field(['id','name','description'])
            ->where(['id'=>['>',1],'delete_time'=>0])
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
     * 添加专业
     */
    public function addPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $post = $this->request->post()['data'];
        if($post['name']==''){
            $this->error('专业名称不能为空');
        }

        $model = new MajorModel();
        $result = $model->doAdd($post);//添加

        if($result['code']==1){
            addLog('添加专业:'.$post['name'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 修改专业页
     */
    public function edit(){
        $id = $this->request->param('id');
        $model = new MajorModel();
        $result = $model->getDetail($id);
        $this->assign($result);
        return $this->fetch();
    }
    /**
     * 修改专业提交
     */
    public function editPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $post = $this->request->post()['data'];

        $model = new MajorModel();
        $result = $model->doEdit($post);

        if($result['code']==1){
            addLog('修改专业:'.$result['data'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 删除专业
     */
    public function delete(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');

        $model = new MajorModel();
        $result = $model->doDelete($id);//删除

        if($result['code']==1){
            addLog('删除专业:'.$result['data'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
}