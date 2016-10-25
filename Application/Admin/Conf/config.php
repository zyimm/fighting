<?php
return [
    //'配置项'=>'配置值'
    'SHOW_PAGE_TRACE' => true,//开启页面trace显示信息
    'LAYOUT_ON'=>true, // 是否启用布局
	'LAYOUT_PATH'=>'../Application/Home/View/default/',
	'LAYOUT_NAME'=>'layout', // 当前布局名称 默认为layout
    'PAGE_SIZE'=>8, //分页大小
    'TOKEN_ON'      =>    false,  // 是否开启令牌验证 默认关闭
    'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
    'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true
];