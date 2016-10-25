<?php
/**
 * 
 * Api公共管理器
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月9日 下午2:40:38
 */
namespace Api\Controller;

use Think\Controller;

class CommonController extends Controller
{

    protected $admin_id;
    protected $admin_info;
    public function __construct()
    {
        parent::__construct();
        //检测登录
        $this->IsLogin();  
    }

    /**
     * 判断是否登录
     */
    private function IsLogin()
    {
        
    }
}