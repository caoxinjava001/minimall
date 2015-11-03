<?php
/***********************************
 * @date  2013/11/29
 * 分页公共方法存放处
 * 为了方便别人查找和防止写多个一样作用的方法
 * 请大家在这里添加的方法都在头部写一下
 * 方便他人就是方便自己
 * 此helper下的所有方法：
 *
 * pages() 分页方法
 * pageurl() 返回分页路径   属于私有方法请勿直接调用
 * url_par() URL路径解析  属于私有方法请勿直接调用
 * get_url() 获取当前页面完整URL地址  属于私有方法请勿直接调用
 * safe_replace() 安全过滤函数 属于私有方法请勿直接调用
 *
 ****************************************/

/**
 * 分页方法
 * @param int $num
 * @param int $curr_page
 * @param int $perpage
 * @param string $url
 * @param int $param_num
 * @param int $setpages
 * @param array $array
 * @return string
 */
function pages($num, $curr_page, $perpage = 20, $url = '', $param_num=3, $setpages = 6, $array = array())
{
	
	$url = url_par('page={$page}');
	$multipage = '';

	if($num > $perpage)
	{
		$multipage .= '<div class="page marb20">';

		$page = $setpages+1;
		$offset = ceil($setpages/2-1);
		$pages = ceil($num / $perpage);
		//if (!defined('PAGES')) define('PAGES', $pages);
		$from = $curr_page - $offset;
		$to = $curr_page + $offset;
		$more = 0;
		if($page >= $pages)
		{
			$from = 2;
			$to = $pages-1;
		}
		else
		{
			if($from <= 1)
			{
				$to = $page-1;
				$from = 2;
			}
			elseif($to >= $pages)
			{
				$from = $pages-($page-2);
				$to = $pages-1;
			}
			$more = 1;
		}
		//$multipage .= '<a class="a1 a_11">'.$num.L('page_item').'</a>';
		if($curr_page <= 0) $curr_page = 1;
		if($curr_page > $pages) $curr_page = $pages;
		if($curr_page > 0)
		{
			$pre = (($curr_page-1)*$perpage) == 0 ? 1 : ($curr_page-1)*$perpage+1;

			$p_end = ($curr_page*$perpage)>$num?$num:($curr_page*$perpage);
			$multipage .= '<a href="'.pageurl($url, $curr_page-1, $param_num, $array).'" class="pre">上一页</a>';

			if($curr_page==1)
			{
				$multipage .= ' <a href="javascript:" class="currend">1</a>';
			}
			elseif($curr_page>4 && $more)
			{
				$multipage .= ' <a href="'.pageurl($url, 1, $param_num, $array).'">1</a><span style="width:10px;">...</span>';
			}
			else
			{
				$multipage .= ' <a href="'.pageurl($url, 1, $param_num, $array).'">1</a>';
			}
		}
		for($i = $from; $i <= $to; $i++)
		{
			if($i != $curr_page)
			{
				$multipage .= ' <a href="'.pageurl($url, $i, $param_num, $array).'">'.$i.'</a>';
			}
			else
			{
				$multipage .= ' <a href="javascript:" class="currend">'.$i.'</a>';
			}
		}
		if($curr_page<$pages)
		{
			if($curr_page<$pages-3 && $more)
			{
				$multipage .= ' <span style="width:10px;">...</span><a href="'.pageurl($url, $pages, $param_num, $array).'">'.$pages.'</a> <a href="'.pageurl($url, $curr_page+1, $param_num, $array).'" class="next">下一页</a>';
			}
			else
			{
				$multipage .= ' <a href="'.pageurl($url, $pages, $param_num, $array).'">'.$pages.'</a> <a href="'.pageurl($url, $curr_page+1, $param_num, $array).'" class="next">下一页</a>';
			}
		}
		elseif($curr_page==$pages)
		{
			$multipage .= ' <a href="javascript:" class="currend">'.$pages.'</a> <a href="'.pageurl($url, $curr_page, $param_num, $array).'" class="next">下一页</a>';
		}
		else
		{
			$multipage .= ' <a href="'.pageurl($url, $pages, $param_num, $array).'">'.$pages.'</a> <a href="'.pageurl($url, $curr_page+1, $param_num, $array).'" class="next">下一页</a>';
		}
		$multipage .= '<span><input id="pages_num" type="text" value="" class="page_ys" /></span>';
		$multipage .= '<input id="go_page_num" type="button" class="page_qd" value="确认" />';

		$multipage .= '<script type="text/javascript">'.chr(13);
		$multipage .= '$("#go_page_num").click(function()'.chr(13);
		$multipage .= '{'.chr(13);
		$multipage .= '	var s,pages_num = $("#pages_num").val();'.chr(13);
		$multipage .= '	if(pages_num == "")'.chr(13);
		$multipage .= '	{'.chr(13);
		$multipage .= '		alert("请输入页码！");'.chr(13);
		$multipage .= '		$("#pages_num").focus();'.chr(13);
		$multipage .= '	}'.chr(13);
		$multipage .= '	else'.chr(13);
		$multipage .= '	{'.chr(13);
		$multipage .= '		if(/^([0-9]+)$/.test(pages_num))'.chr(13);
		$multipage .= '		{'.chr(13);
		$multipage .= '			var h=$(this).siblings("a:first").attr("href");'.chr(13);
		$multipage .= '			if(h.indexOf("page=") == -1){'.chr(13);
		$multipage .= '				if(h.indexOf("?") == -1){'.chr(13);
		$multipage .= '					s = h+"?page="+pages_num'.chr(13);
		$multipage .= '				}else{'.chr(13);
		$multipage .= '					s = h+"&page="+pages_num'.chr(13);
		$multipage .= '				}'.chr(13);
		$multipage .= '			}else{'.chr(13);
		$multipage .= '				s = h.replace(/page=\d+/g,"page="+pages_num);'.chr(13);
		$multipage .= '			}'.chr(13);
		$multipage .= '			window.location.href=s;'.chr(13);
		$multipage .= '		}'.chr(13);
		$multipage .= '		else'.chr(13);
		$multipage .= '		{'.chr(13);
		$multipage .= '			alert("请输入正确的页码！");'.chr(13);
		$multipage .= '			$("#pages_num").focus();'.chr(13);
		$multipage .= '		}'.chr(13);
		$multipage .= '	}'.chr(13);
		$multipage .= '});'.chr(13);
		$multipage .= '</script>'.chr(13);

		$multipage .= '</div>';
	}
	return $multipage;
}
/**
 * 返回分页路径
 *
 * @param $urlrule 分页规则
 * @param $page 当前页
 * @param $array 需要传递的数组，用于增加额外的方法
 * @return 完整的URL路径
 */
