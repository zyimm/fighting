<?php
/**
 * 微章管理
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月19日 下午3:30:19
 */
namespace Store\Controller;

class BadgeController extends CommonController
{
    public function index()
    {
        $model = D('Badge');
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $map = [
            'b.is_del'=>0,     
        ];
        $field = 'b.*,s.store_name';
        $row = $model->getBedgeList($map, $field, $page_now);
        
        // dump($row);exit;
        $this->assign('badge_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
      
        $this->display();
      
        
    }
    
    /**
     * 添加
     */
    public function add()
    {
        
        if(IS_POST){
            $model = D('Badge');
            //属性检查
            if(!empty($_POST['badge_name'])){
                $badge_name = dhtmlspecialchars($_POST['badge_name']);
                $map = [
                        'badge_name'=>$badge_name,
                        'store_id'=>$this->store_id   
                ];
                if($model->where($map)->count()){
                    $this->error('徽章名称已存在！');
                }
            }else{
                $this->error('徽章名称不能为空！');
            }
            $badge_data = [
                'badge_name'=>$badge_name,
                'store_id' =>$this->store_id,
                'badge_icon'=>$_POST['img_url'][0],
                'badge_icon_disable'=>$_POST['img_url'][1],
                'badge_type'=>(int)$_POST['badge_type'],
                'desc'=>$_POST['desc'],
                'is_sys'=>0,
                'time'=>time()  
            ];
            if($model->add($badge_data)){
                $this->success('微章添加成功!');
            }else{
                $this->error('微章添加失败!');
            }
        }else{
            $this->display('edit');
            
        }
        
    }
    /**
     * 
     */
    public function edit()
    {
        $badge_id = (int)$_REQUEST['badge_id'];
        if(empty($badge_id)){
            $this->error('微章id不存在!'); 
        }
        
        
        $model = D('Badge');
        if(IS_POST){
            
            
        }else{
            $map = [
                'b.badge_id' =>$badge_id,
                'b.is_del'=>0,
                'b.is_sys'=>0
                
            ];
            $field = 'b.*,s.store_name';
            $row=$model->getBedgeList($map, $field, $page_now);
            if(empty($row['list'][0])){
                $this->error('数据不存在!');
            }
            $badge_info = $row['list'][0];
            $this->assign('badge_info',$badge_info);
            $this->display('edit');
        }
    }
    
}