<?php
/**
 * MY_Controller 
 * 
 * @uses CI
 * @uses _Controller
 * @package 
 * @version $Id$
 * @author jackcao <caoxin@kangm.com> 
 * 1. 初始化  配置 文件  如： 错误日志 代码 ； DBA 和  http接口地址 等等
 * 2. 获取当前登录用户基本信息   等等
 * 3. 请勿随意存放私有方法
 * 4. 公共方法请存放global_helper.php
 */
class MY_Controller extends CI_Controller{
	public $error_code;			//错误码
	public $system_config;		//系统配置
	public $dns_url_config;		//dns配置
	public $mid;				//当前登录用户id 
	public $nickname;			//当前登录用户昵称
	public $member_info;		//当前登录用户信息
	public $org_id;				//当前登录用户隶属机构id 绝对是机构用户
	public $org_name;			//当前登录用户隶属机构昵
	public $org_info;			//隶属机构基本信息
	public $login_status;		//当前登录状态
	public $backend_header;		//头部模板名
	public $backend_header_data;//头部模板数据
	public $backend_left;		//左侧菜单模板名
	public $backend_left_data;	//左侧菜单数据
	public $backend_main;		//主体部分模板名
	public $backend_footer;		//尾部模板名
	public $login_role_id;
	private $menu_son = FALSE;//菜单是否有可显示的子级
	private $menu_parent_id=0;//菜单父级id
	public $mem_menu_list = array();//用户的菜单-权限2.0
	public $mem_menu_top = array();
	private $menu_list = array();//所有的菜单-权限2.0
	private $mem_menu_id_list = array();//用户可用的菜单id-权限2.0
	private $no_pagesize = 10000;//调用db接口无分页获取数据
	private $menu_list_by_parent = array();//菜单缓存 key为父级id;
	private $menu_auth_expired = 604800;//一周3600*24*7
	private $menu_top;//修改菜单表顶级id
	private $check_menu_no_add = array();
	
	public $check_login_link=array();	//不需要登录的连接 数组格式 类下的所有都不需要时 等于*即可
	public $is_show_menu=array();	//不需要登录的连接 数组格式 类下的所有都不需要时 等于*即可


	/**
	 * __construct 
	 * 
	 * @param string $_flag 
	 * @access public
	 * @return void
	 */
	public function __construct($_flag='backend'){
		parent::__construct();
		header("content-Type: text/html; charset=utf-8");
		$this->init();//初始化系统
		
		if($_flag == 'frontend') {
			//前台 管理信息
			$this->org_info     = array();
			// @todo kill -9 this 
		}else if($_flag == 'usercenter') {
			//用户中心
			$this->checkFrontendLogin(); // 检测前端用户登录状态

		} else {
			// 后台管理 信息
			$this->checkBackLogin(); // 检测后台用户登录状态
		}
	}

	/**
	 * init
	 *
	 * @access private
	 * @return void
	 * @desc  初始化系统
	 */
	private function init(){
		$this->error_code = $this->config->config['error_code'];  //错误码
		$this->system_config = $this->system_info = $this->config->config['system_front'];     //系统配置
		$this->dns_url_config = $this->config->config['dns_url']; //dns配置
		$this->backend_header = "header";		//前台头部模板名
		$this->backend_left   = "left_menu";	//前台左侧菜单模板名
		$this->backend_main   = "main";			//前台主体部分模板名
		$this->backend_footer = "footer";		//前台尾部模板名
		$this->backend_left_data = array();
		$this->backend_header_data = array('top_nav_current' => '',//头部菜单默认选中状态值 ：头部菜单的中文全拼 例：我的服务 wodefuwu
											'title' => '',//页面title
											'keywords'=>'',//页面关键字
											'description'=>'',//页面描述
											);

		//load缓存 默认使用memcache 备用文件缓存
		$this->load->driver('cache',array('adapter'=>'memcached','backup'=>'file'));
	}
	
	
	/**
	 * checkFrontendLogin 
	 * 
	 * @access private
	 * @return void
	 */
	private function checkFrontendLogin() {
		$member_info = $this->membercookie->getMemberInfo($memberInfo);
		$this->login_status = $this->backend_header_data['login_status'] = $member_info;

		$link = '/'.$this->router->class.'/'.$this->router->method;
		if($member_info === false){
			if($this->check_login_link !='*' && !in_array($link, $this->check_login_link)){
				if($this->input->is_ajax_request()){
					 echo "非法请求!";exit;
					//exit(getAjaxResponse('10055'));
				} else {
					//非登录状态 当前连接未在非登录列表内 跳到 医度首页
					redirect(MAIN_PATH, 'refresh');
				}
			}
		}else{

			$this->member_info = $memberInfo['member_info']; //用户信息
			$this->mid = $this->member_info['id'];//登录者id
			$this->nickname = $this->member_info['user_name'];//登录者昵称
			//$this->backend_header_data['nickname'] = $this->nickname;//登录者昵称 模版可以直接$nickname调用
		}
	}
	
