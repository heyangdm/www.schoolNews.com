<?php
 
namespace app\index\controller;
 
use think\Controller;
use think\Db;
use think\Session;
 
class base extends Controller
{
 
    /**
     * 初始化方法,可以控制用户权限、获取菜单等等，只要是继承base类的其它业务类就不需要再重写
     */
    protected function _initialize()
    {
        $getData = base::conectDb();
        $session_data = Session::get();
        if($session_data){
			$user = $getData->table('tb_user')
			->where('uid',$session_data['uid'])
			->select();
			
			$this->assign('username',$user[0]['username']);

			

			$news_list= $getData->table('tb_news')
			->where('uid',$user[0]['conect_admin'])
			->where('is_del',0)
			->limit(4)
			->select();
			$this->assign('news_list',$news_list);
			

			$school_info= $getData->table('tb_school')
			->where('conect_id',$user[0]['conect_admin'])
			->select();
			
			$this->assign('school_info',$school_info[0]);

			

            return $user;
		}else{
			$this->assign('username','');
			return $this->fetch('login');
		}
 
    }

	public function conectDb(){
		$getData = Db::connect([
			'type'            => 'mysql',
			'hostname'        => '127.0.0.1',
			'database'        => 'schoolNews',
			'username'        => 'school',
			'password'        => '123456'
		]);
		return $getData;
	}


}
