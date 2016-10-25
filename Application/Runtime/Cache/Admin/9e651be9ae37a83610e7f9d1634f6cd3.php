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
<div class="padding table-responsive">
	<?php echo crumbs(8);?>
	<hr />
	<table class="table table-bordered table-hover table-striped radius ">
		<tbody>
			<tr>
				<th>版本号</th>
				<th>升级地址</th>
				<th>类型</th>
				<th>版本code</th>
				<th>是否强制更新</th>
				<th>管理操作</th>
			</tr>
			<?php if(is_array($version)): $i = 0; $__LIST__ = $version;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($v["version"]); ?></td>
				<td><?php echo ($v["url"]); ?></td>
				<?php if($v["type"] == 1 ): ?><td><i class="icon-android text-main text-big"></i>Android(安卓)</td>
				<?php else: ?>
				<td><i class="icon-apple text-main text-big"></i>ios(苹果)</td><?php endif; ?>
				<td><?php echo ($v["code"]); ?></td>
				<?php if($v["is_update"] == 1 ): ?><td>是</td>
				<?php else: ?>
				<td>否</td><?php endif; ?>
				<td><a  href='/admin/Setting/appVersion?id=<?php echo ($v["id"]); ?>' class="button button-little border-main icon-edit">编辑</a></td>	
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>
</div>
</body>
</html>