	/**
	 * checkBackLogin 
	 * 
	 * @access private
	 * @return void
	 */
	private function checkBackLogin(){
		$ssoMemberInfo = array();
		$ssoResult = $this->ssocookie->getMemberInfo($ssoMemberInfo);
		//var_dump($ssoResult);exit;
		//echo "end";exit;
		$this->login_status = $this->backend_header_data['login_status'] = $ssoResult;
		$link = '/'.$this->router->class.'/'.$this->router->method;
		if($ssoResult === false){
			if($this->check_login_link !='*' && !in_array($link, $this->check_login_link)){
				if($this->input->is_ajax_request()){
					 echo "非法请求!";exit;
					//exit(getAjaxResponse('10055'));
				} else {
					//非登录状态 当前连接未在非登录列表内 跳到 医度首页
					redirect(MAIN_PATH.'/admin/login', 'refresh');
				}
			}
		}else{
			$this->member_info = $ssoMemberInfo['member_info']; //用户信息
			$this->org_info = $ssoMemberInfo['org_info']; //隶属信息
			$this->mid = $this->member_info['id'];//登录者id
			$this->nickname = empty($this->member_info['org_name']) ?$this->member_info['user_name'] : $this->member_info['org_name'];//登录者昵称
			$this->org_id = $this->org_info['id'];//登录用户隶属分中心的id
			$this->login_role_id = $this->member_info['login_role_id'];
			//$this->org_name = $this->org_info['user_name'];//登录用户隶属分中心的昵称
			$this->backend_header_data['nickname'] = $this->nickname;//登录者昵称 模版可以直接$nickname调用
			$this->backend_left_data['login_role_id'] = $this->login_role_id;
			$this->checkMenu();
		}
	}
	
	/**
	 * rendering_template 
	 * 
	 * @param mixed $data 
	 * @param string $dir 
	 * @param string $file 
	 * @access protected
	 * @return void
	 */
	protected function rendering_template($data,$dir='',$file='') {
		
		if($dir){
			$this->backend_main=$dir;
			if($file)$this->backend_main.='/'.$file;
		}elseif($file){
			$this->backend_main=$file;
		}
		$this->backend_header_data['org_name'] = $this->nickname;
		//$data['org_info'] = $this->org_info;
		$this->backend_header_data['org_info'] = $this->org_info;
		$data['login_status'] = $this->login_status;
		//加载模版
		if($this->login_status){
			$this->backend_header_data['nickname'] = $this->nickname;
		}
		$this->load->view($this->backend_header,$this->backend_header_data);	// 导入 头部 视图模板
		$this->load->view($this->backend_main,$data);	// 导入 主体部分 视图模板
		$this->load->view($this->backend_footer,'');	// 导入 底部 视图模板
	}
	
