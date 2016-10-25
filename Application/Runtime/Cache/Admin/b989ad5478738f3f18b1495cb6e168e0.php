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
	<?php echo crumbs(25,'详细');?>
	<div class="alert"> 
		<a href="/Admin/Brand/add" class="button border-main icon-plus-square" type="submit"> 添加品牌</a>
	</div>
	<hr />
	<table class="table table-bordered table-hover table-striped radius ">
		<tbody>
			<tr>
				<th>品牌ID</th>
				<th>品牌名称</th>
				<th>更新时间</th>
				<th>管理操作</th>
			</tr>
			<?php if(is_array($brand)): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($v["brand_id"]); ?></td>
				<td><?php echo ($v["brand_name"]); ?></td>
				<td><?php echo (date('Y-m-d H:i:s',$v["time"])); ?></td>
				<td><a class="button button-little border-main icon-edit" href='/admin/brand/edit.html?brand_id=<?php echo ($v["brand_id"]); ?>' >编辑</a></td>	
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>
</div>
</body>
</html>