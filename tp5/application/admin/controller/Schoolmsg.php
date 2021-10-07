<?php
    namespace app\admin\controller;
    use think\Controller;
    use think\Db;
    use think\Session;
    class SchoolMsg extends Controller{

        public function index()
        {
            $session_data = Session::get();
            $getData = SchoolMsg::conectDb();
            $result = $getData->table('tb_school')
            ->where('conect_id',$session_data['uid'])
            ->select();

            if(count($result)<1){
                $data=[
                    "school_name"=>'',
                    "school_tel"=>'',
                    "school_phone"=>'',
                    "school_info"=>'',
                    "school_logo"=>'',
                    "file"=>'',
                    "conect_id"=>$session_data['uid']
                ];

                $new_school_msg = $getData->table('tb_school')->data($data)->insert();

                $result = $getData->table('tb_school')
                ->where('conect_id',$session_data['uid'])
                ->select();
            }

            $this->assign("result",$result[0]);
            return $this->fetch();
        }

        //修改用户
        public function edit_school(){
            $data = $_POST;
            $getData = SchoolMsg::conectDb();
            $result = $getData->table('tb_school')->where('id',$data['id'])->update($data);
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