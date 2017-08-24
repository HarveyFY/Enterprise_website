<?php
namespace app\manager\controller;
use app\common\Comm;
use app\manager\model\Admin as AdminModel;

class Admin extends Comm{
	
	public function index(){
		
		//$this->test();
		$adminModel = new AdminModel();
		$managerList = $adminModel->paginate(2);
		
		$this->assign('managerList',$managerList);
		return view();
	}
	public function add(){
		$adminModel = new AdminModel();
		if(request()->isPost()){
			$data = input('post.');
			
			if($_FILES['avatar']['size']>0){
				$avatar = $this->upload('avatar', request()->file('avatar'));
				if($avatar['check_info']){
					$data['avatar'] = $avatar['msg'];
				}else{
					$this->error("头像上传失败！".$avatar['msg']);
				}
			}
			
			$data['regTime']=time();
			if(isset($data['password'])){
				$data['password']=md5($data['password']);
			}
			$rs = $adminModel->save($data);
			if($rs){
				$this->success("添加管理员成功！",url('index'));
			}else{
				$this->error("添加管理员失败！");
			}
			return;
		}
		return view();
	}
	public function edit(){
		$adminModel = new AdminModel();
		$manager = $adminModel->find(input('id'));
		
		if(request()->isPost()){
			$data = input('post.');
			
			if($_FILES['avatar']['size']>0){
				$avatar = $this->upload('avatar', request()->file('avatar'));
				if($avatar['check_info']){
					$data['avatar'] = $avatar['msg'];
				}else{
					$this->error("头像上传失败！".$avatar['msg']);
				}
			}
			
			
			if(isset($data['password'])){
				$data['password']=md5($data['password']);
			}
			$rs = $adminModel->save($data);
			if($rs){
				$this->success("添加管理员成功！",url('index'));
			}else{
				$this->error("添加管理员失败！");
			}
			return;
		}
		if(!empty($manager['birthday'])){
			$manager['birthday'] = date('Y/m/d',strtotime($manager['birthday']));
		}
		//print_r($manager);exit;
		$this->assign('manager' ,$manager);
		return view();
	}
}