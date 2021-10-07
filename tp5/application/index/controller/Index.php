<?php
namespace app\index\controller;

use think\Session;
use think\Request;


class Index extends base
{
    public function index()
    {

		$getData = base::conectDb();
		$session_data = Session::get();

		if($session_data){
			$user = $this->_initialize();
			$book_list = $getData->table('tb_book')
			->where('book_end_ascription',$user[0]['conect_admin'])
			->where('is_del',0)
			->limit(9)
			->select();

			$this->assign('book_list',$book_list);

			$teacher_list = $getData->table('tb_user')
			->where('identity',2)
			->where('conect_admin',$user[0]['conect_admin'])
			->where('is_del',0)
			->limit(3)
			->select();
			$this->assign('teacher_list',$teacher_list);

			return $this->fetch('index');
		}else{
			$this->assign('username','');
			return $this->fetch('login');
		}
		
    }

	public function registration()
	{
		$getData = base::conectDb();
		$agreement = $getData->table('tb_agreement')->select();
		$this->assign('agreement',$agreement[0]);
		return $this->fetch();
	}

	public function sunbmit_register()
	{
		$data = $_POST;
		
		$getData = base::conectDb();
		$teacherIdent = $getData->table('tb_user')
		->where('register_No',$data['register_No'])
		->select();
		if(count($teacherIdent)>0){
			$vailUser = $getData->table('tb_user')
			->where('username',$data['username'])
			->select();
			if(count($vailUser)>0){
				return $this->error('用户名重复！','registration',2);
			}else{
				$data['user_date']=time();
				$data['identity']=3;
				$data['conect_super_admin']=$teacherIdent[0]['conect_super_admin'];                
                $data['conect_admin']=$teacherIdent[0]['conect_admin'];
                $data['conect_teacher']=$teacherIdent[0]['uid'];
				$list = $getData->table('tb_user')->data($data)->insert();
                if($list){
                    return $this->success('注册成功！','index',2);
                }
			}
		}else{
			return $this->error('注册码无效！','registration',2);
		}
	}

	public function login()
	{
		return $this->fetch();
	}
	
	public function sunbmit_login()
	{
		$data=$_POST;
		$getData = base::conectDb();
		$vailUsername = $getData->table('tb_user')
		->where('username',$data['username'])
		->where('identity',3)
		->select();
		if($vailUsername){
			if($data['password1'] == $vailUsername[0]['password1']){
				Session::set('session_start_time', time());
				Session::set('uid',$vailUsername[0]['uid']);
				return $list=[
					"msg"=>"登录成功",
					"code"=>200
				];
			}else{
				return $list=[
					"msg"=>"密码错误",
					"code"=>400
				];
			}
		}else{
			return $list=[
				"msg"=>"用户名不存在",
				"code"=>400
			];
		}
	}

	public function preson_edit()
	{
		$session_data = Session::get();
		$getData = base::conectDb();
		$result = $getData->table('tb_user')
		->where('uid',$session_data['uid'])
		->select();
		$this->assign("result",$result[0]);

		return $this->fetch();
	}

	public function edit_user()
	{
		$data = $_POST;
		$getData = base::conectDb();
		$result = $getData->table('tb_user')->where('uid',$data['uid'])->update($data);
		if($result){
			$return_data=[
				'code'=>200,
				'msg'=>'修改成功！'
			];
			return $return_data;
		}else{
			$return_data=[
				'code'=>600,
				'msg'=>'修改失败'
			];
			return json_encode($return_data);
		}
	}


	public function login_out(){
		Session::clear();
		return redirect("/");
	}

}
