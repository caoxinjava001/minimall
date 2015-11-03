<?php
/***********************************
 * @date  2013/12/18
* 全局接口公共方法存放处
* 为了方便别人查找和防止写多个一样作用的方法
* 请大家在这里添加的方法都在头部写一下
* 方便他人就是方便自己
* 此helper下的所有方法：
*
* xxtEncrypt()      加密算法
* xxtDecrypt()  	解密算法
*
*
****************************************/

/**
 * 加密算法
 * $string 为字符类型
 * @param string $url url 地址
 * @param array $data 参数
 * @return array
 * @author jackcao@159jh.com
 */
function xxtEncrypt($string){
	try {
		$CI =& get_instance();
		$CI->load->library('Xxtea');
		$result = $CI->xxtea->encrypt($string,YDU_LOCKED_KEYS);
		return trim($result,'.');
	}catch (Exception $e) {
		log_message_user('ERROR', 'Curl请求End异常：'.$e->getMessage(),'xxt');
	}

}

/**
 * 解密算法
 * $string 为字符类型
 * @param string $url url 地址
 * @param array $data 参数
 * @return array
 * @author jackcao@159jh.com
 */
function xxtDecrypt($string){
	try {
		$CI =& get_instance();
		$CI->load->library('Xxtea');
		$result = $CI->xxtea->decrypt($string,YDU_LOCKED_KEYS);
		return $result;
	}catch (Exception $e) {
		log_message_user('ERROR', 'Curl请求End异常：'.$e->getMessage(),'xxt');
	}
}



/**
 * 用户信息加密 和setEncodeAuth、getDecodeAuth中的key对应
 * @param array $param
 * @return string
 * @author jackcao
 */
function setEncodeByKey($param){
	$result = '';
	if(is_array($param)){
		foreach($param as $k=>$v){
			$result .= $v;
		}
	}else{
		$result = $param;
	}

	$data = md5($result);
	return substr($data, 8,20);
}


/**
 * @author jackcao
 * 字符串加密
 * @param	string	$txt		字符串
 * @param	string	$key		密钥：数字、字母、下划线
 * @return	string
 */
function setEncodeAuth($value,$key='') {
	$code = sys_auth($value,'ENCODE',$key);
	return $code;
}

/**
 * @author jackcao
 * 字符串解密
 * @param	string	$txt		字符串
 * @param	string	$key		密钥：数字、字母、下划线
 * @return	string
 */
function getDecodeAuth($value,$key='') {
	return sys_auth($value,'DECODE',$key);;
}


/**
 * 字符串加密、解密函数
 *
 *
 * @param	string	$txt		字符串
 * @param	string	$operation	ENCODE为加密，DECODE为解密，可选参数，默认为ENCODE，
 * @param	string	$key		密钥：数字、字母、下划线
 * @param	string	$expiry		过期时间
 * @return	string
 */
function sys_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0) {
	$CI =& get_instance();
	$key_length = 4;
	$key = md5($key != '' ? $key : $CI->config->item('encryption_key'));
	$fixedkey = md5($key);
	$egiskeys = md5(substr($fixedkey, 16, 16));
	$runtokey = $key_length ? ($operation == 'ENCODE' ? substr(md5(microtime(true)), -$key_length) : substr($string, 0, $key_length)) : '';
	$keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
	$string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length));

	$i = 0; $result = '';
	$string_length = strlen($string);
	for ($i = 0; $i < $string_length; $i++){
		$result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
	}
	if($operation == 'ENCODE') {
		return $runtokey . str_replace('=', '', base64_encode($result));
	} else {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$egiskeys), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	}
}


