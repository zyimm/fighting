<?php
/**
 * @author ZYIMM
 * @version 1.0
 * @time 下午12:29:21
 * 版权所有@copyright 2016 www.zyimm.com
 */

namespace Api\Controller;

class ExamController extends CommonController
{
    
    public function index()
    {
        
    }
    /**
     * 考试详情
     * 
     * @author 版权所有@copyright 2016 www.zyimm.com
     */
    public function getExamDetail()
    {
        $exam_id =(int)I('exam_id');
        if(empty($exam_id)){
            static::jsonData('exam_id 不存在!',1);
        }
        $model = D('Store/exam');
        $map =[
                'is_del' =>0,
                'exam_id'=>$exam_id,
                'store_id'=>static::$store_id
        ];
        $field = 'exam_id,exam_title,exam_image,exam_content,exam_time
            ,exam_apply_time,status';
        $exam_data = $model->getExamList($map,$field,[],1,1);
        $exam_data = $exam_data['list'];
         
        if(!empty($exam_data)){
            foreach ($exam_data as $k=>$v){
                $temp_exam_time = explode(',',$v['exam_time']);
                $temp_exam_apply_time = explode(',',$v['exam_apply_time']);
                $exam_data[$k]['exam_start_time'] = $temp_exam_time[0];
                $exam_data[$k]['exam_end_time']   = $temp_exam_time[1];
                $exam_data[$k]['exam_apply_start_time'] = $temp_exam_apply_time[0];
                $exam_data[$k]['exam_apply_end_time'] = $temp_exam_apply_time[1];
                $exam_data[$k]['type'] = 'exam';
            }
            static::jsonData( $exam_data[0]);
        }else{
            static::jsonData('数据不存在!',1);
        }
    }
    /**
     * 考试报名
     * 
     * @author 版权所有@copyright 2016 www.zyimm.com
     */
    public function apply()
    {
        
    }
}