function pageurl($urlrule, $page, $param_num, $array = array()) {
	$url = str_replace('{$page}', $page,$urlrule);
	return $url;
}

/**
 * URL路径解析，pages 函数的辅助函数
 *
 * @param $par 传入需要解析的变量 默认为，page={$page}
 * @param $url URL地址
 * @return URL
 */
function url_par($par, $url = '') {
	if($url == '') $url = get_url();
	$pos = strpos($url, '?');
	if($pos === false) {
		$url .= '?'.$par;
	} else {
		$querystring = substr(strstr($url, '?'), 1);
		parse_str($querystring, $pars);
		$query_array = array();
		foreach($pars as $k=>$v) {
			if($k != 'page') $query_array[$k] = $v;
		}
		$querystring = http_build_query($query_array).'&'.$par;
		$url = substr($url, 0, $pos).'?'.$querystring;
	}
	return $url;
}

/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return ADMIN_PATH.$relate_url;
}

/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
	$string = str_replace('%20','',$string);
	$string = str_replace('%27','',$string);
	$string = str_replace('%2527','',$string);
	$string = str_replace('*','',$string);
	$string = str_replace('"','&quot;',$string);
	$string = str_replace("'",'',$string);
	$string = str_replace('"','',$string);
	$string = str_replace(';','',$string);
	$string = str_replace('<','&lt;',$string);
	$string = str_replace('>','&gt;',$string);
	$string = str_replace("{",'',$string);
	$string = str_replace('}','',$string);
	$string = str_replace('\\','',$string);
	return $string;
}
