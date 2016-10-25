<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<title>管理员登录｜Let's fighting后台管理</title>

<link rel="stylesheet" href="/assets/pintuer/pintuer.css" />

 <style type="text/css">
.background {
	position: absolute;
	right: 0px;
	top: 0px;
	bottom: 0px;
	left: 0px;
	background: #1685d0;
	overflow: hidden;
	width:100%;
	height:100%;
}

.brand {
	width: 100%;
	text-align: center;
}

.brand a {
	height: 65px;
	font-size: 50px;
	text-align: center;
}

.brand a:hover {
	text-decoration: none;
}

.brand img.logo {
	width: 100%;
	max-height: 80px;
	padding: 0 20px;
	text-align: center;
}

.panel-lite {
	margin: 5% auto;
	max-width: 400px;
	background: #fff;
	padding: 45px 30px;
	border-radius: 4px;
	box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
	position: relative;
}

.panel-lite h4 {
	font-weight: 400;
	font-size: 24px;
	text-align: center;
	color: #1685d0;
	margin: 20px auto 35px;
}

.panel-lite a.link {
	display: inline-block;
	margin-top: 25px;
	text-decoration: none;
	color: #1685d0;
	font-size: 14px;
}

.form-group {
	position: relative;
	font-size: 15px;
	color: #666;
}

.form-group+.form-group {
	margin-top: 30px;
}

.form-group .form-label {
	position: absolute;
	z-index: 1;
	left: 0;
	top: 5px;
	-webkit-transition: 0.3s;
	transition: 0.3s;
}

.form-group .form-control {
	width: 100%;
	position: relative;
	z-index: 3;
	height: 35px;
	background: none;
	border: none;
	padding: 5px 0;
	-webkit-transition: 0.3s;
	transition: 0.3s;
	border-bottom: 1px solid #777;
	box-shadow: none;
	border-radius: 0;
}

.form-group .form-control:invalid {
	outline: none;
}

.form-group .form-control:focus, .form-group .form-control:valid {
	outline: none;
	color: #1685d0;
	box-shadow: 0 1px #1685d0;
	border-color: #1685d0;
}

.form-group .form-control:focus+.form-label, .form-group .form-control:valid+.form-label
	{
	font-size: 12px;
	-ms-transform: translateY(-15px);
	-webkit-transform: translateY(-15px);
	transform: translateY(-15px);
}

.floating-btn {
	background: #1685d0;
	width: 60px;
	height: 60px;
	border-radius: 50%;
	color: #fff;
	font-size: 32px;
	border: none;
	position: absolute;
	margin: auto;
	-webkit-transition: 0.3s;
	transition: 0.3s;
	box-shadow: 1px 0px 0px rgba(0, 0, 0, 0.3) inset;
	margin: auto;
	right: -30px;
	bottom: 90px;
	cursor: pointer;
}

.floating-btn:hover {
	box-shadow: 0 0 0 rgba(0, 0, 0, 0.3) inset, 0 3px 6px
		rgba(0, 0, 0, 0.16), 0 5px 11px rgba(0, 0, 0, 0.23);
}

.floating-btn:hover .icon-arrow {
	-ms-transform: rotate(45deg) scale(1.2);
	-webkit-transform: rotate(45deg) scale(1.2);
	transform: rotate(45deg) scale(1.2);
}

.floating-btn:focus, .floating-btn:active {
	outline: none;
}

.icon-arrow {
	position: relative;
	width: 13px;
	height: 13px;
	border-right: 3px solid #fff;
	border-top: 3px solid #fff;
	display: block;
	-ms-transform: rotate(45deg);
	-webkit-transform: rotate(45deg);
	transform: rotate(45deg);
	margin: auto;
	-webkit-transition: 0.3s;
	transition: 0.3s;
}

.icon-arrow:after {
	content: '';
	position: absolute;
	width: 18px;
	height: 3px;
	background: #fff;
	left: -5px;
	top: 5px;
	-ms-transform: rotate(-45deg);
	-webkit-transform: rotate(-45deg);
	transform: rotate(-45deg);
}

.verifyimg-box {
	padding: 0;
	border: 0;
}

.verifyimg-box .verifyimg {
	cursor: pointer;
	width: 130px;
	height: 41px;
	margin-top: -6px;
	border-bottom: 1px solid #777;
}

@media ( max-width : 768px) {
	.background {
		display: none;
	}
	.panel-lite {
		box-shadow: none;
		border-color: #fff;
	}
}
.verifyimg {position:absolute; right:0px; top:-16px;cursor:pointer;z-index:10px;}

</style>

</head>

<body>
	<div class="container">
		<div id="particles-js" class="background"></div>
		<div class="panel-lite">
			<div class="brand">
				
				<a href="/Store/Login" target="_blank"> 
					<b> 
						<span class="open" style="color: #a5aeb4;">Let's</span> 
						<span class="cmf"  style="color: #3fa9f5;">Fighting</span>
					</b>
				</a>
		
			</div>
			<h4>道馆终端管理登录</h4>
			<form class="login-form" action="/Store/Login/doLogin" method="post">
				<div class="form-group">
					<input required="required" class="form-control" name="user_name" autocomplete="off" type="text" />
					<label class="form-label">账　号</label>
				</div>
				<div class="form-group">
					<input required="required" class="form-control" name="password" type="password">
					<label class="form-label">密　码</label>
				</div>
				<div class="form-group">
		
					<input required="required" class="form-control" name="verify" type="text">
					<label class="form-label">验证码</label>
					<img class="verifyimg" alt="验证码" width=130 height=40 src="/admin/login/verify.html" title="点击刷新" onclick="this.src='/admin/login/verify.html?t='+Math.random()" />

				</div>
			
				
				<button type="submit" class="floating-btn ajax-post hidden-xs" target-form="login-form">
					<i class="icon-arrow"></i>
				</button>
			</form>
		</div>
	</div>

	<script type="text/javascript" src='/assets/pintuer/jquery.js'></script>
	<script type="text/javascript" src='/assets/pintuer/pintuer.js'></script>
	<script src="/assets/libs/particles/particles.min.js"></script>
			<script type="text/javascript">
				$(function() {
					//背景粒子效果
					particlesJS('particles-js', {
						particles : {
							color : '#46BCF3',
							shape : 'circle', // "circle", "edge" or "triangle"
							opacity : 1,
							size : 2,
							size_random : true,
							nb : 200,
							line_linked : {
								enable_auto : true,
								distance : 100,
								color : '#46BCF3',
								opacity : .8,
								width : 1,
								condensed_mode : {
									enable : false,
									rotateX : 600,
									rotateY : 600
								}
							},
							anim : {
								enable : true,
								speed : 1
							}
						},
						interactivity : {
							enable : true,
							mouse : {
								distance : 250
							},
							detect_on : 'canvas', // "canvas" or "window"
							mode : 'grab',
							line_linked : {
								opacity : .5
							},
							events : {
								onclick : {
									enable : true,
									mode : 'push', // "push" or "remove" (particles)
									nb : 4
								}
							}
						},
						retina_detect : true
					});
				});
			</script>
</body>


</html>