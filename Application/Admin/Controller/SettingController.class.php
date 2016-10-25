<?php
/**
 * 
 * 平台设置
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月11日 上午10:25:55
 */
namespace Admin\Controller;

use Think\Controller;

class SettingController extends CommonController
{
    /**
     * app
     */
    public function app()
    {
        $version = M("version")->select();
        $this->assign('version',$version);
        $this->display();
    }  
    /**
     * app版本管理
     */
    public function appVersion()
    {
        $id=(int)$_REQUEST['id'];
        $model = M('version');
        if(empty($id)){
            $this->error('id 不存在!');
        }
        
        if(IS_POST){
            if($model->create()){
            
                if($model->save()){
                    $this->success('数据修改成功!');
                }else{
                    $this->error('数据修改失败!');
                }
            }else{
                $this->error('数据创建成功!');
            }   
        }else{
            $version_info = $model->where(['id'=>$id])->find();
            
            $this->assign('version_info',$version_info);
            $this->display();
        }
    }
    
    /**
     * 修改密码
     */
    public function modifyPass()
    {
        $model = D('Admin');
        $admin_info=$model->where(['id'=>$this->admin_id])->find();//dump($role_info);
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
            $admin_info = $model->getAdminInfo($this->admin_id)  ;
            $this->assign('admin_info',$admin_info);
            $this->display();
        }
        
        
    }
}
