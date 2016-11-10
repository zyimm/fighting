<?php
/**
 * 
 * 文章模型
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月28日 上午11:29:13
 */
namespace Store\Model;

use Think\Model;
use Think\Page;

class ArticleModel extends Model
{
    protected $tableName = 'article';

    /**
     * 获取文章列表
     * @param array $map
     * @param string $field
     * @param number $page_now
     * @param number $page_size
     * @param string $order
     * @return array
     */
    public function getArticleList(array $map=[],$field = '*',$page_now = 1,$page_size=0,$order='article_id desc')
    {
        $row = [];
        if($page_size<1){
            $page_size = C('PAGE_SIZE');
        }

        $count = $this->field($field)->where($map)->count();
        $page  = new Page($count,$page_size);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $this->field($field)->where($map)->page("{$page_now},{$page_size}")->order($order)->select();
        
        $row = [            
                'list' =>$list,
                'page_show'=>$show
        ];
        return $row;
    }    
    /**
     * 获取单个广告/幻灯片信息
     * @param number $article_id
     * @param number $store_id
     * @return boolean|string|mixed|boolean|NULL|unknown|object
     */
    public function getArticleInfo($article_id=0,$store_id=0)
    {
        if(empty($article_id) || empty($store_id)){
            return false;
        }else{
            $map = [
                    'store_id' =>$store_id,
                    'is_del' =>0,
                    'ad_id' =>$article_id
            ];
            $data = $this->where($map)->find();
            if(empty($data)){
                return  false;
            }else{
                $data['article_content'] =html_entity_decode($data['article_content']);
                return $data;
            }
        }
    }
}