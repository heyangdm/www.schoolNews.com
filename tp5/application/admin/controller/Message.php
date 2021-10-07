<?php
        namespace app\admin\controller;
        use think\Controller;
        use think\Db;
        use think\Session;
        class Message extends Controller{
            public function index()
            {   
                $session_data = Session::get();
                $getData = Message::conectDb();
                if($session_data['identity'] < 1){
                    $result = $getData->table('tb_msg')
                    ->where('is_del',0)
                    ->select();
                }else{
                    $result = $getData->table('tb_msg')
                    ->where('is_del',0)
                    ->where('connect_id',$session_data['uid'])
                    ->select();
                }

                foreach ($result as $key => &$value) {
                    $newDate = date('Y-m-d',$value['create_time']);
                    $value['create_time'] = date('Y-m-d',$value['create_time']);
                }
                $result = json_encode($result);
                $this->assign("result",$result);
                return $this->fetch();
            }
            public function add()
            {
                $session_data = Session::get();
                $getData = Message::conectDb();
                $result = $getData->table('tb_user')
                ->where('uid',$session_data['uid'])
                ->select();
                $this->assign('result',$result[0]);
                return $this->fetch();
            }
            public function add_newMsg()
            {
                $session_data = Session::get();
                $data = $_POST;
                $data['create_time']=time();
                $data['is_del']=0;
                $data['connect_id']=$session_data['uid'];
                $getData = Message::conectDb();
                $list = $getData->table('tb_msg')->data($data)->insert();
                $result=[
                    'code'=>200,
                    'msg'=>'添加成功',
                ];
                return $result;
            }
            public function edit()
            {
                $data = $_GET;
                $getData = Message::conectDb();
                $result = $getData->table('tb_msg')
                ->where('id',$data['id'])
                ->select();
                $result = $result[0];
                $this->assign("result",$result);
                return $this->fetch();
            }
            public function edit_msg()
            {
                $data = $_POST;
                $data['edit_time']=time();
                $getData = Message::conectDb();
                $list = $getData->table('tb_msg')
                ->where('id',$data['id'])
                ->update($data);
                $result=[
                    'code'=>200,
                    'msg'=>'修改成功',
                ];
                return $result;
            }
            public function del_msg()
            {
                $data = $_POST;
                if($data['id']){
                    $getData = Message::conectDb();
                    $result = $getData->table('tb_msg')
                    ->where('id',$data['id'])
                    ->update(['is_del' => $data['is_del']]);
                    if($result){
                        $return_data=[
                            'code'=>200,
                            'msg'=>'操作成功！'
                        ];
                        return $return_data;
                    }else{
                        $return_data=[
                            'code'=>400,
                            'msg'=>'无需恢复！'
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

            public function really_delete(){
                $data = $_POST;
                if($data['id']){
                    $getData = Message::conectDb();
                    $result = $getData->table('tb_msg')
                    ->where('id',$data['id'])
                    ->delete();
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

            public function recycle(){
                $session_data = Session::get();
                $getData = Message::conectDb();

                if($session_data['identity'] < 1){
                    $result = $getData->table('tb_msg')
                    ->where('is_del','1')
                    ->select();
                }else{
                    $result = $getData->table('tb_msg')
                    ->where('connect_id',$session_data['uid'])
                    ->where('is_del','1')
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