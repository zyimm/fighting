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
	<?php echo crumbs(53,'编辑/添加');?>
	<hr />
	<form method="post"  action='/Admin/Talk/add' class="form-x"  enctype="multipart/form-data">
		<input type="hidden"  name='talk_id'  value="<?php echo ($talk_info['talk_id']); ?>" />
		<table class="table table-hover" style=''>
			<tr>
				<td style="width:160px;">话题标题:</td>
				<td class="text-left"><input name='talk_title' id='_id' datatype='*2-48'    style="width:60%" class='input  display-inline-block' type='text' value='<?php echo ($talk_info['talk_title']); ?>'  required='required'   placeholder= '请认真填写此项!'></td>
			</tr>
			
			<tr>
				<td>话题缩略图:</td>
				<td style='text-align:left'>
					<span class="button bg-yellow" id='img_upload'>缩略图</span>
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
				<td style="width:160px;">所属品牌:</td>
				<td class="text-left">
					<select  class='input' id='brand_id' name='brand_id'  onchange=''   style=''  ><?php  foreach($brand as $key=>$val) { if(!empty($talk_info['brand_id']) && ($talk_info['brand_id'] == $key || in_array($key,$talk_info['brand_id']))) { ?><option selected="selected" value="<?php echo $key; ?>"><?php echo $val; ?></option><?php }else { ?><option value="<?php echo $key; ?>"><?php echo $val; ?></option><?php } } ?></select>
				</td>
			</tr>
		
			
			<tr>
				<td>话题内容详情:</td>
				<td>
					 <!-- 加载编辑器的容器 -->
   					<script id='editor' name='talk_content' type='text/plain'><?php echo ($talk_info['talk_content']); ?></script>
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
				<td>是否显示:</td>
				<td style="text-align:left">
					<input name="status" type="radio" value=1 <?php if($talk_info["status"] == 1 ): ?>checked="checked"<?php endif; ?> />显示
					<input name="status" type="radio" value=0 <?php if($talk_info["status"] == 0 ): ?>checked="checked"<?php endif; ?> />隐藏	
				</td>
			</tr>
			
		</table>
		<div style='text-align:center' class="padding">
			<button class="button button-big" type="submit"><span class="icon-edit text-blue"></span>提交修改</button>
		</div>
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
						window.location.href='/Admin/Talk/index.html';
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