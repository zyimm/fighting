<?php
/**
 * 
 * 课程模型
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月21日 下午5:55:11
 */
namespace Store\Model;

use Think\Model;
use Think\Page;

class CoursesModel extends Model
{
    
    protected $tableName = 'courses';
    
    
    /**
     * 获取课程列表
     * @param array $map
     * @param string $field
     * @param array $search
     * @param string $page_now
     * @param number $page_size
     * @return array
     */
    public function getCoursesList(array $map = [], $field = '*', array $search = [],$page_now=1, $page_size = 8)
    {
        $row = [];
        if ($page_size < 1) {
            $page_size = C('PAGE_SIZE');
        }
       
        $count = $this->field($field)->where($map)->count();
        $page = new Page($count,$page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        if(!empty($search)){
            $page->parameter=$search;
        }
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $this->field($field)->where($map)->page("{$page_now},{$page_size}")->select();
        
        $row = [
            'list' => $list,
            'page_show' => $show
        ];
        return $row;
    } 
    
    
    /**
     * 获取指定课程学生
     * @param array $map
     * @param string $field
     * @param array $search
     * @param number $page_now
     * @param number $page_size
     * @return array
     */
    public function getCourseStudents($map,$field = '*', array $search = [],$page_now=1, $page_size = 8)
    {
        $row = [];
        $model = M('course_students');
       
        $pre = C('DB_PREFIX');
        $count = $model->alias('c')
            ->field($field)
            ->join($pre . 'students as s on c.student_id = s.student_id','left')
            ->join($pre . 'members as m on m.student_id = c.student_id','left')
            ->where($map)
            ->count();
        $page = new Page($count, $page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        if(!empty($search)){
            $page->parameter=$search;
        }
        $show = $page->show(); // 分页显示输出
       
        $list = $model->alias('c')
            ->field($field)
            ->join($pre.'students as s on c.student_id = s.student_id','left')
            ->join($pre . 'members as m on m.student_id = c.student_id','left')
            ->where($map)
            ->page("{$page_now},{$page_size}")
            ->select();
        $row = [
            'list' => $list,
            'count' =>$count,
            'page_show' => $show
        ];
        return $row;
    }
    /**
     * 获取指定课程教练
     * @param number $course_id
     * @param string $field
     * @param array $search
     * @param number $page_now
     * @param number $page_size
     * @return array
     */
    public function getCourseCoachs($course_id = 0,$field = '*', array $search = [],$page_now=1, $page_size = 8)
    {
        $row = [];
        $model = M('course_coachs');
        $map = [
            'cc.course_id'=>$course_id,
            'm.is_del'=>0,
            'm.status'=>1
        ];
        $pre = C('DB_PREFIX');
        $count = $model->alias('cc')
        ->field($field)
        ->join($pre . 'coachs as c on c.coach_id = cc.coach_id','left')
        ->join($pre . 'members as m on c.member_id = m.id','left')
        ->where($map)
        ->count();
        $page = new Page($count, $page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        if(!empty($search)){
            $page->parameter=$search;
        }
        $show = $page->show(); // 分页显示输出
         
        $list = $model->alias('cc')
        ->field($field)
        ->join($pre . 'coachs as c on c.coach_id = cc.coach_id','left')
        ->join($pre . 'members as m on c.member_id = m.id','left')
        ->where($map)
        ->page("{$page_now},{$page_size}")
        ->select();
        $row = [
            'list' => $list,
            'count' =>$count,
            'page_show' => $show
        ];
        return $row;
    }
}