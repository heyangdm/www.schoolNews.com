<?php
namespace app\index\controller;

use think\Session;
use think\Request;


class About extends base
{
    public function index()
    {
        $getData = base::conectDb();
		$session_data = Session::get();

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
		return $this->fetch();
    }


}