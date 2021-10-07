<?php
    namespace app\admin\controller;
    use think\Controller;
    use think\Db;
    use think\Session;
    use think\Image;
    class News extends Controller{
        public function index(){
            $session_data = Session::get();
            $getData = News::conectDb();
            $result = $getData->table('tb_news')
            ->where('uid',$session_data['uid'])
            ->where('is_del',0)
            ->select();
            foreach ($result as $key => &$value) {
                $newDate = date('Y-m-d',$value['create_date']);
                $value['create_date'] = date('Y-m-d',$value['create_date']);
            }
            $result = json_encode($result);
            $this->assign('result',$result);
            return $this->fetch('index');
        }

        public function add()
        {   
            $session_data = Session::get();
            $getData = News::conectDb();
            $result = $getData->table('tb_user')
            ->where('uid',$session_data['uid'])
            ->select();
            $this->assign('result',$result[0]);
            return $this->fetch();
        }

        public function add_news()
        {   
            $session_data = Session::get();
            $data = $_POST;
            $data['create_date']=time();
            $data['is_del']=0;
            $data['uid']=$session_data['uid'];
            
            $getData = News::conectDb();
            $list = $getData->table('tb_news')->data($data)->insert();
            $result=[
                'code'=>200,
                'msg'=>'添加成功',
            ];
            return $result;
        }

        public function edit()
        {
            $data = $_GET;
            $getData = News::conectDb();
            $result = $getData->table('tb_news')
            ->where('id',$data['id'])
            ->select();
            $result = $result[0];
            $this->assign("result",$result);
            return $this->fetch();
        }

        public function edit_news(){
            $data = $_POST;
            $data['edit_time']=time();
            $getData = News::conectDb();
            $list = $getData->table('tb_news')
            ->where('id',$data['id'])
            ->update($data);
            $result=[
                'code'=>200,
                'msg'=>'修改成功',
            ];
            return $result;
        }

        public function del_news(){
            $data = $_POST;
            if($data['id']){
                $getData = News::conectDb();
                $result = $getData->table('tb_news')
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

        public function recycle(){
            $session_data = Session::get();
            $getData = News::conectDb();
            
            $result = $getData->table('tb_news')
            ->where('uid',$session_data['uid'])
            ->where('is_del',1)
            ->select();

            foreach ($result as $key => &$value) {
                $newDate = date('Y-m-d',$value['create_date']);
                $value['create_date'] = date('Y-m-d',$value['create_date']);
            }
            $result = json_encode($result);
            $this->assign('result',$result);
            return $this->fetch();
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