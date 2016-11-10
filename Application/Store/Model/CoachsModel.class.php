<?php
/**
 * 
 * 教练管理模型
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月19日 上午11:31:52
 */
namespace Store\Model;

use Think\Model;
use Think\Page;

class CoachsModel extends Model
{

    /**
     * 获取教练列表
     * @param array $map
     * @param string $field
     * @param array $search
     * @param number $page_now
     * @param number $page_size
     * @return array
     */
    public function getCoachsList(array $map = [], $field = '*',array $search = [], $page_now = 1, $page_size = 0)
    {
        $row = [];
        if ($page_size < 1) {
            $page_size = C('PAGE_SIZE');
        }
        $pre = C('DB_PREFIX');
        $count =  M('members')->alias('m')
            ->field($field)
            ->join($pre . 'coachs as c on  m.id = c.member_id', 'left')
            ->join($pre . 'coachs_store_relation as  cs on  cs.coach_id = c.coach_id', 'left')
            ->where($map)
            ->count();
        $page = new Page($count, $page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $page->show(); // 分页显示输出
  
        $list = M('members')->alias('m')
            ->field($field)
            ->join($pre . 'coachs as c on  m.id = c.member_id', 'left')
            ->join($pre . 'coachs_store_relation as  cs on cs.coach_id = c.coach_id', 'left')
            ->where($map)
            ->page("{$page_now},{$page_size}")
            ->select();
       
        $row = [
            'list' => $list,
            'page_show' => $show
        ]; 
        return $row;
    }
    /**
     * 获得指定教练所属的课程
     */
    public function getCourses($coach_id = 0,$store_id=0,$field='*')
    {
        if(empty($coach_id)){
            return false;
        }
        $pre = C('DB_PREFIX');
        $map = [
            'cc.coach_id' => $coach_id, 
            'c.status'=>1,
            'c.is_del'=>0
        ];
        if(empty($store_id)){
            $map['cc.store_id'] = $store_id;
        }
        $model = M('course_coachs');
        $data = $model->alias('cc')
              ->field($field)
              ->join($pre.'courses as c on c.course_id=cc.course_id')
              ->where($map)
              ->order('c.course_id desc')
              ->select();
        return $data;
    }
    /**
     * 
     * @param string $course_id
     * @param number $store_id
     * @param string $field
     */
    public function getCourseTimeByCoach($course_id = '',$store_id=0,$field='course_time')
    {
        $row = [
            'list'=>'',
            'count'=>0
        ];
        if(empty($course_id)){
            return $row;
        }
        $map = [
            'course_id' =>['in',$course_id],
            'status'=>1,
            'is_del'=>0
        ];
        if(!empty($store_id)){
            $map['store_id'] = $store_id;
        }
        $data = M('courses')->field($field)->where($map)->select();
        $row['list'] = $data;
        $count= 0;
        foreach ($data as $k=>$v){
            if(!empty($v['course_time'])){
                $count+=count(explode('#', $v['course_time']));
            }
        };
        $row['count'] = $count;
        return $row;
    }
    /**
     * 
     * @param string $course_id
     * @param number $store_id
     * @param string $field
     */
    public function getStudentsByCourse($course_id = '',$store_id=0,$field='id,status')
    {
        $row = [
            'list'=>'',
            'count_apply'=>0,
            'count'=>0
        ];
        if(empty($course_id)){
            return $row;
        }
        $map = [
            'course_id' =>['in',$course_id],

        ];
        if(!empty($store_id)){
            $map['store_id'] = $store_id;
        }
        $data =   M('course_students')->field($field)->where($map)->select();
        $row['list'] = $data;
        $count= $count_apply= 0;
        foreach ($data as $k=>$v){
            if(!empty($v['status'])){
                $count+=1;
               
            }else{
                $count_apply+=1;
            }
        };
        $row['count_apply'] = $count_apply;
        $row['count'] = $count;
        return $row;
      
    }
    
}