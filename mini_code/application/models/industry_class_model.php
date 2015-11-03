<?php

/**
 * Video_Model 
 * 
 * @uses MY
 * @uses _Model
 * @package 
 * @version $Id$
 * @author Steven.Robin.Shen <shenyunsheng@yduedu.com> 
 * @date 15/07/16
 */
class Industry_Class_Model extends MY_Model {
    public $_table   = null;      //  表名 暂无
    public $_entity_name = null;  //  表名 暂无
    public $_primary = null;      //  表的 索引键值 
	public $_db_url;              //  DBA  接口
	public $_member_url;          //  用户中心  接口
	public $http_url_list = array(); //  http接口  
    
	public function __construct() {
		parent::__construct();
		$this->_http_name = "industry_class"; //  http 接口名字
		$this->_table = "yt_industry_class";	// 表名 
		$this->_table_desc = '行业分类表'; //数据表描述
		$this->initDB();
    }
}
