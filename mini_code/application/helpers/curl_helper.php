<?php
/***********************************
 * @date  2013/11/29
 * 全局接口公共方法存放处
 * 为了方便别人查找和防止写多个一样作用的方法
 * 请大家在这里添加的方法都在头部写一下
 * 方便他人就是方便自己
 * 此helper下的所有方法：
 *
 * curlHttp()      http请求方法默认post请求 所有调用都是直接用此方法
 * curlHttpPost()  post请求方法 属于私有方法请勿直接调用
 * curlHttpGet()   get请求方法  属于私有方法请勿直接调用
 *
 * --------------------以下为调用接口处理的全站公共方法--------------------
 * filterWord()	         禁词过滤
 *
 ****************************************/

/**
 * curl 封装 默认post发送请求
 * 返回数据被json_deconde为数组
 * @param string $url
 * @param array $data
 * @param string $method
 * @return array
 * @author jackcao@159jh.com
 */
function curlHttp($url,$data,$method='post'){
	if($method == 'post'){
		$result = curlHttpPost($url, $data);
	}else{
		$result = curlHttpGet($url, $data);
	}
	if($result === false){return $result;}
	$data = getJsonToDate($result);
	$data = is_array($data) ? $data : array();
	return $data;
}


/**
 * curl接口post调用
 * @param string $url url 地址
 * @param array $data 参数
 * @return array
 * @author jackcao@159jh.com
 */
function curlHttpPost($url,$data){
	try {
		$CI =& get_instance();
		$CI->load->library('Curl');
		$CI->curl->ssl(false);
		log_message_user('INFO', 'Curl-Post请求Start：url->'.$url.' 参数：'.http_build_query($data),'curl');
		$result = $CI->curl->simple_post($url,$data);
		if($result !== false){
			log_message_user('INFO', 'Curl-Post请求End：url->'.$url.' 参数：'.http_build_query($data).' 返回结果:'.$result,'curl');
			return $result;
		}
		log_message_user('ERROR', 'Curl-Post请求End：url->'.$url.' 参数：'.http_build_query($data).' 请求错误信息:'.$CI->curl->error_string,'curl');
		return false;
	}catch (Exception $e) {
		log_message_user('ERROR', 'Curl-Post请求End异常：'.$e->getMessage(),'curl');
	}

}

/**
 * curl接口get调用
 * @param string $url url 地址
 * @param array $data 参数
 * @return array
 * @author jackcao@159jh.com
 */
function curlHttpGet($url,$data){
	try {
		$CI =& get_instance();
		$CI->load->library('Curl');
		log_message_user('INFO', 'Curl-Get请求Start：url->'.$url.' 参数：'.http_build_query($data),'curl');
		$CI->curl->ssl(false);
		$result = $CI->curl->simple_get($url,$data);
		if($result !== false){
			log_message_user('INFO', 'Curl-Get请求End：url->'.$url.' 参数：'.http_build_query($data).' 返回结果:'.$result,'curl');
			return $result;
		}
		log_message_user('ERROR', 'Curl-Get请求End：url->'.$url.' 参数：'.http_build_query($data).' 请求错误信息:'.$CI->curl->error_string,'curl');
		return false;
	}catch (Exception $e) {
		log_message_user('ERROR', 'Curl-Get请求End异常：'.$e->getMessage(),'curl');
	}
}


/**
 * 禁词过滤
 * @param string $value
 * @return string
 * @author jackcao
 */
function filterWord($value){
	if(empty($value)){
		return '';
	}
	$CI =& get_instance();
	$dns_url_config = $CI->config->config['dns_url']; //dns配置
	$url = $dns_url_config['api_url_filter_word'];
	$result = curlHttp($url, array('word'=>rawurlencode($value)));
	if(isset($result['code'])){
		if($result['code'] == $CI->system_info['api_filter_word_success_code']){
			return $result['data'];
		}else{
			log_message_user('ERROR', '禁词过滤失败 Curl请求：url->'.$url.' 参数：'.$value,'filter_word');
			return $result['data'];
		}
	}else{//调用禁词接口异常
		log_message_user('ERROR', '禁词调用接口异常 Curl请求：url->'.$url.' 参数：'.$value,'filter_word');
		return $value;
	}
}
