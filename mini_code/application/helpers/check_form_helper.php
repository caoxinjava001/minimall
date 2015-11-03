<?php
/**
 * 检测姓名格式
 * 2-4个中文
 * @param string $value
 * @author jackcao
 */
if ( ! function_exists('checkName'))
{
	function checkName($value){
		if(preg_match('/^[\x{4e00}-\x{9fa5}]{2,4}$/u', $value)){
			return true;
		}
		return false;
	}
}


/**
 * 检测手机号格式
 * @param string $value
 * @author jackcao
 */
if ( ! function_exists('checkMobile'))
{
	function checkMobile($value){
		if(preg_match('/^(13|14|15|18|17)\d{9}$/', $value)){
			return true;
		}
		return false;
	}
}

/**
 * 检测密码
 * 4-20位的密码
 * @param string $value
 * @author jackcao
 */
if ( ! function_exists('checkPassword'))
{
	function checkPassword($value){
		if(preg_match('/^[a-zA-Z0-9_]{4,20}$/', $value)){
			return true;
		}
		return false;
	}
}

/**
 * 检测邮箱格式
 * @param string $value
 * @author jackcao
 */
if ( ! function_exists('checkEmail'))
{
	function checkEmail($value){
		if(preg_match('/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/', $value)){
			return true;
		}
		return false;
	}
}

/**
 * 检测公司名称
 * 2-120个中文、字母、数字字符,第一个必须是中文
 * @param string $value
 * @author jackcao
 */
if ( ! function_exists('checkCname'))
{
	function checkCname($value){
		if(preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{2,120}$/u', $value)){
			return true;
		}
		return false;
	}
}

/**
 * 检测公司性质
 * array(1=>'合资','国企','民营公司','政府机关','事业单位','非营利机构','其他性质')
 * @param string $value
 * @author jackcao
 */
if ( ! function_exists('checkNature'))
{
	function checkNature($value){
		if(is_numeric($value)){
			$CI =& get_instance();
			$xing = $CI->system_config['user_xing'];
			if(array_key_exists($value, $xing)){
				return true;
			}
		}
		return false;
	}
}

/**
 * 检测联系地址
 * 2-40个中文 字母 数字字符
 * @param string $value
 * @author jackcao
 */
if ( ! function_exists('checkAddr'))
{
	function checkAddr($value){
		if(preg_match('/^[\x{4e00}-\x{9fa5}a-zA-Z0-9-]{2,120}$/u', $value)){
			return true;
		}
		return false;
	}
}

/**
 * 检测邮编
 * 6位数字
 * @param string $value
 * @author jackcao
 */
if ( ! function_exists('checkPostcode'))
{
	function checkPostcode($value){
		if(preg_match('/^[1-9][0-9]{5}$/', $value)){
			return true;
		}
		return false;
	}
}

/**
 * 检测文本内容
 * 最多300个字
 * @param string $value
 * @author jackcao
 */
if ( ! function_exists('checkContent'))
{
	function checkContent($value){
		$c = strlen($value);
		if($c<300 || $c>2){
			return true;
		}
		return false;
	}
}

/**
 * 检测身份证
 * 
 * @param string $value
 * @author jackcao
 */
if ( ! function_exists('checkCard'))
{
	function checkCard($value){
		if(preg_match('/^\d{14}(\d|x)$/', $value) || preg_match('/^\d{17}(\d|x)$/', $value)){
			return true;
		}
		return false;
	}
}


/**
 * 截取函数
 * 一个汉字2个字符
 * $len为截取后加上...的总长度
 * @param string $str
 * @param int $len
 * @author jackcao
 */
if ( ! function_exists('contentSubstr'))
{
	function contentSubstr($str,$len,$des='...',$code='utf-8'){
		$len = (int) $len;
		if(countStrLength($str,$code) <= $len){
			return $str;
		}else{
			return mb_strimwidth($str, 0, $len,$des,$code);
		}
	}
}

/**
 * 带编码检测字符串的长度
 * @param string $var
 * @param string $encoding
 * @author jackcao
 */
if ( ! function_exists('countStrLength'))
{
	function countStrLength($var,$encoding = 'utf-8'){
		if(strtolower($encoding) != 'utf-8') {
			return strlen($var);
		}

		$count = 0;
		for($i = 0; $i < strlen($var); $i++){
			$value = ord($var[$i]);
			if($value > 127) {
				$count++;
				if($value >= 192 && $value <= 223) $i++;
				elseif($value >= 224 && $value <= 239) $i = $i + 2;
				elseif($value >= 240 && $value <= 247) $i = $i + 3;
			}
			$count++;
		}
		return $count;
	}
}

/**
 * 检测日期格式是否正确
 * @param string $var
 * @author jackcao
 */
if ( ! function_exists('checkDateParam'))
{
	function checkDateParam($key) {
		if (empty($key)) {
			return false;
		}
		$value = trim($key);
		$value = strtotime($value);
		$value = ($value) ? date('Y-m-d', $value) : false;
		return $value;
	}
}

/**
 * 生成采购订单号
 * 用B开头的15位字符
 * @author jackcao
 */
if ( ! function_exists('makeOrderSn'))
{
	function makeOrderSn() {
		$sn = 'B'.date('Ymd',time()).rand(11111,99999);
		return $sn;
	}
}

/**
 * 生成实体卡订单号
 * 用C开头的15位字符
 * @author jackcao
 */
if ( ! function_exists('makeCardSn'))
{
	function makeCardSn() {
		$sn = 'C'.date('Ymd',time()).rand(11111,99999);
		return $sn;
	}
}

/**
 * 入库数据安全过滤
 * @param string $value
 * @return string
 * @author jackcao
 */
if ( ! function_exists('getSafeString'))
{
	function getSafeString($value,$allowable_tags=''){
		$value = trim($value);
		$value = strip_tags($value,$allowable_tags);
		return empty($allowable_tags) ? htmlspecialchars($value) : $value;
	}
}


/**
 * 生成验证码
 * 
 * @return array
 * @author jackcao
 */
function makeVerCode(){
	$CI =& get_instance();
	$CI->load->helper('captcha');
	$exp = 600;//秒
	$vals = array(
			'word'=>rand(11111,99999),
			'img_path' => './captcha/',
			'img_url' => FENZHAN_PATH.'/captcha/',
			'img_width' => 70,
			'img_height' => 26,
			'expiration' => 100
	);
	$cap = create_captcha($vals);
	if($cap){
		cacheFileSave('user/code/c_'.$cap['time'], $cap['word'],$exp);
	}
	return $cap;
}
/**
 * 获取服务器端验证码
 * @author jackcao
 */
function getVerCode($key){
	if(empty($key)){
		return false;
	}
	$value = cacheFileGet('user/code/c_'.$key);
	if($value === false){
		return false;
	}else{
		return $value;
	}
}

/**
 * 校验日期格式是否正确
 * @param time $date
 * @author jackcao
 */
function checkUseDate($date){
	$t = strtotime($date);
	if($t && $t>1){
		return true;
	}else{
		return false;
	}
}
