<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Index 
 * 
 * @uses MY
 * @uses _Controller
 * @package 
 * @version $Id$
 * @author kangm <all@kangm.com> 
 */
class Index extends MY_Controller{

	private $pagesize  = 30;//分页每页条数

	public function __construct(){
		$this->check_login_link = '*';
		parent::__construct('frontend');
		$this->backend_header_data['top_nav_current'] = 'index';
		$this->backend_header_data['title'] = '' ;//页面title
		$this->backend_header_data['keywords'] = '' ;//页面关键字
		$this->backend_header_data['description'] ='' ;//页面描述
	}

	/**
	 * index 
	 * 
	 * @access public
	 * @return void
	 */
	public function index(){
		$data = array();
		echo "start developping ...";exit;
		//$this->rendering_template($data,'','index_main');
	}
}
	
?>
