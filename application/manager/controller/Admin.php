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
			$rs = $adminModel->allowField(true)->save($data);
			echo $adminModel->id;exit;
			//unset($data['groupId']);
			//$uid = Db::name('admin')->insertGetId($data);
			if($rs){
				$groupId=input('groupId');
				$uid = $adminModel->id;
				Db::table('ent_auth_verify')->insert(['uid'=>$uid,'groupId'=>$groupId]);
				$this->success("添加管理员成功！",url('index'));
			}else{
				$this->error("添加管理员失败！");
			}
			return;
		}
		$authGroupList = Db::table('ent_auth_group')->select();
		$this->assign('authGroupList',$authGroupList);
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
					$oldImg = $_SERVER['DOCUMENT_ROOT'].DS.'uploads'.DS. $manager['avatar'];
					if(file_exists($oldImg)){
						@unlink($oldImg);
					}
					$data['avatar'] = $avatar['msg'];
				}else{
					$this->error("头像上传失败！".$avatar['msg']);
				}
			}
			
			if(isset($data['password'])){
				$data['password']=md5($data['password']);
			}
			$groupId = $data['groupId'];
			unset($data['groupId']);
			//print_r($data);exit;
			$rs = $adminModel->update($data);
			if($rs){
				$gs = Db::name('auth_verify')->where('uid',input('id'))->select();
				if($gs){
					Db::name('auth_verify')->where(['uid'=>input('id')])->update(['uid'=>input('id'),'groupId'=>$groupId]);
				}else{
					Db::name('auth_verify')->insert(['uid'=>input('id'),'groupId'=>$groupId]);
				}
				$this->success("修改管理员成功！",url('index'));
			}else{
				$this->error("修改管理员失败！");
			}
			return;
		}
		if(!empty($manager['birthday'])){
			$manager['birthday'] = date('Y-m-d',strtotime($manager['birthday']));
		}
		$authVerifyRs = Db::name('auth_verify')->where('uid',input('id'))->value('groupId');
		$authGroupList = Db::table('ent_auth_group')->select();
		$this->assign('authVerify',$authVerifyRs);
		$this->assign('authGroupList',$authGroupList);
		$this->assign('manager' ,$manager);
		return view();
	}
	public function del(){
		$adminRs = Db::name('admin')->where('id',input('id'))->column('avatar');
		if($adminRs){
			$avatar = $adminRs[0];
			$imgDir = $_SERVER['DOCUMENT_ROOT'].DS.'uploads'.DS.$avatar;
			if(file_exists($imgDir)){
				@unlink($imgDir);
				$rs = Db::name('admin')->delete(input('id'));
				if($rs){
					$this->success('管理员删除成功！',url('index'));
				}else{
					$this->error('管理员删除失败！');
				}
			}
		}
		$this->error('管理员删除失败！');
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
		if(request()->isPost()){
			$data = input('post.');
			$temp = array();
			if(!isset($data['ids'])){
				$this->success('设置成功！',url('authGroupList'));
			}
			foreach($data['ids'] as $v){
				$temp[] = $v;
			}
			unset($data);
			$str = implode('|',$temp);
			$rs=Db::table('ent_auth_group')->update(['rules'=>$str,'id'=>input('id')]);
			if($rs){
				$this->success('设置成功！',url('authGroupList'));
			}else{
				$this->error('设置失败！');
			}
		}
		$authModel = new AuthModel();
		$authGroupRs = Db::table('ent_auth_group')->find(input('id'));
		$authList = $authModel->getAuthList();
		if($authGroupRs['rules']){
			$authGroupRs['rulesIdArr'] = explode('|',$authGroupRs['rules']);
		}else{
			$authGroupRs['rulesIdArr'] = array();
		}
		
		$this->assign('authList',$authList);
		$this->assign('authGroupRs',$authGroupRs);
		return view('auth_group_set');
	}
}