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
	<?php echo crumbs(24,'列表详细');?>
	<hr />
	<div class="panel">
		<div class="panel-head bg-main"><h4 class='icon-edit '>编辑/添加道馆</h4></div>
		<div class="panel-body">
			<form method="post" action='' />
				<input type="hidden" name='store_id' value='<?php echo ($store_info["store_id"]); ?>' />
				<table class="table"> 
					<tr>
						<td>道馆名称:</td>
						<td><input name='store_name' id='_id'      class='input width-250' type='text' value='<?php echo ($store_info["store_name"]); ?>'     placeholder= '道馆名称'></td>
					</tr>
					<tr>
						<td>道馆品牌:</td>
						<td style="text-align:left">
							<select  class='input' id='brand_id' name='brand_id'  onchange=''   style=''  ><?php  foreach($brand as $key=>$val) { if(!empty($store_info['brand_id']) && ($store_info['brand_id'] == $key || in_array($key,$store_info['brand_id']))) { ?><option selected="selected" value="<?php echo $key; ?>"><?php echo $val; ?></option><?php }else { ?><option value="<?php echo $key; ?>"><?php echo $val; ?></option><?php } } ?></select>	
							
						</td>
					</tr>
					<tr> 
						<td>道馆类别:</td>
						<td style="text-align:left">
							<input name="store_type" type="radio" value=1 <?php if($store_info["store_type"] == 1 ): ?>checked="checked"<?php endif; ?> />总部
							<input name="store_type" type="radio" value=0 <?php if($store_info["store_type"] == 0 ): ?>checked="checked"<?php endif; ?> />无
						</td>
						
					</tr>
					<tr> 
						
						<td>道馆状态:</td>
						<td style="text-align:left">
					
							<input name="store_status" type="radio" value=1 <?php if($store_info["store_status"] == 1 ): ?>checked="checked"<?php endif; ?> />启用
							<input name="store_status" type="radio" value=0 <?php if($store_info["store_status"] == 0 ): ?>checked="checked"<?php endif; ?> />禁用
							
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


</body>
</html>