	/**
	 * rendering_admin_template 
	 * 
	 * @param mixed $data 
	 * @param string $dir 
	 * @param string $file 
	 * @access protected
	 * @return void
	 */
	protected function rendering_admin_template($data,$dir='',$file='') {
		
		if($dir){
			$this->backend_main=$dir;
			if($file)$this->backend_main.='/'.$file;
		}elseif($file){
			$this->backend_main=$file;
		}
		$this->backend_header = 'admin/'.$this->backend_header;
		$this->backend_left = 'admin/'.$this->backend_left;
		$this->backend_main = 'admin/'.$this->backend_main;
		$this->backend_footer = 'admin/'.$this->backend_footer;
		$this->backend_header_data['org_name'] = $this->nickname;
		$this->load->view($this->backend_header,$this->backend_header_data);	// 导入 头部 视图模板
		$this->load->view($this->backend_left,$this->backend_left_data);	// 导入 左侧 视图模板
		$this->load->view($this->backend_main,$data);	// 导入 主体部分 视图模板
		$this->load->view($this->backend_footer,'');	// 导入 底部 视图模板
	}

	/**
	 * rendering_no_left_template 
	 * 
	 * @param mixed $data 
	 * @param string $dir 
	 * @param string $file 
	 * @access protected
	 * @return void
	 */
	protected function rendering_no_left_template($data,$dir='',$file='') {
	
		if($dir){
			$this->backend_main=$dir;
			if($file)$this->backend_main.='/'.$file;
		}elseif($file){
			$this->backend_main=$file;
		}
		$this->backend_header = 'admin/'.$this->backend_header;
		$this->backend_left = 'admin/'.$this->backend_left;
		$this->backend_main = 'admin/'.$this->backend_main;
		$this->backend_footer = 'admin/'.$this->backend_footer;
		$this->load->view($this->backend_header,$this->backend_header_data);	// 导入 头部 视图模板
		$this->load->view($this->backend_main,$data);	// 导入 主体部分 视图模板
		$this->load->view($this->backend_footer,'');	// 导入 底部 视图模板
	}

	/**
	 * setJsonResponse
	 *
	 * @param mixed $data
	 * @param int $code
	 * @param string $msg
	 * @access protected
	 * @return void
	 */
	protected function getJsonResponse($code,$data='') {
		$response = array();
		$response['code'] = $code;
		$response['msg'] = $this->getErrorCodeByKey($code);
		if ($data) {
			$response['data'] = ($data);
		}
		return json_encode($response);
	}

	/**
	 * getJsonToData
	 *
	 * @param mixed $data
	 * @access protected
	 * @return void
	 */
	protected function getJsonToData($data) {
		return getJsonToDate($data);
	}

	/**
	 * 返回对应的错误码信息
	 * @param int $param 错误码的key
	 * @return string 错误码的对应值
	 */
	protected function getErrorCodeByKey($param){
		$param  = (int)$param;
		$error_code = $this->error_code;
		return isset($error_code[$param])?$error_code[$param]:'';
	}

	################################## 新三板权限2.0  start ###############################
	/**
	 * 新三板访问权限过滤2.0
	 * @author : sunzheng@yduedu.com
	 */
	private function checkMenu(){
		$this->is_show_menu = true;
	}
	
	/**
	 * 新三板权限2.0
	 * @param unknown_type $pid
	 * @param unknown_type $tid
	 */
	public function getTopId2($pid,$tid){
		foreach($this->menu_list[$pid] as $k=>$v){
			$this->menu_top[$tid][] = $v['id'];
			$this->getTopId2($v['id'],$tid);
		}
		
	}
	
