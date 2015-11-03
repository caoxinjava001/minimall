<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>404页面</title>
<style>
body,html{padding:0px;margin:0px;font-size:12px;font-family:"微软雅黑";}
	a{ text-decoration:none;}
	.main_404{width:960px;height:500px;margin:0px auto;position:relative;background:url(<?php echo STATICS_PATH_IMG;?>1373449127603.png) no-repeat;}
		.Main_4_z{position:absolute;left:490px;top:210px;color:#fe0000;text-align:left;width:265px;height:25px;line-height:25px;}
		.main_4_a{position:absolute;left:500px;top:250px;text-align:left;width:265px;height:28px;line-height:28px;}
		.main_4_a a{height:26px;border:#b6d4df solid 1px;background:#fff;width:80px;text-align:center;float:left;margin-right:20px;color:#2a2a2a;display:block;}
			
</style>
</head>

<body>
<div class="main_404">
	<div class="Main_4_z">您所访问的页面不存在，或者已被删除！</div>
    <div class="main_4_a">
    	<a href="javascript:history.back();">返回上一页</a>
        <a href="<?php echo WF_PATH; ?>">返回首页</a>
    </div>
</div>
	<script type="text/javascript">
        setTimeout(function() {
            window.location = "<?php echo WF_PATH; ?>";
        }, 6000);
    </script>
</body>
</html>
