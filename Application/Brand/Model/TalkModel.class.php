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
    public function getTalkList(array $map = [], $field = '*', array $search = [], $page_now = 1, $page_size = 8,$order='t.talk_id desc')
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
             ->order($order)
             ->page("{$page_now},{$page_size}")
             ->select();
       foreach ($list as $k=>$v){
           $list[$k]['follow_nums'] = M('follow')->where(['table'=>'talk','table_id'=>$v['talk_id']])->count();
           $list[$k]['comment_nums'] = M('talk_comment')->where(['talk_id'=>$v['talk_id']])->count();
       }
        $row = [
            'list' => $list,
            'page_show' => $show
        ]; 
        return $row;
    }
    
    /**
     * 获取话题评论列表
     * @param array $map
     * @param string $field
     * @param array $search
     * @param number $page_now
     * @param number $page_size
     * @param string $order
     * @return array
     */
    public function getTalkComment($map = [],$field ='*',$search = [], $page_now = 1, $page_size = 0,$order='t.id desc')
    {
        $row = [];
        if ($page_size < 1) {
            $page_size = C('PAGE_SIZE');
        }
        
        $pre = C('DB_PREFIX');
        $count = M('talk_comment')->alias('t')
        ->field($field)
        ->join($pre . 'members as m on m.id = t.uid', 'left')
        ->join($pre . 'store as s on s.store_id = m.store_id', 'left')
        ->where($map)
        ->count();
        $page = new Page($count, $page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        if (! empty($search)) {
            $page->parameter = $search;
        }
        $show = $page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = M('talk_comment')->alias('t')
        ->field($field)
        ->join($pre . 'members as m on m.id = t.uid', 'left')
        ->join($pre . 'store as s on s.store_id = m.store_id', 'left')
        ->where($map)
        ->order($order)
        ->page("{$page_now},{$page_size}")
        ->select();
     
        $row = [
            'list' => $list,
            'page_show' => $show
        ];
        return $row;
    }
}