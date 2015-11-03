<?php

 $config['system_backend'] = array( 
         'cookie_sid' => 'BACK_MAIN_DSID', //cookie键 对应的值 为session的键
         'cookie_auth' => 'BACK_MAIN_DAUTH',//cookie键 对应的值是用户id 用户名 密码 加密信息
         'login_expired_time' => 86400,     //默认没有任何用户行为登录过期时间7天
     );  
 
 
 
 $config['system_front'] = array( 
         'cookie_sid' => 'FRONT_MAIN_DSID', //cookie键 对应的值 为session的键
         'cookie_auth' => 'FRONT_MAIN_DAUTH',//cookie键 对应的值是用户id 用户名 密码 加密信息
         'login_expired_time' => 86400,//默认没有任何用户行为登录过期时间7天
     ); 
?>
