<?php
    namespace app\admin\controller;
    use think\Controller;
    use think\Db;
    use think\Session;
    class Personal extends Controller{

        public function index()
        {
            $session_data = Session::get();
            $getData = Personal::conectDb();
            $result = $getData->table('tb_user')
            ->where('uid',$session_data['uid'])
            ->select();
            $this->assign("result",$result[0]);
            return $this->fetch();
        }

        //修改用户
        public function edit_user(){
            $data = $_POST;
            $getData = Personal::conectDb();
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