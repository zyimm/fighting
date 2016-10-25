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
	<?php echo crumbs(24,'列表详细');?>
	<hr />
	<div> 
		<table class="table table-hover table-striped"> 
			<tr>
				<th>ID</th>
				<th>道馆名称</th>
				<th>道馆品牌</th>
				<th>道馆扫描二维码</th>
				<th>状态</th>
				<th>注册时间</th>
				<th>操作</th>
			</tr>
			<?php if(is_array($store_list)): $i = 0; $__LIST__ = $store_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sl): $mod = ($i % 2 );++$i;?><tr> 
				<td><?php echo ($sl["store_id"]); ?></td>
				<td><?php echo ($sl["store_name"]); ?></td>
				<td><?php echo ($sl["brand_name"]); ?></td>
				<td><img src="/Admin/Store/getStoreCode?store_id=<?php echo ($sl["store_id"]); ?>" alt="" width=60 height=60  onclick="showImage('/Admin/Store/getStoreCode?store_id=<?php echo ($sl["store_id"]); ?>')" style='cursor:pointer' /></td>
				<td> 
					<?php if($sl["store_status"] == 1 ): ?><b class="text-main">正常</b>
					<?php else: ?>
						<b class="text-dot">闭馆</b><?php endif; ?>
				</td>
				<td><?php echo (date('Y-m-d H:i:s',$sl["time"])); ?></td>
				<td> 
					<a href="/Admin/Store/edit?store_id=<?php echo ($sl["store_id"]); ?>" class='button border-blue button-little'>修改</a>
					<a href="/Admin/Store/del?store_id=<?php echo ($sl["store_id"]); ?>" class='button border-dot button-little '>删除</a>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			
		</table>
	</div>
</div>
<script type="text/javascript">

	
	/**/
</script>
</body>
</html>