<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/16
 * Time: 10:11
 */
namespace app\admin\controller;
use app\admin\model\BookTypeModel;
use app\AdminBaseController;
use think\Db;

class BookType extends AdminBaseController
{
    /**
     * 图书类别管理页
     */
    public function index(){
        return $this->fetch();
    }
    /**
     * 获取图书类别列表
     */
    public function getList(){
        if(!$this->request->isGet()){
            $this->error('请求失败');
        }
        $limit = $this->request->get('limit');
        $rows = Db::name('book_type')
            ->field(['id','name','description'])
            ->where(['delete_time'=>0])
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
     * 添加图书类别
     */
    public function addPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $post = $this->request->post()['data'];
        if($post['name']==''){
            $this->error('图书类别名称不能为空');
        }

        $model = new BookTypeModel();
        $result = $model->doAdd($post);//添加

        if($result['code']==1){
            addLog('添加图书类别:'.$post['name'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 修改图书类别页
     */
    public function edit(){
        $id = $this->request->param('id');
        $model = new BookTypeModel();
        $result = $model->getDetail($id);
        $this->assign($result);
        return $this->fetch();
    }
    /**
     * 修改图书类别提交
     */
    public function editPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $post = $this->request->post()['data'];

        $model = new BookTypeModel();
        $result = $model->doEdit($post);

        if($result['code']==1){
            addLog('修改图书类别:'.$result['data'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 删除图书类别
     */
    public function delete(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');

        $model = new BookTypeModel();
        $result = $model->doDelete($id);//删除

        if($result['code']==1){
            addLog('删除图书类别:'.$result['data'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
}