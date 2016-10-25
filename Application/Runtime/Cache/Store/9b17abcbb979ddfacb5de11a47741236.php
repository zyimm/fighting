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

<script type="text/javascript" src="/assets/Plugin/Laydate/laydate.js"></script>
<div class="padding table-responsive">
	<?php echo crumbs(20,'列表详细','store_menu');?>
	<hr />
	<div class="alert"><a href="/Store/Role/addAdmin" class="button bg-main icon-plus-square">&nbsp;新增平台管理员</a></div>
	<table class="table table-hover table-striped"> 
		<tr>
			<th>管理员序列</th>
			<th>管理员名称</th>
			<th>所属角色</th>
			<th>管理员状态</th>
			<th>操作</th>
		</tr>
		<?php if(is_array($admin_list)): $i = 0; $__LIST__ = $admin_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$al): $mod = ($i % 2 );++$i;?><tr>
			<td><?php echo ++$key;?></td>
			<td><?php echo ($al["user_name"]); ?></td>
			<td><?php echo ($al["role_name"]); ?></td>
			<?php if($al["status"] == 1 ): ?><td><b class="text-main">正常</b></td>
			<?php else: ?>
			<td><b class="text-dot">禁用</b></td><?php endif; ?>
			<td>
			<?php if($al["role_id"] == 1 ): ?>-x-x-x-
			<?php else: ?>
				
				<a href="/Store/Role/editAdmin?admin_id=<?php echo ($al["id"]); ?>" class="button bg-blue button-little">修改</a>
				<a href="/Store/Role/" class="button bg-dot  button-little">禁用</a><?php endif; ?>			
		
			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		
	</table>	
</div>
</body>
</html>