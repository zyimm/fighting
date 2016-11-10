<?php
/**
 * 
 * 考试模型
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月21日 上午11:33:04
 */
namespace Store\Model;

use Think\Model;
use Think\Page;

class ExamModel extends Model
{
    protected $tableName = 'Exam';
 
    /**
     * 获取活动列表
     * @param array $map
     * @param string $field
     * @param array $search
     * @param number $page_now
     * @param number $page_size
     * @return array
     */
    public function getExamList(array $map = [], $field = '*', array $search = [],$page_now=1, $page_size = 0)
    {
        $row = [];
        if($page_size<1){
            $page_size = C('PAGE_SIZE');
        }
        $count = $this->field($field)->where($map)->count();
        $page  = new Page($count,$page_size);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $this->field($field)->where($map)->page("{$page_now},{$page_size}")->select();
        if(!empty($search)){
            $page->parameter=$search;
        }
        foreach ($list as $k=>$v){
            $map_temp =[
                's.is_del' =>0,
                'a.status' =>1,
                's.student_status' =>1,
                'exam_id'=>$v['exam_id']
            ];
            $temp = $this->getApplyList($map_temp);
            $list[$k]['apply_num'] =$temp['count'];
        }
        $row = [
            'list' =>$list,
            'page_show'=>$show

        ];
        return $row;
    }
    
   
    /**
     * 判断活动是否存在!
     * @param array $map
     * @return boolean
     */
    public function isOnly(array $map =[]){
        if(empty($map)){
            return false;
        }else{
            $map = [
                'Exam_title' =>$Exam_title,
                'store_id' =>$store_id,
                'is_del'=>0
            ];
           return $this->where($map)->count();
        }
    }
    /**
     * 
     * @param array $map
     * @param string $field
     * @param array $search
     * @param number $page_now
     * @param number $page_size
     * @return array
     */
    public function getApplyList(array $map=[],$field = '*', array $search = [],$page_now=1, $page_size = 0)
    {
        $row = [];
        if($page_size<1){
            $page_size = C('PAGE_SIZE');
        }
        $pre = C('DB_PREFIX');
        $model = M('exam_apply');
        $count = $model->alias('a')->field($field)
                ->join($pre.'level as l on a.exam_level = l.level_id','left')
                ->join($pre.'students as s on a.student_id = s.student_id','left') 
                ->join($pre.'members as m on s.student_id = m.student_id','left')
                ->where($map)->count();
        $page  = new Page($count,$page_size);// 实例化分页类 传入总记录数和每页显示的记录数
        $show  = $page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $model->alias('a')->field($field)
                ->join($pre.'level as l on a.exam_level = l.level_id','left')
                ->join($pre.'students as s on a.student_id = s.student_id','left') 
                ->join($pre.'members as m on s.student_id = m.student_id','left')
                ->where($map)->page("{$page_now},{$page_size}")
                ->order('a.exam_level asc')->select();
        if(!empty($search)){
            $page->parameter=$search;
        }
        $row = [
            'list' =>$list,
            'count' =>$count,
            'page_show'=>$show
        
        ];//dump($model->getLastSql());exit;
        return $row;
    }
    /**
     * 检测Exam 是否正常
     * @param number $Exam_id
     * @param number $store_id
     * @return boolean
     */
    public function checkExam($Exam_id = 0,$store_id= 0)
    {
        if(empty($Exam_id) || empty($store_id)) return false;
        $map =[
            'Exam_id'=>$Exam_id,
            'store_id'=>$store_id,
            'status' =>1,
            'is_del' =>0
        ];
        if($this->where($map)->count()){
           return true;
        }else{
           return false;
        }
    }
    
}