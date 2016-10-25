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
        $this->display();
    }
    /**
     * 课程
     */
    public function add()
    {
        //$coach = [];
        if(IS_POST){
            dump($_POST);
            
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
            $this->assign('html',$html);
            $this->display('edit');
        }
    }
}