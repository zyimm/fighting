<?php
return [
	//'配置项'=>'配置值'
	
    //数据库
    'DB_TYPE'         => 'Mysql', // 数据库类型
    'DB_PREFIX'       => 'xy_', // 数据库表前缀
    'DB_PORT'         => 3306,
    // 普通配置
    'DB_USER'         => 'root', // 用户名
    'DB_PWD'          => '', // 密码
    'DB_HOST'         => '127.0.0.1',
    'DB_NAME'         => 'xxxx',
     
    'TOKEN_ON'          => false, //是否开启令牌验证
    'TOKEN_NAME'        => '__hash__', //令牌验证的表单隐藏字段名称
    'TOKEN_TYPE'        => 'md5', //令牌哈希验证规则 默认为MD5
    'TOKEN_RESET'       => true, //令牌验证出错后是否重置令牌 默认为true
    'SESSION_EXPIRE'    => 10800,
    'URL_MODEL'         => 2,   //URL模式
    'MODULE_ALLOW_LIST' => ['Admin','Store','Api','Brand'],
    'DEFAULT_MODULE'    =>  'Admin',
    
    'UPLOADS_PATH'      =>WEB_PATH.'/uploads/',
        
    'IMAGE_UPLOAD_LIMIT'=> 5*1024*1024,
    'IMAGE_CROP_WIDTH'  => 275.00,
    'ALLOW_EXTS'        => 'jpg,jpeg,png,gif,apk',
    'THUMBNAILS'        =>  ['small' =>['w' => 100, 'h' => 90], 'attachemnt-list' =>['w' => 80, 'h' => 44]],
    
    //短信接口地址
    'SMS_ULR' =>'https://api.submail.cn/message/send.json',
    'SMS_APPID'  =>10619,
    'SMS_SIGN' =>'【验证码短信】',
    'SMS_SIGNATURE' =>'xxx',
    'SHOW_PAGE_TRACE'=>'true',
    
    //分页
    'PAGE_SIZE' => 8
    
    
];
