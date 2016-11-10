<?php
/**
 * 
 * 考试报名
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月20日 下午12:53:30
 */
namespace Admin\Controller;

class ExamController extends CommonController
{
    /**
     * 列表
     */
    public function index()
    {
        $model = D('Store/exam');
        $map =[
            'is_del' =>0,
        ];
        $field = 'exam_id,exam_title,exam_image,exam_content,exam_time,store_id
            ,exam_apply_time,status';
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $row = $model->getExamList($map,$field,[],$page_now);
        foreach ($row['list'] as $k=>$v){
            $temp_exam_time = explode(',',$v['exam_time']);
            $temp_exam_apply_time = explode(',',$v['exam_apply_time']);
            $row['list'][$k]['exam_time'] = '起:'.date('Y年/m月/d日 H:i:s',$temp_exam_time[0]).'<br />'.'止:'.date('Y年/m月/d日 H:i:s',$temp_exam_time[1]);
            $row['list'][$k]['exam_apply_time'] = '起:'.date('Y年/m月/d日 H:i:s',$temp_exam_apply_time[0]).'<br />'.'止:'.date('Y年/m月/d日 H:i:s',$temp_exam_apply_time[1]);
        }
        $this->assign('exam_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
        $this->display();
    }
    
    /**
     * 添加
     */
    public function add()
    {
        if(IS_POST){//dump($_POST);
            if(empty($_POST['exam_title'])){
                $this->error('考试标题为空!');
            }
            $exam_title = dhtmlspecialchars($_POST['exam_title']);
            $model = D('exam');
            $map = [
                'exam_title' =>$exam_title,
               // 'store_id' =>$this->store_id,
                'is_del' =>0
            
            ];
            if($model->isOnly($map)){
                $this->error('考试已存在!');
            }
            //考试时间
            $exam_time = [];
            if(empty($_POST['star_time']) || empty($_POST['end_time'])){
                $this->error('考试时间必须填写!');
            }else{
               $star_time = strtotime($_POST['star_time']);
               $end_time = strtotime($_POST['end_time']);
               if($star_time>$end_time){
                   $this->error('考试开始时间应该小于结束时间!');
               }else{
                   $exam_time =[$star_time,$end_time];
               }
            }
            //考试报名时间
            $exam_apply_time = [];
            if(empty($_POST['star_apply_time']) || empty($_POST['end_apply_time'])){
                $this->error('考试报名时间必须填写!');
            }else{
                $star_apply_time = strtotime($_POST['star_apply_time']);
                $end_apply_time = strtotime($_POST['end_apply_time']);
                if($star_appaly_time>$end_apply_time){
                    $this->error('考试开始时间应该小于结束时间!');
                }else{
                    $exam_apply_time = [$star_apply_time,$end_apply_time];
                }
            }
            $exam_data = [
                'exam_title' => $exam_title,
                'exam_image' => $_POST['img_url'][0],
                'exam_time'=>join(',',$exam_time),
                'exam_apply_time'=>join(',',$exam_apply_time),
                'status' =>(int)$_POST['status'],
                'exam_content'=>I('exam_content'),
                'store_id' =>$this->store_id
            ];
            if($model->add($exam_data)){
                $this->success('添加考试成功!');
            }else{
                $this->error('添加考试失败!');
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
        $exam_id =(int)$_REQUEST['exam_id'];
        if(empty($exam_id)){
            $this->error('exam_id 不存在!');
        }
        $model = D('Exam');
        if(IS_POST){//dump($_POST);
            if(empty($_POST['exam_title'])){
                $this->error('考试标题为空!');
            }
            $exam_title = dhtmlspecialchars($_POST['exam_title']);
           
            $map = [
                'exam_title' =>['neq',$exam_title],
                'store_id' =>$this->store_id,
                'is_del' =>0
                
            ];
            if($model->isOnly($map)){
                $this->error('考试已存在!');
            }
            //考试时间
            $exam_time = [];
            if(empty($_POST['star_time']) || empty($_POST['end_time'])){
                $this->error('考试时间必须填写!');
            }else{
                $star_time = strtotime($_POST['star_time']);
                $end_time = strtotime($_POST['end_time']);
                if($star_time>$end_time){
                    $this->error('考试开始时间应该小于结束时间!');
                }else{
                    $exam_time =[$star_time,$end_time];
                }
            }
            //考试报名时间
            $exam_apply_time = [];
            if(empty($_POST['star_apply_time']) || empty($_POST['end_apply_time'])){
                $this->error('考试报名时间必须填写!');
            }else{
                $star_apply_time = strtotime($_POST['star_apply_time']);
                $end_apply_time = strtotime($_POST['end_apply_time']);
                if($star_appaly_time>$end_apply_time){
                    $this->error('考试开始时间应该小于结束时间!');
                }else{
                    $exam_apply_time = [$star_apply_time,$end_apply_time];
                }
            }
            $exam_data = [
                'exam_title' => $exam_title,
                'exam_image' => $_POST['img_url'][0],
                'exam_time'=>join(',',$exam_time),
                'exam_apply_time'=>join(',',$exam_apply_time),
                'exam_nums' =>(int)$_POST['exam_nums'],
                'status' =>(int)$_POST['status'],
                'exam_content'=>I('exam_content'),
                'time' =>time()
            ];
            $map = [
                'exam_id'=>$exam_id,
                'store_id' => $this->store_id,
                'is_del' =>0
                
            ];
            if($model->where($map)->save($exam_data)){
                $this->success('添加考试更新成功!');
            }else{
                $this->error('添加考试更新失败!');
            }
        }else{
            
            $map =[
                'is_del' =>0,
                'store_id'=>$this->store_id
            ];
            $field = 'exam_id,exam_title,exam_image,exam_content,exam_time
            ,exam_apply_time,exam_nums,status';
            $row = $model->getexamList($map,$field);
            foreach ($row['list'] as $k=>$v){
                $temp_exam_time = explode(',',$v['exam_time']);
                $temp_exam_apply_time = explode(',',$v['exam_apply_time']);
                $row['list'][$k]['star_time'] = date('Y-m-d H:i:s',$temp_exam_time[0]);
                $row['list'][$k]['end_time']  = date('Y-m-d H:i:s',$temp_exam_time[1]);
                $row['list'][$k]['star_apply_time'] = date('Y-m-d H:i:s',$temp_exam_apply_time[0]);
                $row['list'][$k]['end_apply_time']  = date('Y-m-d H:i:s',$temp_exam_apply_time[1]);
 
            }
            
            //头像
            if(!empty($row['list'][0]['exam_image'])){
            
                $album="<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$row['list'][0]['exam_image']}' name='img_url[]' />
                <img src='{$row['list'][0]['exam_image']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
                $this->assign('album',$album);
            }
            $row['list'][0]['exam_content'] = html_entity_decode($row['list'][0]['exam_content']);
            $this->assign('exam_info', $row['list'][0]);
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
            $model = D('exam');
            $map = [
                'exam_id' =>$id,
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
        $exam_id =(int)$_REQUEST['exam_id'];
        if(empty($exam_id)){
            $this->error('exam_id 不存在!');
        }
        $model = D('Store/exam');
      
        $map =[
            's.is_del' =>0,
            's.student_status' =>1,
            'exam_id'=>$exam_id
        ];
        $field = 'a.apply_id,a.apply_time,a.status,a.operator_id,a.operator_time,
            s.student_name,s.student_age,s.student_sex,s.level_id,
            m.nick_name, m.mobile,l.level_name';
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $row = $model->getApplyList($map,$field,[],$page_now,100000);
        $store_model  = D('Store/Store');
        foreach ($row['list'] as $k=>$v){
            if(!empty($v['operator_id'])){
                $temp = $store_model->getStoreAdminInfo($v['operator_id']);
                $row['list'][$k]['operator_name']=$temp['user_name'];
            }else{
                continue;
            }
        }
       //dump($row['list']);
        $this->assign('apply_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
        $this->display();
    }
    
   
}