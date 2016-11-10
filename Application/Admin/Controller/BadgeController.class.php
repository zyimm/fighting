<?php
/**
 * 微章管理
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月19日 下午3:30:19
 */
namespace Admin\Controller;

class BadgeController extends CommonController
{
    public function index()
    {
        $model = D('Store/Badge');
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $map = [
            'b.is_del'=>0,     
            'b.is_sys'=>1    
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
            $model = D('Store/Badge');
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
                'is_sys'=>1,
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
        
        
        $model = D('Store/Badge');
        if(IS_POST){
            //属性检查
            if(!empty($_POST['badge_name'])){
                $badge_name = dhtmlspecialchars($_POST['badge_name']);
                $map = [
                        'badge_id'=>['neq',$badge_id],
                        'badge_name'=>$badge_name,
                        'is_del' =>0
                ];
                if($model->where($map)->count()){
                   //echo $model->getLastSql();exit;
                    $this->error('徽章名称已存在！');
                }
            }else{
                $this->error('徽章名称不能为空！');
            }
            $badge_data = [
                    'badge_name'=>$badge_name,
                    'badge_icon'=>$_POST['img_url'][0],
                    'badge_icon_disable'=>$_POST['img_url'][1],
                    'badge_type'=>(int)$_POST['badge_type'],
                    'desc'=>$_POST['desc'],
                    'is_sys'=>1,
                    'time'=>time()
            ];
            if($model->where(['badge_id'=>$badge_id])->save($badge_data)){
                $this->success('微章更新成功!');
            }else{
                $this->error('微章更新失败!');
            }
            
        }else{
            $map = [
                'b.badge_id' =>$badge_id,
                'b.is_del'=>0,
                'b.is_sys'=>1
                
            ];
            $field = 'b.*,s.store_name';
            $row=$model->getBedgeList($map, $field, $page_now);
            if(empty($row['list'][0])){
                $this->error('数据不存在!');
            }
            $badge_info = $row['list'][0];
            if (!empty($badge_info['badge_icon'])) {
            
                $album = "<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$badge_info['badge_icon']}' name='img_url[]' />
                <img src='{$badge_info['badge_icon']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
            }
            if(!empty($badge_info['badge_icon_disable'])){
                $album .= "<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$badge_info['badge_icon_disable']}' name='img_url[]' />
                <img src='{$badge_info['badge_icon_disable']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
            }
         
            $this->assign('album', $album);
            $this->assign('badge_info',$badge_info);
            $this->display('edit');
        }
    }
    public function del()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = D('Badge');
            $map = [
                'badge_id' =>$id,
              
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            }
        }
    }
    
}