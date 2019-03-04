<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/16
 * Time: 15:16
 */
namespace app\admin\controller;
use app\admin\model\BookModel;
use app\admin\model\UserModel;
use app\AdminBaseController;
use think\Db;
use think\Validate;

class Book extends AdminBaseController
{
    /**
     * 图书管理页
     */
    public function index(){
        $typeList = Db::name('book_type')->field(['id','name'])->where('delete_time',0)->select();
        $this->assign('typeList',$typeList);
        return $this->fetch();
    }
    /**
     * 获取图书列表
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
            $conditions['b.id|b.name|b.publisher'] = ['like', "%$keyword%"];
        }
        $rows = Db::name('book b')
            ->field(['b.id','b.name','b.price','b.description',
                'b.publisher','b.status','b.location','bt.name type',
                'FROM_UNIXTIME(b.create_time,\'%Y-%m-%d %H:%i:%s\') create_time'
            ])
            ->join('book_type bt','bt.id=b.type_id')
            ->where(['b.delete_time'=>0])
            ->where($conditions)
            ->order('b.create_time desc')
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
     * 添加图书
     */
    public function addPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $validate = new Validate([
            'id'       => 'require',
            'name'     => 'require',
            'price'      => 'require',
            'publisher'   => 'require',
            'type_id'     => 'require',
            'location' => 'require',
        ]);
        $validate->message([
            'id.require'       => '图书编号不能为空',
            'name.require'     => '书名不能为空',
            'price.require'      => '价格不能为空',
            'publisher.require'   => '出版社不能为空',
            'type_id.require'     => '图书类别不能为空',
            'location.require' => '所在位置不能为空',
        ]);
        $post = $this->request->post()['data'];
        if (!$validate->check($post)) {
            $this->error($validate->getError());
        }

        $model = new BookModel();
        $result = $model->doAdd($post);//添加

        if($result['code']==1){
            addLog('添加图书:'.$post['id'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 修改图书页
     */
    public function edit(){
        $id = $this->request->param('id');
        $model = new BookModel();
        $result = $model->getDetail($id);//获取信息
        //获取图书类型列表
        $typeList = Db::name('book_type')->field(['id','name'])
            ->where(['delete_time'=>0])
            ->select();
        $this->assign($result);
        $this->assign([
            'typeList' => $typeList,
        ]);
        return $this->fetch();
    }
    /**
     * 修改图书提交
     */
    public function editPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $validate = new Validate([
            'id'       => 'require',
            'name'     => 'require',
            'price'      => 'require',
            'publisher'   => 'require',
            'type_id'     => 'require',
            'location' => 'require',
        ]);
        $validate->message([
            'id.require'       => '图书编号不能为空',
            'name.require'     => '书名不能为空',
            'price.require'      => '价格不能为空',
            'publisher.require'   => '出版社不能为空',
            'type_id.require'     => '图书类别不能为空',
            'location.require' => '所在位置不能为空',
        ]);
        $post = $this->request->post()['data'];
        if (!$validate->check($post)) {
            $this->error($validate->getError());
        }

        $model = new BookModel();
        $result = $model->doEdit($post);

        if($result['code']==1){
            addLog('修改图书信息:'.$post['id'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 删除图书
     */
    public function delete(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $id = $this->request->post('id');

        $model = new BookModel();
        $result = $model->doDelete($id);//删除

        if($result['code']==1){
            addLog('删除图书:'.$id,getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
    /**
     * 根据输入的图书编号加载书名
     */
    public function showBookName(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $bookId = $this->request->post('bookId');
        $model = new BookModel();
        $result = $model->getDetail($bookId);
        if($result){
            $this->success('请求成功','',$result['name']);
        }else{
            $this->error('输入的图书编号有误');
        }
    }
}