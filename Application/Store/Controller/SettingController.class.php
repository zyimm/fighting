<?php
/**
 * 
 * 道馆设置
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月11日 上午10:25:55
 */
namespace Store\Controller;

use Think\Controller;

class SettingController extends CommonController
{
    /**
     * 场馆信息
     */
    public function index()
    {
        if(IS_POST){
            $model = M('store');
            $data = [];
            $store_id =(int)$_REQUEST['store_id'];
            if(empty($store_id)){
                $this->error('store_id 不存在!');
                
            }   
            $data = [
                'store_title' =>dhtmlspecialchars($_POST['store_title']),
                'store_image' =>$_POST['img_url'][0],
                'content'   => $_POST['content'],
                'time'      => time()
            ];
            
            if($model->where(['store_id' => $store_id])->save($data)){
                $this->success('数据处理成功!');
                
            }else{  
                $this->success('数据处理失败!');
            }
        }else{
            $album='';
           
            $store_info = D('store')->getStoreInfo($this->store_admin_info['store_id']);
            if(!empty($store_info['store_image'])){
                
                $album="<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$store_info['store_image']}' name='img_url[]' />
                <img src='{$store_info['store_image']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
            }
            $this->assign('store_info',$store_info);
            $this->assign('album',$album);
            $this->display();
        }
        
    }  
    /**
     * 修改密码
     */
    public function modifyPass()
    {
        $model = D('Store');
        $admin_info=$model->where(['id'=>$this->store_admin_id])->find();//dump($role_info);
        if(IS_POST){
            if($model->create()){
                if(!filter_var($model->email, FILTER_VALIDATE_EMAIL)){
                    $this->error('邮箱格式不对!');
                }
                $model->password=md5($model->password);
                if($admin_info['password']== $model->password){
                    if(!$model->checkEmail($this->store_admin_id,$model->email)){
                        $this->error('邮箱已经被占用!');
                    }
                    $model->password = md5($_POST['new_password']);
                    $model->login_time = time();
                    if($model->save()){
                        $this->success('数据修改成功!');
                    }else{
                        $this->error('数据修改失败！');
                    }
                }else{
                    $this->error('旧密码不对!');
                }
    
            }else{
    
                $this->error('数据对象创建失败!');
            }
    
    
        }else{
            $admin_info = $model->getStoreAdminInfo($this->store_admin_id)  ;
            $this->assign('admin_info',$admin_info);
            $this->display();
        }
    
    
    }
}
