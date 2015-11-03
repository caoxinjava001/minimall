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
class sk_biaozhun_Model extends MY_Model{

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->_table = "yt_biaozhun";	// 表名
		$this->initDB();
	}


}
?>
