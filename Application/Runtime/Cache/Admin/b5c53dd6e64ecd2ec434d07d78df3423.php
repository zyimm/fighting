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
.list-group li, .list-link a {
    padding: 4px 8px;
    border-bottom:none;
}
</style>
<div class="padding table-responsive">
	<?php echo crumbs(10,'权限更改');?>
	<hr />
	<div class="panel">
		<form action="" method="post" class="form-x" >
		<input type="hidden" name='role_id' value='<?php echo ($role_id); ?>' />
		<?php if(is_array($auth_list)): $i = 0; $__LIST__ = $auth_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$al): $mod = ($i % 2 );++$i;?><div class="padding"> 
				<div class="panel-head"> 
					<input type="checkbox" name='auth[]' class=""  onclick="checkAll(this,1)" value='<?php echo ($al["id"]); ?>' />&nbsp;<b class='text-big icon-language'>&nbsp;<?php echo ($al["title"]); ?></b>
				</div>
				<?php if(is_array($al["node"])): $i = 0; $__LIST__ = $al["node"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$aln): $mod = ($i % 2 );++$i;?><ul class="list-group padding-big-left">
					<li><input type="checkbox" name='auth[]' class="" onclick="checkAll(this,2)" value='<?php echo ($aln["id"]); ?>' />&nbsp;<?php echo ($aln["title"]); ?></li>
					<li> 
						<ul class="list-group padding-big-left">
						<?php if(is_array($aln["node"])): $i = 0; $__LIST__ = $aln["node"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$alnn): $mod = ($i % 2 );++$i;?><li class="float-left"><input type="checkbox" name='auth[]' value='<?php echo ($alnn["id"]); ?>' class="" />&nbsp;<?php echo ($alnn["title"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
							<li class="clearfix"></li>
						</ul>
					</li>
				</ul><?php endforeach; endif; else: echo "" ;endif; ?>				
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="padding text-center">
				<input value="提交保存" class="button bg-blue" type="submit" />
			</div>
		</form>
	</div>
</div>

<script type="text/javascript"> 
	var flag = true;
	var checkAll=function(o,type){
		//console.log(flag);
		var _this = o;
		if(type == 1){
			if(flag){
				$(_this).prop("checked",true);
				$(_this).parent().siblings('ul').find('input[type=checkbox]').prop("checked",true);
				flag = false;
			}else{
				$(_this).prop("checked",false);
				$(_this).parent().siblings('ul').find('input[type=checkbox]').prop("checked",false);
				flag = true;
			}
		}else{
			//console.log($(_this).parent().siblings());
			if(flag){
				
				$(_this).prop("checked",true);
				$(_this).parent().siblings('li').find('input[type=checkbox]').prop("checked",true);
				flag = false;
			}else{
				$(_this).prop("checked",false);
				$(_this).parent().siblings('li').find('input[type=checkbox]').prop("checked",false);
				flag = true;
			}
		}
		
		
	};
	
	$(function(){
		$(".form-x").Validform({	
			tiptype:3,
			ajaxPost:true,
			showAllError : true,
			callback:function(data){
				if(data.status==1){
					layer.msg(data.info,{time:2000,icon:1,shade: [0.8, '#393D49']},function(){
					layer.closeAll();
					window.location.reload();
				});	
				}else{
					layer.msg(data.info,{icon:2,shade: [0.8, '#393D49']});
				}
			}
		});
	});
</script>
</body>
</html>