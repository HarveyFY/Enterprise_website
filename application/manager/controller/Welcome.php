<?php
namespace app\manager\controller;
use think\Controller;
use think\View;

class Welcome extends Controller{
	
	public function login(){
		if(request()->isPost()){
			$data = input('post.');
			$captcha = input($data['captcha']);
			if(!captcha_check($captcha)){
				$this->error('验证码错误！');
			}
			echo 'ok';exit;
		}
		
		return view('login');
	}
	
	public function loginOut(){
		
		$this->success('退出成功！',url('login'));
	}
	
	public function register(){
		
		echo 'register';
	}
}