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
	<?php echo crumbs(10,'权限更改');?>
	<hr />
	<div class="alert"><a href="/Store/Role/add" class="button bg-main icon-plus-square">&nbsp;新增角色</a></div>
	<table class="table table-hover table-striped"> 
		<tr>
			<th>ROLE_ID</th>
			<th>角色名称</th>
			<th>角色状态</th>
			<th>操作</th>
		</tr>
		<?php if(is_array($role_list)): $i = 0; $__LIST__ = $role_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rl): $mod = ($i % 2 );++$i;?><tr>
			<td><?php echo ($rl["id"]); ?></td>
			<td><?php echo ($rl["title"]); ?></td>
			<?php if($rl["status"] == 1 ): ?><td><b class="text-main">正常</b></td>
			<?php else: ?>
			<td><b class="text-dot">禁用</b></td><?php endif; ?>
			<td>
			<?php if($rl["id"] == 1 ): ?>-x-x-x-
			<?php else: ?>
				<a href="/Store/Role/edit?role_id=<?php echo ($rl["id"]); ?>" class="button bg-blue button-little">修改</a>
				<a href="/Store/Role/roleRule?role_id=<?php echo ($rl["id"]); ?>" class="button bg-green  button-little">权限修改</a><?php endif; ?>	
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		
	</table>
	
</div>
</body>
</html>