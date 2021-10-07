<?php
        namespace app\admin\controller;
        use think\Controller;
        use think\Db;
        use think\Session;
        class Order extends Controller{
            public function index()
            {
                $session_data = Session::get();
                $getData = Order::conectDb();
                if($session_data['identity'] == 1){
                    $result = $getData->table('tb_order')
                    ->alias('a')
                    ->join('tb_user b','a.order_user_id = b.uid')
                    ->where('b.conect_admin',$session_data['uid'])
                    ->select();

                }else if($session_data['identity'] == 2){
                    $result = $getData->table('tb_order')
                    ->alias('a')
                    ->join('tb_user b','a.order_user_id = b.uid')
                    ->where('b.conect_teacher',$session_data['uid'])
                    ->select();
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
                $result = json_encode($result);
                $this->assign('result',$result);

                return $this->fetch();
            }

            public function edit()
            {
                $data = $_GET;
                $session_data = Session::get();
                $getData = Order::conectDb();
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

            public function edit_update()
            {
                $data = $_POST;
                $getData = Order::conectDb();
                $tmp=$data['book_name'];
                foreach( $data as $k=>$v) {
                    if($tmp == $v) unset($data[$k]);
                }
                $list = $getData->table('tb_order')
                ->where('order_id',$data['order_id'])
                ->update($data);
                $result=[
                    'code'=>200,
                    'msg'=>'修改成功',
                ];
                return $result;
            }

            public function gobook()
            {
                $data = $_GET;
                $session_data = Session::get();
                $getData = Order::conectDb();
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

            public function order_fa_book()
            {
                $data = $_POST;
                $getData = Order::conectDb();
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