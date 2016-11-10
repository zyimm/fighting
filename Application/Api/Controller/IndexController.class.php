<?php
/**
 * 
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月9日 上午10:27:34
 */
namespace Api\Controller;

class IndexController extends CommonController
{

    /**
     * 首页接口
     */
    public function index()
    {
       $ad_model = D('Store/Ad');
       $ad_map = [
           'is_del' => 0,
           'status' =>1,
           'store_id' =>static::$store_id
       ];
       $ad_list = $ad_model->getAdList($ad_map,'ad_title,ad_image,ad_url,ad_type');
       
       //道馆品牌信息
       $model = D('Admin/store');
       $field='s.store_id,s.country,s.province,s.city,s.district,
            s.store_name,s.brand_id,b.brand_name';
       $store_map = [
           's.is_del'=>0,
           's.store_status' =>1,
           's.store_id'=>static::$store_id
       ];
       $store_list=$model->getStoreList($store_map,$field);
       foreach ($store_list['list'] as $k=>$v){
           $area = [$v['country'],$v['province'],$v['city'],$v['district']];
           $store_list['list'][$k]['area'] = get_region_info($area);
       }
       
       $data = [
           'banners'=>$ad_list['list'],
           'store_info'=>$store_list['list'][0],
           'list' =>$this->getList()
       ];
       
       if(empty($data)){
           static::jsonData('暂无数据!',0);
       }else{
           static::jsonData($data);
       }
       
    }
    
    /**
     * 首页消息集合
     * @return array
     */
    private function getList()
    {
        /*信息集合*/
        $list = [];
        //通知文章
        $article_model = D('Store/Article');
        $map = [
            'is_del' => 0,
            'store_id' => static::$store_id
        ];
        $article_field = 'article_id,article_title,article_image,time';
        $article_data = $article_model->getArticleList($map,$article_field,1,3);
        $article_data = $article_data['list'];
        if(!empty($article_data)){
            foreach ($article_data as $k=>$v){
                $article_data[$k]['type'] = 'article';
                $article_data[$k]['url'] ='http://'.$_SERVER['HTTP_HOST'].'/api/Article/view?article_id='.$v['article_id'];
            }
            $list[] = $article_data;
        }
        $map = [
            'is_del' => 0,
            'status' =>1,
            'store_id' => 0
        ];
        $article_field = 'article_id,article_title,article_image,time';
        $article_data = $article_model->getArticleList($map,$article_field,1,3);
        $article_data = $article_data['list'];
        if(!empty($article_data)){
            foreach ($article_data as $k=>$v){
                $article_data[$k]['type'] = 'article';
                $article_data[$k]['url'] ='http://'.$_SERVER['HTTP_HOST'].'/api/Article/view?article_id='.$v['article_id'];
            }
            $list[] = $article_data;
        }

        //话题
        $talk_model = D('Brand/Talk');
        $map = [
            't.is_del'=>0,
            'status' =>1,
            't.brand_id'=>static::$brand_id
        ];
        $filed ='t.talk_id,t.talk_title,t.talk_image,t.time';
        $talk_data =  $talk_model->getTalkList($map,$filed,[],1,3);
        $talk_data =  $talk_data['list'];
        if(!empty($talk_data)){
            foreach ($talk_data as $k=>$v){
                $talk_data[$k]['type'] = 'talk';
            }
            $list[] = $talk_data;
        }
        
        return $list;
        
    }
    
    /**
     * 获取道馆信息
     * @param $store_id
     * @url /api/index/getStoreInfo
     */
    public function getStoreInfo()
    {
        
        //道馆品牌信息
        $model = D('Admin/store');
        $field='s.store_id,s.country,s.province,s.city,s.district,
            s.store_name,s.brand_id,b.brand_name';
        $store_map = [
            's.is_del'=>0,
            's.store_status' =>1,
            's.store_id'=>static::$store_id
        ];
        $store_list=$model->getStoreList($map,$field);
        foreach ($store_list['list'] as $k=>$v){
            $area = [$v['country'],$v['province'],$v['city'],$v['district']];
            $store_list['list'][$k]['area'] = get_region_info($area);
        }
        $data = $store_list['list'][0];
        if(!empty($data)){
            static::jsonData($data);
        }else{
            static::jsonData('数据不存在!',1);
        }  
    }
    
   
    /**
     * 
     * @url /api/index/updateVersion
     */
    public function updateVersion()
    {
        $version = I('version');
        $type = I('type');
        if(empty($version) || empty($type)){
            static::jsonData('参数不对!',1);
        }
        $data = [
            'version' =>I('version'),
            'type' =>I('type') 
        ];
        $map = [
            'type' =>$type
        ];
        $field = 'version,url,version_desc,is_update,time';
        $data = M('version')->field($field)->where($map)->find();
        if(empty($data)){
            static::jsonData('没有该版本数据!',1);
        }
        if($data['version']!=$version){
            static::jsonData($data);
        }else{
            static::jsonData('已经是最新版本!',1);
        }
        
    }
    /**
     * 获取匹配的学生
     */
    public function getMatchStudent()
    {
        $map = [
            'student_status'=>1,
            'is_del'=>0,
            'parent_mobile'=>static::$member_info['mobile'],
            'store_id'=>static::$store_id
        
        ];
        $student_info = M('students')->field('student_name,student_sex,student_avatar')->where($map)->find();
        if(!empty($student_info)){
            static::jsonData($student_info);
        }else{
            static::jsonData('未找到匹配的学生!',1);
        }
    }
    
    /**
     * 绑定道馆
     */
    public function bingStore()
    {
        $store_id = (int)I('store_id');
        if(empty($store_id)){
            static::jsonData('道馆id为空!',1);
        }
        if(M('members')->where(['id'=>static::$member_info['id']])->setField('store_id',$store_id)){
            static::jsonData('绑定道馆成功!');
        }else{
            static::jsonData('绑定道馆失败!',1);
        }
        
    }
    /**
     * 搜索道馆
     */
    public function searchStore()
    {
        $store_name = I('store_name');
        if(empty($store_name)){
            static::jsonData('道馆名称为空!',1);
        }
        $map['store_name'] = ['like','%'.addslashes($store_name).'%'];
        $map['store_status'] = 1;
        $map['is_del'] = 0;
        $data = M('store')->field('store_id,store_name')->where($map)->select();
        if(empty($data)){
            static::jsonData('暂无数据!',1);
        }else{
            static::jsonData($data);
        }
    }
    
    public function getStudentByMember()
    {
        $mobile = static::$member_info['mobile'];
        $map['_query'] = "parent_mobile={$mobile}&self_mobile={$mobile}&_logic=or";
        
        $student_info = M('students')->field('student_id,student_name,student_name,student_avatar')->where($map)->find();
        if($student_info){
            static::jsonData($student_info);
        }else{
            static::jsonData('暂无数据!',1);
        }
    }
   
}