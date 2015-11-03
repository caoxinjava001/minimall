<?php
/**
 * 机构——用户关系表(jh_member_org)Model
 * @author sunzheng@yduedu.com
 *
 */
class Ent_Sort_Model extends MY_Model
{
	public $_table   = null;      //  表名 暂无
	public $_entity_name = null;  //  表名 暂无
	public $_primary = null;      //  表的 索引键值
	public $_db_url;              //  DBA  接口
	public $_member_url;          //  用户中心  接口
	public $http_url_list = array(); //  http接口

	public function __construct()
	{
		parent::__construct();
		$this->_http_name = "menu"; //  http 接口名字
		
		$this->_table = "yt_ent_sort"; //  表名 暂无
		$this->_table_desc = '企业信息分类表'; //数据表描述
		$this->initDB();
	}


}
