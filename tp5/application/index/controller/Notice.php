<?php
namespace app\index\controller;

use think\Session;
use think\Request;


class Notice extends base
{
    public function index()
    {

		$getData = base::conectDb();
		$session_data = Session::get();
		$user = base::_initialize();
		$msg_list = $getData->table('tb_msg')
		->where('connect_id',$user[0]['conect_admin'])
		->select();
		$this->assign('msg_list',$msg_list);

		$new_list = $getData->table('tb_news')
		->where('uid',$user[0]['conect_admin'])
		->select();
		$this->assign('new_list',$new_list);

		return $this->fetch('index');
    }

    public function detail()
    {
		$data=$_GET;
		$getData = base::conectDb();
		$session_data = Session::get();
		$user = base::_initialize();
		$msg_list = $getData->table('tb_msg')
		->where('connect_id',$user[0]['conect_admin'])
		->select();
		$this->assign('msg_list',$msg_list);

		$news_detail = $getData->table('tb_news')
		->where('id',$data['id'])
		->select();
		$this->assign('news_detail',$news_detail[0]);

        return $this->fetch();
    }
}