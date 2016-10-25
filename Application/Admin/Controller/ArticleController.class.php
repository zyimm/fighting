<?php
/**
 * 通知公告管理
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月12日 下午1:55:24
 */
namespace Admin\Controller;

class ArticleController extends CommonController
{
    /**
     * 列表
     */
    public function index ()
    {
        $model = D('Store/Article');
        $map = [
                'is_del' => 0,
               'store_id' => 0
        ];
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
    
        $row = $model->getArticleList($map, '*', $page_now);
    
        // dump($row);exit;
        $this->assign('article_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
    
        $this->display();
    }
    
    /**
     * 添加
     */
    public function add ()
    {
        if (IS_POST) {
            $model = D('Store/Article');
            $data = [
                    'article_title' => dhtmlspecialchars($_POST['article_title']),
                    'article_image' => $_POST['img_url'][0],
                    /* 'article_type' => I('article_type'),
                    'article_url' => I('article_url'), */
                    'article_content' => I('article_content'),
                    'article_source' =>I('article_source'),
                    'status' => I('status'),
                   // 'store_id' => $this->store_id,
                    'time' => time()
            ];
            if ($model->add($data)) {
    
                $this->success('添加成功!');
            } else {
                $this->error('添加失败!');
            }
        } else {
    
            $this->display('edit');
        }
    }
    
    /**
     * 编辑
     */
    public function edit ()
    {
        $article_id = (int) I('article_id');
        if (empty($article_id)) {
            $this->error('id 不存在!');
        }
        $model = D('Store/Article');
        if (IS_POST) {
                $data = [
                    'article_title' => dhtmlspecialchars($_POST['article_title']),
                    'article_image' => $_POST['img_url'][0],
                   // 'article_type' => I('article_type'),
                    //'article_url' => I('article_url'),
                    'article_content' => I('article_content'),
                    'article_source' =>I('article_source'),
                    'status' => I('status'),
                    //'store_id' => $this->store_id,
                    'time' => time()
            ];
            if ($model->where([ 'article_id' => $article_id])->save($data)) {
    
                $this->success('编辑成功!');
            } else {
                $this->error('编辑失败!');
            }
        } else {
            $map = [
                'is_del'=>0
            ];
            $article_info = $model->getArticleList($map);
            if(empty($article_info['list'][0])){
                $this->error('数据不存在!');
            }
            $article_info = $article_info['list'][0];
            $album = '';
    
            if (! empty($article_info['article_image'])) {
    
                $album = "<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$article_info['article_image']}' name='img_url[]' />
                <img src='{$article_info['article_image']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
            }
            $article_info['article_content'] = html_entity_decode($article_info['article_content']);
            $this->assign('album', $album);
            $this->assign('article_info', $article_info);
            $this->display('edit');
        }
    }
    
    
    public function del()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = D('Article');
            $map = [
                    'article_id' =>$id
                   
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            }
        }
    }
    
    
}