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
}