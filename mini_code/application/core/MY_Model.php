<?php
/**
 * MY_Model 
 * 
 * @uses CI
 * @uses _Model
 * @package 
 * @version $Id$
 * @author kangm <all@kangm.com> 
 */
class MY_Model extends CI_Model{
	public $_table = null;      //表名
	public $_table_desc = null; //数据表描述
	public $curr_page  = 0;     // 当前页页码；
	public $pages      = "";    // 分页html代码；
	public $rows_count = 0;     // 总记录条数；

	public function __construct(){
		parent::__construct();
		$this->init();					   //初始化一些信息

	}

	/**
	 * init
	 * 
	 * @desc 初始化
	 */
	private function init(){

	}

	/**
	 * initDB
	 *
	 * @desc 链接数据连接池
	 * 暂时 不支持多个数据库
	 */
	protected function initDB() {
		if ( !isset($this->db) ) {
			$this->load->database();
		}
	}

	
	/**
	 * insert 
	 * 
	 * @param mixed $data 
	 * @param mixed $return_insert_id 
	 * @access public
	 * @return void
	 */
	public function insert($data, $return_insert_id = false) {
		try{
			if(empty($data)){
				$message = "insert：".$this->_table."表 数据异常 data: ".http_build_query($data);
				log_message_user("ERROR", $message,'mysql');
				return false;
			}
			$ret = $this->db->insert($this->_table, $data);
			if ($return_insert_id) {
				$ret = $this->db->insert_id();
			}
			return $ret;
		} catch(Exception $e) {
			$message = "insert：".$this->_table."表 增加数据失败 data: ".http_build_query($data).' 错误信息：'. $e->getMessage();
			log_message_user("ERROR", $message,'mysql');
			return false;
		}
	}

	/**
	 * execute 
	 * 
	 * @param mixed $sql 
	 * @access private
	 * @return void
	 */
	private function execute($sql) {
		// 过滤 insert update delete drop 操作  by jackcao

		if ( preg_match("/insert/i",$sql) ) {
			echo "no permission to operate insert function";
			exit;
		}
		if ( preg_match("/update/i",$sql) ) {
			echo "no permission to operate update function";
			exit;
		}
		if ( preg_match("/delete/i",$sql) ) {
			echo "no permission to operate delete function";
			exit;
		}
		if ( preg_match("/drop/i",$sql) ) {
			echo "no permission to operate drop function";
			exit;
		}
		// 过滤 insert update delete drop 操作  by jackcao

		$this->query  = $this->db->query($sql);
		//$this->lastqueryid=$this->query->result();
		$this->lastqueryid=$this->query ;
		//$this->querycount++;
		return $this->lastqueryid;
	}

	

	/**
	 * get_execute 
	 * 
	 * @param mixed $sql 
	 * @access public
	 * @return void
	 * 执行sql语句
	 */
	public function get_execute($sql){
		$this->execute($sql);
		if(is_object($this->lastqueryid)){
			return $this->lastqueryid->result_array();	
		}else{
			return $this->lastqueryid;
		}
	}	

	/**
	 * insertBatch 
	 * 
	 * @param mixed $data 
	 * @access public
	 * @return void
	 * @param array $data 二维数组
	 * @desc   批量插入数据 到数据库
	 */
	public function insertBatch($data) {
		try{
			if(empty($data)){
				$message = "insertBatch：".$this->_table."表 数据异常 data: ".http_build_query($data);
				log_message_user("ERROR", $message,'mysql');
				return false;
			}
			$ret = $this->db->insert_batch($this->_table, $data);
			return $ret;
		} catch(Exception $e) {
			$message = "insertBatch：".$this->_table."表 批量增加数据失败 data: ".http_build_query($data).' 错误信息：'. $e->getMessage();
			log_message_user("ERROR", $message,'mysql');
			return false;
		}
	}
	
