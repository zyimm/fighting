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
						<label>用户姓名:</label> 
					</div> 
					<div class="field">
						<input name='nick_name' id='_id'     style="width:160px;" class='input' type='text' value='<?php echo ($map["nick_name"]); ?>'     placeholder= '用户姓名'>	
					</div> 
			
					<div class="label"> 
						<label>用户联系方式:</label> 
					</div> 
					<div class="field">
						<input name='mobile' id='_id'     style="width:160px;" class='input' type='text' value='<?php echo ($map["mobile"]); ?>'     placeholder= '用户联系方式'>	
					</div> 
					<div class="label"> 
						<label for="password">注册时间:</label>
					</div>
					<div class="field">
						<input name='star_time' id='_date'  style='height: 34px;line-height: 20px;  '  class=' input laydate-icon'  onclick="laydate({istime: true, max:laydate.now(),format: 'YYYY-MM-DD'})"  type='text' value='<?php echo ($map["star_time"]); ?>'  placeholder= ''>~
						<input name='end_time' id='_date'  style='height: 34px;line-height: 20px; '  class=' input laydate-icon'  onclick="laydate({istime: true, max:laydate.now(),format: 'YYYY-MM-DD'})"  type='text' value='<?php echo ($map["end_time"]); ?>'  placeholder= ''>
					</div> 
				</div> 
				<div class="form-group"> 
					<div class="label"> 
						<label for="password">是否绑定学生:</label>
					</div>
					<div class="field">
						<select name="is_bing"  class="input">
							<option value="0">未绑定</option>
							<option value="1">已绑定</option>
						</select>
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
				<th colspan="4">绑定学生信息</th>
				<th colspan="2">注册信息</th>
				<th rowspan="2">操作</th>
			</tr>
			<tr>
			  <th>学生姓名</th>
			  <th>学生年龄</th>
			  <th>学生性别</th>
			  <th>所在道馆</th>
			  <th>注册时间</th>
			  <th>最后登录时间</th>
			</tr>
			<?php if(is_array($member_list)): $i = 0; $__LIST__ = $member_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ml): $mod = ($i % 2 );++$i;?><tr>
			
				<td rowspan="1"><?php echo ++$key;?></td>
				<td rowspan="1"><?php echo ($ml["nick_name"]); ?></td>
				<td rowspan="1">性别:
				<?php if($ml["sex"] == 1 ): ?><i class="icon-male text-blue"></i>(男)
				<?php else: ?>
				  <i class="icon-female text-dot"></i>(女)<?php endif; ?>
				</td>
				<td rowspan="1">年龄:<?php echo ($ml["age"]); ?></td>
				<td rowspan="1">手机:<?php echo ((isset($ml["mobile"]) && ($ml["mobile"] !== ""))?($ml["mobile"]):'暂无!'); ?></td>
		
				<td><?php echo ((isset($ml["student_name"]) && ($ml["student_name"] !== ""))?($ml["student_name"]):'暂无!'); ?></td>
				<td>
					<?php echo ((isset($ml["student_age"]) && ($ml["student_age"] !== ""))?($ml["student_age"]):'暂无!'); ?>
				</td>
				<td>
					<?php if($ml["student_sex"] == 1 ): ?><i class="icon-male text-blue"></i>(男)
					<?php else: ?>
					<i class="icon-female text-dot"></i>(女)<?php endif; ?>
				</td>
				<td><?php echo ((isset($ml["store_name"]) && ($ml["store_name"] !== ""))?($ml["store_name"]):'暂无!'); ?></td>
				
	
				<td rowspan="1"><?php echo (date('Y-m-d H:i:s',$ml["reg_time"])); ?></td>
				<td rowspan="1">登录时间：<?php echo (date('Y-m-d H:i:s',$ml["last_login_time"])); ?></td>
				<td rowspan="1">
					-x-x-x-
					<!-- <a class="button border-blue button-little" href="/Admin/Members/edit?id=<?php echo ($ml["id"]); ?>">编辑</a> 
					<a class="button border-red button-little " href="javascript:;" onclick="itemDel(<?php echo ($ml["id"]); ?>,'/Admin/Members/del')">删除</a> -->
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