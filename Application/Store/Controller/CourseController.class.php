<?php
/**
 * 
 * 课程管理
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月20日 上午10:38:29
 */
namespace Store\Controller;

class CourseController extends CommonController
{
    /**
     * 课程列表
     */
    public function index()
    {
        $model = D('courses');
        $map =[
            'is_del' =>0,
            'store_id'=>$this->store_id
        ];
        $field = 'course_id,course_title,course_image
            ,course_time,course_home,course_students,status';
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $row = $model->getCoursesList($map,$field,[],$page_now);
        $week_array=["日","一","二","三","四","五","六"];
        foreach ($row['list'] as $k=>$v){
            $temp_course_time = explode('#',$v['course_time']);
            foreach ($temp_course_time as $val){
                $_t = explode(',',$val);
                $row['list'][$k]['course_time_formation'].= '每周'.$week_array[$_t[0]]."的{$_t[1]}:{$_t[2]}-{$_t[3]}:{$_t[4]} <br />";
               
            }
        }
        
        foreach ($row['list'] as $k=>$v){
            $map_students = [
                'c.course_id'=>$v['course_id'],
                's.is_del'=>0,
                's.student_status'=>1,
                'c.store_id'=>$this->store_id
            ];
            $temp = $model->getCourseStudents($map_students,'c.id');
            $row['list'][$k]['apply_num'] =$temp['count'];
            $temp_coachs  = $model->getCourseCoachs($v['course_id'],'cc.coach_id,m.nick_name');
            $coachs = [];
            foreach ($temp_coachs['list'] as $key=>$val){
                $coachs[$val['coach_id']] = $val['nick_name'];
            }
            $row['list'][$k]['coachs'] =join(',',$coachs);
        }
        $this->assign('course_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
        $this->display();
    }
    /**
     * 课程
     */
    public function add()
    {
        //$coach = [];
        if(IS_POST){
           $model = D('courses');
           $course_title = $_POST['course_title'];
           if(empty($course_title)){
               $this->error('课程标题不能为空!');
           }
            $map = [
                'course_title' => $course_title,
                'is_del' => 1,
                'store_id' => $this->store_id
            ];
           if($model->where($map)->count()){
               $this->error('课程已存在!');
           } 
           if(!empty($_POST['coachs_id'])){
               $coachs_id = trim($_POST['coachs_id']);
           }else{
               $this->error('必须添加教练!');
           }
           
            //课程时间
            $course_time = [];
            foreach ($_POST['week_day'] as $k=>$v){
                if(!empty($_POST['start'][$k*2]) && !empty($_POST['start'][($k*2)+1])){   
                    $course_time[$k]="{$v},{$_POST['start'][$k*2]},{$_POST['end'][$k*2]},{$_POST['start'][($k*2)+1]},{$_POST['end'][($k*2)+1]}";
                    //$k*2,($k*2)+1
                }             
            }
            $course_time = join('#',$course_time);
            $course_data =[
                'course_title'=>$course_title,
                'course_image'=>$_POST['img_url'][0],
                'course_home' =>I('course_home'),
                'course_content' =>I('course_content'),
                'course_time' =>$course_time,
                'store_id'=>$this->store_id,
                'time'=>time(),
                'status' =>(int)I('status')
            ];
            $model->startTrans();
            //插入课程数据
            $course_id = $model->add($course_data);
            if($course_id){
                //关联教练
               if(empty($_POST['coachs_id'])){
                   $model->rollback();
                   $this->error('关联教练数据不存在!');
               }else{
                   $coachs_id =join(',',$coachs_id);
                   $course_model = M('course_coachs');
                   if(!empty($coachs_id)){
                       $map = [
                           'course_id'=>$course_id,
                           'coach_id'=>['in',$coachs_id]
                       ];   
                       $course_model->where($map)->delete();
                   }
                   
                   $data = [];
                   foreach ($_POST['coach'] as $v){
                       if(!empty($v)){
                           $data[] = [
                               'course_id'=>$course_id,
                               'coach_id'=>$v,
                               'store_id'=>$this->store_id
                           ];
                       }
                       
                   } 
              
                   //
                   if($course_model->addAll($data)){ 
                       $model->commit();
                       $this->success('数据插入成功!');
                   }
                   
               }
               $this->error($course_model->getLastSql());
            }else{
                $model->rollback();
                $this->error('课程提交数据失败!');
                
            }
            //dump(join('#',$course_time));
        }else{
            $coach_model = D('Store/Coachs');
            $map = [
                'm.is_del' =>0,
                'm.status' =>1,
                'm.utype' =>2,
                'cs.store_id' => $this->store_id
            ];
            $field = 'm.nick_name,cs.coach_id';
            $coach_temp = $coach_model->getCoachsList($map,$field);
            $html = '';
            foreach ($coach_temp['list'] as $k=>$v){
                $html.= "<option value={$v['coach_id']} >{$v['nick_name']}</option>";
            
            }
            $course_time [0]=[
                'week'=>1 ,
                1=>00,
                2=>00,
                3=>00,
                4=>00
            ];
            $this->assign('course_time',$course_time);
            $this->assign('html',$html);
            $this->display('edit');
        }
    }
    
    public function edit(){
        
        $course_id =(int)$_REQUEST['course_id'];
        if(empty($course_id)){
            $this->error('course_id 不存在!');
        }
        $model = D('courses');
        if(IS_POST){
            
            $course_title = $_POST['course_title'];
            if(empty($course_title)){
                $this->error('课程标题不能为空!');
            }
            $map = [
                'course_title' => $course_title,
                'is_del' => 1,
                'store_id' => $this->store_id
            ];
            if($model->where($map)->count()){
                $this->error('课程已存在!');
            }
            
            if(!empty($_POST['coachs_id'])){
                $coachs_id = trim($_POST['coachs_id']);
            }else{
                $this->error('必须添加教练!');
            }
            
            //课程时间
            $course_time = [];
            foreach ($_POST['week_day'] as $k=>$v){
                if(!empty($_POST['start'][$k*2]) && !empty($_POST['start'][($k*2)+1])){
                    $course_time[$k]="{$v},{$_POST['start'][$k*2]},{$_POST['end'][$k*2]},{$_POST['start'][($k*2)+1]},{$_POST['end'][($k*2)+1]}";
                    //$k*2,($k*2)+1
                }
            }
            $course_time = join('#',$course_time);
            $course_data =[
                'course_title'=>$course_title,
                'course_image'=>$_POST['img_url'][0],
                'course_home' =>I('course_home'),
                'course_content' =>I('course_content'),
                'course_time' =>$course_time,
                'store_id'=>$this->store_id,
                'time'=>time(),
                'status' =>(int)I('status')
            ];
            $model->startTrans();
            //插入课程数据
            $result = $model->where(['course_id'=>$course_id])->save($course_data);
            if($result){
                //关联教练
                $coachs_id =join(',',$coachs_id);
                $course_model = M('course_coachs');
                if(!empty($coachs_id)){
                    $map = [
                        'course_id'=>$course_id,
                        'coachs_id'=>['in',$coachs_id]
                    ];
                    $course_model->where($map)->delete();
                }
                 
                $data = [];
                foreach ($_POST['coachs_id'] as $v){
                    if(!empty($v)){
                        $data[] = [
                            'course_id'=>$course_id,
                            'coach_id'=>$v,
                            'store_id'=>$this->store_id
                        ];
                    }
                }
                //
                if($course_model->addAll($data)){
                    $model->commit();
                    $this->success('课程数据更新成功!');
                }else{
          
                    $model->rollback();
                    $this->error('课程数据更新失败!');
                }
          
            }else{
                $model->rollback();
                $this->error('课程数据更新失败!');
        
            }
            //dump(join('#',$course_time));
        }else{
            $map =[
                'is_del' =>0,
                'course_id'=>$course_id,
                'store_id'=>$this->store_id
            ];
            $field = '*';
           
            $row = $model->getCoursesList($map,$field,[],1);
            if(empty($row['list'][0])){
                $this->error('数据不存在!');
            }
            //头像
            if(!empty($row['list'][0]['course_image'])){
            
                $album="<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$row['list'][0]['course_image']}' name='img_url[]' />
                <img src='{$row['list'][0]['course_image']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
                $this->assign('album',$album);
            }
            $row['list'][0]['course_content'] = html_entity_decode($row['list'][0]['course_content']);
            
            $course_info = $row['list'][0];
            
            $this->assign('course_info',$course_info);
            //课程下面的教练
            $coach_arr_temp = $model->getCourseCoachs($course_id,'cc.coach_id');
            $coach_arr = [];
            foreach ($coach_arr_temp['list'] as $v){
                $coach_arr[]=$v['coach_id'];
            }
            $coach_model = D('Store/Coachs');
            $map = [
                'm.is_del' =>0,
                'm.status' =>1,
                'm.utype' =>2,
                'cs.store_id' => $this->store_id
            ];
            $field = 'm.nick_name,cs.coach_id';
            //道馆下面的教练
            $coach_temp = $coach_model->getCoachsList($map,$field);
            $html = '';
            foreach ($coach_temp['list'] as $k=>$v){
                $selected =(in_array($v['coach_id'],$coach_arr))?'selected':'';
                $html.= "<option value={$v['coach_id']} {$selected} >{$v['nick_name']}</option>";
        
            }
            $this->assign('html',$html);
            
            //course-time 课程时间
            $course_time = [];
            $_time = explode('#',$course_info['course_time']);
            foreach ($_time as $k=>$v){
                $_t = explode(',',$v);
                $course_time [$k]=[
                   'week'=> (int)$_t[0],
                    1=>(int)$_t[1],
                    2=>(int)$_t[2],
                    3=>(int)$_t[3],
                    4=>(int)$_t[4]        
                ];
            
            }
         
            $this->assign('course_time',$course_time);
            $this->display('edit');
        }
    }
    
    
    public function del()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = D('Courses');
            $map = [
                'course_id' =>$id,
                'store_id' =>$this->store_id
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            }
        }
    }
  
    public function apply()
    {
        $course_id = $_REQUEST['course_id'];
        if(empty($course_id)){
            $this->error('课程id为空!');
        }
        $model = D('Courses');
        $map = [
            'c.course_id'=>$course_id,
            's.is_del'=>0,
            's.student_status'=>1,
            'c.store_id'=>$this->store_id
        ];
        $field = 'c.id,c.course_id,c.apply_time,c.status,c.operator_id,c.operator_time,
            s.student_name,s.student_age,s.student_sex,s.level_id,
            m.nick_name, m.mobile';  
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $row = $model->getCourseStudents($map,$field,[],$page_now);
        
        $store_model  = D('Store');
        foreach ($row['list'] as $k=>$v){
            if(!empty($v['operator_id'])){
                $temp = $store_model->getStoreAdminInfo($v['operator_id']);
                $row['list'][$k]['operator_name']=$temp['user_name'];
            }else{
                continue;
            } 
        }
        $this->assign('apply_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
        $this->display();
    }
    /**
     * 审核
     */
    public function  doApply()
    {
        $apply_id =(int)$_REQUEST['id'];
        if(empty($apply_id)){
            $this->error('id 不存在!');
        }
        $model = D('course_students');
        $data = [
            'operator_time' =>time(),
        ];
        $map = ['id'=>$apply_id,'store_id'=>$this->store_id];
        $status = $model->where($map)->getField('status');

        $data['status'] = ($status==1)?0:1;
        if($model->where($map)->save($data)){
            $this->success('处理成功！');
        }else{
            $this->error('处理失败！');
        }
    }
    
    public function delApply()
    {
        $apply_id =(int)$_REQUEST['id'];
        if(empty($apply_id)){
            $this->error('id 不存在!');
        }
        $model = D('course_students');
       
        if($model->where(['id'=>$apply_id,'store_id'=>$this->store_id])->delete()){
            $this->success('处理成功！');
        }else{
            $this->error('处理失败！');
        }
    }
}