<?php
namespace app\manager\controller;
use think\Controller;
use think\View;
use app\manager\validate\Admin;
use think\Loader;
use think\Db;
use think\Session;
use think\Cookie;

class Welcome extends Controller{
	
	public function login(){
		
		if(request()->isPost()){

			$data = input('post.');
			
			//验证数据合法性
			$validate = Loader::validate('Admin');
			if(!$validate->check($data)){
				$this->error($validate->getError());
			}
			$adminRs = array();
			$adminRs = Db::name('Admin')->where('username',$data['username'])->find();
			if(empty($adminRs)){
				$adminRs = Db::name('Admin')->where('username',$data['username'])->find();
			}
			if(empty($adminRs)){
				$this->error('用户名或密码错误！');
			}
			if($adminRs['password']==md5($data['password'])){
				
				Session::set('user_id',$adminRs['id']);
				$expire_time = time();
				$user_info = $adminRs['username']."|".$expire_time."|".sha1($adminRs['username'].$adminRs['password'].$expire_time);
				$user_info = base64_encode($user_info);
				Cookie::set('user_info',$user_info,3600*24*7);
				$this->success('登录成功！',url('admin/index'));
			}else{
				$this->error('用户名或密码错误！');
			}
			
		}
		
		return view('login');
	}
	
	public function loginOut(){
		
		Session::delete('user_id');
	
		$this->success('退出成功！',url('login'));
	}
	
	public function register(){
		
		echo 'register';
	}
}