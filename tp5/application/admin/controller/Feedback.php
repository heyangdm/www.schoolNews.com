<?php
    namespace app\admin\controller;
    use think\Controller;
    use think\Db;
    use think\Session;
    use think\Image;
    class Feedback extends Controller{
        public function index(){
            $session_data = Session::get();
            $getData = Feedback::conectDb();
            $result = $getData->table('tb_feedback')
            ->select();
            foreach ($result as $key => &$value) {
                $newDate = date('Y-m-d',$value['feedback_date']);
                $value['feedback_date'] = date('Y-m-d',$value['feedback_date']);
            }
            $result = json_encode($result);
            $this->assign('result',$result);
            return $this->fetch('index');
        }

        public function handle_msg(){
            $data = $_POST;
            $getData = Feedback::conectDb();
            $result = $getData->table('tb_feedback')
            ->where('id',$data['id'])
            ->update(['isHandle'=>'1']);
            if($result){
                $retrun_data=[
                    'code'=>'200',
                    'msg'=>'处理成功！'
                ];
                return $retrun_data;
            }
            return $return_data;
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