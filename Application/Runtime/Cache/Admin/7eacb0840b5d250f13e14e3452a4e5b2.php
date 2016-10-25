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
	<?php echo crumbs(17,'列表详细','store_menu');?>
	<hr />
	<div class="alert margin-bottom"><a href="/Admin/Article/add" class="button border-main icon-plus-square">&nbsp;新增</a></div>
	<table class="table table-bordered table-hover table-striped">
		<tbody> 
			<tr>
				<th>资讯标题</th>
				<th>资讯缩略图</th>
				<th>来源</th>
				<th>更新时间</th>
				<th>是否显示</th>
				<th>所属道馆</th>
				<th>相关操作</th>
			</tr>
			<?php if(is_array($article_list)): $i = 0; $__LIST__ = $article_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$article): $mod = ($i % 2 );++$i;?><tr>
				<td><?php echo ($article["article_title"]); ?></td>
				<td><img src="<?php echo ($article["article_image"]); ?>" alt="" width=40 height=40 onclick="showImage('<?php echo ($article["article_image"]); ?>')" /></td>
				<td> <?php echo ((isset($article["article_source"]) && ($article["article_source"] !== ""))?($article["article_source"]):'暂无来源'); ?></td>
				
				<td><?php echo (date('Y-m-d H:i:s',$article["time"])); ?></td>
				<td>
				<?php if($article["status"] == 1 ): ?><tag class="badge bg-main">显示</tag>
				<?php else: ?>
					<tag class="badge bg-dot">隐藏</tag><?php endif; ?>
				</td>
				<td><?php echo get_store_name($article['store_id']);?></td>
				<td>
					<a href="/Admin/Article/edit?article_id=<?php echo ($article["article_id"]); ?>" class="button bg-blue button-little">编辑</a>
					<a href="javascript:void(0)" onclick="itemDel(<?php echo ($article["article_id"]); ?>,'/Admin/Article/del');" class="button bg-dot button-little">删除</a>
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