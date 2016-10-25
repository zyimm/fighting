<?php
/**

 */
namespace Store\Model;

use Think\Model;
use Think\Page;

class AdModel extends Model
{
    protected $tableName = 'ad';
    
    /**
     * 获取广告列表
     * @param array $map
     * @param string $field
     * @param number $page_now
     * @param number $page_size
     * @return unknown[]|string[]
     */
    public function getAdList($map=[],$field = '*',$page_now = 1,$page_size=0)
    {
        $row = [];
        if($page_size<1){
            $page_size = C('PAGE_SIZE');
        }
        
        $count = $this->where($map)->count();
        $page  = new Page($count,$page_size);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $this->where($map)->page("{$page_now},{$page_size}")->select();
       
        $row = [
            'list' =>$list,
            'page_show'=>$show
            
        ];
        return $row;
        
    }
   /**
    * 获取单个广告/幻灯片信息
    * @param number $ad_id
    * @param number $store_id
    * @return boolean|array
    */
    public function getAdInfo($ad_id=0,$store_id=0)
    {
        if(empty($ad_id) || empty($store_id)){
            return false;
        }else{
            $map = [
                'store_id' =>$store_id,
                'is_del' =>0,
                'ad_id' =>$ad_id  
            ];
            $data = $this->where($map)->find();
            if(empty($data)){
                return  false;
            }else{
                $data['content'] =html_entity_decode($data['content']);
                return $data;
            }
        }
    }
}