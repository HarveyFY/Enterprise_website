<?php
namespace app\manager\controller;
use app\common\Comm;
use app\manager\model\Admin as AdminModel;
use app\manager\model\Auth as AuthModel;
use think\Db;

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
			//print_r($data);exit;
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
			$rs = $adminModel->update($data);
			if($rs){
				$this->success("修改管理员成功！",url('index'));
			}else{
				$this->error("修改管理员失败！");
			}
			return;
		}
		if(!empty($manager['birthday'])){
			$manager['birthday'] = date('Y-m-d',strtotime($manager['birthday']));
		}
		//print_r($manager);exit;
		$this->assign('manager' ,$manager);
		return view();
	}
	
	public function authList(){
		$authModel = new AuthModel();
		$authList = $authModel->getAuthList();
	
		
		$this->assign('authList',$authList);
		return view('auth_list');
	}
	
	public function authAdd(){
		if(request()->isPost()){
			$rs = Db::table('ent_auth_rules')->insert(input('post.'));
			if($rs){
				$this->success('添加权限节点成功！','authList');
			}else{
				$this->error('添加权限节点失败！');
			}
		}
		$authModel = new AuthModel();
		$authList = $authModel->getAuthList();
		
		$this->assign('authList',$authList);
		return view('auth_add');
	}
	
	public function authEdit(){
		if(request()->isPost()){
			$data = input('post.');
			$rs = Db::table('ent_auth_rules')->update($data);
			if($rs){
				$this->success('权限修改成功！',url('authList'));
			}else{
				$this->error('权限修改失败！');
			}
		}
		$authModel = new AuthModel();
		$authRs = Db::table('ent_auth_rules')->find(input('id'));
		$authList = $authModel->getAuthList();
		
		$this->assign('authList',$authList);
		$this->assign('authRs',$authRs);
		return view('auth_edit');
	}
	
	public function authDel(){
		$authModel = new AuthModel();
		
		$ids = $authModel->getChildIds(input('id'));
		array_push($ids,(int)(input('id')));
		
		$rs = Db::table('ent_auth_rules')->delete($ids);

		if($rs){
			$this->success('权限删除成功！','authList');
		}else{
			$this->error('权限删除失败！');
		}
	}
	
	public function authGroupList(){
		$authGroupList = Db::table('ent_auth_group')->select();
		
		$this->assign('authGroupList',$authGroupList);
		return view('auth_group_list');
	}
	
	public function authGroupAdd(){
		if(request()->isPost()){
			$data = input('post.');
			$rs = Db::table('ent_auth_group')->insert($data);
			if($rs){
				$this->success('添加权限组成功！','authGroupList');
			}else{
				$this->error('添加权限组失败！');
			}
		}
		return view('auth_group_add');
	}
	
	public function authGroupSet(){
		$authModel = new AuthModel();
		$authGroupRs = Db::table('ent_auth_group')->find(input('id'));
		$authList = $authModel->getAuthList();
		
		
		
		$this->assign('authList',$authList);
		$this->assign('authGroupRs',$authGroupRs);
		return view('auth_group_set');
	}
}