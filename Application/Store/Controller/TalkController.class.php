<?php
/**
 * 
 * 道馆话题控制器
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月17日 下午5:37:08
 */
namespace Store\Controller;

class TalkController extends CommonController
{
    public function index()
    {
        $model = D('Brand/Talk');
        $field = 't.id,t.talk_id,t.images,t.comment,t.status,t.time,m.nick_name,m.avatar,s.store_name';
        $map = [
            't.status'=>1,
            't.store_id'=>$this->store_id
        ];
        $data = $model->getTalkComment($map,$field,$page_now);
        foreach ($data['list'] as $k=>$v){
            $data['list'][$k]['talk_title'] = M('talk')->where(['talk_id'=>$v['talk_id']])->getField('talk_title');
        }
        $this->assign('talk_list',$data['list']);
        $this->assign('page_show',$data['page_show']);
        $this->display();
    }
    /**
     * 
     */
    public function edit()
    {
        $id = (int)$_REQUEST['id'];
        $model = D('Brand/Talk');
        if(IS_POST){
            $model = M('talk_comment');
            $comment_data =[
                'comment'=>I('comment'),
                'status'=>(int)I('status'),
                'images'=>join(',',$_POST['img_url']),
                'time'=>time()
            ];
            $map = [
                'store'=>$this->store_id,
                'id'=>$id
            ];
           if($model->where($map)->save($comment_data)){
               $this->success('编辑成功!');
           }else{
               $this->error('编辑失败!');
           }
        }else{
            $field = 't.id,t.talk_id,t.images,t.comment,t.status,
                t.time,m.nick_name,m.avatar,s.store_name';
            $map = [
                't.id'=>$id,
                't.status'=>1,
                't.store_id'=>$this->store_id
            ];
            $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
            $data = $model->getTalkComment($map,$field,$page_now);
            foreach ($data['list'] as $k=>$v){
                $data['list'][$k]['talk_title'] = M('talk')->where(['talk_id'=>$v['talk_id']])->getField('talk_title');
            
            }
            $talk_info = $data['list'][0];
            if(empty($talk_info)){
                $this->error('暂无数据!');
            }
            $talk_info['album'] = explode(',',$talk_info['album']);
            $album = '';
            foreach ($talk_info['album'] as $key=>$val){
                if(empty($val)){
                    $val ='#';
                    
                }
                $album.="<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$val}' name='img_url[]' />
                <img src='{$val}' alt='' onerror=\"javascript:this.src='/Public/Common/Images/no-img.png';\"  class='radius-big' width='88' height='88' />
                </div>";
            }
             
            $this->assign('album',$album);
            $this->assign('talk_info',$talk_info);
            $this->display();
        }
    }
    
    public function del()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = M('talk_comment');
            $map = [
                'id' =>$id,
                'store_id' =>$this->store_id
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            }
        }
    }
}