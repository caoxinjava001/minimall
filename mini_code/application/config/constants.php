<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);
define('WF_PATH','182.92.72.93:9010 ');//金衡网地址
/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*********域名常量start************/
define('MAIN_PATH',        'http://182.92.72.93:9010');
define('STATICS_PATH', 		'http://182.92.72.93:9010/statics/main');	//静态页面系统地址
define('MAIN_H_PATH', 		'http://'.$_SERVER['HTTP_HOST']);//分中心网地址
define('ADMIN_PATH',        'http://182.92.72.93:9010');	//社区接口
define('PIC_PATH',        'http://pic.kangm.cn');	//图片路径
//define('HOME_PATH',         'http://home.yduedu.com/');		//社区接口
//define('NEWS_PATH',         'http://news.yduedu.com');		//金衡网新闻中心地址
//define('WWW_IMG_PATH',      STATICS_PATH.'/yidu_web3.0/images/');
/*********域名常量end************/

/**********JS CSS IMG地址 start***************/
define('STATICS_PATH_JS',	STATICS_PATH.'/js');      // 公用JS目录
define('STATICS_PATH_CSS',	STATICS_PATH.'/css');     // 公用CSS目录
define('STATICS_PATH_IMG',	STATICS_PATH.'/images');  // 公用images目录

define('STATICS_PATH_BACKEND_JS',	STATICS_PATH.'/backend/js');      // 后台JS目录
define('STATICS_PATH_BACKEND_CSS',	STATICS_PATH.'/backend/css');     // 后台CSS目录
define('STATICS_PATH_BACKEND_IMG',	STATICS_PATH.'/backend/images');  // 后台images目录

define('STATICS_PATH_FRONTEND_JS',	STATICS_PATH.'/frontend/js');     // 前台JS目录
define('STATICS_PATH_FRONTEND_CSS',	STATICS_PATH.'/frontend/css');    // 前台CSS目录
define('STATICS_PATH_FRONTEND_IMG',	STATICS_PATH.'/frontend/images'); // 前台images目录

//define('API_ADD_COLLECT',           API_PATH . '/collect/addCollect'); //API添加收藏接口地址
/**********JS CSS IMG地址 end***************/


/*******全局 状态 start************/
define('DELETE_STATUS', 0);// 删除状态
define('NO_DELETE_STATUS', 1);// 未删除状态
define('CURRENT_PAGE_NUM_OF_PARAM', 3);// 页码在参数中的位置
define('YDU_LOCKED_KEYS',  "YduEDu.com@))$"); // 公共密匙
/*******全局状态 end************/

define('SYS_TIME',time()); 

/*******上传 状态 end************/
define('UPLOAD_TYPE_PIC',      1);   // 图片
define('FLASH_TYPE_FROM_YDU', 2); //  来源于医度
define('PIC_TYPE_VAL',"*.gif;*.gif;*.jpeg;*.jpg;*.png"); // 允许上传的图片格式

/**************************************************************/
define('PARTNER_ADMIN',      1);   // 后台账号
define('PARTNER_ORG',      2);   // 合伙人机构
define('PARTNER_PERSONAL',     3);   // 合伙人个人
define('SALE_PERSONAL',     4);   //销售人员 
define('PARTNER_INSIDE',     5);   //合伙人内勤

define('VER_NOT_AUDIT',     0);   // 审核不通过
define('VER_IN_AUDIT',     1);   // 未审核
define('VER_HAD_AUDIT',     2);   //已审核通过

/*******用户信息 start************/
define('USER_MEMBER',	1);//普通用户
define('USER_ORG',		2);//机构
define('USER_SAVANT',	3);//专家
define('USER_DEAN',		4);//教务

define('MEMBER_SEX_MAN', 1); //男
define('MEMBER_SEX_WOMAN', 2); //女
define('MEMBER_SEX_SECRECY', 3); //保密

define('CLOUD_MENU_STATUS_OPEN',			1);//新三板菜单 启用
define('CLOUD_MENU_STATUS_CLOSE',			2);//新三板菜单 禁用
define('CLOUD_MENU_DISPLAY_BLOCK',			1);//新三板菜单是否前台展示 显示
define('CLOUD_MENU_DISPLAY_NONE',			2);//新三板菜单是否前台展示 隐藏

define('CLOUD_MENU_ORG_LOOK_STATUS_OPEN',	1);//机构访问新三板后台状态 启用
define('CLOUD_MENU_ORG_LOOK_STATUS_CLOSE',	2);//机构访问新三板后台状态 禁用

define('CLOUD_MENU_MEMBER_LOOK_STATUS_OPEN',1);//机构下的普通用户访问新三板后台状态 启用
define('CLOUD_MENU_MEMBER_LOOK_STATUS_CLOSE',2);//机构下的普通用户访问新三板后台状态 禁用
/*******用户信息 end************/

/*********管理员角色 start*********/
define('ROLE_ONE',100);     //销售内勤
define('ROLE_TWO',200);     //销售经理
define('ROLE_THREE',300);   //销售总裁
define('ROLE_FOUR',400);    //投资内勤
define('ROLE_FIVE',500);    //投资经理
define('ROLE_SIX',600);     //投资总监
define('ROLE_SEVEN',700);   //投资总经理
define('ROLE_EIGHT',800);   //投资总经理
define('ROLE_NINE',900);   //投资总经理
/*********管理员角色 end*********/

/********* yt_ent_audit_log 审核阶段 start*********/
define('STEP_ONE',0);   //资料审核
define('STEP_TWO',1);   //项目审核
/********* yt_ent_audit_log 审核阶段 end*********/

/********* 项目审核状态 *********/
define('OBJ_REPLAR',-1);      //重新上传
define('OBJ_NONE',0);      //未立项
define('OBJ_ONE',1);        //已立项，尽调进行中
define('OBJ_TWO',2);        //待投资总监审核
define('OBJ_THREE',3);      //待投资总经理审核
define('OBJ_FOUR',4);       //已上会讨论

/***********调查阶段 **********/

define('SEARCH_START','启动初调');      //启动初调
define('SEARCH_START_PASS','初调通过');      //启动初调
define('SEARCH_START_NOPASS','启动不通过');      //启动初调

/****** 审核等级 ******/
define('AUDIT_LEVEL_ONE',400);
define('AUDIT_LEVEL_TWO',500);
define('AUDIT_LEVEL_THREE',600);
define('AUDIT_LEVEL_FOUR',700);

/********** 可信度 ***********/
define('TRUTH_NORMAL',1);   //正常
define('TRUTH_INVALID',2);  //无效
define('TRUTH_QUERY',3);    //质疑

/*********** 审核类型 **********/
define('CONTENT_AUDIT',1);  //内容审核
define('PRO_AUDIT',2);      //专业审核

/*********** 尽调类型 **********/
define('INVEST',1); //投资管理
define('MARKET',2); //市值管理

?>
