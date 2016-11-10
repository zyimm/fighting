<?php
/**
 * 
 * 话题
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月31日 上午9:09:30
 */
namespace Api\Controller;


class TalkController extends CommonController
{
    /**
     * 列表
     */
    public function index()
    {
        $model = D('Brand/Talk');
       
        $map = [
            't.is_del'=>0,
            'status'=>1,
            't.brand_id'=>static::$brand_id
        ];
        $filed ='t.talk_id,t.talk_title,t.talk_image,t.talk_content';
        $row =  $model->getTalkList($map,$filed,[],1,1000); 
        $row['count'] = count($row['list']);
        unset($row['page_show']);
        static::jsonData($row);
    }
    /**
     * 话题详细
     */
    public function view()
    {
        $talk_id = (int)I('talk_id');
        if(empty($talk_id)){
            static::jsonData('talk_id 不存在！',1);
        }
        $page_now = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $model = D('Brand/Talk');
        $map = [
            't.is_del'=>0,
            't.status'=>1,
            't.talk_id'=>$talk_id,
            't.brand_id'=>static::$brand_id
        ];
        $data = [];
        $filed ='t.*';
        $row =  $model->getTalkList($map,$filed,[],1);
        if(empty($row['list'][0])){
            static::jsonData('数据不存在!',1);
        }else{
            $map = [
                't.status'=>1,
                't.talk_id'=>['in',$talk_id]
            
            ];
            $field = 't.talk_id,t.images,t.comment,t.time,m.nick_name,m.avatar,s.store_name';
            $data['talk'] = $row['list'][0];
            $data['comment'] = $model->getTalkComment($map,$field,$page_now);
            $data['comment'] = $data['comment']['list'];
            $data['is_follow'] = whether_follow($talk_id,'talk',static::$member_id);
            static::jsonData($data);
        }
    }
    /**
     * 话题评论列表
     */
    public function commentList()
    {
        $talk_id = (int)I('talk_id');
        if(empty($talk_id)){
            static::jsonData('talk_id 不存在！',1);
        }
        $model = D('Brand/Talk');
        $page_now = empty($_REQUEST['page'])?1:$_REQUEST['page'];
        $field = 't.talk_id,t.images,t.comment,t.time,m.nick_name,m.avatar,s.store_name';
        $map = [
            't.status'=>1,
            't.talk_id'=>['in',$talk_id]
        ];
        $data = $model->getTalkComment($map,$field,$page_now);
        $data= $data['list'];
        static::jsonData($data);
    }
    
    /**
     *添加评论
     */
    public function addComment(){
    
        $comment=I("comment");
        $image1=I("image1");
        $image2=I("image2");
        $image3=I("image3");
        $talk_id=I("talk_id");
        //根据话题 找到这个话题属于那个品牌
         
        $brand_id = M('talk')->where(['talk_id'=>$talk_id])->getField('brand_id');
        if(M('store')->where(['store_id'=>$store_id])->getField('brand_id') == $brand_id){
            if(static::$member_info['utype']<2){//普通用户
                if(empty(static::$member_info['student_id'])){
                    static::jsonData('您无权限添加评论-!',1);
                }
            
            }
        }else{
            static::jsonData('您无权限添加评论-!',1);
        }
         
        if(empty($comment)){
            if(empty($image1) && empty($image2) && empty($image3))$this->returnJsonp('-2','最少上传一张图片');
        }
    
        if(empty($talk_id)){
            static::jsonData('评论id不能为空！',1);
        }
        if(M('talk_comment')->where(['talk_id'=>$talk_id,'uid'=>static::$member_info['id'],'comment'=>strip_tags($comment)])->count()){
            static::jsonData('请不要重复评论!',1);
        }
        $data=array(
            'talk_id'=>$talk_id,
            'uid'=>static::$member_info['id'],
            'comment'=>strip_tags($comment),
            'images'=>$image1.','.$image2.','.$image3,
            'time'=>time(),
            'status'=>1,
            'store_id'=>static::$member_info['store_id']
        );
        $r = M('talk_comment')->add($data);
        if($r){
            static::jsonData('评论成功!');
        }else{
            static::jsonData('添加失败!',1);
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