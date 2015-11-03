<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin 
 * 
 * @uses MY
 * @uses _Controller
 * @package 
 * @version $Id$
 * @author jackcao <caoxin@kangm.com> 
 */
class Admin extends MY_Controller {

	/**
	 * __construct 
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->check_login_link = array('/admin/login','/admin/ajaxHtml','/admin/ajaxLoginOut');//该类下的数组里面的方法不需要强制登录
		parent::__construct();
		$this->backend_header_data['top_nav_current'] = 'index';
		$this->backend_header_data['title'] = '' ;//页面title
		$this->backend_header_data['keywords'] = '' ;//页面关键字
		$this->backend_header_data['description'] = '' ;//页面描述
		//$this->load->model("logo_pic_model");  // 导入分中心上传图片表模型
		$this->load->helper('check_form');
	}

	/**
	 * 首页
	 * @author jackcao
	 */
	public function index() {
		redirect(MAIN_PATH, 'refresh');
	}
	
	/**
	 * login 
	 * 
	 * @access public
	 * @return void
	 */
	public function login() {
		//$arr = array();
		//$arr['create_company_time'] ='2012-11-01';//企业创办时间

		//$arr['industry_id_first'] = 74;//一级分类
		//$arr['industry_id_second'] = 3;//二级分类
		//$arr['industry_id_third'] = 24;//三级分类
		//$arr['reg_capital'] = 1000;// 注册资本
		//$arr['ent_revenue_2014']= 4000;//2014收入
		//$arr['ent_mrmb_2014']=500;// 2014净利润
		//$arr['ent_totaldebt_2015']= 300; // 2015总负责
		//$arr['ent_totalassets_2015']= 1000; // 2015总资产
		//$arr['ent_currentassets_2015']= 1500; // 2015流动资产
		//$arr['ent_currentdebt_2015']=1000; // 2015流动负债
		//$arr['ent_expectedrevenue_2015']=7100;// 2015预计收入
		//$arr['ent_expectedmrmb_2015']=1000;// 2015预计净利润


		//$arr['ent_expectedrevenue_2016']= 14000; //2016年收入
		//$arr['ent_expectedmrmb_2016']=1500; //2016年净利润
		//$t = getScoreByList($arr);
		//var_dump($t);
		//exit;
/*
*/
		//$this->load->model('role_info_model');
		//$ret = $this->role_info_model->getHighRoleInfoByRoleId(100);
		//if ($_REQUEST['test']) {
		//var_dump($ret);exit;
		//}
		$data = array();
		if($this->login_status) {
			redirect(MAIN_PATH.'/audit/allmember', 'refresh'); //登录成功后的默认页面.
		}
		$this->load->view("admin/login",$data);
	}


	/**
	 * ajaxHtml 
	 * 
	 * @access public
	 * @return void
	 */
	public function ajaxHtml(){
		$error = array();//错误信息
		$check_form_status = true;
		$username = $this->input->get_post('username');//帐号
		$password = $this->input->get_post('password');//密码
		//$v = encryptMd5('a123456',123456);
		//echo $v;exit;
		
		if(!checkMobile($username)){
			$error['username'] = '手机号格式错误！';//错误信息
			$check_form_status = false;
		}
		
		if(!checkPassword($password)){
			$error['password'] = '密码由4-20个字母、数字、下划线组成';//错误信息
			$check_form_status = false;
		}
		if(!$check_form_status){
			exit(getAjaxResponse(10051,$error));
		}
		$info = $this->ssocookie->checkUP($username,$password);
		
		exit(getAjaxResponse(10050,array('nickname'=>$info['name']))); 
	}
	
	/**
	 * ajaxLoginOut 
	 * 
	 * @access public
	 * @return void
	 */
	public function ajaxLoginOut(){
		$ex_time = $this->config->config["system_backend"]['login_expired_time'];
		setcookie($this->config->config['system_backend']['cookie_sid'], '', time()-$ex_time,$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
		setcookie($this->config->config['system_backend']['cookie_auth'], '', time()-$ex_time,$this->config->item('cookie_path'),$this->config->item('cookie_domain'));
		//exit(getAjaxResponse(10060));
		redirect(MAIN_PATH,'refresh');
	}

}
?>
