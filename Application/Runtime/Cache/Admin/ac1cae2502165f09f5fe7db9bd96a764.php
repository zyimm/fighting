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

<style type="text/css"> 
	.table-over td{padding:4px;border:none;border-bottom:solid 1px #ddd; }
</style>

<script type="text/javascript" src="/assets/Plugin/Laydate/laydate.js"></script>
<div class="padding table-responsive">
	<?php echo crumbs(26,'列表详细','store_menu');?>
	<hr />
	<!-- 搜索 -->
	<div class="search margin"> 
		<div class="search-from alert"> 
			<form method="post" action='' class="form-x form-auto">
				<div class="form-group"> 
					<div class="label"  > 
						<label>教练姓名:</label> 
					</div> 
					<div class="field">
						<input name='coach_name' id='_id'     style="width:160px;" class='input' type='text' value='<?php echo ($map["coach_name"]); ?>'     placeholder= '教练姓名'>	
					</div> 
			
					<div class="label"> 
						<label>教练联系方式:</label> 
					</div> 
					<div class="field">
						<input name='mobile' id='_id'     style="width:160px;" class='input' type='text' value='<?php echo ($map["mobile"]); ?>'     placeholder= '教练联系方式'>	
					</div> 
					<div class="label"> 
						<label for="password">录入时间:</label>
					</div>
					<div class="field">
						<input name='star_time' id='_date'  style='height: 34px;line-height: 20px;  '  class=' input laydate-icon'  onclick="laydate({istime: true, max:laydate.now(),format: 'YYYY-MM-DD'})"  type='text' value='<?php echo ($map["star_time"]); ?>'  placeholder= ''>~
						<input name='end_time' id='_date'  style='height: 34px;line-height: 20px; '  class=' input laydate-icon'  onclick="laydate({istime: true, max:laydate.now(),format: 'YYYY-MM-DD'})"  type='text' value='<?php echo ($map["end_time"]); ?>'  placeholder= ''>
					</div> 
				</div> 
				
			
				<div class="form-button">
					<button class="button border-main  icon-search" type="submit"> 搜索</button> 
			
				</div> 	
			</form>
		</div>
	</div>
	<table class="table table-bordered table-hover table-striped radius ">
		<tbody>
			<tr>
				<th colspan="5" rowspan="2">基础信息</th>
				<th colspan="4">所教班级</th>
				<th colspan="2">相关信息</th>
				<th rowspan="2">操作</th>
			</tr>
			<tr>
			  <th>所教班级</th>
			  <th>上课时间</th>
			  <th>班级人数</th>
			  <th>已报名人数</th>
			  <th>是否绑定道馆</th>
			  <th>注册时间</th>
			</tr>
			<?php if(is_array($coachs_list)): $i = 0; $__LIST__ = $coachs_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sl): $mod = ($i % 2 );++$i;?><tr>
				<td rowspan="1"><?php echo ++$key;?></td>
				<td rowspan="1"><?php echo ($sl["nick_name"]); ?></td>
				<td rowspan="1">性别:
				<?php if($sl["sex"] == 1 ): ?><i class="icon-male text-blue"></i>(男)
				<?php else: ?>
				  <i class="icon-female text-dot"></i>(女)<?php endif; ?>
				</td>
				<td rowspan="1">年龄:<?php echo ($sl["age"]); ?></td>
				<td rowspan="1">联系手机:<?php echo ((isset($sl["mobile"]) && ($sl["mobile"] !== ""))?($sl["mobile"]):'暂无!'); ?></td>
				
				 
				<td>暂无课程!</td>
				<td>周一到周五 下午三点</td>
				<td>30/人</td>
				<td>15/人</td>
				
				
				
				
	
				<td rowspan="1">
					<?php if($sl["relation_status"] == 1 ): ?><span class="text-green">已绑定道馆</span>
					<?php else: ?>
					<span class="text-dot">未绑定当前道馆</span><?php endif; ?>
				</td>
				<td rowspan="1">录入时间：<?php echo (date('Y-m-d H:i:s',$sl["reg_time"])); ?></td>
				<td rowspan="1">
					<?php if($sl["relation_status"] == 1 ): ?><a class="button border-blue button-little" href="#" onclick="unbing(<?php echo ($sl["coach_id"]); ?>)">与他/她解约</a> 
					<?php else: ?>
					<a class="button border-blue button-little" href="#" onclick="bing(<?php echo ($sl["coach_id"]); ?>)">签约道馆</a><?php endif; ?>
				</td>
			 
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
      
           
        </tbody>
	</table>
	<div class="admin-page">  
		<?php echo ($page_show); ?>
	</div>
</div>

<script type="text/javascript"> 
	var unbing=function(id){
		$.post('/Admin/Coachs/unbing',{coach_id:id},function(data){
			if(data.status==1){
					layer.msg(data.info,{time: 3000,icon:1},function(){
						//layer.closeAll();
						window.location.reload();
				});	
			}else{
				layer.msg(data.info,{icon:2});
			}
		
		},'json');
	};
	var bing=function(id){
		$.post('/Admin/Coachs/bing',{coach_id:id},function(data){
			if(data.status==1){
					layer.msg(data.info,{time: 3000,icon:1},function(){
						//layer.closeAll();
						window.location.reload();
				});	
			}else{
				layer.msg(data.info,{icon:2});
			}
		
		},'json');
	};
</script>
</body>
</html>