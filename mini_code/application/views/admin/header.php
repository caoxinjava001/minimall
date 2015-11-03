<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
    <link rel="stylesheet" type="text/css" href="<?php echo STATICS_PATH_BACKEND_CSS; ?>/common.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo STATICS_PATH_BACKEND_CSS; ?>/main.css"/>
    <!--link href="<?php echo STATICS_PATH_BACKEND_CSS; ?>/admin_login.css" rel="stylesheet" type="text/css" /-->
    <link href="<?php echo STATICS_PATH; ?>/css/public.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript" src="<?php echo STATICS_PATH_BACKEND_JS; ?>/libs/modernizr.min.js"></script>
    <script type="text/javascript" src="<?php echo STATICS_PATH_BACKEND_JS; ?>/libs/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="<?php echo STATICS_PATH; ?>/js/util.js"></script>

</head>
<body>
<div class="topbar-wrap white">
    <div class="topbar-inner clearfix">
        <div class="topbar-logo-wrap clearfix">
            <h1 class="topbar-logo none"><a href="#" class="navbar-brand">后台管理</a></h1>
            <ul class="navbar-list clearfix">
                <li><a class="on" href="/">首页</a></li>
				<?php
				/*
                <li><a href="/">网站首页</a></li>
				*/
                ?>
            </ul>
        </div>
        <div class="top-info-wrap">
            <ul class="top-info-list clearfix">
                <li><a href="<?php MAIN_PATH;?>/manage/index?id=<?php echo $this->mid;?>"><?php echo $org_name; ?></a></li>
                <!--<li><a href="#">修改密码</a></li>-->
                <li><a href="/admin/ajaxLoginOut">退出</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="container clearfix">
