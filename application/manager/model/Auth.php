<?php
namespace app\manager\model;
use think\model;
use think\Db;

class Auth extends Model{
	
	public function getAuthList(){
		$authList = Db::table('ent_auth_rules')->select();
		$authList2 = $this->treeList($authList);
		return $authList2;
	}
	
	public function treeList($list='',$id=0,$level=0){
		static $temp = array();
		
		foreach($list as $k => $v){
			if($v['pid']==$id){
				$v['level']=$level;
				$temp[] = $v;
				$this->treeList($list,$v['id'],$level+1);
			}
			
		}
		return $temp;
	}
	
	public function getChildIds($id){
		$authList= Db::table('ent_auth_rules')->select();
		
		return $this->getChildren($authList,$id);
	}
	
	public function getChildren($data,$id=0){
		static $temp = array();
		
		foreach($data as $k => $v){
			if($id==$v['pid']){
				$temp[] = $v['id'];
				$this->getChildren($data, $v['id']);
			}
		}
		return $temp;
	}
	
	
	
	
}