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
	<?php echo crumbs(10,'列表详细');?>
	<hr />
	<div class="panel">
		<div class="panel-head "><h4 class='icon-edit '>编辑/角色</h4></div>
		<div class="panel-body">
			<form method="post" action='' class="form-x"  />
				<input type="hidden" name='id' value='<?php echo ($role_info["id"]); ?>' />
				<table class="table"> 
					<tr>
						<td>角色名称:</td>
						<td class="text-left"><input name='title' id='_id' datatype='*'  ajaxurl=/admin/role/checkRole   class='input width-250 display-inline-block ' type='text' value='<?php echo ($role_info["title"]); ?>'  required='required'   placeholder= '角色名称'></td>
					</tr>	
					<tr> 	
						<td>角色状态:</td>
						<td style="text-align:left">
					
							<input name="status" type="radio" value=1 <?php if($role_info["status"] == 1 ): ?>checked="checked"<?php endif; ?> />启用
							<input name="status" type="radio" value=0 <?php if($role_info["status"] == 0 ): ?>checked="checked"<?php endif; ?> />禁用
							
						</td>
					
					</tr>
					<tr>
						<td colspan="2"><input value="保存" class="button bg-blue" type="submit"></td>
					</tr>

				</table>
			</form>	
		</div>
	</div>
</div>

<script type="text/javascript"> 
	$(function(){
		$(".form-x").Validform({	
			tiptype:3,
			ajaxPost:true,
			showAllError : true,
			callback:function(data){
				if(data.status==1){
					layer.msg(data.info,{time: 2000},function(){
					layer.closeAll();
					window.location.href='/Admin/Role';
				});	
				}else{
					layer.msg(data.info);
				}
			}
		});
	});
</script>


</body>
</html>