	/**
	 * 新三板权限2.0
	 * 获取当前登录用户的菜单列表
	 * $this->mem_menu_list 用户可用菜单列表
	 * @author sunzheng
	 */
	private function getMemMenuList(){
		$this->menu_list = $this->getMenuInfo();
		$this->mem_menu_id_list = $this->getMemAuthList();
		$this->getNavlist();
        //echo '<pre>';
        //print_r($this->mem_menu_id_list);exit;
        //print_r($this->mem_menu_id_list);exit;
	}
	/**
	 * 新三板权限2.0
	 * 获取菜单列表
	 * @author sunzheng
	 */
	private function getMenuInfo(){
		$menu_list = false;//cacheFileGet('menu/menu_list');
		$menu_list_code_top  = false; //cacheFileGet('menu/menu_list_code_top');
		if($menu_list === false || $menu_list_code_top === false){
			$this->load->model('Menu_model');
			$list = $this->Menu_model->select('*',array('status'=>CLOUD_MENU_STATUS_OPEN,'dele_status'=>NO_DELETE_STATUS,'display'=>CLOUD_MENU_DISPLAY_BLOCK),'3000');
			//归类
			$menu_list = array();
			foreach($list as $k=>$v){
				$info = array('id'=>$v['id'],
						'name'=>$v['name'],
						'module'=>$v['module'],
						'menu_class'=>$v['menu_class'],
						'function'=>$v['function'],
						'code'=>$v['code'],
						'parent_id'=>$v['parent_id'],
						'top_id'=>empty($v['top_id'])?$v['id']:$v['top_id'],
						'display'=>$v['display'],
						'menu_sort'=>$v['menu_sort'],
				);
				if(isset($menu_list[$v['id']])){
					$menu_list[$v['id']] = array_merge($info,$menu_list[$v['id']]);
				}else{
					$menu_list[$v['id']] = $info;
				}
				
				if($v['parent_id'] == 0){
					$menu_list['top'][$v['menu_sort']] = $v['id'];
				}else{
					if(isset($menu_list[$v['parent_id']]['list'][$v['menu_sort']])){
						$menu_list[$v['parent_id']]['list'][] = $v['id'];
					}else{
						$menu_list[$v['parent_id']]['list'][$v['menu_sort']] = $v['id'];
					}
				}
				$menu_list_code_top[$v['code']] = $v['top_id'];
			}
			
			ksort($menu_list['top']);
			foreach($menu_list as $k=>$v){
				if($k=='top'){continue;}
				if(!isset($v['id'])){
					unset($menu_list[$k]);
				}
				if(isset($menu_list[$k]['list']) && is_array($menu_list[$k]['list'])){
					ksort($menu_list[$k]['list']);
				}
			}
			//排序 并将每级下面是否有显示的菜单计算出来
            //缓存暂时注释掉 15/06/01  Steven.Robin.Shen
			//cacheFileSave('menu/menu_list', $menu_list,86400000);//1000天
			//cacheFileSave('menu/menu_list_code_top', $menu_list_code_top,86400000);//1000天
		}
		$this->check_menu_no_add = $menu_list_code_top;
		return $menu_list;
	}

