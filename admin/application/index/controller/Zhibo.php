<?php
namespace app\index\controller;

use think\Controller;
use think\Session;

class zhibo extends Controller
{
  
   public function advert()
    {

        if(request()->Post())
        {
          db('advert')->where('id',14)->update(['title'=>input('fxpic1')]);
          db('advert')->where('id',15)->update(['title'=>input('fxurl1')]);
          db('advert')->where('id',16)->update(['title'=>input('fxpic2')]);
          db('advert')->where('id',17)->update(['title'=>input('fxurl2')]);
			Session::flash('code','1');
			$this->redirect('vip/advert');
        }
		 $code                    =   session('code');
        return view('advert',[
		'code'=>$code]);
    }
  
    public function _initialize()
    {
        $id = session('user');

        if (!$id) {
            $this->redirect('login/login/index');
        }
    }
	
    public function index()
    {
        $code       =   input('code');
        $msg        =   input('msg');

        $list       =   db('zhibo')->order('id desc')->paginate(30);
        return view('index',[
            'msg'   =>  $msg,
            'list'  =>  $list,
            'code'  =>  $code
        ]);
    }

    public function add()
    {
        $code   =   input('msg');
        return view('add',
            [
                'code'  =>  $code
            ]);
    }

    public function update()
    {
        $code   =   input('msg');
        $data   =   db('zhibo')->where('id',input('id'))->find();
        return view('update',
            [
                'data'  =>  $data,
                'code'  =>  $code
            ]);
    }

    public function del()
    {
        $data   =   db('zhibo')->where('id',input('id'))->delete();
        return redirect('zhibo/index',['code'=>1,'msg'=>'删除成功']);
    }

    public function create()
    {
        $file = request()->file('img');
        if($file){

            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');

            if($info){
                $type   =   ['gif','jpeg','png','bmp','jpg'];
                $types  =   $info->getExtension();
                $url    =   'http://88.88.88.88/public/uploads/'. $info->getSaveName();
                if(in_array($types,$type))
                {
                    $insert['img']   =   str_replace('\\','/',str_replace('\\\\','/',$url));
                    $insert['title']  =   input('title');
                    $insert['url']  =   input('url');
                    if(db('zhibo')->insert($insert)!==false)
                    {
                        return redirect('zhibo/index',['code'=>1,'msg'=>'添加成功']);
                    }else{
                        unlink($url);
                        return redirect('zhibo/add',['code'=>0,'msg'=>'添加失败']);
                    }
                }else{
                    unlink($url);
                    return redirect('zhibo/add',['code'=>0,'msg'=>'请上传图片']);
                }
            }else{

                return redirect('zhibo/add',['code'=>0,'msg'=>'上传失败'.$file->getError()]);
            }
        }else{
            return redirect('zhibo/add',['code'=>0,'msg'=>'图片未上传']);
        }

    }

    public function edit()
    {
        $file = request()->file('img');
        if($file){

            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $type   =   ['gif','jpeg','png','bmp','jpg'];
                $types  =   $info->getExtension();
                $url    =   '/public/uploads/'. $info->getSaveName();
                if(in_array($types,$type))
                {
                    $insert['img']   =   str_replace('\\','/',str_replace('\\\\','/',$url));
                }else{
                    unlink($url);
                    return redirect('zhibo/add',['code'=>0,'msg'=>'请上传图片']);
                }
            }else{

                return redirect('zhibo/add',['code'=>0,'msg'=>'上传失败'.$file->getError()]);
            }
        }
        $insert['title']  =   input('title');
        $insert['url']  =   input('url');
        if(db('zhibo')->where('id',input('id'))->update($insert)!==false)
        {
            return redirect('zhibo/index',['code'=>1,'msg'=>'添加成功']);
        }else{
            unlink($url);
            return redirect('zhibo/add',['code'=>0,'msg'=>'添加失败']);
        }



    }
}