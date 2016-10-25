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
	<?php echo crumbs(28,'列表详细','store_menu');?>
	<hr />
	<!-- 搜索 -->
	<div class="search margin"> 
		<div class="search-from alert"> 
			<form method="post" action='' class="form-x form-auto">
				<div class="form-group"> 
					<div class="label"  > 
						<label>学员姓名:</label> 
					</div> 
					<div class="field">
						<input name='student_name' id='_id'     style="width:160px;" class='input' type='text' value='<?php echo ($map["student_name"]); ?>'     placeholder= '学员姓名'>	
					</div> 
			
					<div class="label"> 
						<label>家长联系方式:</label> 
					</div> 
					<div class="field">
						<input name='mobile' id='_id'     style="width:160px;" class='input' type='text' value='<?php echo ($map["mobile"]); ?>'     placeholder= '学员联系方式'>	
					</div> 
					<div class="label"> 
						<label for="password">录入时间:</label>
					</div>
					<div class="field">
						<input name='star_time' id='_date'  style='height: 34px;line-height: 20px;  '  class=' input laydate-icon'  onclick="laydate({istime: true, max:laydate.now(),format: 'YYYY-MM-DD'})"  type='text' value='<?php echo ($map["star_time"]); ?>'  placeholder= ''>~
						<input name='end_time' id='_date'  style='height: 34px;line-height: 20px; '  class=' input laydate-icon'  onclick="laydate({istime: true, max:laydate.now(),format: 'YYYY-MM-DD'})"  type='text' value='<?php echo ($map["end_time"]); ?>'  placeholder= ''>
					</div> 
				</div> 
				<div class="form-group"> 
					<div class="label"> 
						<label for="password">学员等级:</label>
					</div>
					<div class="field">
						<select  class='input' id='level_id' name='level_id'  onchange=''   style=''  ><?php  foreach($level as $key=>$val) { if(!empty($map['level_id']) && ($map['level_id'] == $key || in_array($key,$map['level_id']))) { ?><option selected="selected" value="<?php echo $key; ?>"><?php echo $val; ?></option><?php }else { ?><option value="<?php echo $key; ?>"><?php echo $val; ?></option><?php } } ?></select>	
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
				<th colspan="7" rowspan="2">基础信息</th>
				<th colspan="5">班级</th>
				<th colspan="2">经办信息</th>
				<th rowspan="2">操作</th>
			</tr>
			<tr>
			  <th>班级名称</th>
			  <th>卡种</th>
			  <th>会员卡信息</th>
			  <th>所属道馆</th>
			  <th>状态</th>
			  <th>经办人</th>
			  <th>经办时间</th>
			</tr>
			<?php if(is_array($students_list)): $i = 0; $__LIST__ = $students_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sl): $mod = ($i % 2 );++$i;?><tr>
			
				<td rowspan="1"><?php echo ++$key;?></td>
				<td rowspan="1"><?php echo ($sl["student_name"]); ?></td>
				<td rowspan="1">性别:
				<?php if($sl["student_sex"] == 1 ): ?><i class="icon-male text-blue"></i>(男)
				<?php else: ?>
				  <i class="icon-female text-dot"></i>(女)<?php endif; ?>
				</td>
				<td rowspan="1">年龄:<?php echo ($sl["student_age"]); ?></td>
				<td rowspan="1">家长手机:<?php echo ((isset($sl["parent_mobile"]) && ($sl["parent_mobile"] !== ""))?($sl["parent_mobile"]):'暂无!'); ?></td>
				<td rowspan="1">学号:<?php echo ((isset($sl["student_no_alias"]) && ($sl["student_no_alias"] !== ""))?($sl["student_no_alias"]):'暂无!'); ?></td>
				<td rowspan="1">当前段位:<b class="button border-green button-little"><?php echo $level[$sl['level_id']] ?></b> </td>
				<td>暂无课程!</td>
				<td>
					<span style="color: orange;">[<?php echo ($sl["card_type"]); ?>]</span>
				</td>
				<td><?php echo ($sl['card_info']['info']); ?></td>
				<td><?php echo ($sl["store_name"]); ?></td>
				<td>
					<?php if($sl["student_status"] == 1 ): ?><span class="text-green">正常</span>
					<?php else: ?>
					<span class="text-dot">冻结</span><?php endif; ?>
				</td>
	
				<td rowspan="1"><?php echo ($sl["user_name"]); ?></td>
				<td rowspan="1">录入时间：<?php echo (date('Y-m-d H:i:s',$sl["operator_time"])); ?></td>
				<td rowspan="1">-x-x-x-
					<!-- <a class="button border-blue button-little" href="/Admin/Students/edit?student_id=<?php echo ($sl["student_id"]); ?>">编辑</a> 
					<a class="button border-red button-little " href="javascript:;" onclick="itemDel(<?php echo ($sl["student_id"]); ?>,'/Admin/Students/del')">删除</a> -->
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