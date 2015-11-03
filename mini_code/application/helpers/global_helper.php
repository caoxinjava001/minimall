<?php

/**
 * @Name   : load_lang_file
 * @author : tuzhanpeng@159jh.com
 * @desc   : 显示语言包中指定key对应的值；
 * @date   : 2013-08-26 AM;
 * @param  : $lang_key 语言包中定义的key；
 * @reutrn : void;
 */
function L($lang_key='')
{
	/*$return_value = lang($lang_key);	// 获取key对应的语言字符串；
	if(!$return_value) $return_value = $lang_key;*/
	$return_value = $lang_key;
	return $return_value;
}

/**
 *
 * 日志记录
 * 添加自定义目录功能
 * @param $level 级别array('ERROR', 'DEBUG',  'INFO', 'ALL')
 * @param $message 日志信息
 * @param $dir 日志记录目录 例如$dir=login  ##/application/logs/login
 * @return	void
 * @author jackcao
 */
function log_message_user($level, $message , $dir='')
{
	$_log =& load_class('Log');
	$_log->write_log_user($level, $message, $dir);
}

/**
 * 错误提示
 * 加返回上一步
 * 模版：/application/errors/error_message.php 后期可以扩展
 * @param string $message 提示的信息
 * @param string $link 跳转的连接
 * @param int $time 跳转倒计时
 * @param string $heading 提示信息标题
 * @param int $status_code 显示错误信息代码
 *
 * @author jackcao
 */
function show_message($message, $link='' , $time=5 , $heading='温馨提示',$status_code=200)
{
      $_error =& load_class('Exceptions', 'core');
      echo $_error->show_message($message, $link , $time , $heading,$status_code);
      exit;
}

/**
 * 获取jsonP格式的数据 跨域用到
 * @param 随意 $param 需要添加jsonp返回的数据 格式随意
 * @param string $jsonp 自定义的jsonp串
 * @return string
 * @author jackcao
 */
function getJsonPData($param,$jsonp=''){
	if(empty($jsonp)){
		$CI =& get_instance();
		$jsonp = $CI->input->get_post('jsoncallback');
	}
	return $jsonp.'('.$param.')';
}

/**
 * 获取ajax请求响应
 * 返回ajax请求的数据 json格式
 * @param int code 错误码
 * @param string/array $data 需要返回的数据 格式自定义
 * @param string $str 需要追加在返回信息 msg后追加的一句话
 * return json格式数据
 * @author jackcao
 */
function getAjaxResponse($code,$data='',$str=''){
	$response = array();
	$CI =& get_instance();
	$error_code = $CI->error_code;
	$response['code'] = $code;
	$response['msg'] = isset($error_code[$code])?$error_code[$code]:''.$str;
	if($data) {
		$response['data'] = ($data);
	}
	return getDateToJson($response);
}

/**
 * json转换数组
 * @param string $data
 * @param boolean $default true是转为数组，false是转为对象 
 * @author jackcao
 */
function getJsonToDate($data,$default=true) {
	return json_decode($data,$default);
}

/**
 * json转换数组
 * @param string $data
 * @author jackcao
 */
function getDateToJson($data) {
	return json_encode($data);
}

/**
 * 数字转换大写
 * @param int $value
 * @author jackcao
 */
function intToUpper($value){
	$arr = array('一','二','三','四','五','六','七','八','九');
	$data = str_split($value);
	$result = '';
	foreach($data as $k=>$v){
		if(count($data) > 1 && $k == 0){
			$result .= '十';
			continue;
		}
		$result .= $arr[$v-1];
	}
	return $result;
}

/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @param string $source 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符后缀
 +----------------------------------------------------------
 * @return string
 * @author lixiaojun
 +----------------------------------------------------------
 */
