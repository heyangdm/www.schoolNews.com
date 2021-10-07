<?php
namespace app\index\controller;

use think\Session;
use think\Request;


class Book extends base
{
    public function index()
    {
		return $this->fetch('index');
    }

    public function query_book_salse()
    {
        $getData = base::conectDb();
		$user = base::_initialize();
        $booksalselist = $getData->table('tb_salse')
        ->where('conect_admin',$user[0]['conect_admin'])
        ->where('is_del',0)
        ->select();
        $return = [
            'data'=>$booksalselist,
            'code'=>200,
            'msg'=>'获取成功！'
        ];
        return $return;
    }

    public function query_book()
    {
        $data = $_POST;
        $getData = base::conectDb();
		$session_data = Session::get();
		$user = base::_initialize();
        if($data['id'] == 0){
            $book_list = $getData->table('tb_book')
            ->where('book_end_ascription',$user[0]['conect_admin'])
            ->where('is_del',0)
            ->select();
            foreach ($book_list as $key => &$value) {
                $booksalse = $getData->table('tb_salse')
                ->where('id',$value['book_sale'])
                ->where('is_del',0)
                ->select();
                $value['book_sale'] = $booksalse[0]['salse_name'];
            }
        }else{
            $book_list = $getData->table('tb_book')
            ->where('book_end_ascription',$user[0]['conect_admin'])
            ->where('is_del',0)
            ->where('book_sale',$data['id'])
            ->select();
            foreach ($book_list as $key => &$value) {
                $booksalse = $getData->table('tb_salse')
                ->where('id',$value['book_sale'])
                ->where('is_del',0)
                ->select();
                $value['book_sale'] = $booksalse[0]['salse_name'];
            }
        }

        $return = [
            'data'=>$book_list,
            'code'=>200,
            'msg'=>'获取成功！'
        ];

        return $return;
    }

    public function detail()
    {
		$data=$_GET;
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

        return $this->fetch();
    }
}