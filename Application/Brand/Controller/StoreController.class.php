<?php
/**
 * 道馆控制器
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月12日 上午9:40:42
 */
namespace Brand\Controller;
use Lib\QRcode;
class StoreController extends CommonController
{
    /**
     * 二维码
     * @param number $store_id
     */
    public function getStoreCode($store_id=0)
    {
        $store_id=I("store_id");
        //http://gnd.chengpai.net.cn/reg.html?store_id=4
        //生成推荐二维码
        $qrcodeRedirect='';
        $qrcodeRedirect="http://".$_SERVER['HTTP_HOST'].'/reg.php?store_id='.$store_id;
        QRcode::png($qrcodeRedirect,false,'H',4,2);
    }
    
    /**
     * 列表
     */
    public function index()
    {
        $model = D('Admin/store');
        $field='s.*,b.brand_name';
        $map['s.is_del']=0;
        $map['store_id'] = ['in',$this->store_id_gather];
        $store_list=$model->getStoreList($map,$field);
        $this->assign('store_list',$store_list);
        $this->display();
    }

    /**
     * 添加
     */
    public function add()
    {
        $model = D('Admin/store');
        if (IS_POST) {
            if ($model->create()) {
                $model->time = time();
                if ($model->add()) {
                    $this->success('数据修改成功!', __CONTROLLER__);
                } else {
                    $this->error('数据修改失败!');
                }
            } else {
                $this->error('数据对象创建失败!');
            }
        } else {
            $map = [
                'brand_id' =>$this->brand_id,
                'brand_status' => 1,
                'is_del' => 0
            ];
            $brand_temp = M('brand')->where($map)->select();
            $brand = [];
            foreach ($brand_temp as $k => $v) {
                $brand[$v['brand_id']] = $v['brand_name'];
            }
            // dump($brand);
            $this->assign('brand', $brand);
            $this->display('edit');
        }
    }
    /**
     * 编辑
     */
    public function edit()
    {
        $model = D('Admin/store');
        if (IS_POST) {
            if ($model->create()) {
                $model->time = time();
                if ($model->save()) {
                    $this->success('数据修改成功!');
                } else {
                    //echo $model->getLastSql();
                    $this->error('数据修改失败!');
                }
            } else {
                $this->error('数据对象创建失败!');
            }
        } else {
            $store_id = (int) $_REQUEST['store_id'];
            if (empty($store_id))
                $this->error('id不存在!');
            $store_info = $model->where([
                'store_id' => $store_id,
                'is_del' => 0
            ])->find();
            $map = [
                'brand_id' =>$this->brand_id,
                'brand_status' => 1,
                'is_del' => 0
            ];
            $brand_temp = M('brand')->where($map)->select();
            $brand = [];
            foreach ($brand_temp as $k => $v) {
                $brand[$v['brand_id']] = $v['brand_name'];
            }
            // dump($brand);
            $this->assign('brand', $brand);
            $this->assign('store_info', $store_info);
            $this->display();
        }
    }
    
    public function del()
    {
       
        $store_id = (int) $_REQUEST['store_id'];
        if (empty($store_id))  $this->error('id不存在!');
        $model = D('Admin/store');
        if($model->where(['store_id'=>$store_id])->setField('is_del',1)){
            $this->success('数据修改成功!');
        }else{
            //echo $model->getLastSql();
            $this->error('数据修改失败!');
        }
    }
    
    public function storeAdminList()
    {
        
        $model = D('Admin/store');
        $map = [
            'su.is_del'=>0,
            'su.role_id'=>1,
            'su.brand_id'=>$this->brand_id,
        ];
        $field = 'su.*,s.store_name';
        $admin_list = $model->getStoreAdminList($map,$field);
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
            $model = M('store_user');
            $map = [
                'role_id' =>1,
                'store_id' =>(int)$_POST['store_id']
            ];
    
            if($model->where($map)->count()){
                $this->error('该品牌管理员已经存在!');
            }
            //添加品牌管理员
            if($model->create()){
                if($model->where(['email'=>$model->email])->count()){
                    
                    $this->error('邮箱已存在!');
                }
                $model->role_id = 1;
                //$model->expire_time = strtotime($model->expire_time);
                $model->brand_id = $model->brand_id;
                if(!empty($this->password)){
                    $model->password = md5($model->password);
                }else{
                    $this->error('密码不能为空!');
                }
               
                if($model->add()){
                    $this->success('添加成功!');
                }else{
                    $this->error('添加失败!');
                }
            }else{
                $this->error('数据对象创建失败!');
            }
        }else{
            $model = D('Admin/store');
            $map = [
                'b.brand_id' =>$this->brand_id,
                's.store_id' =>['in',$this->store_id_gather],
                's.is_del' =>0
            ];
            $store_temp = $model->getStoreList($map,'s.store_id,store_name');
            $store = [];
            foreach ( $store_temp as $v){
                $store[$v['store_id']] = $v['store_name'];
            }
            
            $this->assign('store',$store);
            $this->display('editAdmin');
        }
    }
    
    
    public function editAdmin()
    {
        $admin_id = (int)I('admin_id');
        if (empty($admin_id)) {
            $this->error('id 不存在!');
        }
        $model = M('store_user');
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
                if(!empty($this->password)){
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
            $model = D('Admin/store');
            $map = [
                'id'=>$admin_id,
                'status' =>1,
                'brand_id'=>$this->brand_id,
                'is_del' =>0
            ];
            
            $field = 'su.*';
            $map = [
                    'su.is_del'=>0,
                    'su.role_id'=>1,
                    'su.brand_id'=>$this->brand_id,
            ];
            $admin_list = $model->getStoreAdminList($map,$field);
            $admin_info = $admin_list[0];
            if(empty($admin_info)){
                $this->error('数据不存在!');
            }
            $map = [
                    'b.brand_id' =>$this->brand_id,
                    's.store_id' =>['in',$this->store_id_gather],
                    's.is_del' =>0
            ];
            $store_temp = $model->getStoreList($map,'s.store_id,s.store_name');
            $store = [];
            foreach ( $store_temp as $v){
                $store[$v['store_id']] = $v['store_name'];
            }
           // $admin_info['expire_time'] = date('Y-m-d H:i:s',$admin_info['expire_time']);
            $this->assign('store',$store);
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
            $model = D('store_user');
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