	/**
	 * 新三板权限2.0
	 * 获取用户的权限列表
	 * @author sunzheng
	 */
	private function getMemAuthList(){
		$menu_id_list = cacheFileGet('menu/mem_menu_id_list_'.$this->mid);
		if($menu_id_list === false){
			$member_info = $this->member_info;
            /**
			//检测用户本身是机构 还是机构赋予的权限用户
			if(isset($member_info['user_type']) && ($member_info['user_type'] == USER_ORG || $member_info['user_type']== USER_SAVANT)){
				//自身是机构 或者 专家
				$this->load->model('privilege_model');
				$org_auth_info = $this->privilege_model->get_one('*',array('member_id'=>$member_info['id'],'status'=>CLOUD_MENU_ORG_LOOK_STATUS_OPEN,'dele_status'=>NO_DELETE_STATUS));
			}else if(isset($member_info['user_type']) &&  $member_info['user_type'] == USER_DEAN){
				//自身是教务
				$this->load->model('Member_org_model');
				$org_auth_info = $this->Member_org_model->get_one('*',array('member_id'=>$member_info['id'],'belong_org_id'=>$member_info['belong_org_id'],'status'=>CLOUD_MENU_ORG_LOOK_STATUS_OPEN,'dele_status'=>NO_DELETE_STATUS));
			}else{
				log_message_user('ERROR','checkOrgLoginByCookie检测新三板登录 当前登录帐号ID:'.$member_info['id'].' 无此用户类型退出','login');
				$this->ssocookie->loginOut();
				redirect(ADMIN_LOGIN_PATH, 'refresh');
			}
            **/
			
        //其他管理人员
        //echo $member_info['id'];exit;
        if($member_info['id'] != 1){        
            $this->load->model('Member_org_model');
            //$org_auth_info = $this->Member_org_model->get_one('*',array('member_id'=>$member_info['id'],'belong_org_id'=>$member_info['belong_org_id'],'status'=>CLOUD_MENU_ORG_LOOK_STATUS_OPEN,'dele_status'=>NO_DELETE_STATUS));
            $org_auth_info = $this->Member_org_model->get_one('*',array('member_id'=>$member_info['id'],'status'=>CLOUD_MENU_ORG_LOOK_STATUS_OPEN,'dele_status'=>NO_DELETE_STATUS));
           //echo '<pre>';
           //print_r($org_auth_info['menu_id']);exit;
        }else{ //超级管理员
            $this->load->model('privilege_model');
			$org_auth_info = $this->privilege_model->get_one('*',array('member_id'=>$member_info['id'],'status'=>CLOUD_MENU_ORG_LOOK_STATUS_OPEN,'dele_status'=>NO_DELETE_STATUS));
            }
            //print_r($org_auth_info);exit;
			if(empty($org_auth_info)){//此用户无权限信息
				log_message_user('ERROR','checkOrgLoginByCookie检测新三板登录 当前登录帐号ID:'.$member_info['id'].' 当前用户无新三板权限','login');
				$this->ssocookie->loginOut();
				show_message('您没有信诺后台权限,请联系管理员！^|^',MAIN_PATH.'/admin/ajaxLoginOut',3);
			}
			$menu_id_list = explode(',',$org_auth_info['menu_id']);
            //print_r($menu_id_list);exit;
            
            //缓存暂时注释掉 15/06/01  Steven.Robin.Shen
			//cacheFileSave('menu/mem_menu_id_list_'.$this->mid, $menu_id_list,86400000);//1000天
		}
		return $menu_id_list;
	}
	
	/**
	 * 新三板权限2.0
	 * 递归获取用户的菜单列表
	 * @author sunzheng
	 */
	private function getNavlist($parent='top'){
		$is_son = false;
		if(isset($this->menu_list[$parent])){
			if($parent == 'top'){
				$list = $this->menu_list[$parent];
			}else{
				$list = isset($this->menu_list[$parent]['list'])?$this->menu_list[$parent]['list']:array();
			}
			
			if($list)
			{
				foreach($list as $k=>$v){
// 					//当前用户可用的菜单列表 用来比较菜单库是否不全 
// 					if($this->menu_list[$v]['parent_id'] == 0){
// 						$this->check_menu_no_add[$this->menu_list[$v]['code']] = $this->menu_list[$v]['id'];
// 					}else{
// 						$this->check_menu_no_add[$this->menu_list[$v]['code']] = $this->menu_list[$v]['top_id'];
// 					}
					//获取用户可用的菜单列表
					if(in_array($v,$this->mem_menu_id_list)){
						if($parent == 'top'){
							$this->mem_menu_list['top'][] = $v;
						}
						$this->menu_list[$v]['display'] = ($this->menu_list[$v]['display'] == CLOUD_MENU_DISPLAY_BLOCK)?true:false;
						if($this->menu_list[$v]['parent_id'] == 0){
							$this->mem_menu_top[$this->menu_list[$v]['code']] = $this->menu_list[$v]['id'];
						}else{
							$this->mem_menu_top[$this->menu_list[$v]['code']] = $this->menu_list[$v]['top_id'];
						}
						$this->mem_menu_list[$v] = $this->menu_list[$v];
						unset($this->mem_menu_list[$v]['list']);
						$this->getNavlist($v);
						if($this->menu_list[$v]['display'] == CLOUD_MENU_DISPLAY_BLOCK){
							$is_son = true;
						}
						if($parent != 'top'){
							$this->mem_menu_list[$parent]['list'][] = $v;
						}
						
					}
				}
				if($parent != 'top'){
					$this->mem_menu_list[$parent]['son'] = $is_son;
				}
			}
		}
	}
	
	
	################################## 新三板权限2.0 end ###############################
	
}
?>