	/**
	 * update 
	 * 
	 * @param mixed $data 
	 * @param mixed $where 
	 * @access public
	 * @return void
	 * @desc 根据条件修改数据信息
	 */
	public function update($data, $where) {
		try{
			if(empty($data) || empty($where)){
				$message = "update：".$this->_table."表 数据异常 data: ".http_build_query($data).' where:'.http_build_query($where);
				log_message_user("ERROR", $message,'mysql');
				return false;
			}
			$ret = $this->db->update($this->_table, $data, $where);
			return $ret;
		} catch(Exception $e) {
			$message = "update：".$this->_table."表 修改数据失败 data: ".http_build_query($data).' where:'.http_build_query($where).' 错误信息：'. $e->getMessage();
			log_message_user("ERROR", $message,'mysql');
			return false;
		}
	}
	
	/**
	 * select 
	 * 
	 * @param string $select 
	 * @param string $where 
	 * @param int $limit 
	 * @param string $order 
	 * @param int $offset 
	 * @param string $group 
	 * @access public
	 * @return void
	 * 获取多条记录查询
	 * @param $select 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $this->_table 		数据表
	 * @param $where 		查询条件
	 * @param $limit		查询条数
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $offset		开始位置
	 * @param $group 		分组方式	[默认为空]
	 * @return array	数据查询结果集,如果不存在，则返回空
	 */
	function select($select='*', $where='', $limit=500, $order='id DESC', $offset=0, $group='') {
		try{
			$data = array();
			$select = empty($select)?'*':$select;
			$where = $where == '' ? array() : $where;
			
			$this->db->select($select);
			$this->db->from($this->_table);
			$this->db->where($where);
			if(!empty($order)){
				$this->db->order_by($order);
			}
			if(!empty($group)){
				$this->db->group_by($group);
			}
			$this->db->limit($limit,$offset);
			$data = $this->db->get()->result_array();
			return $data;
		} catch(Exception $e) {
			$message = "select：".$this->_table."表 获取多行数据失败 select: ".http_build_query($select).' where：'.http_build_query($where).' limit：'.$limit.' order：'.$order.' offset：'.$offset.' group：'.$group.' 错误信息：'. $e->getMessage();
			log_message_user("ERROR", $message,'mysql');
			return false;
		}
	}
	/**
	 * select
	 *
	 * 获取多条记录查询
	 * @param $select 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $this->_table 		数据表
	 * @param $where 		查询条件
	 * @param $limit		查询条数
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $offset		开始位置
	 * @param $group 		分组方式	[默认为空]
	 * @return array	数据查询结果集,如果不存在，则返回空
	 */
	function select_limit($select='*', $where='', $limit='', $order='id DESC', $offset=0, $group='') {
		try{
			$data = array();
			$select = empty($select)?'*':$select;
			$where = $where == '' ? array() : $where;
			
			$this->db->select($select);
			$this->db->from($this->_table);
			$this->db->where($where);
			if(!empty($order)){
				$this->db->order_by($order);
			}
			if(!empty($group)){
				$this->db->group_by($group);
			}
			if($limit){
				$this->db->limit($limit,$offset);
			}
			$data = $this->db->get()->result_array();
			return $data;
		} catch(Exception $e) {
			$message = "select：".$this->_table."表 获取多行数据失败 select: ".http_build_query($select).' where：'.http_build_query($where).' limit：'.$limit.' order：'.$order.' offset：'.$offset.' group：'.$group.' 错误信息：'. $e->getMessage();
			log_message_user("ERROR", $message,'mysql');
			return false;
		}
	}
	
