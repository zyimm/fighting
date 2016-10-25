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
<div class="pbadgeding table-responsive">
	<?php echo crumbs(31,'列表详细','store_menu');?>
	<hr />
	<div class="alert margin-bottom"><a href="/Store/Badge/add" class="button border-main icon-plus-square">&nbsp;新增</a></div>
	<table class="table table-bordered table-hover table-striped">
		<tbody> 
			<tr>
				<th>徽章名称</th>
				<th>徽章图标</th>
				<th>徽章图标(未点亮)</th>
				<th>徽章类型</th>
				<th>是否平台默认</th>
				<th>是否显示</th>
				<th>相关操作</th>
			</tr>
			<?php if(is_array($badge_list)): $i = 0; $__LIST__ = $badge_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$badge): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($badge["badge_name"]); ?></td>
				<td><img src="<?php echo ($badge["badge_icon"]); ?>" alt="" width=40 height=40 onclick="showImage('<?php echo ($badge["badge_icon"]); ?>')" /></td>
				<td><img src="<?php echo ($badge["badge_icon_disable"]); ?>" alt="" width=40 height=40 onclick="showImage('<?php echo ($badge["badge_icon_disable"]); ?>')" /></td>
				<td> 
				<?php if($badge["badge_type"] == 1 ): ?>训练徽章<?php endif; ?>	
				<?php if($badge["badge_type"] == 2): ?>赛事徽章<?php endif; ?>	
				<?php if($badge["badge_type"] == 3): ?>活动徽章<?php endif; ?>
				</td>
				
				<td>
					<?php if($badge["is_sys"] == 1): ?>是
					<?php else: ?>
						否<?php endif; ?>
				</td>
				<td>
				<?php if($badge["status"] == 1 ): ?><tag class="badge bg-main">显示</tag>
				<?php else: ?>
					<tag class="badge bg-dot">隐藏</tag><?php endif; ?>
				</td>
				<td>
					<a href="/Store/Badge/edit?badge_id=<?php echo ($badge["badge_id"]); ?>" class="button bg-blue button-little">编辑</a>
					<a href="javascript:void(0)" onclick="itemDel(<?php echo ($badge["badge_id"]); ?>);" class="button bg-dot button-little">删除</a>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			
		</tbody>
           
	</table>
	<div class="admin-page">  
		<?php echo ($page_show); ?>
	</div>
</div>
</body>
</html>