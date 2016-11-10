<?php
/**
 * 
 * 资讯
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月28日 上午11:29:54
 */
namespace Api\Controller;

class ArticleController extends CommonController
{
    
    /**
     * 
     */
    public function ad(){
       
        $ad_model = D('Store/Ad');
        $ad_map = [
                'is_del' => 0,
                'status' =>1,
                'store_id' =>0
        ];
        $ad_list = $ad_model->getAdList($map,'ad_title,ad_image,ad_url,ad_type');
       
        if(empty($ad_list['list'])){
            static::jsonData('暂无数据!',0);
        }else{
            static::jsonData($ad_list['list']);
        }
    }
    
    /**
     * 查看
     */
    public function view()
    {
        $article_id = (int) I('article_id');
        if (empty($article_id)) {
            static::jsonData('no id',1);
        }
        $map = [
                'is_del'=>0
        ];
        $model = D('Store/Article');
        $article_info = $model->getArticleList($map);
        if(empty($article_info['list'][0])){
            static::jsonData('数据不存在!',1);
        }
        $article_info = $article_info['list'][0];
        $article_info['is_follow'] = whether_follow($article_id,'article',static::$member_id);
        $article_info['article_content'] = html_entity_decode($article_info['article_content']);
        $this->assign('article_info', $article_info);
        $this->display('Html/article');
    }
    
    public function index(){
        
        $model = D('Store/Article');
        $map = [
                'is_del' => 0,
                'status' =>1,
                'store_id' =>0
        ];
        $field = 'article_id,article_title,article_image,article_content,time';
        $page_now = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $row = $model->getArticleList($map, $field, $page_now);
        foreach ($row['list'] as $k=>$v){
            $row['list'][$k]['url'] ='http://'.$_SERVER['HTTP_HOST'].'/api/Article/view?article_id='.$v['article_id']; 
        }
        if(empty($row['list'])){
            static::jsonData('暂无数据!',1);
        }else{
            static::jsonData($row['list']);
        }
    }
    /**
     * 关注
     */
    public function addFollow()
    {
        $data = [
            'table_id'=>I('table_id'),
            'table'=>I('table'),
            'uid'=>static::$member_id   
                
        ];
        if(add_follow($data)){
            static::jsonData('关注成功!');
        }else{
            static::jsonData('关注失败!',1);
        }
        
    }
    /**
     * 移除关注
     */
    public function cancelFollow()
    {
        $data = [
                'table_id'=>I('table_id'),
                'table'=>I('table'),
                'uid'=>static::$member_id
        ];
        if(cancel_follow($data)){
            static::jsonData('移除成功!');
        }else{
            static::jsonData('移除失败!',1);
        }
    
    }
}