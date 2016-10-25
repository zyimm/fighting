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
	<?php echo crumbs(12,'编辑/添加','brand_menu');?>
	<hr />
	<form method="post"  action='/Brand/Store/editAdmin' class="form-x"  >
		<input type="hidden" name='admin_id' value='<?php echo ($admin_info["id"]); ?>' />
		<table class="table"> 
			<tr>
				<td>选择道馆:</td>
				<td>
					<select  class='input' id='store_id' name='store_id'  onchange=''   style=''  ><?php  foreach($store as $key=>$val) { if(!empty($admin_info['store_id']) && ($admin_info['store_id'] == $key || in_array($key,$admin_info['store_id']))) { ?><option selected="selected" value="<?php echo $key; ?>"><?php echo $val; ?></option><?php }else { ?><option value="<?php echo $key; ?>"><?php echo $val; ?></option><?php } } ?></select>
				</td>
			</tr>
			<tr>
				<td>管理员名称:</td>
				<td style="text-align:left"> 
					<input name='user_name' id='_id' datatype='*2-16'     class='input width-250 display-inline-block ' type='text' value='<?php echo ($admin_info["user_name"]); ?>'  required='required'   placeholder= '管理员名称'></td>
				</td>
			</tr>	
			
			<tr>
				<td>管理员邮箱:</td>
				<td style="text-align:left">
					<input name='email' id='_id' datatype='e'     class='input width-250 display-inline-block ' type='text' value='<?php echo ($admin_info["email"]); ?>'  required='required'   placeholder= '管理员邮箱'></td>
				</td>
			</tr>
			
			<tr> 
				<td>角色密码:</td>
				<td style="text-align:left">
					<input name='password' id='_id'      class='input width-250 display-inline-block ' type='password' value=''     placeholder= '角色密码！'></td>
				</td>
			</tr>
			
		<!-- 	<tr>
				<td>过期时间</td>
				<td style="text-align:left">
					<input name='expire_time' id='_date'  style=' width:16%;height: 34px;line-height: 20px;  '  class=' input display-inline-block laydate-icon'  onclick="laydate({istime: true, max:laydate.now(+2000),format: 'YYYY-MM-DD hh:mm:ss'})"  type='text' value='<?php echo ($admin_info["expire_time"]); ?>'  placeholder= ''></td>
				</td>
			</tr> -->
			
			<tr> 	
				<td>管理员状态:</td>
				<td style="text-align:left">
					<input name="status" type="radio" value=1 <?php if($admin_info["status"] == 1 ): ?>checked="checked"<?php endif; ?> />启用
					<input name="status" type="radio" value=0 <?php if($admin_info["status"] == 0 ): ?>checked="checked"<?php endif; ?> />禁用	
				</td>
				
			</tr>
			
			
			<tr>
				<td colspan="2"><input value="保存" class="button bg-blue" type="submit"></td>
			</tr>

		</table>
	</form>	
	
	
	
</div>	

<script type="text/javascript"> 
	$(function(){
		$(".form-x").Validform({	
			tiptype:3,
			ajaxPost:true,
			showAllError : true,
			callback:function(data){
				if(data.status==1){
					layer.msg(data.info,{time: 3000,icon:1},function(){
						layer.closeAll();
						window.location.href='/Brand/Store/storeAdminList.html';
					});	
				}else{
					layer.msg(data.info,{icon:2});
				}
			}
		});
	});
</script>

</body>
</html>