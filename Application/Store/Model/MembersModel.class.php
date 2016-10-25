<?php
/**
 * 
 * 会员模型
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月20日 下午2:36:01
 */
namespace Store\Model;


use Think\Model;
use Think\Page;

class MembersModel extends Model
{
    
    /**
     * 获取会员列表
     * @param array $map
     * @param string $field
     * @param array $search
     * @param unknown $page_now
     * @param number $page_size
     * @return string[]|unknown[]
     */
    public function getMembersList(array $map = [], $field = '*', array $search = [],$page_now=1, $page_size = 0)
    {
        $row = [];
        if ($page_size < 1) {
            $page_size = C('PAGE_SIZE');
        }
       
        $pre = C('DB_PREFIX');
        $count = $this->alias('m')
            ->field($field)
            ->join($pre . 'students as su on m.student_id = su.student_id', 'left')
            ->join($pre . 'store as s on s.store_id = su.store_id', 'left')
            ->where($map)
            ->count();
        $page = new Page($count, $page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        if(!empty($search)){
            $page->parameter=$search;
        }
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $this->alias('m')
            ->field($field)
            ->join($pre . 'students as su on m.student_id = su.student_id', 'left')
            ->join($pre . 'store as s on s.store_id = su.store_id', 'left')
            ->where($map)
            ->page("{$page_now},{$page_size}")
            ->select();
     
        $row = [
            'list' => $list,
            'page_show' => $show
        ]
        ; // dump($list);exit;
        return $row;
    }
}