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
	<?php echo crumbs(12,'列表详细','brand_menu');?>
	<div class="alert"> 
		<a href="/Brand/Store/addAdmin" class="button border-main icon-plus-square" type="submit"> 添加道馆管理员</a>
	</div>
	<hr />
	<table class="table table-bordered table-hover table-striped radius ">
		<tbody>
			<tr>
				<th>道馆管理员序列</th>
				<th>用户名</th>
				<th>所属道馆</th>
				<th>状态</th>
				<th>邮箱</th>
				<th>上一次登录时间</th>
				<th>管理操作</th>
			</tr>
			<?php if(is_array($admin_list)): $i = 0; $__LIST__ = $admin_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ++$key;?></td>
				<td><?php echo ($v["user_name"]); ?></td>
				<td><?php echo ($v["store_name"]); ?></td>
				<td> 
					<?php if($v["status"] == 1 ): ?><span class="text-green">正常</span>
					<?php else: ?>
						<span class="text-dot">关闭</span><?php endif; ?>
				</td>
				<td><?php echo ((isset($v["email"]) && ($v["email"] !== ""))?($v["email"]):'暂无!'); ?></td>
				<td><?php echo (date('Y-m-d H:i:s',$v["login_time"])); ?></td>
				<td>
					<a class="button button-little border-main icon-edit" href='/Brand/Store/editAdmin.html?admin_id=<?php echo ($v["id"]); ?>' >编辑</a>
					<a class="button button-little border-dot icon-trash" href="javascript:void(0)" onclick="itemDel(<?php echo ($v["id"]); ?>,'/Brand/Store/delAdmin');" >删除</a>
				</td>	
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>
</div>
</body>
</html>