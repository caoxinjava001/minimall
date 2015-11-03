<?php
/**
 * SsoCookie{ 
 * 
 * @package 
 * @version $Id$
 * @author jackcao <caoxin@kangm.com> 
 */
class SsoCookie{
	private $cookie_sid = '';//cookie key
	private $cookie_auth = '';//cookie key
	private $hpi_result_login;//回传值
	private $CI;
	
	public function __construct(){
		$this->CI =& get_instance();
		$this->cookie_sid = $this->CI->config->config['system_backend']['cookie_sid'];
		$this->cookie_auth = $this->CI->config->config['system_backend']['cookie_auth'];
	}
	
	/**
	 * 检测用户登录信息
	 * @param array $data
	 */
	public function getMemberInfo(&$data){
		$statusData = $this->getDataByCookie();
		if($statusData){//成功返回
			$data = $this->hpi_result_login;
			return true;
		}else{
			return false;
		}
	
	}
	
	/**
	 * 根据cookie
	 */
	private function getDataByCookie(){
		//1.获取cookie
		$cookie_sid_value = isset($_COOKIE[$this->cookie_sid])?$_COOKIE[$this->cookie_sid]:'';
		$cookie_auth_value = isset($_COOKIE[$this->cookie_auth])?$_COOKIE[$this->cookie_auth]:'';
		
		//2.校验cookie中的用户名
		@list($mid,$username,$user_type,$token) = explode(',',getDecodeAuth($cookie_auth_value,$cookie_sid_value));

		if(!is_numeric($mid) || empty($username)  || empty($token)){
			log_message_user('ERROR','检测登录状态: cookieValue无法解析反转','login');
			return false;
		}
		
		$this->CI->load->model('admin_user_model','User_model');
		$data = $this->CI->User_model->getUserInfoByLoginId($mid);
		if($data){
			$this->hpi_result_login = $data;
			return true;
		}else{
			$this->hpi_result_login = array();
			return false;
		}
		
	}
	
	/**
	 * 内部校验帐号密码是否可以登录
	 *
	 * @param string $username
	 * @param string $password
	 */
	public function checkUP($username,$password){
		//获取用户信息根据用户名密码
		$user_info = $this->getUserInfoByUserName($username,$password);
		if(!is_array($user_info)){
			//return $user_info;
			exit($user_info);
		}
			
		//加密token 登录一次变一次
		$token = randomstr(12);
		$cookieKey = setEncodeByKey($user_info['id'].$user_info['mobile'].$token.$this->CI->config->item('encryption_key'));
		$cookieValue = setEncodeAuth($user_info['id'].','.$username.','.$user_info['login_role_id'].','.$token,$cookieKey);
		log_message_user('INFO','当前登录帐号：'.$username.' 帐号ID:'.$user_info['id'].' cookieKey:'.$cookieKey.' cookieValue:'.$cookieValue,'login');
		//存cookie
		$this->setCookieData($this->cookie_sid, $cookieKey);
		$this->setCookieData($this->cookie_auth, $cookieValue);
			
		return $user_info;
	}
	
	/**
	 * 根据用户账号、密码 获取用户信息
	 *
	 * @param string $username
	 * @param string $password
	 */
	private function getUserInfoByUserName($username,$password){

		//$password = 'a123456';
		//$user_info['encrypt'] = 'abcdef';
		//$ee = encryptMd5($password,$user_info['encrypt']);
		//var_dump($ee);exit;
		$error = array();
		$this->CI->load->model('Admin_user_Model','User_model');
		//$user_info =$this->CI->User_model->get_one('id,name,mobile,password,encrypt,last_login_date',array('mobile'=>$username,'dele_status'=>NO_DELETE_STATUS,'status'=>2));
		$user_info =$this->CI->User_model->get_one('*',array('mobile'=>$username,'dele_status'=>NO_DELETE_STATUS));
		//var_dump($user_info,'123456');exit;
		if($user_info){//用户存在

			//校验密码
			if($user_info['password'] == encryptMd5($password,$user_info['encrypt'])){//密码正确

				$login_role_id = $user_info['login_role_id'];
				// 合伙人 start
				//if ($login_role_id == PARTNER_ORG || $login_role_id == PARTNER_PERSONAL) {
				//	if (empty($user_info['identify_pic'])  ) {
				//		$error['msg']='资料信息不全，请增加信息!!';
				//		$error['id']=$user_info['id'];
				//		return getAjaxResponse(10051,$error);
				//	}

				//	if (empty($user_info['yy_pic']) && $login_role_id == PARTNER_ORG ) {
				//		$error['msg']='资料信息不全，请增加信息!!!';
				//		$error['id']=$user_info['id'];
				//		return getAjaxResponse(10056,$error);
				//	}

				//	$c_status_val = intval($user_info['status']);

				//	if($c_status_val === VER_NOT_AUDIT) { // 审核未通过
				//		
				//		$error['msg']='审核未通过';
				//		$error['id']=$user_info['id'];
				//		return getAjaxResponse(10052,$error);
				//	}

				//	if($c_status_val === VER_IN_AUDIT) { // 审核未通过
				//		$error['msg'] = '小编正在加紧审核，请稍候再试！';
				//		return getAjaxResponse(10053,$error);
				//	}

				//}
				//// 合伙人 end

				//维护最后登录时间
				$time = date('Y-m-d H:i:s',time());
				$up['last_login_date'] = $time;
				$this->CI->User_model->update($up,array('id'=>$user_info['id']));
				return $user_info;
			} else {//密码错误
				//log_message_user('ERROR','当前登录帐号：'.$username.' 帐号ID:'.$user_info['id'].' 密码:'.$password.' 密码错误','login');
				$error = '请输入正确密码!';
				return getAjaxResponse(10060,$error);
			}
		}else{//帐号不存在
			//log_message_user('ERROR','当前登录帐号：'.$username.' 此帐号不存在','login');
			$error = '帐号不存在';
			return getAjaxResponse(10061,$error);
		}
	}
	
	/**
	 *
	 * 设置cookie
	 * @param string $name 键
	 * @param string $value 值
	 * @param int $expire 过期时间
	 * @param string $domain 域名
	 * @param string $path 存放地址
	 */
	private function setCookieData($name,$value,$expire='',$domain='',$path=''){
		$expire = $this->CI->config->config["system_backend"]['login_expired_time'];//缓存时间
		empty($domain) && $domain = $this->CI->config->item('cookie_domain');
		empty($path) && $path = $this->CI->config->item('cookie_path');
		$cookie = array(
				'name'   => $name,
				'value'  => $value,
				'expire' => $expire,
				'domain' => $domain,
				'path'   => $path,
				'prefix' => $this->CI->config->item('cookie_prefix')
		);
		$this->CI->input->set_cookie($cookie);
	}
	
     /**
      * 无后台权限清除登录
      * @author sunzheng
      */
     public function loginOut(){
         $this->cookie_sid_value = $_COOKIE[$this->cookie_sid];
         $this->cookie_auth_value = $_COOKIE[$this->cookie_auth];
         log_message_user('INFO', 'loginOut因无后台权限清除kookie BACK_MAIN_DSID：'.$this->cookie_sid_value.'BACK_MAIN_DAUTH：'.$this->cookie_auth_value,'login');
         setcookie($this->cookie_sid,'',time() - 3600,$this->CI->config->item('cookie_path'),$this->CI->config->item('cookie_domain'));
         setcookie($this->cookie_auth,'',time() - 3600,$this->CI->config->item('cookie_path'),$this->CI->config->item('cookie_domain'));
         return true;
     }

}
