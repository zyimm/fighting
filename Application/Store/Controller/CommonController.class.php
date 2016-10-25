<?php
/**
 * 
 * 道馆后台公共管理器
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月9日 下午2:40:38
 */
namespace Store\Controller;

use Think\Controller;

class CommonController extends Controller
{

    protected $store_id;
    protected $store_admin_id;
    protected $store_admin_info;
    protected $brand_id;
    public function __construct()
    {
        parent::__construct();
        //检测登录
        $this->IsLogin();
        //判断权限
        /* $auth=new Auth();
        if(!$auth->check(MODULE_NAME.'-'.ACTION_NAME,$this->$admin_id)){
            if(IS_AJAX){
                ajaxMsg('你没有权限',0);
            }else{
                $this->error('你没有权限');
            }
        } */
        
    }

    /**
     * 判断是否登录
     */
    private function IsLogin()
    {
        $store_admin_id = session('store_admin_id');
        if (!empty($store_admin_id)) {
            $this->store_admin_id = $store_admin_id;
            $this->store_admin_info = D('Store')->getStoreAdminInfo($this->store_admin_id);
            $this->brand_id = $this->store_admin_info['brand_id'];
            $this->store_id = $this->store_admin_info['store_id'];
        } else {
            $this->redirect('/store/login');
        }
    }
}