	/**
	 * get_one 
	 * 
	 * 获取单条记录查询
	 * @param $select 		需要查询的字段值[例`name`,`gender`,`birthday`]
	 * @param $this->_table 		数据表
	 * @param $where 		查询条件
	 * @param $order 		排序方式	[默认按数据库默认方式排序]
	 * @param $group 		分组方式	[默认为空]
	 * @return array	数据查询结果集,如果不存在，则返回空
	 */
	function get_one($select='*', $where = '', $order = 'id DESC',$group='') {
		try{
			$data = array();
			$select = empty($select)?'*':$select;
			$where = $where == '' ? array() : $where;
			
			$this->db->select($select);
			$this->db->from($this->_table);
			$this->db->where($where);
			if(!empty($order)){
				$this->db->order_by($order);
			}
			if(!empty($group)){
				$this->db->group_by($group);
			}
			$this->db->limit(1);
			$row = $this->db->get()->result_array();
			if(isset($row[0])){
				$data = $row[0];
			}
			return $data;
		} catch(Exception $e) {
			$message = "get_one：".$this->_table."表 获取一行数据失败 select: ".http_build_query($select).' where：'.http_build_query($where).' order：'.$order.' group：'.$group.' 错误信息：'. $e->getMessage();
			log_message_user("ERROR", $message,'mysql');
			return false;
		}
	}

	/**
	 * getCount
	 *
	 * @param array $where 条件
	 * @desc 获取 总行数
	 */
	public function getCount($where = array()) {
		try{
			$this->db->from($this->_table);
			if(!empty($where)){
				$this->db->where($where);
			}
			$count = $this->db->count_all_results();
			return $count;
		} catch(Exception $e) {
			$message = "getCount：".$this->_table."表 获取总数异常 where: ".http_build_query($where).' 错误信息：'. $e->getMessage();
			log_message_user("ERROR", $message,'mysql');
			return false;
		}
	}
	
	/**
	 * list_info 
	 * 
	 * @param string $select 
	 * @param array $where 
	 * @param int $curr_page 
	 * @param int $page_size 
	 * @param string $order 
	 * @access public
	 * @return void
	** @desc   : 根据where条件返回数据列表数组；
	 */
	public function list_info($select = '*', $where = array(), $curr_page = 1, $page_size = 20, $order = 'id DESC') {
		try
		{
			$this->rows_count = $this->getCount($where); // 获取总记录条数；
			$page_count = intval($this->rows_count/$page_size);
			if($page_count * $page_size < $this->rows_count) $page_count++;
			if($curr_page > $page_count) $curr_page = $page_count;
			if($curr_page < 1) $curr_page = 1;
			$this->pages      = pages($this->rows_count, $curr_page, $page_size); // 获取分页信息
			$this->curr_page  = $curr_page; // 获取当前第几页；
	
			$date_list = $this->select($select, $where, $page_size, $order, ($curr_page-1) * $page_size); //获取列表信息
			return $date_list;
		} catch(Exception $e) {
			$message = "10004 ".$this->_table."  list_info条件查询单表数据失败". $e;
			log_message("ERROR", $message);
			return false;
		}
	}

	/**
	 * delete 
	 * 
	 * @param mixed $where 
	 * @access public
	 * @return void
	 * @author jackcao
	 * @desc 删除 数据  假删除  
	 */
	public function delete($where, $data = array()) {
		try{
			// 获取 要修改 id, 为了全站 的 缓存key 唯一更新   start  by jackcao
			$select = " id ";
			$this->db->select($select)->from($this->_table)->where($where);
			$query = $this->db->get();

			$ret_tmp = $query->result_array();
			if (is_array($ret_tmp) && count($ret_tmp) > 0 ) {
				foreach ($ret_tmp as $val) {
					$primary_id_tmp = intval($val["id"]);
					if ( !$primary_id_tmp ) {
						continue;
					}
					//$this->setPrimaryKey($primary_id_tmp);    
					//$this->cache->delete($this->_primary_key);
					//save_syslog(SYS_LOG_DEL, $primary_id_tmp, $this->_table, $this->_table_desc); // 记录操作日志
				}
			}
			// 获取 要修改 id, 为了全站 的 缓存更新 end  by jackcao
			$data["dele_status"] = DELETE_STATUS;
			$ret = $this->db->update($this->_table, $data, $where);	
			return $ret;
		} catch(Exception $e) {
			$message = "10006 ".$this->_table. " ".$primary_id."  删除数据失败". $e;
			log_message("ERROR", $message);
			return false;
		}
	}
	

}
