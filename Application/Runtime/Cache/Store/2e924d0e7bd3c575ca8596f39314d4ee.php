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
	<?php echo crumbs(15,'场馆信息','store_menu');?>
	<hr />
	<div class="panel">
		<div class="panel-head "><h4 class='icon-edit '>信息修改</h4></div>
		<div class="panel-body">
			<form method="post" action='' class="form-x"  enctype="multipart/form-data" />
				<input type="hidden" name='store_id' value='<?php echo ($store_info["store_id"]); ?>'  />
				<table class="table"> 
				
					<tr>
						<td style='width:160px;'>场馆信息标题:</td>
						<td style="text-align:left">
							<input name='store_title' id='_id' datatype='*'    style="width:360px;" class='input display-inline-block' type='text' value='<?php echo ($store_info["store_title"]); ?>'  required='required'   placeholder= '标题'></td>

						</td>
					</tr>	
				
					<tr>
						<td>缩略图:</td>
						<td style="text-align:left">
							<span class="button bg-yellow button-little" id='img_upload'>展示图片(355x150)</span>
							    			<script id="picarr"></script>
    			<script> 
    			    var imageLength =  0;
	    			$(function() {
					   //重新实例化一个编辑器，防止在上面的editor编辑器中显示上传的图片或者文件
					    _e = UE.getEditor('picarr', {
					        //serverUrl :'/Home/Index/ueditor.html',
					     });
					    _e.ready(function() {
					        //设置编辑器不可用
					        _e.setDisabled('insertimage');
					        //隐藏编辑器，因为不会用到这个编辑器实例，所以要隐藏
					        _e.hide();
					        //侦听图片上传
					        _e.addListener('beforeInsertImage', function(t,arg) {
					            if(imageLength){
					               var n = imageLength;
                                }else{
                                   var n=arg.length;
                                }
					            var n=arg.length;
					            var str='';
					            for(i=0;i<n;i++){
					              str+="<div style='width:120px;float:left;margin:8px;position:relative;'><i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i><input type='hidden' value="+arg[i].src+" name='img_url[]' /><img src="+arg[i].src+" alt=''  class='radius-big' width='88' height='88' /></div>";
					            }
					           
					            $('#img_preview').append(str);
					        })
					    });
	    				$('#img_upload').click(function(){
					      _e.getDialog("insertimage").open();
					    });
					});
    			</script>
							<div id="img_preview">
								<?php echo ($album); ?>
							</div>
						</td>
					</tr>
					<tr>
						<td>内容详情:</td>
						<td>
							 <!-- 加载编辑器的容器 -->
   					<script id='editor' name='content' type='text/plain'><?php echo ($store_info['content']); ?></script>
				    <!-- 配置文件 -->
				    <script type='text/javascript' src='/assets/Plugin/UE/ueditor.config.js'></script>
				    <!-- 编辑器源码文件 -->
				    <script type='text/javascript' src='/assets/Plugin/UE/ueditor.all.js'></script>
				    <!-- 实例化编辑器 -->
				    <script type='text/javascript'>
				        var ue = UE.getEditor('editor',{
				        	initialFrameWidth:'80%',
				        	initialFrameHeight:'320',
    						serverUrl:'/assets/Plugin/UE/php/controller.php'
    					});
				    </script>
        		<!-- 编辑器调用结束 -->
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


</div>
</body>
</html>