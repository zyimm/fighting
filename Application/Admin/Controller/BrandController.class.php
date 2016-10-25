<?php
/**
 * 
 * 品牌管理
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月11日 上午10:20:31
 */
namespace Admin\Controller;

class BrandController extends CommonController
{

    public function index()
    {
        $brand = M('brand')->where([
            'brand_status' => 1,
            'is_del' => 0
        ])->select();
        $this->assign('brand', $brand);
        $this->display();
    }

    /**
     * 添加
     */
    public function add()
    {
        $model = M('brand');
        
        if (IS_POST) {
            //dump($_POST);exit;
            if ($model->create()) {
                $model->time = time();
                if ($model->add()) {
                   $this->success('数据修改成功!',__CONTROLLER__);
                }else{
                        
                   $this->error('数据修改失败!');
               }
            } else {
                $this->error('数据对象创建失败!');
            }
        } else {
            
            $this->display('edit');
        }
    }

    /**
     * 修改
     */
    public function edit()
    {
        $model = M('brand');
        if (IS_POST) {
            if ($model->create()) {
                $model->time = time();
                if ($model->brand_id) { // 更新
                    if ($model->save()) {
                        $this->success('数据修改成功!');
                    } else {
                        
                        $this->error('数据修改失败!');
                    }
                } else { // 添加
                    if ($model->add()) {
                        $this->success('数据修改成功!');
                    } else {
                        
                        $this->error('数据修改失败!');
                    }
                }
            } else {
                $this->error('数据对象创建失败!');
            }
        } else {
            $brand_id = (int) $_REQUEST['brand_id'];
            if (empty($brand_id)){
                $this->error('id不存在!');
            }
                
            $brand_info = M('brand')->where([
                'brand_id' => $brand_id,
                'is_del' => 0
            ])->find();
            $this->assign('brand_info', $brand_info);
            $this->display();
        }
    }
    
    public function brandAdminList()
    {
        $model = D('Store');
        $map = [
            'bu.is_del'=>0 
        ];
        $field = 'bu.*,b.brand_name';
        $admin_list = $model->getBrandAdminList($map,$field);
        $this->assign('admin_list', $admin_list);
        $this->display('adminList');
    }
    /**
     * 添加平台管理员
     */
    public function addAdmin()
    {
        if(IS_POST){
            //判断
            $model = M('brand_user');
            $map = [
                'role_id' =>1,
                'brand_id' =>(int)$_POST['brand_id']  
            ];
            
            if($model->where($map)->count()){
                $this->error('该品牌管理员已经存在!');
            }
            //添加品牌管理员
            if($model->create()){
                if($model->where(['email'=>$model->email])->count()){
                    $this->error('邮箱已存在!');
                }
                if(!empty($model->password)){
                    $model->password = md5($model->password);
                }else{
                    $this->error('密码不能为空!');
                }
                $model->role_id = 1;
                $model->expire_time = strtotime($model->expire_time);
               
                if($model->add()){
                    $this->success('添加成功!');
                }else{
                    $this->error('添加失败!');
                }

            }else{
                
                $this->error('数据对象创建失败!');
            }
        }else{
            $model = D('store');
            $map = [
                'brand_status' =>1,
                'is_del' =>0          
            ];
            $brand_temp = $model->getBrandList($map,'brand_id,brand_name');
            $brand = [];
            foreach ( $brand_temp as $v){
                $brand[$v['brand_id']] = $v['brand_name'];           
            }
            $this->assign('brand',$brand);
            $this->display('editAdmin');
        }
    }
    
    
    public function editAdmin()
    {
        $admin_id = (int)I('admin_id');
        if (empty($admin_id)) {
            $this->error('id 不存在!');
        }
        $model = M('brand_user');
        if (IS_POST) {
            if($model->create()){
                if($model->where(['email'=>$model->email,'id'=>['neq',$admin_id]])->count()){
                    $this->error('邮箱已被占用!');
                }
                $model->expire_time =strtotime($model->expire_time);
                $model->login_time = time();
                $map = [
                    'id' =>$admin_id,
                ];
                if(!empty($model->password)){
                    $model->password = md5($model->password);
                }else{
                    unset($model->password);
                }
                if($model->where($map)->save()){
                    $this->success('编辑成功!');
                }else{
                    $this->error('编辑失败!');
                }
            
            }else{
            
                $this->error('数据对象创建失败!');
            }
        } else {
            $model = D('store');
            $map = [
                'bu.id'=>$admin_id,
                'b.brand_status' =>1,
                'bu.is_del' =>0
            ];
            $field = 'bu.*,b.brand_name';
            $admin_list = $model->getBrandAdminList($map,$field);
            $admin_info = $admin_list[0];
            
            $map = [];
            $brand_temp = $model->getBrandList($map,'brand_id,brand_name');
            $brand = [];
            foreach ( $brand_temp as $v){
                $brand[$v['brand_id']] = $v['brand_name'];
            }
            $admin_info['expire_time'] = date('Y-m-d H:i:s',$admin_info['expire_time']);
            $this->assign('brand',$brand);
            $this->assign('admin_info',$admin_info);
            $this->display('editAdmin');
       }
    }
   
    
    public function delAdmin()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = D('brand_user');
            $map = [
                'id' =>$id 
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            }
        }
    }
}