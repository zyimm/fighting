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
	<?php echo crumbs(44,'列表详细','store_menu');?>
	<hr />
	
	<table class="table table-bordered table-hover table-striped">
		<tbody> 
			<tr>
				<th>考试标题</th>
				<th>缩略图</th>
				<th>考试时间</th>
				<th>报名时间</th>
				<th>已报名人数</th>
				<th>是否开启</th>
				<th>相关操作</th>
			</tr>
			<?php if(is_array($exam_list)): $i = 0; $__LIST__ = $exam_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$exam): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($exam["exam_title"]); ?></td>
				<td><img src="<?php echo ($exam["exam_image"]); ?>" alt="" width=40 height=40 onclick="showImage('<?php echo ($exam["exam_image"]); ?>')" /></td>
				<td><?php echo ($exam["exam_time"]); ?></td>
				<td><?php echo ($exam["exam_apply_time"]); ?></td>
				<td><?php echo ($exam["apply_num"]); ?>/人</td>
				
				<td>
				<?php if($exam["status"] == 1 ): ?><tag class="badge bg-main">开启</tag>
				<?php else: ?>
					<tag class="badge bg-dot">关闭</tag><?php endif; ?>
				</td>
				<td>
					<a href="/Store/Exam/edit?exam_id=<?php echo ($exam["exam_id"]); ?>" class="button bg-blue button-little">编辑</a>
					<?php if($exam["status"] == 1 ): ?><a href="/Store/Exam/apply?exam_id=<?php echo ($exam["exam_id"]); ?>" class="button bg-main button-little">查看报名列表</a>
					<?php else: ?>
					<a href="javascript:alert('该考试处于关闭状态!')" class="button bg-gray button-little">查看报名列表</a><?php endif; ?>
					<a href="javascript:void(0)" onclick="itemDel(<?php echo ($exam["exam_id"]); ?>,'/Store/Exam/del');" class="button bg-dot button-little">删除</a>
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