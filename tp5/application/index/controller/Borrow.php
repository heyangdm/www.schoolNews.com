<?php
namespace app\index\controller;

use think\Session;
use think\Request;


class Borrow extends base
{
    public function index()
    {
        $data = $_GET;
        $getData = base::conectDb();
		$session_data = Session::get();
		$user = base::_initialize();
        $book_list = $getData->table('tb_book')
        ->where('book_end_ascription',$user[0]['conect_admin'])
        ->where('is_del',0)
        ->where('bookid',$data['bookid'])
        ->select();
        foreach ($book_list as $key => &$value) {
            $booksalse = $getData->table('tb_salse')
            ->where('id',$value['book_sale'])
            ->where('is_del',0)
            ->select();
            $value['book_sale'] = $booksalse[0]['salse_name'];
        }

        $this->assign('bookInfo',$book_list[0]);
		return $this->fetch('index');
    }

    public function submit_order()
    {
        $data = $_POST['data'];
        $getData = base::conectDb();
		$session_data = Session::get();
		$user = base::_initialize();
        
        $data['order_id']=time().$session_data['uid'];
        $data['create_time']=time();
        $data['order_status']=0
        ;
        $data['order_user_id']=$session_data['uid'];
        $data['order_del']=0;
        $data['order_cancel_time']=0;
        
        $result = $getData->table('tb_order')->data($data)->insert();
		if($result){
			$retrun_data=[
				'code'=>'200',
				'msg'=>'借阅成功！'
			];
			return $retrun_data;
		}
    }

    public function order_list()
    {
        return $this->fetch();
    }

    public function order_query_list()
    {
        $data = $_POST;
        $getData = base::conectDb();
		$session_data = Session::get();
		$user = base::_initialize();

        if(!$data['order_id']&&!$data['create_time']){
            $result = $getData->table('tb_order')
            ->where('order_user_id',$session_data['uid'])
            ->select();
        }else if($data['order_id']){
            $result = $getData->table('tb_order')
            ->where('order_id',$data['order_id'])
            ->where('order_user_id',$session_data['uid'])
            ->select();
        }else if($data['create_time']){
            $result = $getData->table('tb_order')
            ->where('create_time','> time',$data['create_time'])
            ->where('order_user_id',$session_data['uid'])
            ->select();
        }

        foreach ($result as $key => &$value) {
            $bookname = $getData->table('tb_book')
            ->where('bookid',$value['book_id'])
            ->where('is_del',0)
            ->select();
            $value['book_name'] = $bookname[0]['book_name'];

            $newDate = date('Y-m-d',$value['create_time']);
            $value['create_time'] = date('Y-m-d h:m',$value['create_time']);
        }

        
        if($result){
            $return_data = [
                'msg'=>'查询成功',
                'code'=>200,
                'data'=>$result
            ];
            return $return_data;
        }else{
            $return_data = [
                'msg'=>'查询失败',
                'code'=>400
            ];
            return $return_data;
        }
    }

    public function acceptBooks()
    {
        $data = $_POST;
        $getData = base::conectDb();
        $list = $getData->table('tb_order')
        ->where('order_id',$data['order_id'])
        ->update($data);
        if($list){
            $result=[
                'code'=>200,
                'msg'=>'修改成功',
            ];
            return $result;
        }
    }

    public function giveBackBooks()
    {
        $data = $_POST;
        $getData = base::conectDb();
        $list = $getData->table('tb_order')
        ->where('order_id',$data['order_id'])
        ->update($data);
        if($list){
            $result=[
                'code'=>200,
                'msg'=>'修改成功',
            ];
            return $result;
        }
    }

    public function canelOrders()
    {
        $data = $_POST;
        $getData = base::conectDb();
        $list = $getData->table('tb_order')
        ->where('order_id',$data['order_id'])
        ->update($data);
        if($list){
            $result=[
                'code'=>200,
                'msg'=>'修改成功',
            ];
            return $result;
        }
    }

    public function order_detail()
    {
        $data = $_GET;
        $session_data = Session::get();
        $getData = base::conectDb();
        $result = $getData->table('tb_order')
        ->where('order_id',$data['order_id'])
        ->select();

        foreach ($result as $key => &$value) {
            $bookname = $getData->table('tb_book')
            ->where('bookid',$value['book_id'])
            ->where('is_del',0)
            ->select();
            $value['book_name'] = $bookname[0]['book_name'];
        }

        foreach ($result as $key => &$value) {
            $newDate = date('Y-m-d',$value['create_time']);
            $value['create_time'] = date('Y-m-d H:m:s',$value['create_time']);

            $newDate1 = date('Y-m-d',$value['order_strat_time']);
            $value['order_strat_time'] = date('Y-m-d H:m:s',$value['order_strat_time']);

            $newDate2 = date('Y-m-d',$value['order_end_time']);
            $value['order_end_time'] = date('Y-m-d H:m:s',$value['order_end_time']);

            $bookname = $getData->table('tb_book')
            ->where('bookid',$value['book_id'])
            ->where('is_del',0)
            ->select();
            $value['book_name'] = $bookname[0]['book_name'];
        }

        $this->assign('result',$result[0]);
        return $this->fetch();
    }
}