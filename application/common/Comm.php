<?php
namespace app\common;
use think\controller;
use think\Request;
use think\Db;

class Comm extends Controller{
	private $uid=1;
	private $uername;
	private $phone;
	private $groupName;
	
	public function _initialize(){
		
 		//echo ROOT_PATH;
		$request = Request::instance();
		$requestURL = strtolower($request->module().DS.$request->controller().DS.$request->action());
		
		$auth = $this->check($requestURL);
		if(!$auth){
			$this->error('没有权限！');
		}
		
	}
	public function check($rules=''){
		
		if('超级员'==$this->groupName){
			return true;
		}
		if($rules=='' || empty($rules)){
			return false;
		}
		$authUrlArr = $this->getAuthUrl($uid=1);

		if(empty($authUrlArr)){
			return false;
		}
		
		foreach($authUrlArr as $v){
			if($rules==$v){
				return true;
			}
		}
		return false;
		
	}
	public function getAuthUrl($uid){
		$rulesIds = array();
		$rulesIdArr = array();
		$urlArr = array();
		
		$rulesList = array();
		
		$rulesIdSQL= 'SELECT
				  ag.rules
				FROM
				  ent_auth_verify AS av
				  LEFT JOIN ent_auth_group AS ag
				    ON av.`groupId` = ag.id
				  WHERE av.uid= '.$uid;
		
		$rulesIds = Db::query($rulesIdSQL);
		
		if(!empty($rulesIds)){
			foreach($rulesIds as $k => $v){
					
				if($k==0){
					$rulesIdArr = explode('|',$v['rules']);
				}else{
					array_merge($rulesIdArr,explode('|',$v['rules']));
				}
		
			}
		}else{
			return $urlArr;
		}
		unset($rulesIds);
		$rulesSQL = 'SELECT id,url FROM ent_auth_rules';
		$rulesList = Db::query($rulesSQL);
		
		if(!empty($rulesList)){
			foreach($rulesIdArr as $k => $v){
				foreach($rulesList as $lk => $lv){
					if($v==$lv['id']){
						$urlArr[]=strtolower($lv['url']);
					}
				}
			}
			unset($rulesIdArr);
		}
		
		return $urlArr;
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