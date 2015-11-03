<?php
/**
 * 机构——用户关系表(jh_member_org)Model
 * @author sunzheng@yduedu.com
 *
 */
class Member_org_Model extends MY_Model
{
    public $_table   = null;      //  表名 暂无
    public $_entity_name = null;  //  表名 暂无
    public $_primary = null;      //  表的 索引键值 
	public $_db_url;              //  DBA  接口
	public $_member_url;          //  用户中心  接口
	public $http_url_list = array(); //  http接口  
	public $count = 0;//总数
    
	public function __construct()
	{
		parent::__construct();
		$this->_table = "yt_admin_user_org"; //  表名 暂无
		$this->_table_member_org = 'jh_view_member_org';//权限表视图
		$this->_table_desc = '机构用户表'; //数据表描述
		$this->initDB();
		
		$this->_http_name = "memberOrg"; //  http 接口名字
		//$this->http_url_list = $this->initHttp();
    }
    
    /**
     * 教务权限 走视图搜索
     * @author sunzheng@yduedu.com
     */
    public function list_info_view($select='*',$where = array(), $curr_page = 1, $page_size = 20){
    	$this->count = $rows_count = $this->list_count_view($where);// 获取去重的总记录条数；
    	if($rows_count == 0){
    		return array();
    	}
    	
    	$page_count = intval($rows_count/$page_size);
    	if($page_count * $page_size < $rows_count) $page_count++;
    	if($curr_page > $page_count) $curr_page = $page_count;
    	if($curr_page < 1) $curr_page = 1;
    	$this->pages      = pages($rows_count, $curr_page, $page_size); // 获取分页信息
    	$this->curr_page  = $curr_page; // 获取当前第几页；
    	$this->db->select($select);
    	$this->db->from($this->_table_member_org);
    	if(isset($where['nickname'])){
    		$this->db->like('nickname',$where['nickname']);
    	}
    	if(isset($where['teacher_name'])){
    		$this->db->like('name',$where['teacher_name']);
    	}
    	$this->db->limit($page_size,($curr_page-1) * $page_size);
    	$result_category = $this->db->get()->result_array();;
    	return $result_category;
    }
    /**
     * 权限视图 总条数
     * @param array $where
     * @return int
     * @author sunzheng@yduedu.com
     */
    public function list_count_view($where){
    	$this->db->select('id');
    	$this->db->from($this->_table_member_org);
    	if(isset($where['nickname'])){
    		$this->db->like('nickname',$where['nickname']);
    	}
    	if(isset($where['teacher_name'])){
    		$this->db->like('name',$where['teacher_name']);
    	}
    	$count = $this->db->count_all_results();;
    	return $count;
    }
    
	
}
