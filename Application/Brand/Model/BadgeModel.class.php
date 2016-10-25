<?php
/**
 * 
 * 微章模型
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月19日 下午4:19:00
 */
namespace Store\Model;


use Think\Model;
use Think\Page;

class BadgeModel extends Model
{
    public function getBedgeList($map = [], $field = '*', $page_now = 1, $page_size = 0)
    {
        $row = [];
        if ($page_size < 1) {
            $page_size = C('PAGE_SIZE');
        }
        $pre = C('DB_PREFIX');
        $count = $this->alias('b')
            ->field($field)
            ->join($pre . 'store as s on b.store_id = s.store_id', 'left')
            ->where($map)
            ->count();
        $page = new Page($count, $page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $this->alias('b')
            ->field($field)
            ->join($pre . 'store as s on b.store_id = s.store_id', 'left')
            ->where($map)
            ->page("{$page_now},{$page_size}")
            ->select();
        $row = [
            'list' => $list,
            'page_show' => $show
        ]; // dump($list);exit;
        return $row;
    }
}