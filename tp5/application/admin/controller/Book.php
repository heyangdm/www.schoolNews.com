<?php
    namespace app\admin\controller;
    use think\Controller;
    use think\Db;
    use think\Session;
    use think\Image;
    class Book extends Controller{
        public function index(){
            $session_data = Session::get();
            $getData = Book::conectDb();

            if($session_data['identity'] == 1){
                $result = $getData->table('tb_book')
                ->where('book_end_ascription',$session_data['uid'])
                ->where('is_del',0)
                ->select();
            }else if($session_data['identity'] == 2){
                $result = $getData->table('tb_book')
                ->where('sid',$session_data['uid'])
                ->where('is_del',0)
                ->select();
            }

            foreach ($result as $key => &$value) {
                $newDate = date('Y-m-d',$value['create_time']);
                $value['create_time'] = date('Y-m-d',$value['create_time']);
                
                $book_sale = $getData->table('tb_salse')
                ->where('id',$value['book_sale'])
                ->where('is_del',0)
                ->select();

                if($book_sale){
                    $value['book_sale'] = $book_sale[0]['salse_name'];
                }
            }

            $result = json_encode($result);
            $this->assign('result',$result);
            return $this->fetch('index');
        }

        public function add()
        {   
            $session_data = Session::get();
            $getData = Book::conectDb();

            if($session_data['identity'] == 1){
                $sale_list = $getData->table('tb_salse')
                ->where('conect_admin',$session_data['uid'])
                ->where('is_del',0)
                ->select();

            }else if($session_data['identity'] == 2){
                $sale_list = $getData->table('tb_salse')
                ->where('sid',$session_data['uid'])
                ->where('is_del',0)
                ->select();
            }

            $this->assign('sale_list',$sale_list);
            return $this->fetch();
        }

        public function add_book()
        {   
            $session_data = Session::get();
            $getData = Book::conectDb();
            $data = $_POST;
            $data['create_time']=time();
            $data['is_del']=0;

            $salse_info = $getData->table('tb_salse')
            ->where('id',$data['book_sale'])
            ->select();

            $data['sid']=$salse_info[0]['sid'];
            $data['book_end_ascription']=$salse_info[0]['conect_admin'];
            
            $list = $getData->table('tb_book')->data($data)->insert();
            $result=[
                'code'=>200,
                'msg'=>'添加成功',
            ];
            return $result;
        }

        public function edit()
        {
            $data = $_GET;
            $getData = Book::conectDb();
            $session_data = Session::get();

            $result = $getData->table('tb_book')
            ->where('bookid',$data['bookid'])
            ->select();
            $result = $result[0];

            if($session_data['identity'] == 1){
                $sale_list=$getData->table('tb_salse')
                ->where('conect_admin',$session_data['uid'])
                ->select();
            }else{
                $sale_list=$getData->table('tb_salse')
                ->where('sid',$session_data['uid'])
                ->select();
            }

            $this->assign("sale_list",$sale_list);
            $this->assign("result",$result);
            return $this->fetch();
        }

        public function edit_book(){
            $data = $_POST;
            
            $data['edit_time']=time();
            $getData = Book::conectDb();
            $list = $getData->table('tb_book')
            ->where('bookid',$data['bookid'])
            ->update($data);
            $result=[
                'code'=>200,
                'msg'=>'修改成功',
            ];
            return $result;
        }

        public function del_book(){
            $data = $_POST;
            if($data['bookid']){
                $getData = Book::conectDb();
                $result = $getData->table('tb_book')
                ->where('bookid',$data['bookid'])
                ->update(['is_del' => $data['is_del']]);
                if($result){
                    $return_data=[
                        'code'=>200,
                        'msg'=>'操作成功！'
                    ];
                    return $return_data;
                }
            }else{
                $return_data=[
                    'code'=>600,
                    'msg'=>'操作失败'
                ];
                return json_encode($return_data);
            }
        }

        public function recyle(){
            $session_data = Session::get();
            $getData = Book::conectDb();
            
            if($session_data['identity'] == 1){
                $result = $getData->table('tb_book')
                ->where('book_end_ascription',$session_data['uid'])
                ->where('is_del',1)
                ->select();
            }else{
                $result = $getData->table('tb_book')
                ->where('sid',$session_data['uid'])
                ->where('is_del',1)
                ->select();
            }


            foreach ($result as $key => &$value) {
                $newDate = date('Y-m-d',$value['create_time']);
                $value['create_time'] = date('Y-m-d',$value['create_time']);
            }
            $result = json_encode($result);
            $this->assign('result',$result);
            return $this->fetch();
        }

        public function sale(){
            $session_data = Session::get();
            $getData = Book::conectDb();

            $result = $getData->table('tb_salse')
            ->where('conect_admin',$session_data['uid'])
            ->select();

            foreach ($result as $key => &$value) {
                $teacherMsg = $getData->table('tb_user')
                ->where('uid',$value['sid'])
                ->where('is_del',0)
                ->select();
                $value['teacherName'] = $teacherMsg[0]['username'];
            }

            $result = json_encode($result);
            $this->assign('result',$result);
            return $this->fetch();
        }
        public function addsale(){
            $session_data = Session::get();
            $getData = Book::conectDb();
            
            $userList = $getData->table('tb_user')
            ->where('conect_admin',$session_data['uid'])
            ->where('identity',2)
            ->where('is_del',0)
            ->select();

            $this->assign('userList',$userList);
            return $this->fetch();
        }

        public function add_sale()
        {
            $data = $_POST;
            $getData = Book::conectDb();

            $user_admin = $getData->table('tb_user')
            ->where('uid',$data['sid'])
            ->where('is_del',0)
            ->select();

            $data['conect_admin'] = $user_admin[0]['conect_admin'];
            $data['is_del']=0;
            $result = $getData->table('tb_salse')
            ->data($data)
            ->insert();
            if($result){
                $return_data=[
                    'code'=>200,
                    'msg'=>'添加成功！'
                ];
            }else{
                $return_data=[
                    'code'=>400,
                    'msg'=>'添加失败！'
                ];
            }

            return $return_data;
        }

        public function del_sale(){
            $data = $_POST;
            if($data['id']){
                $getData = Book::conectDb();
                $result = $getData->table('tb_salse')
                ->where('id',$data['id'])
                ->update(['is_del' => $data['is_del']]);
                if($result){
                    $return_data=[
                        'code'=>200,
                        'msg'=>'操作成功！'
                    ];
                    return $return_data;
                }
            }else{
                $return_data=[
                    'code'=>600,
                    'msg'=>'操作失败'
                ];
                return json_encode($return_data);
            }
        }

        public function edit_sale()
        {
            $data = $_GET;
            $getData = Book::conectDb();
            $session_data = Session::get();
            $nowSale = $getData->table('tb_salse')
            ->where('id',$data['id'])
            ->select();

            $result = $getData->table('tb_user')
            ->where('uid',$nowSale[0]['sid'])
            ->select();

            $userList = $getData->table('tb_user')
            ->where('conect_admin',$session_data['uid'])
            ->select();

            $this->assign("nowSale",$nowSale[0]);
            $this->assign("userList",$userList);
            $this->assign("result",$result[0]);
            return $this->fetch();
        }

        public function edit_sale_update()
        {
            $data = $_POST;
            $data['edit_time']=time();
            $getData = Book::conectDb();
            $list = $getData->table('tb_news')
            ->where('id',$data['id'])
            ->update($data);
            $result=[
                'code'=>200,
                'msg'=>'修改成功',
            ];
            return $result;
        }



        public function upload(){
            $file = request()->file('file');
            if($file){
                // 上传新的图片
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    $img = $info->getSaveName();
                    $imgpath = DS.'uploads'.DS.$img;
                    $path = str_replace(DS,"/",$imgpath);
                    $data['src'] = $path;

                    $result=[
                        'code'=>0,
                        'msg'=>'上传成功',
                        'data'=>$data
                    ];
                    return $result;
                } else {
                    $result=[
                        'code'=>1,
                        'msg'=>'图片上传失败'
                    ];
                    return $result;
                }

            }else{
                return "失败";
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