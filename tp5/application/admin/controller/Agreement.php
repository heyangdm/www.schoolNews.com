<?php
    namespace app\admin\controller;
    use think\Controller;
    use think\Db;
    use think\Session;
    class Agreement extends Controller{

        public function index()
        {
            $getData = Agreement::conectDb();
            $session_data = Session::get();
            $result = $getData->table('tb_agreement')
            ->where('is_del',0)
            ->where('conect_id',$session_data['uid'])
            ->select();

            foreach ($result as $key => &$value) {
                $newDate = date('Y-m-d',$value['create_time']);
                $value['create_time'] = date('Y-m-d',$value['create_time']);
            }

            $result = json_encode($result);
            $this->assign('result',$result);
            return $this->fetch();
        }

        public function add()
        {
            $session_data = Session::get();
            $getData = Agreement::conectDb();
            $result = $getData->table('tb_user')
            ->where('uid',$session_data['uid'])
            ->select();
            $this->assign('result',$result[0]);
            return $this->fetch();
        }

        public function add_agreement()
        {
            $session_data = Session::get();
            $getData = Agreement::conectDb();
            $data = $_POST;
            $data['conect_id'] = $session_data['uid'];
            $data['create_time'] = time();
            $data['is_del'] = 0;
            
            $result = $getData->table('tb_agreement')->data($data)->insert();
            
            if($result){
                $return = [
                    'msg'=>"添加成功！",
                    'code'=>200
                ];
                return $return;
            }
        }

        public function edit()
        {
            $data = $_GET;
            $session_data = Session::get();
            $getData = Agreement::conectDb();
            $result = $getData->table('tb_agreement')
            ->where('id',$data['id'])
            ->select();
            $result=$result[0];
            $this->assign('result',$result);
            return $this->fetch();
        }

        public function edit_agreement()
        {
            $data = $_POST;
            $data['create_time'] = time();
            $getData = Agreement::conectDb();
            $list = $getData->table('tb_agreement')
            ->where('id',$data['id'])
            ->update($data);

            if($list){
                $result=[
                    'code'=>200,
                    'msg'=>'修改成功',
                ];
                return $result;
            }else{
                $result=[
                    'code'=>400,
                    'msg'=>'修改失败',
                ];
            }

        }

        public function del_agreement()
        {
            $data = $_POST;
            if($data['id']){
                $getData = Agreement::conectDb();
                $result = $getData->table('tb_agreement')
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

        public function recyle()
        {
            $session_data = Session::get();
            $getData = Agreement::conectDb();
            $result = $getData->table('tb_agreement')
            ->where('is_del',1)
            ->select();

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