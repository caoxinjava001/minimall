<?php
/**
 * 根据cookie检测登录情况
 * 1.cookie的登录检测与系统独立开来
 * 2.可以直接复制到任意系统下使用 （注意常量 API_PATH）
 * 3.这里只做cookie的检测 和数据的返回 不做逻辑层的操作
 * 4.return true：登录成功 false：登录失败
 * 			&$data 直接修改内存地址数据 因为返回数据量大 此方法效率高
 * @author jackcao@yduedu.com
 *
 */
class MemberCookie{
	private $cookie_sid = 'XnIoP_Sid';//cookie key
	private $cookie_auth = 'XnIoP_Auth';//cookie key
	private $cookie_sid_value; //全局cookie值
	private $cookie_auth_value;//全局cookie值
	
	private $hpi_result_login;//登录接口返回结果
	private $api_url_checklogin;//登录api接口路径
	private $login_success_code;//登录成功code
	private $curl;//curl实例化
	private $CI;
	
	public function __construct(){
		//$this->api_url_checklogin=LOGIN_PATH.'login/checkMemberLoginByCookie';//登录api接口路径
		$this->api_url_checklogin=false;
		$this->login_success_code=41001;//登录成功code
		$this->CI =& get_instance();
		
	}
	
	/**
	 * 检测用户登录信息
	 * @param array $data
	 * @author jackcao@159jh.com
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
	 * 检测cookie
	 * @author jackcao@159jh.com
	 */
	private function checkCookie(){
		$this->cookie_sid_value = $this->CI->input->cookie($this->cookie_sid);
		$this->cookie_auth_value = $this->CI->input->cookie($this->cookie_auth);
		if (!$this->cookie_sid_value || !$this->cookie_auth_value) {
			return false;
		}
		return true;
	}




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
		
		$this->CI->load->model('User_model');
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
	 * 调用接口根据cookie校验用户登录情况以及返回用户信息
	 * @author jackcao@159jh.com
	 */
	private function getDataByCookie_old(){
		$statusCookie = $this->checkCookie();
		if(!$statusCookie){
			return false;
		}
		$data = array($this->cookie_sid=>$this->cookie_sid_value,$this->cookie_auth=>$this->cookie_auth_value);
		$result =curlHttp($this->api_url_checklogin,$data);
		if(!isset($result) || $result['code'] != $this->login_success_code){
			setcookie($this->cookie_sid,'',time() - 3600,$this->CI->config->item('cookie_path'),$this->CI->config->item('cookie_domain'));
			setcookie($this->cookie_auth,'',time() - 3600,$this->CI->config->item('cookie_path'),$this->CI->config->item('cookie_domain'));
			
			//老医度同步退出
			setcookie('VpTMo_auth', '', time()-3600,$this->CI->config->item('cookie_path'),$this->CI->config->item('cookie_domain'));
			setcookie('VpTMo__userid', '', time()-3600,$this->CI->config->item('cookie_path'),$this->CI->config->item('cookie_domain'));
			setcookie('VpTMo__username', '', time()-3600,$this->CI->config->item('cookie_path'),$this->CI->config->item('cookie_domain'));
			setcookie('VpTMo__nickname', '', time()-3600,$this->CI->config->item('cookie_path'),$this->CI->config->item('cookie_domain'));
			setcookie('VpTMo__groupid', '', time()-3600,$this->CI->config->item('cookie_path'),$this->CI->config->item('cookie_domain'));
			setcookie('VpTMo__tokenLogin', '', time()-3600,$this->CI->config->item('cookie_path'),$this->CI->config->item('cookie_domain'));
			//老医度同步退出
			return false;
		}
		$this->hpi_result_login = $result['data'];
		return true;
	}


	/**
	 * checkUP 
	 * 
	 * @param mixed $username 
	 * @param mixed $password 
	 * @access public
	 * @return void
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
		$cookieValue = setEncodeAuth($user_info['id'].','.$username.',,'.$token,$cookieKey);
		//log_message_user('INFO','当前登录帐号：'.$username.' 帐号ID:'.$user_info['id'].' cookieKey:'.$cookieKey.' cookieValue:'.$cookieValue,'login');
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
		//$username = '13910001000';
		//$e='123456';

		//$v = encryptMd5($password,$e);
		//var_dump($v);exit;
		$error = array();
		$this->CI->load->model('User_model');
		$user_info =$this->CI->User_model->get_one('id,name,mobile,password,encrypt,last_login_date',array('mobile'=>$username,'dele_status'=>NO_DELETE_STATUS));
		//var_dump($user_info,'123456');exit;
		if($user_info){//用户存在
			//校验密码
			if($user_info['password'] == encryptMd5($password,$user_info['encrypt'])){//密码正确
				//维护最后登录时间
				$time = date('Y-m-d H:i:s',time());
				$up['last_login_date'] = $time;
				$this->CI->User_model->update($up,array('id'=>$user_info['id']));
				return $user_info;
			} else {//密码错误
				$error = '请输入正确密码!';
				return getAjaxResponse(10060,$error);
			}
		} else {//帐号不存在
			$error = '帐号不存在';
			return getAjaxResponse(10061,$error);
		}
	}


	/**
	 * setCookieData 
	 * 
	 * @param mixed $name 
	 * @param mixed $value 
	 * @param string $expire 
	 * @param string $domain 
	 * @param string $path 
	 * @access private
	 * @return void
	 */
	private function setCookieData($name,$value,$expire='',$domain='',$path=''){
		$expire = $this->CI->config->config["system_front"]['login_expired_time'];//缓存时间
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
	
	
}
