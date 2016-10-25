<?php
/**
 * 话题
*
* @author zyimm <799783009@qq.com>
* Copyright (c) 2016 http://www.zyimm.com All rights reserved.
* 2016年10月18日 上午8:43:56
*/
namespace Brand\Controller;


class TalkController extends CommonController
{
    /**
     * 话题列表
     */
    public function index()
    {
        $model = D('Brand/Talk');
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $map = [
            't.is_del'=>0,
            't.brand_id'=>$this->brand_id   
                
        ];
        $filed ='t.*,b.brand_id,brand_name';
        $row =  $model->getTalkList($map,$filed,[],$page_now);
       
        $this->assign('talk_list',$row['list']);
        $this->assign('page_show', $row['page_show']);
        $this->display();
    }
    public function add()
    {
        if(IS_POST){

            $model = D('Brand/Talk');
            //判断
            $map = [
                    'role_id' =>1,
                    'brand_id' =>$this->brand_id
            ];
            if(empty($_POST['talk_title'])){
                $this->error('话题标题不能为空!');
            }else{
                $talk_title = dhtmlspecialchars($_POST['talk_title']);
            }

            //添加品牌管理员
            if($model->create()){
                $map = [
                        'talk_title' =>$talk_title,
                        'brand_id' =>$this->brand_id
                ];
                if($model->where($map)->count()){
                    $this->error('该话题同名称已存在!');
                }
                $model->talk_image = empty($_POST['img_url'][0])?'':dhtmlspecialchars($_POST['img_url'][0]);
                $model->brand_id = $this->brand_id;
                $model->time = time();
                if($model->add()){
                    $this->success('添加成功!');
                }else{
                    $this->error('添加失败!');
                }

            }else{

                $this->error('数据对象创建失败!');
            }
        }else{
           
            $this->display('edit');
        }

    }

    public function edit()
    {

        $talk_id = (int)$_REQUEST['talk_id'];
        if(empty($talk_id)){
            $this->error('话题id不存在!');
        }
        $model = D('Brand/Talk');
        if(IS_POST){
            //判断
            $map = [
                    'role_id' =>1,
                    'brand_id' =>$this->brand_id
            ];
            if(empty($_POST['talk_title'])){
                $this->error('话题标题不能为空!');
            }else{
                $talk_title = dhtmlspecialchars($_POST['talk_title']);
            }

            //添加品牌管理员
            if($model->create()){
                $map = [
                        'talk_title' =>$talk_title,
                        'talk_id' =>['neq',$talk_id]
                ];
                if($model->where($map)->count()){
                    $this->error('该话题同名称已存在!');
                }
                $model->talk_image = empty($_POST['img_url'][0])?'':dhtmlspecialchars($_POST['img_url'][0]);
                $model->time = time();
                $model->brand_id = $this->brand_id;
                if($model->where(['talk_id'=>$talk_id])->save()){
                    $this->success('编辑成功!');
                }else{
                    $this->error('编辑失败!');
                }

            }else{

                $this->error('数据对象创建失败!');
            }


        }else{
             
            $map = [
                    't.talk_id'=>$talk_id,
                    't.is_del'=>0
            ];

            $talk_info = $model->getTalkLIst($map,$field);
            $talk_info = $talk_info['list'][0];
            if(empty($talk_info)){
                $this->error('数据不存在!');
            }
            $brand_model =D('Admin/Store');
            $map = [
                    'is_del'=>0,
                    'status'=>1
            ];
            $brand_temp = $brand_model->getBrandList($map,'brand_id,brand_name');
            $brand = [];
            foreach ( $brand_temp as $v){
                $brand[$v['brand_id']] = $v['brand_name'];
            }
            $this->assign('brand',$brand);
            $talk_info['talk_content'] = html_entity_decode($talk_info['talk_content']);
            
            //头像
            if(!empty($talk_info['talk_image'])){
            
                $album="<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$talk_info['talk_image']}' name='img_url[]' />
                <img src='{$talk_info['talk_image']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
            }
            $this->assign('album',$album);
            
            $this->assign('talk_info',$talk_info);
            $this->display('edit');

        }
    }
    
    /**
     * 删除
     */
    public function del()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = D('Talk');
            $map = [
                    'talk_id' =>$id,
                    'brand_id' =>$this->brand_id
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            } 
        }
    }
    
    /**
     * 话题评论
     */
    public function comment()
    {
        //
    }

}