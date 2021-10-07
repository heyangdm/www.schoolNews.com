<?php
namespace app\index\controller;

use think\Session;
use think\Request;


class Contact extends base
{
    public function index()
    {
		return $this->fetch('index');
    }

	public function submit_conectMsg()
	{
		$data=$_POST;
		$session_data = Session::get();
		$data = $_POST;
		$data['create_time']=time();
		$data['uid']=$session_data['uid'];
		$data['isHandle']="0";
		$getData = base::conectDb();
		$result = $getData->table('tb_school_feedback')
		->data($data)
		->insert();
		if($result){
			$retrun_data=[
				'code'=>'200',
				'msg'=>'反馈成功！'
			];
			return $retrun_data;
		}
	}

}