<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/16
 * Time: 16:05
 */
namespace app\admin\controller;
use app\admin\model\BookBorrowModel;
use app\admin\model\BookModel;
use app\admin\model\UserModel;
use app\AdminBaseController;
use think\Db;
use think\Exception;
use think\Validate;

class BookBorrow extends AdminBaseController
{
    /**
     * 图书借阅管理页
     */
    public function index(){
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
            $conditions['u.id|u.name|b.id|b.name'] = ['like', "%$keyword%"];
        }

        $rows = Db::name('book_borrow bb')
            ->field(['b.id book_id','b.name book_name','u.id user_id',
                'bb.renew_times','bb.overdue_money','u.name user_name','bb.id',
                'FROM_UNIXTIME(bb.begin_time,\'%Y-%m-%d\') begin_time',
                'FROM_UNIXTIME(bb.end_time,\'%Y-%m-%d\') end_time',
                'IF(bb.real_end_time>0, FROM_UNIXTIME(bb.real_end_time,\'%Y-%m-%d %H:%i:%s\'), \'借阅中\') real_end_time',
            ])
            ->join([
                ['book b','bb.book_id=b.id'],
                ['user u','bb.user_id=u.id'],
            ])
            ->where($conditions)
            ->order('bb.begin_time desc')
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
     * 图书出借
     */
    public function addPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $post = $this->request->post()['data'];
        if($post['book_id']=='' || $post['user_id']==''){
            $this->error('请输入图书编号和用户编号');
        }
        if($post['book_name']=='' || $post['user_name']==''){
            $this->error('请输入图书编号和用户编号');
        }
        //查询图书是否存在,是否可借
        $BookModel = new BookModel();
        $findBook = $BookModel->getDetail($post['book_id']);
        if (!$findBook) $this->error('该图书不存在,可能已被删除');
        if ($findBook['status']==1) $this->error('该图书已被借出');
        if ($findBook['status']==2) $this->error('该图书暂停借阅');
        //查询用户是否存在
        $UserModel = new UserModel();
        $findUser = $UserModel->getDetail($post['user_id']);
        if (!$findUser) $this->error('该用户不存在');
        if ($findUser['status']==0) $this->error('该用户已被禁用');
        //查询该用户正在借阅的图书数量,若超过8本则不能继续借阅
        $BorrowModel = new BookBorrowModel();
        $borrowingCount = $BorrowModel->getBorrowingCount($post['user_id']);
        if($borrowingCount==8) $this->error('该用户已借图书数量超过最大限制');

        $result1 = [];
        Db::startTrans();//开启事务处理
        try{
            $result1 = $BorrowModel->doAdd($post['book_id'],$post['user_id']);//添加借阅记录
            $result2 = $BookModel->setBorrowed($post['book_id']);//将图书的status设为已借阅
            $result3 = $UserModel->updateLastBorrowTime($post['user_id'],time());
            if(!$result1['code'] || !$result2['code'] || !$result3['code']){
                throw new Exception("办理借阅失败,请重试");
            }
            Db::commit();// 提交事务
        }catch (\Exception $e){
            Db::rollback();// 回滚事务
            $this->error($e->getMessage());
        }
        addLog('办理借阅:《'.$post['book_name'].'》;'.$post['user_name'],getAdminId());//记录日志
        $this->success($result1['msg']);
    }

    /**
     * 办理归还
     */
    public function returnPost(){
        if(!$this->request->isPost()){
            $this->error('请求失败');
        }
        $post = $this->request->post()['data'];
        if($post['book_id']=='' || $post['user_id']==''){
            $this->error('请输入图书编号和用户编号');
        }
        if($post['book_name']=='' || $post['user_name']==''){
            $this->error('请输入图书编号和用户编号');
        }
        $overdueMoney = $this->calcOverdue($post['book_id'],$post['user_id'])['overdue_money'];//超期费用

        $BookBorrowModel = new BookBorrowModel();
        $BookModel = new BookModel();

        $result1 = [];
        Db::startTrans();//开启事务处理
        try{
            $result1 = $BookBorrowModel->doReturn($post['book_id'],$post['user_id'],$overdueMoney);
            $result2 = $BookModel->setCanBorrow($post['book_id']);
            if(!$result1['code'] || !$result2['code']){
                throw new Exception("办理归还失败,请重试");
            }
            Db::commit();// 提交事务
        }catch (\Exception $e){
            Db::rollback();// 回滚事务
            $this->error($e->getMessage());
        }
        addLog('办理归还:《'.$post['book_name'].'》;'.$post['user_name'],getAdminId());//记录日志
        $this->success($result1['msg']);
    }

    /**
     * 计算归还超期情况
     */
    public function calcOverdue($bookId,$userId){
        $overdueDays = $money = 0;
        //获取最晚归还时间
        $endTime = Db::name('book_borrow')->where(['book_id'=>$bookId,'user_id'=>$userId])->value('end_time');
        //计算超期天数
        $overdueDays = (time() - $endTime)/86400;
        if($overdueDays>0){
            //超期(不满一天按一天计算;满一天不满两天,按两天计算;以此类推)
            if(is_float($overdueDays)) $overdueDays = ceil($overdueDays);
            $money = $overdueDays * 0.1;//每超期1天0.1元
        }else{
            //未超期
            $overdueDays = 0;
            $money = 0;
        }
        return ['overdue_days'=>$overdueDays,'overdue_money'=>$money];
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
            addLog('办理续借:'.$result['data']['book'].';'.$result['data']['user'],getAdminId());//记录日志
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
}