<?php
/**
 * Created by PhpStorm.
 * User: Stone
 * Date: 2018/11/16
 * Time: 16:06
 */
namespace app\admin\model;
use think\Model;

class BookBorrowModel extends Model
{
    protected $table = 'book_borrow';

    /**
     * 查询用户当前正在借阅的图书数量
     * @param $userId
     * @return number
     */
    public function getBorrowingCount($userId){
        $count = $this->where(['user_id'=>$userId,'real_end_time'=>0])->count();
        return $count;
    }

    /**
     * 办理借阅
     * @param $bookId
     * @param $userId
     * @return array
     */
    public function doAdd($bookId,$userId){
        $time = time();//当前时间戳
        //从当日0点时间戳开始计算到第30天的24点时间戳为最晚归还时间
        $endTime = strtotime(date("Y-m-d"),time()) + 86400*30 -1;
        $result = $this->insert([
            'book_id'=>$bookId,
            'user_id'=>$userId,
            'begin_time'=>$time,
            'end_time'=>$endTime
        ]);
        if ($result) {
            return ['code' => 1, 'msg' => '借阅成功,最晚归还日期为:'.date("Y-m-d",$endTime)];
        } else {
            return ['code' => 0, 'msg' => '借阅失败'];
        }
    }
    /**
     * 办理归还
     * @param $bookId
     * @param $userId
     * @param $money
     * @return array
     */
    public function doReturn($bookId,$userId,$money){
        $find = $this->where(['book_id'=>$bookId,'user_id'=>$userId])->find();
        if($find){
            $result = $this->where(['book_id'=>$bookId,'user_id'=>$userId])->update([
                'real_end_time'=>time(),
                'overdue_money'=>$money
            ]);
            if ($result) {
                return ['code' => 1, 'msg' => '图书归还成功'];
            } else {
                return ['code' => 0, 'msg' => '图书归还失败'];
            }
        }else{
            return ['code' => 0, 'msg' => '无该借阅记录'];
        }

    }
    /**
     * 办理续借
     * @param $id
     * @return array
     */
    public function doRenew($id){
        $find = $this->where(['id'=>$id])->find();
        if($find){
            if($find['real_end_time']>0) return ['code' => 0, 'msg' => '该图书已归还'];
            if($find['renew_times']==0) return ['code' => 0, 'msg' => '无剩余续借次数'];
            $result = $this->where(['id'=>$id])->update([
                'renew_times'=>$find['renew_times']-1,
                'end_time' => $find['end_time'] + 86400*30 -1
            ]);
            if ($result) {
                return ['code' => 1, 'msg' => '续借成功','data'=>['book'=>$find['book_id'],'user'=>$find['user_id']]];
            } else {
                return ['code' => 0, 'msg' => '续借失败'];
            }
        }else{
            return ['code' => 0, 'msg' => '无该借阅记录'];
        }

    }
}