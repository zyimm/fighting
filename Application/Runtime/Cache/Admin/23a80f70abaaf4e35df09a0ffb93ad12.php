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
	<?php echo crumbs(18,'列表详细');?>
	<hr />
	<div class="alert margin-bottom"><a href="/Admin/Ad/add" class="button border-main icon-plus-square">&nbsp;新增</a></div>
	<table class="table table-bordered table-hover table-striped">
		<tbody> 
			<tr>
				<th>标题</th>
				<th>缩略图</th>
				<th>是否跳转</th>
				<th>更新时间</th>
				<th>是否显示</th>
				<th>相关操作</th>
			</tr>
			<?php if(is_array($ad_list)): $i = 0; $__LIST__ = $ad_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ad): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($ad["ad_title"]); ?></td>
				<td><img src="<?php echo ($ad["ad_image"]); ?>" alt="" width=40 height=40 onclick="showImage('<?php echo ($ad["ad_image"]); ?>')" /></td>
				<td> 
				<?php if($ad["ad_type"] == 1 ): ?>跳转
				<?php else: ?>
					不跳转<?php endif; ?>
				</td>
				
				<td><?php echo (date('Y-m-d H:i:s',$ad["time"])); ?></td>
				<td>
				<?php if($ad["status"] == 1 ): ?><tag class="badge bg-main">显示</tag>
				<?php else: ?>
					<tag class="badge bg-dot">隐藏</tag><?php endif; ?>
				</td>
				<td>
					<a href="/Admin/Ad/edit?ad_id=<?php echo ($ad["ad_id"]); ?>" class="button bg-blue button-little">编辑</a>
					<a href="javascript:void(0)" onclick="itemDel(<?php echo ($ad["ad_id"]); ?>,'/Admin/Ad/del');" class="button bg-dot button-little">删除</a>
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