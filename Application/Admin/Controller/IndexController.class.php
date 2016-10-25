<?php
/**
 * 
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月9日 上午10:27:34
 */
namespace Admin\Controller;



class IndexController extends CommonController
{

    public function index()
    {
        layout(false);
        $category_level=[];
        $model=D('AdminMenu');
 
        //获取一级菜单  
        $category_level_first= $model->where(['pid'=>0,'status'=>1])->order('id asc')->select();
        //根据一级菜单获取获取二级级菜单
        $category_level=[];
        foreach ($category_level_first as $k=>$v){
           
            $category_level[$v['id']]=$model->where(['pid'=>$v['id'],'status'=>1])->select();
            
        }
        //菜单整合
        foreach ($category_level as $k=>$v){
            //$category_level[$v['id']]=$model->where(['pid'=>$v['id'],'status'=>1])->select();
            foreach ($v as $key=>$val){
                $category_level[$k][$key]['children']=$model->where(['pid'=>$val['id'],'status'=>1])->select();
            }
        }
        //
        $this->assign('category_level_second',$category_level);
        $this->assign('category_level_first',$category_level_first);
        $this->assign('admin_info',$this->admin_info);
        $this->display();
    }
    
    
    /**
     * 欢迎页
     */
    public function welcome()
    {
        
        $this->assign('admin_info',$this->admin_info);
        $this->display();
    }
}