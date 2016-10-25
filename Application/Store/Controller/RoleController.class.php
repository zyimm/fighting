<?php
/**
 * 道馆终端角色管理
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月12日 下午1:55:24
 */
namespace Store\Controller;

class RoleController extends CommonController
{
    
    
    public function index()
    { 
        $model=D('Store');
        $map = [
          'store_id' => $this->store_id,   
        ];
        $role_list=$model->getRoleList($map,$field);
        $this->assign('role_list',$role_list);
        $this->display();
    }
    
    public function adminList()
    {
        $model=D('Store');
        $map=[
            'a.is_del'=>0,
            'a.store_id'=>$this->store_id,
            'r.status'=>1
        ];
        $field="a.*,r.title as role_name,r.status";
        $admin_list=$model->getAdminList($map,$field);
        $this->assign('admin_list',$admin_list);
        $this->display();

    }
    
    /**
     * 添加角色
     */
    public function add()
    {
        $model=M('store_auth_group');
        if(IS_POST){
            if($model->create()){
                $model->store_id = $this->store_id;
                if($model->where(['title'=>$this->title,'store_id'=>$this->store_id])->count()){
                    $this->error('角色名称被占用!');
                }
                if($model->add()){     
                    $this->success('数据修改成功!');
                }else{
                    $this->error('数据修改失败!');     
                }
            }else{
               $this->error('数据创建成功!'); 
            }
        }else{
            session('is_role_edit',null);
            $this->display('edit');
        }
    }
    
    public function edit()
    {
        $role_id=(int)$_REQUEST['role_id'];
        if(empty($role_id)) $this->error('id不存在!');
        $model=M('store_auth_group');
        if(IS_POST){
            $map = [
                'store_id'=>$this->store_id,
                'id' =>$role_id   
            ];
            if($model->create()){ 
                $model->store_id = $this->store_id;
                $where = [
                        'title' => $this->title,
                        'store_id' => $this->store_id,
                        'id' => ['neq',$role_id]
                ];
                if($model->where($where)->count()){
                    $this->error('角色名称被占用!');
                }
                if($model->where($map)->save()){
                    $this->success('数据修改成功!');
                }else{
                    $this->error('数据修改失败!');
                }
            }else{
                $this->error('数据创建成功!');
            }
        }else{
            $map = [
                'id'=> $role_id,
                'store_id' => $this->store_id
            ];
            $role_info=$model->where($map)->find();//dump($role_info);
            session('is_role_edit',1);
            $this->assign('role_info',$role_info);
            $this->display('edit');
        }
        
        
    }
    /**
     * 添加管理员
     */
    public function addAdmin()
    {
        $model=D('Store');
        if(IS_POST){
            if($model->create()){
                if(!empty($model->password)){
                    $model->password = md5($model->password);
                }else{
                    $this->error('密码不能为空!');
                }
                $model->login_time = time();
                $model->store_id = $this->store_id;
                $model->brand_id = $this->brand_id;
                if($model->add()){
                    $this->success('数据修改成功!');
                }else{
                    //$this->error($model->getLastSql());
                    $this->error('数据修改失败！');
                }
            }else{
                $this->error('数据创建成功!');
            }
        
        
        }else{
            $role_list=[];
            $map=['is_root'=>['neq',1],'store_id'=>$this->store_id,'status'=>1];
            $role_list_temp = $model->getRoleList($map);
            foreach ($role_list_temp as $k=>$v){
                $role_list[$v['id']]=$v['title'];
            }
            $this->assign('role_list',$role_list);
            $this->assign('is_add',1);
            $this->display('editAdmin');
        }
    }
    /**
     * 编辑管理员
     */
    public function  editAdmin()
    {
        $admin_id=(int)$_REQUEST['admin_id'];
        if(empty($admin_id) || $admin_id==1 ) $this->error('id不存在!');
        $model=D('Store');
        if(IS_POST){
            if($model->create()){ 
                if(!$model->checkEmail($admin_id,$model->email)){
                    $this->error('邮箱已经被占用!');
                }
                if(!empty($model->password)){
                    $model->password = md5($model->password);
                }else{
                    unset($model->password);
                }
                $role_id = $model->role_id;
                if($model->save()){
                    $model->bingRole($admin_id,$role_id);
                    $this->success('数据修改成功!');
                }else{
                    $this->error('数据修改失败！');
                }
            }else{
                $this->error('数据创建成功!');
            }
        }else{
            $admin_info=$model->where(['id'=>$admin_id])->find();//dump($role_info);
            $role_list=[];
            $map=['is_root'=>['neq',1],'store_id'=>$this->store_id,'status'=>1];
            $role_list_temp = $model->getRoleList($map);
            foreach ($role_list_temp as $k=>$v){
                $role_list[$v['id']]=$v['title'];
            }
            $this->assign('role_list',$role_list);
            $this->assign('admin_info',$admin_info);//dump($admin_info);
            $this->display('editAdmin');
        }
    }
    
    
    
    /**
     * 更新角色的权限
     * @param number $role_id
     */
    public function roleRule()
    {
       $role_id=(int)$_REQUEST['role_id'];
       if($role_id <=1){
           $this->error('role_id 错误!');
       } 
       if(IS_POST){
          $auth_ids = join(',',$_POST['auth']);
          $model = M('store_auth_group');
         
          if($model->where(['id'=>$role_id])->setField('rules',$auth_ids)){
              $this->success('更新数据成功!');
          
          }else{
          
              $this->error('更新数据失败或没有更新!');
          }
         
          
          $this->success($auth_ids); 
       }else{
           
           
           $model=D('Store');
           $auth = $model->getAuthList($role_id); 
           $this->assign('role_id',$role_id);
           $this->assign('auth_list',$auth);
           $this->display();
       }
    }
    
    
    
    public function checkRole()
    {
        $model=M('store_auth_group');
        $role_name=I('param');
        $nums = $model->where(['title'=>$role_name])->count();
        if(session('is_role_edit')){//
            if($nums == 1){
                $nums = 0;
            }
        }
        if($nums == 1){
            $data=[
                'info'=>'角色名称被占用!',
                'status'=>'n'
            ];
        }else{
            $data=[
              'info'=>'可以使用!',
              'status'=>'y' 
            ];
        }
       
        //echo $model->getLastSql();
        echo json_encode($data);
    }
    
    public function checkEmail()
    {
        $model=D('Store');
        $email=I('param');
        $data=[];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $data=[
                'info'=>'格式不对!',
                'status'=>'n'
            ];
            echo json_encode($data);exit;
        }
        if($model->where(['email'=>$email])->count()){
            $data=[
                'info'=>'邮箱被占用!',
                'status'=>'n'
            ];
        }else{
            $data=[
                'info'=>'可以使用!',
                'status'=>'y'
            ];
        }
         
        //echo $model->getLastSql();
        echo json_encode($data);
    }
    
}