<?php
/**
 * 
 * 官方
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月28日 下午2:07:09
 */
namespace Api\Controller;


class OfficialController extends CommonController
{
    /**
     * 菜单
     */
    public function index()
    {
        $data = [];
        $map = ['brand_id'=>static::$brand_id];
        $data['brand_image'] = M('brand')->where($map)->getField('brand_image');
        $data['menu'] = [
            0 => [
                'name' => "场馆",
                'image' => "/assets/Memu/cg.png"
            ],
            1 => [
                'name' => "教练",
                'image' => "/assets/Memu/jl.png"
            ],
            2 => [
                'name' => "公告",
                'image' => "/assets/Memu/gg.png"
            ],
            3=>[
                'name'=>"课程",
                'image'=>"/assets/Memu/kc.png"
            ]
        ];
        static::jsonData($data);
    }
    
    /**
     * 获取公告 此公告 会包含的考试,活动,通知
     */
    public function getNotices()
    {
        /*信息集合*/
        $list = [];
        //考试
        $model = D('Store/exam');
        $map =[
                'is_del' =>0,
                'status' =>1,
                'store_id'=>static::$store_id
        ];
        $field = 'exam_id,exam_title,exam_image,time';
        $exam_data = $model->getexamList($map,$field,[],1,6);
        $exam_data = $exam_data['list'];
       
        if(!empty($exam_data)){
            foreach ($exam_data as $k=>$v){
                $exam_data[$k]['type'] = 'exam';
            }
            $list[] = $exam_data;
        }
        //活动
        $model = D('Store/Activity');
        $map =[
                'is_del' =>0,
                'status' =>1,
                'store_id'=>static::$store_id
        ];
        $field = 'activity_id,activity_title,activity_image,time';
       
        $activity_data = $model->getActivityList($map,$field,[],1,6);
        $activity_data = $activity_data['list'];
        if(!empty($activity_data)){
            foreach ($activity_data as $k=>$v){
                $activity_data[$k]['type'] = 'activity';
            }
            $list[] = $activity_data;
        }    
        
        //通知
        $article_model = D('Store/Article');
        $map = [
                'is_del' => 0,
                'status' =>1,
                'store_id' => static::$store_id
        ];
        $article_field = 'article_title,article_image,time';
        $article_data = $article_model->getArticleList($map,$article_field,1,3);
        $article_data = $article_data['list'];
        if(!empty($article_data)){
            foreach ($article_data as $k=>$v){
                $article_data[$k]['type'] = 'article';
            }
            $list[] = $article_data;
        }
  
        if(empty($list)){
            static::jsonData('数据获取不到!',1);
        }else{
            static::jsonData($list);
        }
        
      
    }
   /**
    * 获取教练列表
    */
   public function coachs()
   {
       $model = D('Store/Coachs');
       $map = [
           'm.is_del' =>0,
           'm.status' =>1,
           'cs.store_id' => static::$store_id
       ];
       $field = 'm.id as coach_id,m.mobile,m.age,m.avatar,m.age,m.nick_name,cs.store_id,c.resume,c.honor,c.exp,c.motto';
       $row = $model->getCoachsList($map, $field,[],1,1000);
       foreach ($row['list'] as $key=>$val){
           $row['list'][$key]['star'] = 5;
       }
       if(empty($row['list'])){
           static::jsonData('暂无数据!',1);
       }else{
           static::jsonData($row['list']);
       }
      
   }
   
   public function courses()
   {
       $model = D('Store/courses');
       $map =[
           'is_del' =>0,
           'store_id'=>static::$store_id
       ];
       $field = 'course_id,course_title,course_image
            ,course_time,course_home,course_students';
     
       $row = $model->getCoursesList($map,$field,[],1,1000);
       $week_array=["日","一","二","三","四","五","六"];
       foreach ($row['list'] as $k=>$v){
           $temp_course_time = explode('#',$v['course_time']);
           foreach ($temp_course_time as $val){
               $_t = explode(',',$val);
               $row['list'][$k]['course_time_formation'] = empty($row['list'][$k]['course_time_formation'])?'':$row['list'][$k]['course_time_formation'];
               $row['list'][$k]['course_time_formation'].= '每周'.$week_array[$_t[0]]."的{$_t[1]}:{$_t[2]}-{$_t[3]}:{$_t[4]} <br />";
               $map_students = [
                   'c.course_id'=>$v['course_id'],
                   's.is_del'=>0,
                   's.student_status'=>1,
                   'c.store_id'=>static::$store_id
               ];
               $temp = $model->getCourseStudents($map_students,'c.id');
               $row['list'][$k]['apply_num'] =$temp['count'];
               $temp_coachs  = $model->getCourseCoachs($v['course_id'],'cc.coach_id,m.nick_name');
               $coachs = [];
               foreach ($temp_coachs['list'] as $key=>$val){
                   $coachs[$val['coach_id']] = $val['nick_name'];
               }
               $row['list'][$k]['coachs_nums'] = count($coachs);
               $row['list'][$k]['coachs'] =join(',',$coachs);
                
               unset($row['list'][$k]['course_time']);
           }
       }
      
       
       if(empty($row['list'])){
           static::jsonData('暂无数据!',1);
       }else{
           static::jsonData($row['list']);
       }
      
   }
   
   
}

