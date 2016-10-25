<?php
/**
 * 
 * 品牌设置
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月11日 上午10:25:55
 */
namespace Brand\Controller;

use Think\Controller;

class SettingController extends CommonController
{
    /**
     * 品牌信息
     */
    public function index()
    {
        if(IS_POST){
            $model = M('brand');
            $data = [];
            $brand_id =(int)$_REQUEST['brand_id'];
            if(empty($brand_id)){
                $this->error('brand_id 不存在!');
                
            }   
            $data = [
                'brand_title' =>dhtmlspecialchars($_POST['brand_title']),
                'brand_image' =>$_POST['img_url'][0],
                'content'   => $_POST['content'],
                'time'      => time()
            ];
            
            if($model->where(['brand_id' => $brand_id])->save($data)){
                $this->success('数据处理成功!');
                
            }else{  
                $this->success('数据处理失败!');
            }
        }else{
            $album='';
           
            $brand_info = D('store')->getStoreInfo($this->brand_admin_info['brand_id']);
            if(!empty($brand_info['brand_image'])){
                
                $album="<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$brand_info['brand_image']}' name='img_url[]' />
                <img src='{$brand_info['brand_image']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
            }
            $this->assign('brand_info',$brand_info);
            $this->assign('album',$album);
            $this->display();
        }
        
    }  
    /**
     * 修改密码
     */
    public function modifyPass()
    {
        $model = M('brand_user');
       
        if(IS_POST){
            if($model->create()){
                if(!filter_var($model->email, FILTER_VALIDATE_EMAIL)){
                    $this->error('邮箱格式不对!');
                }
                $model->password=md5($model->password);
                if($admin_info['password']== $model->password){
                    if(!$model->checkEmail($this->admin_id,$model->email)){
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
            $admin_info=$model->where(['id'=>$this->admin_id])->find();
            $admin_info['role_name'] = M('brand_auth_group')->where(['id'=>$admin_info['role_id']])->getField('title');
            $this->assign('admin_info',$admin_info);
            $this->display();
        }
    
    
    }
}
