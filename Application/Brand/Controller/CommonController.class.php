<?php
/**
 * 
 * 品牌后台公共管理器
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月9日 下午2:40:38
 */
namespace Brand\Controller;

use Think\Controller;

class CommonController extends Controller
{

    protected $store_id_gather;
    protected $admin_id;
    protected $admin_info;
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
        $brand_admin_id = session('brand_admin_id');
        if (!empty($brand_admin_id)) {
            $this->admin_id = $brand_admin_id;
            $model = D('Brand');
            $this->admin_info = $model->getBrandAdminInfo($this->admin_id);
            $this->brand_id = $this->admin_info['brand_id'];
            $this->brand_info = M('brand')->where(['brand_id'=>$this->brand_id])->find();
            $this->store_id_gather = $model->getStoreIdGather($this->brand_id);
        } else {
            $this->redirect('/brand/login');
        }
    }
    
    public function getStoreArray()
    {
        $map = [
                'brand_id'=>$this->brand_id,
                'store_status'=>1,
                'is_del'=>0
        ];
        $data = M('store')->field('store_id,store_name')->where($map)->select();
        $store_list = [
            0=>'请选择道馆'
        ];
        foreach ($data as $k=>$v){
            $store_list[$v['store_id']] = $v['store_name'];
        }
        return  $store_list;
    }
}