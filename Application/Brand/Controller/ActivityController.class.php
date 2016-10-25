<?php
/**
 * 
 * 活动报名
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月20日 下午12:53:30
 */
namespace Store\Controller;

class ActivityController extends CommonController
{
    
    /**
     * 列表
     */
    public function index()
    {
        $model = D('Activity');
        $map =[
            'is_del' =>0,
            'store_id'=>$this->store_id 
        ];
        $field = 'activity_id,activity_title,activity_image,activity_content,activity_time
            ,activity_apply_time,activity_nums,status';
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $row = $model->getActivityList($map,$field,[],$page_now);
        foreach ($row['list'] as $k=>$v){
            $temp_activity_time = explode(',',$v['activity_time']); 
            $temp_activity_apply_time = explode(',',$v['activity_apply_time']);
            $row['list'][$k]['activity_time'] = date('Y年/m月/d日 H:i:s',$temp_activity_time[0]).'~'.date('Y年/m月/d日 H:i:s',$temp_activity_time[1]);
            $row['list'][$k]['activity_apply_time'] = date('Y年/m月/d日 H:i:s',$temp_activity_apply_time[0]).'~'.date('Y年/m月/d日 H:i:s',$temp_activity_apply_time[1]);
        }
        $this->assign('activity_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
        $this->display();
    }
    
    /**
     * 添加
     */
    public function add()
    {
        if(IS_POST){//dump($_POST);
            if(empty($_POST['activity_title'])){
                $this->error('活动标题为空!');
            }
            $activity_title = dhtmlspecialchars($_POST['activity_title']);
            $model = D('Activity');
            $map = [
                'activity_title' =>$activity_title,
                'store_id' =>$this->store_id,
                'is_del' =>0
            
            ];
            if($model->isOnly($map)){
                $this->error('活动已存在!');
            }
            //活动时间
            $activity_time = [];
            if(empty($_POST['star_time']) || empty($_POST['end_time'])){
                $this->error('活动时间必须填写!');
            }else{
               $star_time = strtotime($_POST['star_time']);
               $end_time = strtotime($_POST['end_time']);
               if($star_time>$end_time){
                   $this->error('活动开始时间应该小于结束时间!');
               }else{
                   $activity_time =[$star_time,$end_time];
               }
            }
            //活动报名时间
            $activity_apply_time = [];
            if(empty($_POST['star_apply_time']) || empty($_POST['end_apply_time'])){
                $this->error('活动报名时间必须填写!');
            }else{
                $star_apply_time = strtotime($_POST['star_apply_time']);
                $end_apply_time = strtotime($_POST['end_apply_time']);
                if($star_appaly_time>$end_apply_time){
                    $this->error('活动开始时间应该小于结束时间!');
                }else{
                    $activity_apply_time = [$star_apply_time,$end_apply_time];
                }
            }
            $activity_data = [
                'activity_title' => $activity_title,
                'activity_image' => $_POST['img_url'][0],
                'activity_time'=>join(',',$activity_time),
                'activity_apply_time'=>join(',',$activity_apply_time),
                'activity_nums' =>(int)$_POST['activity_nums'],
                'status' =>(int)$_POST['status'],
                'activity_content'=>I('activity_content'),
                'store_id' =>$this->store_id
            ];
            if($model->add($activity_data)){
                $this->success('添加活动成功!');
            }else{
                $this->error('添加活动失败!');
            }
        }else{
            $this->display('edit');
            
        }

    }
    /**
     * 编辑
     */
    public function edit()
    {
        $activity_id =(int)$_REQUEST['activity_id'];
        if(empty($activity_id)){
            $this->error('activity_id 不存在!');
        }
        $model = D('Activity');
        if(IS_POST){//dump($_POST);
            if(empty($_POST['activity_title'])){
                $this->error('活动标题为空!');
            }
            $activity_title = dhtmlspecialchars($_POST['activity_title']);
           
            $map = [
                'activity_title' =>['neq',$activity_title],
                'store_id' =>$this->store_id,
                'is_del' =>0
                
            ];
            if($model->isOnly($map)){
                $this->error('活动已存在!');
            }
            //活动时间
            $activity_time = [];
            if(empty($_POST['star_time']) || empty($_POST['end_time'])){
                $this->error('活动时间必须填写!');
            }else{
                $star_time = strtotime($_POST['star_time']);
                $end_time = strtotime($_POST['end_time']);
                if($star_time>$end_time){
                    $this->error('活动开始时间应该小于结束时间!');
                }else{
                    $activity_time =[$star_time,$end_time];
                }
            }
            //活动报名时间
            $activity_apply_time = [];
            if(empty($_POST['star_apply_time']) || empty($_POST['end_apply_time'])){
                $this->error('活动报名时间必须填写!');
            }else{
                $star_apply_time = strtotime($_POST['star_apply_time']);
                $end_apply_time = strtotime($_POST['end_apply_time']);
                if($star_appaly_time>$end_apply_time){
                    $this->error('活动开始时间应该小于结束时间!');
                }else{
                    $activity_apply_time = [$star_apply_time,$end_apply_time];
                }
            }
            $activity_data = [
                'activity_title' => $activity_title,
                'activity_image' => $_POST['img_url'][0],
                'activity_time'=>join(',',$activity_time),
                'activity_apply_time'=>join(',',$activity_apply_time),
                'activity_nums' =>(int)$_POST['activity_nums'],
                'status' =>(int)$_POST['status'],
                'activity_content'=>I('activity_content'),
                'time' =>time()
            ];
            $map = [
                'activity_id'=>$activity_id,
                'store_id' => $this->store_id,
                'is_del' =>0
                
            ];
            if($model->where($map)->save($activity_data)){
                $this->success('添加活动更新成功!');
            }else{
                $this->error('添加活动更新失败!');
            }
        }else{
            
            $map =[
                'is_del' =>0,
                'store_id'=>$this->store_id
            ];
            $field = 'activity_id,activity_title,activity_image,activity_content,activity_time
            ,activity_apply_time,activity_nums,status';
            $row = $model->getActivityList($map,$field);
            foreach ($row['list'] as $k=>$v){
                $temp_activity_time = explode(',',$v['activity_time']);
                $temp_activity_apply_time = explode(',',$v['activity_apply_time']);
                $row['list'][$k]['star_time'] = date('Y-m-d H:i:s',$temp_activity_time[0]);
                $row['list'][$k]['end_time']  = date('Y-m-d H:i:s',$temp_activity_time[1]);
                $row['list'][$k]['star_apply_time'] = date('Y-m-d H:i:s',$temp_activity_apply_time[0]);
                $row['list'][$k]['end_apply_time']  = date('Y-m-d H:i:s',$temp_activity_apply_time[1]);
 
            }
            
            //头像
            if(!empty($row['list'][0]['activity_image'])){
            
                $album="<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$row['list'][0]['activity_image']}' name='img_url[]' />
                <img src='{$row['list'][0]['activity_image']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
                $this->assign('album',$album);
            }
            $row['list'][0]['activity_content'] = html_entity_decode($row['list'][0]['activity_content']);
            $this->assign('activity_info', $row['list'][0]);
            $this->display('edit');
        
        }
    }
    
    /**
     * 删除
     */
    public function del()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = D('Activity');
            $map = [
                'activity_id' =>$id,
                'store_id' =>$this->store_id
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            }
        }
    }
    /**
     * 
     */
    public function apply()
    {
        $activity_id =(int)$_REQUEST['activity_id'];
        if(empty($activity_id)){
            $this->error('activity_id 不存在!');
        }
        $model = D('Activity');
        //检测Activity 是否正常
        if(!$model->checkActivity($activity_id,$this->store_id)){
            $this->error('该活动处于非正常状态!');
        }
        $map =[
            's.is_del' =>0,
            's.student_status' =>1,
            'activity_id'=>$activity_id
        ];
        $field = 'a.apply_id,a.apply_time,a.status,
            s.student_name,s.student_age,s.student_sex,
            m.nick_name, m.mobile';
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $row = $model->getApplyList($map,$field,[],$page_now);
       //dump($row['list']);
        $this->assign('apply_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
        $this->display();
    }
    /**
     * 审核
     */
    public function  doApply()
    {
        $apply_id =(int)$_REQUEST['apply_id'];
        if(empty($apply_id)){
            $this->error('apply_id 不存在!');
        }
        $model = D('Activity');
        //检测从属Activity 是否正常
        $activity_id =$model->where(['apply_id'=>$id,'store_id'=>$this->store_id])->getField('activity_id');
        
        if(!$model->checkActivity($activity_id,$this->store_id)){
            $this->error('该活动处于非正常状态!');
        }else{
            $data = [
                'deal_time' =>time(),
                'status'=>1   
            ];
            if(M('activity_apply')->where(['apply_id'=>$apply_id])->save($data)){
                $this->success('审核成功！');  
            }else{
                $this->error('审核失败！');
            }
        }
    }
    /**
     * 
     */
    public function delApply()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = D('Activity');
            //检测从属Activity 是否正常
            $activity_id = $model->where(['apply_id'=>$id,'store_id'=>$this->store_id])->getField('activity_id');
            
            if(!$model->checkActivity($activity_id,$this->store_id)){
                $this->error('该活动处于非正常状态!');
            }else{
                if(M('activity_apply')->where(['apply_id'=>$apply_id])->delete()){
                   $this->success('删除成功!');
                }else{
                    $this->error('删除失败！');
                }
            }
        }
    }
}