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
class role_info_Model extends MY_Model{

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->_table = "yt_role_info";	// 表名
		$this->initDB();
	}

	/**
	 * getRoleInfo 
	 * 
	 * @param int $limit 
	 * @param string $order 
	 * @access public
	 * @return void
	 * @desc : 获取所有角色信息
	 */
	public function getRoleInfo($limit=500,$order='id asc'){
		$ret = array();
		$where['dele_status'] = NO_DELETE_STATUS;
		$tmp_ret = $this->select('*',$where,$limit,$order);
		$ret = is_array($tmp_ret) ? $tmp_ret : $ret;
		return $ret;
	}


	/**
	 * getRoleNameByRoleId 
	 * 
	 * @param mixed $id 
	 * @access public
	 * @return void
	 * @desc : 根据角色id获取角色信息
	 */
	public function getRoleInfoByRoleId($id){
		$ret = array();
		if(!is_numeric($id)){
			return $ret;
		}
		$where['id'] = $id;
		$tmp_ret = $this->get_one('*',$where);
		$ret = is_array($tmp_ret) ? $tmp_ret : $ret;
		return $ret;
	}

	/**
	 * getHighRoleInfoByRoleId 
	 * 
	 * @param mixed $id 
	 * @access public
	 * @return void
	 * desc : 根据当前角色id获取该角色上一级角色信息：
	 */
	public function getHighRoleInfoByRoleId($id){
		$ret = array();
		if(!is_numeric($id)){
			return $ret;
		}
		$where['id'] = $id;
		$where['dele_status'] = NO_DELETE_STATUS;
		$tmp_ret = $this->get_one('*',$where);
		$t_ret = is_array($tmp_ret) ? $tmp_ret : $ret;
		$is_bool_id = empty($t_ret["id"]) ? false : intval($t_ret["id"]);
		if (!$is_bool_id) {
			return $ret;
		}
		$where_high = 'id >'.$is_bool_id;	
		$tmp_ret = $this->get_one('*',$where_high,'id asc');
		$ret = is_array($tmp_ret) ? $tmp_ret : $ret;
		return $ret;
	}

}
?>
