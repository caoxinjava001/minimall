<?php
/**
 * Admin_user_Model 
 * 
 * @uses MY
 * @uses _Model
 * @package 
 * @version $Id$
 * @author jackcao <caoxin@kangm.com> 
 */
class Admin_user_Model extends MY_Model{

	public function __construct() {
		parent::__construct();
		$this->_table = "h_admin_user";	// 表名
		$this->initDB();
	}
	
	/**
	 * getUserInfoByLoginId 
	 * 
	 * @param mixed $id 
	 * @access public
	 * @return void
	 */
	public function getUserInfoByLoginId($id){
		if(!is_numeric($id)){
			return false;
		}
		$data = array();
		$u_info = $this->getUserInfo($id);
		if($u_info){
			$data['member_info'] = $u_info;//登陆人信息
			$data['org_info'] = $u_info;//隶属机构信息
			return $data;
		}else{
			return false;
		}
	}
	
	/**
	 * getUserInfo 
	 * 
	 * @param mixed $id 
	 * @access public
	 * @return void
	 */
	public function getUserInfo($id){
		if(!is_numeric($id)){
			return false;
		}
		
		$info = $this->get_one('*',array('id'=>$id,'dele_status'=>NO_DELETE_STATUS));
		if($info){
			$data = array('id'=>$info['id'],
					'mobile'=>$info['mobile'],
					'user_name'=>$info['name'],
					'org_name'=>$info['org_name'],
					'login_role_id'=>$info['login_role_id'],
					'role_id'=>$info['role_id'],
					'last_login_date'=>$info['last_login_date'],
					);
			return $data;
		}else{
			return false;
		}
	}
    /**
     * 获取介绍人
     * @param $intro_id
     * @return mixed
     */
    public function getIntro($intro_id){
        $name=$this->get_one('*','id = '.$intro_id);
        return $name;
    }


	/**
	 * getVailIntroList 
	 * 
	 * @access public
	 * @return void
	 * @desc 获取有效介绍人
	 */
	public function getVailIntroList($limit=500,$order='id desc'){
		$ret = array();
		$where = 'status ='.VER_HAD_AUDIT;
		//$where .= ' and login_role_id in('.PARTNER_ORG.','.PARTNER_PERSONAL.','.SALE_PERSONAL.')';
		$where .= ' and dele_status='.NO_DELETE_STATUS;
		$tmp_ret = $this->select('*',$where,$limit,$order);
		$ret = is_array($tmp_ret) ? $tmp_ret : $ret;
		return $ret;
	}

    /**
     * 根据role_id获取相关所有管理员
     * @param $role_id
     */
    public function getListByRoleId($role_id){
        $where=array(
            'role_id'=>$role_id,
            'dele_status'=>NO_DELETE_STATUS
        );
        return $this->select('id,name',$where);
    }

}
?>
