<?php
namespace Brand\Model;

use Think\Model;
use Think\Page;

class TalkModel extends Model
{

    /**
     * 获取话题列表
     * @param array $map            
     * @param string $field            
     * @param array $search            
     * @param number $page_now            
     * @param number $page_size            
     */
    public function getTalkList(array $map = [], $field = '*', array $search = [], $page_now = 1, $page_size = 0)
    {
        $row = [];
        if ($page_size < 1) {
            $page_size = C('PAGE_SIZE');
        }
        $pre = C('DB_PREFIX');
        $count = $this->alias('t')
            ->field($field)
            ->join($pre . 'brand as b on b.brand_id = t.brand_id', 'left')
  
            ->where($map)
            ->count();
        $page = new Page($count, $page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        if (! empty($search)) {
            $page->parameter = $search;
        }
        $show = $page->show(); // 分页显示输出
                               // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $this->alias('t')
             ->field($field)
             ->join($pre . 'brand as b on b.brand_id = t.brand_id', 'left')
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