<?php
    namespace app\admin\controller;
    use think\Controller;
    use think\Db;
    use think\Session;
    class User extends Controller{

        public function index()
        {
            $getData = User::conectDb();
            $session_data = Session::get();
            if($session_data['identity'] == 0){
                $result = $getData->table('tb_user')
                ->where('is_del',0)
                ->select();
            }else if($session_data['identity'] == 1){
                $result = $getData->table('tb_user')
                ->where('conect_admin',$session_data['uid'])
                ->where('is_del',0)
                ->select();
            }else if($session_data['identity'] == 2){
                $result = $getData->table('tb_user')
                ->where('conect_teacher',$session_data['uid'])
                ->where('is_del',0)
                ->select();
            }

            foreach ($result as $key => &$value) {
                $newDate = date('Y-m-d',$value['user_date']);
                $value['user_date'] = date('Y-m-d',$value['user_date']);
            }
            $result = json_encode($result);
            $this->assign('result',$result);
            return $this->fetch('index');
        }

        public function operaterule()
        {
            $getData = User::conectDb();
            $session_data = Session::get();
            if($session_data['identity'] == 0){
                $result = $getData->table('tb_user')
                ->where('is_del',1)
                ->select();
            }else if($session_data['identity'] == 1){
                $result = $getData->table('tb_user')
                ->where('conect_admin',$session_data['uid'])
                ->where('is_del',1)
                ->select();
            }else if($session_data['identity'] == 2){
                $result = $getData->table('tb_user')
                ->where('conect_teacher',$session_data['uid'])
                ->where('is_del',1)
                ->select();
            }
            

            foreach ($result as $key => &$value) {
                $newDate = date('Y-m-d',$value['user_date']);
                $value['user_date'] = date('Y-m-d',$value['user_date']);
            }
            $result = json_encode($result);
            $this->assign('result',$result);

            return $this->fetch();
        }

        public function add()
        {   
            $session_data=Session::get();
            $getData = User::conectDb();

            if($session_data['identity'] == 0){
                $user_admin_List = $getData->table('tb_user')
                ->where('identity',1)
                ->where('conect_super_admin',$session_data['uid'])
                ->select();
                $this->assign("user_admin_List",$user_admin_List);
            }else if($session_data['identity'] == 1){
                $user_teacher_List = $getData->table('tb_user')
                ->where('identity',2)
                ->where('conect_admin',$session_data['uid'])
                ->select();
                $this->assign("user_teacher_List",$user_teacher_List);
            }

            $this->assign("result",$session_data);
            return $this->fetch();
        }

        public function get_teacher()
        {
            $data = $_POST;
            $getData = User::conectDb();
            $result = $getData->table('tb_user')
            ->where('conect_admin',$data['uid'])
            ->where('identity',2)
            ->select();

            $return_data=[
                'code'=>200,
                'msg'=>'查询成功',
                'data'=>$result
            ];

            return $return_data;
        }

        public function edit()
        {
            $data = $_GET;
            $getData = User::conectDb();
            $result = $getData->table('tb_user')
            ->where('uid',$data['uid'])
            ->select();
            $result = $result[0];
            $this->assign("result",$result);
            return $this->fetch();
        }

        // 新增用户
        public function add_new_user(){
            $data = $_POST;
            $data['user_date']=time();
            $session_data = Session::get();
            $getData = User::conectDb();
            if($session_data['identity'] == 0){
                $data['conect_super_admin']=$session_data['uid']; 
            }else if($session_data['identity'] == 1){
                $conect_super=$getData->table('tb_user')->where('uid',$session_data['uid'])
                ->select();
                $data['conect_super_admin']=$conect_super[0]['conect_super_admin'];                
                $data['conect_admin']=$session_data['uid'];
            }else if($session_data['identity'] == 2){
                $conect_super=$getData->table('tb_user')->where('uid',$session_data['uid'])
                ->select();
                $data['conect_super_admin']=$conect_super[0]['conect_super_admin'];                
                $data['conect_admin']=$conect_super[0]['conect_admin'];
                $data['conect_teacher']=$session_data['uid'];
            }
            $username = $data['username'];
            $result = $getData->table('tb_user')->where('username',$username)->select();
            if($result){
                $return_data=[
                    'code'=>600,
                    'msg'=>'添加失败，账户名已存在'
                ];
                return json_encode($return_data);
            }else{
                $list = $getData->table('tb_user')->data($data)->insert();
                $return_data=[
                    'code'=>200,
                    'msg'=>'添加成功！'
                ];
                return $return_data;
            }
        }

        //修改用户
        public function edit_user(){
            $data = $_POST;
            $getData = User::conectDb();
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
        //删除用户
        public function lock_user(){
            $data = $_POST;
            if($data['uid']){
                $getData = User::conectDb();
                $result = $getData->table('tb_user')
                ->where('uid',$data['uid'])
                ->update(['is_del' => $data['is_del']]);
                if($result){
                    $return_data=[
                        'code'=>200,
                        'msg'=>'删除成功！'
                    ];
                    return $return_data;
                }
            }else{
                $return_data=[
                    'code'=>600,
                    'msg'=>'删除失败'
                ];
                return json_encode($return_data);
            }

        }

        //注册码
        
        public function register_no()
        {
            $session_data = Session::get();
            $getData = User::conectDb();
            $result = $getData->table('tb_user')
            ->where('uid',$session_data['uid'])
            ->select();

            $this->assign("register_No",$result[0]['register_No']);
            $this->assign("uid",$session_data['uid']);
            return $this->fetch();
        }

        public function updata_register_no()
        {
            $data=$_POST;
            $getData = User::conectDb();

            //查重
            $double_num = $getData->table('tb_user')
            ->where('register_No',$data['register_No'])
            ->select();

            if($double_num){
                $return_data=[
                    'code'=>400,
                    'msg'=>'注册码重复，请重新生成'
                ];
                return $return_data;
            }else{
                $result = $getData->table('tb_user')
                ->where('uid',$data['uid'])
                ->update(['register_No' => $data['register_No']]);
    
                if($result){
                    $return_data=[
                        'code'=>200,
                        'msg'=>'操作成功！'
                    ];
                    return $return_data;
                }
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