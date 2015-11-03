<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
    <link href="<?php echo STATICS_PATH_BACKEND_CSS; ?>/admin_login.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo STATICS_PATH; ?>/css/ui/jquery-ui-custom.min.css" type="text/css" rel="stylesheet"/>
      <link href="<?php echo STATICS_PATH; ?>/css/public.css" type="text/css" rel="stylesheet"/>
      <link href="<?php echo STATICS_PATH; ?>/css/b_reg.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="<?php echo STATICS_PATH; ?>/js/jq_min.js"></script>
    <script type="text/javascript" src="<?php echo STATICS_PATH; ?>/js/ui/jquery-ui.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo STATICS_PATH; ?>/js/util.js"></script>

</head>
<body>
    <div class="wrap">
        <div class="login_left">
                <h1>圣康金融云平台</h1>
                <div class="lg_r"><em class="lg_r_em"></em><span class="lg_r_span">系统登录</span></div>
        </div>
        <div class="admin_login_wrap">
        <h1>用户登录 LOGIN</h1>
        <div class="adming_login_border">
            <div class="admin_input">
                <form action="index.html" method="post">
                    <ul class="admin_items">
                        <li>
                            <label for="user">用户名：</label>
                            <input type="text" name="username" value="" id="user" size="40" class="admin_input_style" />
                        </li>
                        <li>
                            <label for="pwd">密码：</label>
                            <input type="password" name="pwd" value="" id="pwd" size="40" class="admin_input_style" />
                        </li>
                        <li>
                            <input type="button" onclick="return loginAction();" tabindex="3" value="提交" class="btn btn-primary" />
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <?php /*
        <p class="admin_copyright"><a tabindex="5" href="/register_cop/index">合伙人注册</a> &copy; 2015 Powered by kangm</p>

        */ ?>
        <!--p class="admin_copyright"><a tabindex="5" href="/bs/index">企业注册</a>&nbsp;&nbsp;<a tabindex="5" href="/login/logg">企业登入</a>&nbsp;&nbsp;<a tabindex="5" href="/register_cop/index">合伙人注册</a>  by kangm</p-->
    </div>
    </div>
    <script>
        function loginAction() {
	 var url = "/admin/ajaxHtml";
	 var username=$('#user').val();
	 var password=$('#pwd').val();
	 var message_info = '请正确输入手机号和密码';
	 //_Util.show_message("test");
	 //return false;
	 if (username == '' || username == undefined || password == '' || password== undefined) {
		 //$todo
		 _show_msg(message_info,2500);
		return false;
	 }
	 $.post(
			 url,
			 {
			 username:username,
			 password:password
			 },
			 function(data) {
				var is_code_val = data["code"];
				 if (is_code_val == 10050) {
					window.location.href='/audit/allmember';//登录成功后的默认页面.

				 } else if(is_code_val == 10051) { //合伙人  身份证
					_show_msg(data['data'],2500);
					window.location.href='/register_cop/auth_first?member_id='+data['data']['id'];

				 } else if(is_code_val == 10056) { //合伙人  营业执照
					_show_msg(data['data'],2500);
					window.location.href='/register_cop/auth_sec?member_id='+data['data']['id'];


				 } else if(is_code_val == 10052) { //合伙人  审核未通过
					_show_msg(data['data'],2500);
					window.location.href='/register_cop/index?member_id='+data['data']['id'];
				 } else {

					//_show_msg(message_info,2500);
					_show_msg(data['data'],2500); //_show_msg(data['data']['id'],2500);
				 }
				 return false;
			 },
			 "json"
		  );
}

        $("form",".admin_login_wrap").keydown(function(e){
            if(e.keyCode==13){
                loginAction();
            }
        });
    </script>
</body>
</html>
