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
	
</head>
<body>
	<div class="layout admin-top">
		<!-- header-logo -->
		<div class="admin-logo float-left padding">
			<a class="text-large " target="_blank" href="javascript:void(0)">
                <span><b><span class="open" style="color: #a5aeb4;">Let's</span>
				<span class="cmf" style="color: #3fa9f5;">Fighting</span></b></span>
            </a>
		</div>
		<!-- admin-top-memu -->
		<div class="admin-top-memu float-left "> 
			<ul class="nav nav-menu nav-inline nav-navicon"> 
			<?php if(is_array($category_level_first)): $i = 0; $__LIST__ = $category_level_first;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$clf): $mod = ($i % 2 );++$i;?><li class='float-left' rel='<?php echo ($clf["id"]); ?>'><a href='javascript:void(0)' onclick="switchMenu(<?php echo ($clf["id"]); ?>,this)"  ><i class="<?php echo ($clf["icon"]); ?> padding-small-right"></i><span><?php echo ($clf["name"]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>	
			</ul>
		</div>
		<div class="admin-top-memu float-right "> 
			<ul class="nav nav-menu nav-inline nav-navicon"> 
				<li class='float-right'><a href="/store/login/loginout.html" ><i class="icon-sign-out padding-small-right"></i><span>退出</span></a></li>
				<li class='float-right'><a href="javascript:clearCache('/store/index/clearCache.html');" ><i class="icon-trash-o padding-small-right"></i><span>清空缓存</span></a></li>
				<li class='float-right'><a href="javascript:void(0)" ><i class="icon-user padding-small-right"></i><span><?php echo ($admin_info["user_name"]); ?>[<?php echo ($admin_info["role_name"]); ?>]</span></a></li>
			</ul>
		</div>
	</div>
	<div class="layout"> 
		<!-- sidebar -->
		<div class="sidebar">
			<?php if(is_array($category_level_second)): $k = 0; $__LIST__ = $category_level_second;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cls): $mod = ($k % 2 );++$k;?><div class="sidebar-tab-<?php echo ($k); ?> hidden"> 
				<?php if(is_array($cls)): $i = 0; $__LIST__ = $cls;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$clss): $mod = ($i % 2 );++$i;?><ul class="admin-side-memu "> 
					<li>
						<a href="javascript:void(0)" class='display-block admin-side-memu-a'> 
							<i class="icon-folder-open-o"></i>
							<span class="nav-label"><?php echo ($clss["name"]); ?></span>
							<span class="icon-angle-left float-right" style="color:#777;"></span>
						</a>
						<ul class='admin-side-memu-ul' >
						<?php if(is_array($clss["children"])): $i = 0; $__LIST__ = $clss["children"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$clsss): $mod = ($i % 2 );++$i;?><li><a href='javascript:void(0)' onclick="toFrame('<?php echo ($clsss["url"]); ?>',this)" class="display-block padding-small">
							<i class='<?php echo ($clsss["icon"]); ?> padding-small-right'></i><span><?php echo ($clsss["name"]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>	
					</li>
				</ul><?php endforeach; endif; else: echo "" ;endif; ?>
				
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<div class="admin-main"> 
			<iframe  id='MainIframe' name="MainIframe" class="iframe" src="/Brand/Index/welcome.html" style="width: 100%;height: 100%;border: 0;"></iframe>
		</div>
		
	</div>

	<script type="text/javascript" src="/assets/Common/Js/jquery.js"></script>
	<script type="text/javascript" src="/assets/Common/Js/pintuer.js"></script>
	<script type="text/javascript">  
		//console.log($('.admin-side-memu'));
		
		$(function(){
			$('.sidebar').children('div').eq(0).removeClass('hidden');
		
			$('.admin-side-memu-a').click(function(){
				if ($(this).children('span.float-right').hasClass("icon-angle-left")){
					$(this).children('span.float-right').removeClass('icon-angle-left').addClass('icon-angle-down');
				}else{
					$(this).children('span.float-right').removeClass('icon-angle-down').addClass('icon-angle-left');
				}

				$(this).next('ul.admin-side-memu-ul').slideToggle(400);
			});
			 
		});
		/*清楚缓存*/
		var clearCache=function(url){
			
		};
		//
		var toFrame=function(url,o){
			var _this=o;
			//console.log($(_this).parent('li').parent('ul').parent('li').parent('ul').siblings('ul'));
			$(_this).parent('li').css('backgroundColor','#eee').siblings('li').css('backgroundColor','#fff');
			$(_this).parent('li').parent('ul').parent('li').parent('ul').siblings('ul').children('li').children('ul').children('li').css('backgroundColor','#fff');
			parent.MainIframe.location = url;
		};
		/*菜单切换*/
		var switchMenu=function(id,o){
			var _this=o;
			$(_this).children('span').css('color','#fff');
			//console.log($(_this).parent('li.float-left').siblings('li').children('a').children('span'));
			$(_this).parent('li.float-left').siblings('li').children('a').children('span').css('color','#777');
			$('.sidebar-tab-'+id).removeClass('hidden').siblings('div').addClass('hidden');
		}
	</script>
</body>
</html>