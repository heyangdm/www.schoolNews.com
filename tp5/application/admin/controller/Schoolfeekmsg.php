<?php
    namespace app\admin\controller;
    use think\Controller;
    use think\Db;
    use think\Session;
    use think\Image;
    class Schoolfeekmsg extends Controller{
        public function index(){
            $session_data = Session::get();
            $getData = Schoolfeekmsg::conectDb();

            if($session_data['identity'] ==1){
                $result = $getData->table('tb_user')
                ->alias('a')
                ->join('tb_school_feedback b','a.uid = b.uid')
                ->where('a.conect_admin',$session_data['uid'])
                ->select();
            }else if($session_data['identity'] ==2){
                $result = $getData->table('tb_user')
                ->alias('a')
                ->join('tb_school_feedback b','a.uid = b.uid')
                ->where('a.conect_teacher',$session_data['uid'])
                ->select();
            }

            foreach ($result as $key => &$value) {
                $newDate = date('Y-m-d',$value['create_time']);
                $value['create_time'] = date('Y-m-d',$value['create_time']);
            }
            $result = json_encode($result);
            $this->assign('result',$result);
            return $this->fetch('index');
        }

        public function handle_msg(){
            $data = $_POST;
            $getData = Schoolfeekmsg::conectDb();
            $result = $getData->table('tb_school_feedback')
            ->where('id',$data['id'])
            ->update(['isHandle'=>'1']);
            if($result){
                $retrun_data=[
                    'code'=>'200',
                    'msg'=>'处理成功！'
                ];
                return $retrun_data;
            }else{
                $retrun_data=[
                    'code'=>'400',
                    'msg'=>'处理失败！'
                ];
                return $retrun_data;
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