<?php
/**
 * 平台角色管理
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月12日 下午1:55:24
 */
namespace Admin\Controller;

class RoleController extends CommonController
{
    
    
    public function index()
    { 
        $model=D('Admin');
        $role_list=$model->getRoleList($map,$field);
        $this->assign('role_list',$role_list);
        $this->display();
    }
    
    public function adminList()
    {
        $model=D('Admin');
        $map=[
            'a.is_del'=>0,
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
        $model=M('auth_group');
        if(IS_POST){
            if($model->create()){
                if($model->add()){
                    
                    $this->success('数据修改成功!');
                }else{
                    $this->error('数据修改失败!');
                    
                }
            }else{
                
               $this->error('数据创建成功!'); 
            }
        }else{
            
            $this->display('edit');
        }
    }
    
    public function edit()
    {
        $role_id=(int)$_REQUEST['role_id'];
        if(empty($role_id)) $this->error('id不存在!');
        $model=M('auth_group');
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
            $role_info=$model->where(['id'=>$role_id])->find();//dump($role_info);
            $this->assign('role_info',$role_info);
            $this->display('edit');
        }
        
        
    }
    /**
     * 添加管理员
     */
    public function addAdmin()
    {
        $model=D('Admin');
        if(IS_POST){
            if($model->create()){
                if(!empty($this->password)){
                    $model->password = md5($model->password);
                }else{
                    $this->error('密码不能为空!');
                }
                $model->login_time = time();
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
            $role_list_temp = $model->getRoleList(['id'=>['neq',1],'status'=>1]);
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
        $model=D('Admin');
        if(IS_POST){
            if($model->create()){ 
                if(!$model->checkEmail($admin_id,$model->email)){
                    $this->error('邮箱已经被占用!');
                }
                $role_id = $model->role_id;
                if(!empty($model->password)){
                    $model->password = md5($model->password);
                }else{
                    unset($model->password);
                }
                $model->login_time = time();
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
            $role_list_temp = $model->getRoleList(['id'=>['neq',1],'status'=>1]);
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
          $model = M('auth_group');
         
          if($model->where(['id'=>$role_id])->setField('rules',$auth_ids)){
              $this->success('更新数据成功!');
          
          }else{
          
              $this->error('更新数据失败或没有更新!');
          }
         
          
          $this->success($auth_ids); 
       }else{
           
           
           $model=D('Admin');
           $auth = $model->getAuthList($role_id); 
           $this->assign('role_id',$role_id);
           $this->assign('auth_list',$auth);
           $this->display();
       }
    }
    
    
    
    public function checkRole()
    {
        $model=M('auth_group');
        $role_name=I('param');
        if($model->where(['title'=>$role_name])->count()){
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
        $model=D('Admin');
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