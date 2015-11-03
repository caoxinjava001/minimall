<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/



/**
 * 自定义
 *
 */
//$route['(:any)'] = 'module/$1';
$route['default_controller'] = "admin/admin/login";
$route['404_override'] = '';
$route['y404'] = "index/y404";


/*----------------------后台 start -----------------------------*/
$route['admin'] = "admin/admin/index";
$route['admin/(:any)'] = "admin/admin/$1";

$route['main'] = "admin/content/clist";
$route['main/(:any)'] = "admin/content/$1"; 
$route['main.html']="admin/content/clist";



//菜单相关 tart
$route['information_auth/(:any)'] 	= "information/information_auth/$1";
$route['information_menu/(:any)'] 	= "information/information_menu/$1";
$route['information_accounts/(:any)'] 	= "information/information_accounts/$1";
$route['information_detail/(:any)'] = "information/information_detail/$1";
//菜单相关 end 


/*----------------------后台 end -----------------------------*/


/*----------------------前台 start -----------------------------*/
// @todo
//合伙人注册 start 
$route['register_cop/(:any)'] = "admin/register_cop/$1";
$route['audit']="admin/audit/index";
$route['audit/(:any)']="admin/audit/$1";
$route['enterprise/(:any)'] = "admin/enterprise/$1";
$route['market/(:any)'] = "admin/market/$1";
$route['enterprise_sort/(:any)'] = "admin/enterprise_sort/$1"; //企业信息分类
$route['manage/(:any)'] = "admin/manage/$1";
$route['object/(:any)'] = "admin/object/$1";


$route['mycop/(:any)']="admin/mycop/$1";
//合伙人注册 end



//  企业注册 s
$route['bs/(:any)'] = "front/reg_business/$1";
//  企业登入
$route['login/(:any)'] = "front/login/$1";
$route['show/(:any)'] = "usercentre/showStatus/$1";
/*----------------------前台 end -----------------------------*/

/*---------------------- 中心 start -----------------------------*/

/*---------------------- 中心 end -----------------------------*/


/*----------------------用户中心 start -----------------------------*/
// @todo
/*----------------------用户中心 end -----------------------------*/


/* End of file routes.php */
/* Location: ./application/config/routes.php */
##############销售人员创建和管理 start##############################
$route['manage_sale/(:any)'] = "admin/manage_sale/$1";
##############销售人员创建和管理 end##############################

##############合伙人内勤创建和管理 start##############################
$route['manage_inside/(:any)'] = "admin/manage_inside/$1";
##############合伙人内勤创建和管理 end##############################

##############审核日志列表 start##############################
$route['review_log/(:any)'] = "admin/review_log/$1";
##############审核日志列表 end##############################

##############行业分类 列表 start##############################
$route['industry_class/(:any)'] = "admin/industry_class/$1";
##############行业分类 列表 end##############################

$route['tools/(:any)'] = "front/tools/$1"; //圣康小工具
$route['angel/(:any)'] = "front/angel/$1"; //圣康企业融资测试帮（天使轮）

##############新三板调查信息 列表 start ##############################
$route['xinsanban_investigation_info/(:any)'] = "admin/xinsanban_investigation_info/$1";
##############新三板调查信息 列表 end ##############################

##############天使轮 调查信息 列表 start ##############################
$route['angel_investigation_info/(:any)'] = "admin/angel_investigation_info/$1";
##############天使轮 调查信息 列表 end ##############################


?>
