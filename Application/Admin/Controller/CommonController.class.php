<?php
/**
 * 
 * 后台公共管理器
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月9日 下午2:40:38
 */
namespace Admin\Controller;

use Think\Controller;
use Think\Auth;

class CommonController extends Controller
{

    protected $admin_id;
    protected $admin_info;
    public function __construct()
    {
        parent::__construct();
        //检测登录
        $this->IsLogin();
        //判断权限
        if($this->admin_info['role_id'] != 1){
            $auth=new Auth();
            $auth_id = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
          
            if(!$auth->check($auth_id,$this->admin_id)){
                /* if(IS_AJAX){
                 ajaxMsg('你没有权限',0);
                 }else{
                 $this->error('你没有权限');
                 } */
                
                $this->error('你没有权限');
            }
        }
        
        
    }

    /**
     * 判断是否登录
     */
    private function IsLogin()
    {
        $admin_id = session('admin_id');
        if (!empty($admin_id)) {
            $this->admin_id = $admin_id;
            $this->admin_info = D('Admin')->getAdminInfo($this->admin_id);
        } else {
            $this->redirect('/admin/login');
        }
    }
}