<?php
namespace app\common;
use think\controller;
use think\Request;

class Comm extends Controller{
	private $uid;
	private $uername;
	private $phone;
	
	public function _initialize(){
		
 		//echo ROOT_PATH;
		$request = Request::instance();
		$requestURL = $request->module().DS.$request->controller().DS.$request->action();
		//echo $requestURL;
	}
	
	//$file = request()->file('image');
	public function upload($catalog,$file){
		// 获取表单上传文件 例如上传了001.jpg
		//$file = request()->file('image');
		// 移动到框架应用根目录/public/uploads/ 目录下
		$info = $file->validate(['size'=>5242880,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 
				'public' .DS. 'uploads'.DS.$catalog);
		
		if($info){
			// 成功上传后 获取上传信息
			// 输出 jpg
			//echo $info->getExtension();
			// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
			//echo $info->getSaveName();
			// 输出 42a79759f284b767dfcb2a0197904287.jpg
			//echo $info->getFilename();
			
			return array('check_info'=>true,'msg'=>$catalog.DS.$info->getSaveName());

		}else{
			// 上传失败获取错误信息
			//echo $file->getError();exit;
			return array('check_info'=>false, 'msg'=>$file->getError());
		}
	}
	
}