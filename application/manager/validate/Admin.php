<?php
namespace app\manager\validate;
use think\Validate;

class Admin extends Validate{
	
	protected $rule = [
			'username' => 'require|max:25',
			'password' => 'require|max:25',
			'captcha|验证码'=>'require|captcha'
	];
	protected $message = [
			'username.require' => '名称必须',
			'username.max' => '名称最多不能超过25个字符',
			'password.require' => '密码必须',
			'password.max' =>'密码不能超过２５个字符',
			
	];
	
}