function xs_substr($source, $start=0, $length, $charset="utf-8", $suffix="")
{
	if(function_exists("mb_substr"))        //采用PHP自带的mb_substr截取字符串
	{
		$string = mb_substr($source, $start, $length, $charset).$suffix;
	}
	elseif(function_exists('iconv_substr')) //采用PHP自带的iconv_substr截取字符串
	{
		$string = iconv_substr($source,$start,$length,$charset).$suffix;
	}
	else
	{
		$pattern['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$pattern['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$pattern['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$pattern['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($pattern[$charset], $source, $match);
		$slice = join("",array_slice($match[0], $start, $length));

		$string = $slice.$suffix;
	}
	return $string;
}

/**
 * 获取当前url
 * @return string
 * @author jackcao
 */
function current_url_param(){
	$url_this =  "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	return $url_this;
}

/**
 * 密码加密
 * @param password 密码
 * @param encrypt 随机码
 * @return string
 * @author jackcao@yduedu.com
 */
function  encryptMd5($password='',$encrypt=''){
	if(empty($password)||empty($encrypt)) return false;
	$psd = md5(md5($password).$encrypt);
	return $psd;
}

/**
 * 生成随机字符串
 * @param string $lenth 长度
 * @return string 字符串
 * @return string
 */
function randomstr($lenth = 6) {
	$value = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
	$str = '';
	for($i=0;$i<$lenth;$i++){
		$str .= substr($value,mt_rand(0,strlen($value)-1),1);
	}
	return $str;
}

/**
 * 获取统计时间点
 * @author jackcao
 */
function get_stat_date()
{
	$return_value = array();
	$return_value['today']      = date('Y-m-d H:i:s', strtotime(date('Ymd'))); // 当天
	$return_value['yesterday']  = date('Y-m-d H:i:s', strtotime(date('Ymd', strtotime('-1 day'))));   // 昨天
	$return_value['last_week']  = date('Y-m-d H:i:s', strtotime(date('Ymd', strtotime('-1 week'))));  // 上周
	$return_value['last_month'] = date('Y-m-d H:i:s', strtotime(date('Ymd', strtotime('-1 month')))); // 上月
	$return_value['last_three_month'] = date('Y-m-d H:i:s', strtotime(date('Ymd', strtotime('-3 month')))); // 近三个月

	return $return_value;
}


/**
 * createDomainAjax 
 * 
 * @param mixed $message 
 * @param int $status 
 * @param string $domain 
 * @param string $function 
 * @access public
 * @return void
 * by jackcao
 */
function createDomainAjax($message, $status = 0,$domain= "182.92.72.93", $function = "getShow" ) {
	$ret = "<script language='javascript'>";
	if (!empty($domain)) {
		$ret .= "document.domain='".$domain."';";
	}
	$ret .= "parent.window.".$function."(".$status.",'".$message."');";
	$ret .= "</script>";
	echo $ret;
	exit;
}

/**
 * 菜单md5加密规则
 * @param string $param
 * @return string 
 * @author sunzheng@kangm.cn
 */
function md5Menu($param){
	$md5_value = md5(trim($param,'/'));
	return substr($md5_value, 4,9);
}


 function getAuditStatus($role_id,$status){
        if($status==VER_IN_AUDIT && $role_id==0) {
           return '启动初调';
        }
        if($status==VER_HAD_AUDIT && $role_id>0) {
            return '初调通过';
        }
        if($status==VER_NOT_AUDIT && $role_id>0) {
            return '初调未通过';
        }
    }


 function RunAction($member_info_list){
		//$member_info_list = $this->member_info;
		$role_id = $member_info_list['role_id'];
		$login_role_id = $member_info_list['login_role_id'];
		// 内勤，经理，总监，总经理
		if ($role_id) {
			redirect(MAIN_PATH.'/object/actionList', 'refresh'); 
			exit;
		}
		// 业务员
		if ($login_role_id == SALE_PERSONAL) {
			redirect(MAIN_PATH.'/enterprise/index', 'refresh'); 
			exit;
		}

		// 超级管理员
		if ($login_role_id == PARTNER_ADMIN) {
		redirect(MAIN_PATH.'/manage/allManager', 'refresh'); 
		exit;
		}
	}

// 小工具评分start
/**
 * getScoreByList 
 * 
 * @param mixed $arr 
 * @access public
 * @return void
 * @array 数据
  $arr['create_company_time'] =;//企业创办时间
  $arr['industry_id_first'] =;//一级分类
  $arr['industry_id_second'] =;//二级分类
  $arr['industry_id_third'] =;//三级分类
  $arr['reg_capital'] = ;// 注册资本
  $arr['ent_revenue_2014']=;//2014收入
  $arr['ent_mrmb_2014']=;// 2014净利润
  $arr['ent_totaldebt_2015']=; // 2015总负责
  $arr['ent_totalassets_2015']=; // 2015总资产
  $arr['ent_currentassets_2015']=; // 2015流动资产
  $arr['ent_currentdebt_2015']=; // 2015流动负债
  $arr['ent_expectedrevenue_2015']=;// 2015预计收入
  $arr['ent_expectedmrmb_2015']=;// 2015预计净利润
  $arr['ent_expectedrevenue_2016']=; //2016年收入
  $arr['ent_expectedmrmb_2016']; //2016年净利润
 */
function getScoreByList($arr){
	$ret = 0;
	if ( !is_array($arr) ) {
		return $ret;							
	}
	if (empty($arr['create_company_time'])) {
		return $ret;
	}
	//2015-09-17 09:10:01
	//$o_time = strtotime($arr['create_company_time']);
	//$d_time = strtotime("2013-01-01");	
	//if ( $o_time > $d_time) {
	//	return $ret;
	//}
	$industry_id_first = intval($arr['industry_id_first']);
	if (!$industry_id_first) {
		return $ret;
	}

	$industry_id_second = intval($arr['industry_id_second']);
	$industry_id_third = intval($arr['industry_id_third']);
	// 查询分析表数据 

	$CI =& get_instance();
	$CI->load->model("sk_biaozhun_model");	
	$where = 'cateid1 =' . $industry_id_first;
	$where .= ' and cateid2=' . $industry_id_second;
	$where .= ' and cateid3=' . $industry_id_third;
	$curr_list = $CI->sk_biaozhun_model->get_one('*', $where);
	if (!is_array($curr_list) || count($curr_list)<= 0) {
		return $ret;
	}
	$total = 0;
	// 注册资本
	$zczb_v = $curr_list['zczb']; // 注册资本
	$zczb_s = getCurrScore($zczb_v,$arr['reg_capital']);

	//2014收入
	$income2014_v = $curr_list['income2014']; //2014收入
	$income2014_s = getCurrScore($income2014_v,$arr['ent_revenue_2014']);
	//var_dump($income2014_v,$arr['ent_revenue_2014'],$income2014_s);exit;

	// 2014净利润
	$gain2014_v = $curr_list['gain2014']; // 2014净利润
	$gain2014_s = getCurrScore($gain2014_v,$arr['ent_mrmb_2014']);
	//var_dump($gain2014_s,$gain2014_v,$arr['ent_mrmb_2014']);exit;

	// 2015资产负责率 (总负债/总资产)
	$ent_totaldebt_2015 = $arr['ent_totaldebt_2015']; // 2015总负责
	$ent_totalassets_2015 = $arr['ent_totalassets_2015']; // 2015总资产
	$fzl_2015 = $ent_totaldebt_2015/$ent_totalassets_2015;
    $fzl_2015 = sprintf('%.2f',$fzl_2015);
	$fzl_2015 = $fzl_2015 * 100;
	//var_dump($ent_totaldebt_2015,$ent_totalassets_2015,$fzl_2015);exit;
	$fzl_2015_v = $curr_list['fuzhai'];
	$fzl_2015_s = getCurrScore($fzl_2015_v,$fzl_2015); // 2015资产负责率
	//var_dump($ent_totaldebt_2015,$ent_totalassets_2015,$fzl_2015_s ,$fzl_2015_v,$fzl_2015);exit;

	// 2015速动比率 (流动资产/流动负债)
	$ent_currentassets_2015 = $arr['ent_currentassets_2015']; // 2015流动资产
	$ent_currentdebt_2015 = $arr['ent_currentdebt_2015']; // 2015流动负债
	$sdl_2015 = $ent_currentassets_2015/$ent_currentdebt_2015;
    $sdl_2015 = sprintf('%.2f',$sdl_2015);
	$sdl_2015 = $sdl_2015 * 100;
	$sdl_2015_v = $curr_list['ldbl'];
	$sdl_2015_s = getCurrScore($sdl_2015_v,$sdl_2015); // 2015资产负责率
	//var_dump($ent_currentassets_2015,$ent_currentdebt_2015,$sdl_2015_s ,$sdl_2015_v,$sdl_2015);exit;

	// 2015预计收入
	$income2015_v = $curr_list['income2015']; 
	$income2015_s = getCurrScore($income2015_v,$arr['ent_expectedrevenue_2015']);
	//var_dump($income2015_s ,$income2015_v,$arr['ent_expectedrevenue_2015']);exit;

	// 2015预计净利润
	$gain2015_v = $curr_list['gain2015']; // 2015预计净利润
	$gain2015_s = getCurrScore($gain2015_v,$arr['ent_expectedmrmb_2015']);
	//var_dump($gain2015_s,$gain2015_v,$arr['ent_expectedmrmb_2015']);exit;
    
	//2016年预计收入增长率 ((2016年收入 - 2015年收入) / 2015年收入) * 100
	$ent_expectedrevenue_2016 = $arr['ent_expectedrevenue_2016']; //2016年收入
	$ent_expectedrevenue_2015 = $arr['ent_expectedrevenue_2015']; //2015年收入
	$ent_16_15 = $ent_expectedrevenue_2016 - $ent_expectedrevenue_2015;
	$yjl_2016 = $ent_16_15/$ent_expectedrevenue_2015;
    $yjl_2016 = sprintf('%.2f',$yjl_2016);
	$yjl_2016 = $yjl_2016 * 100;
	$yjl_2016_v = $curr_list['shouruinc'];
	$yjl_2016_s = getCurrScore($yjl_2016_v,$yjl_2016); //2016年预计收入增长率
	//var_dump($ent_expectedrevenue_2016,$ent_expectedrevenue_2015,$yjl_2016_s,$yjl_2016_v,$yjl_2016);exit;

	//2016年预计收入利润增长率 ((2016年利润-2015年净利润)/2015净利润) * 100
	$ent_expectedmrmb_2016 = $arr['ent_expectedmrmb_2016']; //2016年净利润
	$ent_expectedmrmb_2015 = $arr['ent_expectedmrmb_2015']; //2015年净利润
	$entr_16_15 = $ent_expectedmrmb_2016 - $ent_expectedmrmb_2015;
	$yjlr_2016 = $entr_16_15/$ent_expectedmrmb_2015;
    $yjlr_2016 = sprintf('%.2f',$yjlr_2016);
	$yjlr_2016 = $yjlr_2016 * 100;
	$yjlr_2016_v = $curr_list['liruninc'];
	$yjlr_2016_s = getCurrScore($yjlr_2016_v,$yjlr_2016); //2016年预计收入增长率
	//var_dump($ent_expectedmrmb_2016,$ent_expectedmrmb_2015,$yjlr_2016_s ,$yjlr_2016_v,$yjlr_2016);exit;
	//获取总分数


	$industry_id_first = intval($arr['industry_id_first']);
	if (!$industry_id_first) {
		return $ret;
	}
	$left_cate_s = 10;
	if ($industry_id_second && $industry_id_third) {
		$left_cate_s = 30;
	}
	$total = $zczb_s + $income2014_s + $gain2014_s + $fzl_2015_s + $sdl_2015_s + $income2015_s + $gain2015_s + $yjl_2016_s + $yjlr_2016_s + $left_cate_s;
	return $total;
}

function getCurrScore($str,$c_v){
	$ret = array();
	$str_list = explode("|",$str);
	$one_val = intval($str_list[0]);
	$tmp_scond = explode(',',$str_list[1]);
	$s_one_val = intval($tmp_scond[0]);
	$s_two_val = intval($tmp_scond[1]);
	return $c_v >= $one_val ? $s_one_val : $s_two_val;
}
// 小工具评分end

?>
