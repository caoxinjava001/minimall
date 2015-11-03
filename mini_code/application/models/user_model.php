<?php
/**
 * role_info_Model 
 * 
 * @uses MY
 * @uses _Model
 * @package 
 * @version $Id$
 * @author kangm <all@kangm.com> 
 */
class User_Model extends MY_Model{

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->_table = "yt_user";	// 表名
		$this->initDB();
	}

    /**
     * @param array $where
     * @param string $limit
     * @param string $order
     */
	public function getMyAuditUsers(){

	}

	/**
	 * 获取管理员管理过的用户
	 * @return mixed
	 */
	public function getHistoryAudit($limit){
		$this->db->select("u.*");
		$this->db->from('yt_user as u');
		$this->db->join('yt_ent_audit_log as e','e.ent_id = u.id');
		$this->db->where('manage_id ='.$this->mid);
		$this->db->order_by('u.id desc');
		if($limit){
			$this->db->limit($limit);
		}
		$data=$this->db->get()->result_array();
		return $data;
	}


	public function getUserInfoByLoginId($id){
		if(!is_numeric($id)){
			return false;
		}
		$data = array();
		$u_info = $this->getUserInfo($id);
		if($u_info){
			$data['member_info'] = $u_info;//登陆人信息
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
					);
			return $data;
		}else{
			return false;
		}
	}
	/**
	 * getUserInfoByMobile 
	 * 
	 * @param mixed $mobile 
	 * @access public
	 * @return void
	 */
	public function getUserInfoByMobile($mobile){
		$ret = array();
		$is_bool_mobile=checkMobile($mobile);
		if(!$is_bool_mobile){
			return $ret;
		}
		$where['mobile'] = $mobile;
		$where['dele_status'] = NO_DELETE_STATUS;
		$info = $this->get_one('*', $where);
		$ret = is_array($info) ? $info : $ret;
		return $ret;
	}

}
?>
