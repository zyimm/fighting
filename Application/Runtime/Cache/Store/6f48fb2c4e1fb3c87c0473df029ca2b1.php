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
	<?php echo crumbs(37,'列表详细','store_menu');?>
	<hr />
	
	<table class="table table-bordered table-hover table-striped">
		<tbody> 
			<tr>
				<th>活动标题</th>
				<th>活动海报图</th>
				<th>活动时间</th>
				<th>报名时间</th>
				<th>已报名人数</th>
				<th>额定人数</th>
				<th>是否开启</th>
				<th>相关操作</th>
			</tr>
			<?php if(is_array($activity_list)): $i = 0; $__LIST__ = $activity_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$activity): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($activity["activity_title"]); ?></td>
				<td><img src="<?php echo ($activity["activity_image"]); ?>" alt="" width=40 height=40 onclick="showImage('<?php echo ($activity["activity_image"]); ?>')" /></td>
				<td><?php echo ($activity["activity_time"]); ?></td>
				<td><?php echo ($activity["activity_apply_time"]); ?></td>
				<td><?php echo ($activity["apply_num"]); ?>/人</td>
				<td><?php echo ($activity["activity_nums"]); ?>/人</td>
				<td>
				<?php if($activity["status"] == 1 ): ?><tag class="badge bg-main">开启</tag>
				<?php else: ?>
					<tag class="badge bg-dot">关闭</tag><?php endif; ?>
				</td>
				<td>
					<a href="/Store/Activity/edit?activity_id=<?php echo ($activity["activity_id"]); ?>" class="button bg-blue button-little">编辑</a>
					<?php if($activity["status"] == 1 ): ?><a href="/Store/Activity/apply?activity_id=<?php echo ($activity["activity_id"]); ?>" class="button bg-main button-little">查看报名列表</a>
					<?php else: ?>
					<a href="javascript:alert('该活动处于关闭状态!')" class="button bg-gray button-little">查看报名列表</a><?php endif; ?>
					<a href="javascript:void(0)" onclick="itemDel(<?php echo ($activity["activity_id"]); ?>,'/Store/Activity/del');" class="button bg-dot button-little">删除</a>
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