<?php
    namespace app\admin\controller;
    use think\Controller;
    use think\Db;
    use think\Session;
    class Index extends Controller{

        public function index()
        {
            return $this->fetch('login');
        }

        public function login(){
            $data = $_POST;
            $username = $data['username'];
            $password = $data['password'];
            $identity = $data['identity'];
            $getData = Index::conectDb();
            $result = $getData->table('tb_user')
            ->where('username',$username)
            ->where('identity',$identity)
            ->select();
            if($result){
                if($result[0]['password1'] == $password){
                    Session::set('identity',$identity);
                    Session::set('uid',$result[0]['uid']);
                    return $this->redirect('Index/main',302);
                }else{
                    return $this->error('登录失败，密码输入错误！','index',2);
                }
                
            }else{
                return $this->error('登录失败，账户名不存在！','index',2);
            }
        }

        public function main(){
            $session_data = Session::get();
            if(!$session_data){
                return redirect("index");
            }
            $getData = Index::conectDb();
            $result = $getData->table('tb_user')
            ->where('uid',$session_data['uid'])
            ->select();
            $new_msg = $getData->table('tb_msg')
            ->where('is_del',0)
            ->order('id desc')
            ->limit(1)
            ->select();
            $this->assign('new_msg',$new_msg[0]['msg_content']);
            $this->assign('result',$result[0]);
            return $this->fetch('main');
        }

        public function console(){
            $getData = Index::conectDb();
            $user_list = $getData->table('tb_user')->select();
            $tody_userList = $getData->table('tb_user')->whereTime('user_date', 'today')->select();
            $tody_userNum = count($tody_userList);
            $user_num=count($user_list);

            $news_list = $getData->table('tb_news')->select();
            $tody_News_List = $getData->table('tb_news')->whereTime('create_date', 'today')->select();
            $tody_News_num = count($tody_News_List);
            $news_list_num = count($news_list);

            $book_list = $getData->table('tb_book')->select();
            $tody_book_List = $getData->table('tb_book')->whereTime('create_time', 'today')->select();
            $tody_book_num = count($tody_book_List);
            $book_list_num = count($book_list);

            // 柱状图数据
            $pie_data=[];
            for($i=0;$i<4;$i++){
                $item_num = $news_list = $getData->table('tb_user')
                ->where('identity',$i)
                ->select();
                array_push($pie_data,count($item_num));
            }

            $time='';
            $format='Y-m-d';
            $time = $time != '' ? $time : time();
            //组合数据
            $date = [];
            for ($i=1; $i<=7; $i++){
                $date[$i] = date($format,strtotime( '+' . $i-7 .' days', $time));
            }

            $seven_day_data = [];
            $use_date=$date;
            for ($i=1; $i <= 7; $i++){
                $next_time = strtotime($use_date[$i])+86400;
                $item_date_data = $getData->table('tb_user')
                // ->whereTime('user_date', strtotime($use_date[$i]) )
                // ->select();
                // ->whereTime('user_date', $use_date[$i] )
                ->whereTime('user_date', 'between', [$use_date[$i],$next_time])
                ->select();

                // ->whereTime('user_date',$use_date[$i])
                // ->select();    
                
                // var_dump(count($item_date_data));
                array_push($seven_day_data,count($item_date_data));
            }

            

            $retrun_num=[
                'user_num'=>$user_num,
                'tody_userNum'=>$tody_userNum,
                'news_list_num'=>$user_num,
                'tody_News_num'=>$tody_News_num,
                'book_list_num'=>$book_list_num,
                'tody_book_num'=>$tody_book_num,
                'pie_data'=>$pie_data,
                'seven_date'=>$date,
                'seven_day_data'=>$seven_day_data
            ];

            $this->assign('retrun_num',$retrun_num);
            return $this->fetch('console');
        }

        public function register(){
            return $this->fetch('register');
        }

        public function register_sub(){
            $data = $_POST;
            $data['user_date']=time();
            $username = $data['username'];
            $getData = Index::conectDb();
            $result = $getData->table('tb_user')->where('username',$username)->select();
            if($result){
                $this->error('用户名已存在','register');
            }else{
                $list = $getData->table('tb_user')->data($data)->insert();
                if($list){
                    return $this->success('注册成功！','index',2);
                }
            }
        }

        public function feedback(){
            $session_data = Session::get();
            $data = $_POST;
            $data['feedback_date']=time();
            $data['uid']=$session_data['uid'];
            $data['isHandle']="0";
            $getData = Index::conectDb();
            $result = $getData->table('tb_feedback')
            ->data($data)
            ->insert();
            if($result){
                $retrun_data=[
                    'code'=>'200',
                    'msg'=>'反馈成功！'
                ];
                return $retrun_data;
            }

        }

        public function login_out(){
            Session::clear();
            return redirect("index");
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