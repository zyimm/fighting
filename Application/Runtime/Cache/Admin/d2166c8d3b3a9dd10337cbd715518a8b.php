<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="renderer" content="webkit">
	<title>Let's Fighting|后台管理中心</title>
    <link rel="stylesheet" href="/assets/Common/Css/pintuer.css" />
    <link rel="stylesheet" href="/assets/Admin/Css/admin.css" />
    <script type="text/javascript" src="/assets/Common/Js/jquery.js"></script>
    <script type="text/javascript" src="/assets/Common/Js/pintuer.js"></script>
    <script type="text/javascript" src="/assets/Admin/js/Validform.min.js"></script>
	<script type="text/javascript" src="/assets/Plugin/Layer/layer.js"></script>
	<script type="text/javascript" src="/assets/Admin/js/admin.js"></script>

</head>
<body>
<div class="padding">
	<div class="panel x5 margin-large-right">
		<div class="panel-head">
			<h4><i class="icon-user text-red"></i>&nbsp;个人信息</h4>
		</div>
		<div class="panel-body">
			<p>您好，<?php echo ($admin_info["user_name"]); ?></p>
			<p>所属角色: <b><?php echo ($admin_info["role_name"]); ?></b> </p>
			<p>当前平台: <b>平台终端管理</b> </p>
			<p>上次登录时间：<?php echo date('Y-m-d H:i:s');?> </p>
			<p>上次登录IP：<?php echo $_SERVER['REMOTE_ADDR'];?> </p>
		</div>
	</div>
	<div class="panel x5">
		<div class="panel-head">
			<h4><i class="icon-cogs text-main"></i>&nbsp;系统信息</h4>
		</div>
		<div class="panel-body">
			<p>程序版本：<a href="http://www.zyimm.com">V.2016</a>05.10 </p>
			<p>操作系统：<?php echo PHP_OS;?></p>
			<p>服务器名称：<?php echo $_SERVER['SERVER_NAME'];?></p>
			<p> PHP版本：<?php echo PHP_VERSION;?></p>
		</div>
	</div>
	
</div>


</body>
</html>