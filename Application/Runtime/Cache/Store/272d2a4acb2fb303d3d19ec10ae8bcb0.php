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
	<?php echo crumbs(34,'修改详细','store_menu');?>
	<hr />
	<div class="panel">
		<div class="panel-head "><h4 class='icon-edit '>信息修改</h4></div>
		<div class="panel-body">
			<form method="post" action='' class="form-x"  />
				<input type="hidden" name='id' value='<?php echo ($admin_info["id"]); ?>' />
				<table class="table"> 
				
					<tr>
						<td>管理员名称:</td>
						<td style="text-align:left"><?php echo ($admin_info["user_name"]); ?></td>
					</tr>	
				
					<tr>
						<td>管理员邮箱:</td>
						<td style="text-align:left">
							<input name='email' id='_id' datatype='e'     class='input width-250 display-inline-block ' type='text' value='<?php echo ($admin_info["email"]); ?>'  required='required'   placeholder= '管理员邮箱'></td>
						</td>
					</tr>
					<tr>
						<td>所属角色:</td>
						<td style="text-align:left"><?php echo ($admin_info["role_name"]); ?></td>
					</tr>	
					<tr> 
						<td>原先密码:</td>
						<td style="text-align:left">
							<input name='password' id='_id' datatype='*'     class='input width-250 display-inline-block ' type='password' value=''  required='required'   placeholder= '角色密码'></td>
						</td>
					</tr>
					<tr> 
						<td>新密码:</td>
						<td style="text-align:left">
							<input name='new_password' id='_id' datatype='*'     class='input width-250 display-inline-block ' type='password' value=''  required='required'   placeholder= '新密码'></td>
						</td>
					</tr>
					<tr> 	
						<td>管理员状态:</td>
						<td style="text-align:left">
							<?php if($admin_info["status"] == 1 ): ?><i class="icon-check text-main"></i>启用
							<?php else: ?>
							<i class="icon-exclamation-triangle text-yellow">禁用</i><?php endif; ?>
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
					layer.msg(data.info,{time: 3000},function(){
						layer.closeAll();
						//window.location.href='/Store/Setting/adminList';
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