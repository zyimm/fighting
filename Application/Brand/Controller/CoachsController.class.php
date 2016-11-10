<?php
/**
 * 
 * 教练管理
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月19日 上午11:29:20
 */
namespace Brand\Controller;

class CoachsController extends CommonController
{
    
    public function index()
    {
        
        $model = D('Store/Coachs');
        $map = [
            'm.is_del' =>0,
            'm.status' =>1,
            'cs.store_id' =>['in',$this->store_id_gather]
        ];
        //搜索
        if (!empty($_REQUEST['coach_name'])) {
            $map['m.nick_name'] = array(
                'like',
                "%{$_REQUEST['coach_name']}%"
            );
            $search['coach_name'] = urlencode($_REQUEST['coach_name']);
        }
       
        if (!empty($_REQUEST['mobile'])) {
            $map['m.mobile'] = $_REQUEST['mobile'];
            $search['mobile'] = $_REQUEST['mobile'];
        }
        if(!empty($_REQUEST['store'])){
            $map['m.store_id'] = (int)$_REQUEST['store'];
            $search['store_id'] = $map['m.store_id'];
        }
        $_REQUEST['star_time'] = empty($_REQUEST['star_time'])?'2015-01-01':$_REQUEST['star_time'];
        $search['star_time'] = $_REQUEST['star_time'];
        $_REQUEST['end_time'] = empty($_REQUEST['end_time'])?date('Y-m-d',time()):$_REQUEST['end_time'];
        $search['end_time'] = $_REQUEST['end_time'];
        $map['m.reg_time'] = array(
            'BETWEEN',
            array(
                strtotime($_REQUEST['star_time'] . " 00:00:00"),
                strtotime($_REQUEST['end_time'] . " 23:59:59")
            )
        );
        
        $this->assign('map',$_REQUEST);
       
        
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $field = 'm.id as coach_id,cs.relation_status,m.avatar,
            m.mobile,m.sex,m.age,m.nick_name,m.address,m.reg_time';
        $row = $model->getCoachsList($map, $field, $search,$page_now);
        
       // dump($row);exit;
        $this->assign('coachs_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
        $this->assign('store', $this->getStoreArray());
  
        $this->display();
    }
    
    /**
     * 添加
     */
    public function add()
    {
        
         $model = D('Coachs');
         if (IS_POST) {
            
             //检查唯一性验证手机格式是否正确
             if (! check_mobile($_POST['mobile'])) {
                 $this->error('教练手机号号码错误!');
             } else {
                 // 检查唯一性
                 if (M('members')->where(['mobile' => $_POST['mobile']])->count()) {
                     $this->error('教练手机号被占用!');
                 }
             }
             
             $model->startTrans();//事务
             
             $member_data = [
                 'nick_name'=>dhtmlspecialchars($_POST['coach_name']),
                 'mobile'=>$_POST['mobile'],
                 'sex' =>$_POST['coach_sex'],
                 'age' =>(int)$_POST['coach_age'],
                 'utype'=>2,
                 'reg_ip'=>get_client_ip(),
                 'reg_time'=>time(),
                 'avatar'=>empty($_POST['img_url'][0])?'':$_POST['img_url'][0],
                 'store_id'=>$this->store_id,
                 'address_desc'=>empty($_POST['address'])?'':dhtmlspecialchars($_POST['address']), 
                 'birth' =>empty($_POST['birth'])?time():strtotime($_POST['birth']),
                 'status'=>1
             ];
             $member_id = M('members')->add($member_data);
             if(empty($member_id)){
                 //$this->error('教练基础信息插入失败!');
                 $this->error(M('members')->getLastSql());
                 
             }
             $coachs_data = [
                 'member_id' =>$member_id,
                 'motto'=>empty($_POST['motto'])?'':dhtmlspecialchars($_POST['motto']),
                 'honor' =>empty($_POST['honor'])?'':dhtmlspecialchars($_POST['honor']),
                 'resume' => empty($_POST['resume'])?'':dhtmlspecialchars($_POST['resume']), 
                 'exp' =>empty($_POST['exp'])?'':dhtmlspecialchars($_POST['exp'])   
             ];
             
             $coach_id =$model->add($coachs_data);
             if(empty($coachs_id)){
                 $this->error('教练其他信息插入失败!');   
             }
             $relation_data = [
                 'coach_id'=>$coach_id,
                 'store_id'=>$this->store_id,
                 'brand_id'=>$this->brand_id,
                 'relation_status'=>(int)$_POST['relation_status']
             ];
             $relation_id = M('coachs_store_relation')->add($relation_data);
             if(empty($relation_id)){
                 $this->error('教练与道馆绑定信息插入失败!');
             } 
             if($relation_id && $member_id && $coachs_id){
 
                 $model->commit();
                 $this->success('添加教练数据成功!');
             }else{
                 $model->rollback();
                 $this->error('添加教练数据失败!');
                
             }
             
         }else{
             $this->display();
         }
    }
    /**
     * 
     */
    public function unbing()
    {
        $coach_id = (int)$_REQUEST['coach_id'];
        $model = D('Coachs');
        if(empty($coach_id)){
            $this->error('教练的id不存在!');
        
        }
        $map = [
            'member_id'=>$coach_id,
            
            
        ];
        $id = $model->where($map)->getField('coach_id');
        $coachs_store_relation = M('coachs_store_relation');
        $relation_id =$coachs_store_relation->where(['coach_id'=>$id,'store_id'=>$this->store_id])->getField('id');
        if(empty($relation_id) ){
            $this->error('相关的数据不存在!');
            //$this->error($coachs_store_relation->getLastSql());
        }else{
           if($coachs_store_relation->where(['id'=>$relation_id])->setField('relation_status',0)){
              $this->success('解绑成功!');
           }else{
               $this->error('解绑失败!');
           }
            
        }
    }
    
    /**
     * 
     */
    public function bing()
    {
        $coach_id = (int)$_REQUEST['coach_id'];
        $model = D('Coachs');
        if(empty($coach_id)){
            $this->error('教练的id不存在!');
        }
        $map = [
            'member_id'=>$coach_id,
        ];
        $id = $model->where($map)->getField('coach_id');
        $coachs_store_relation = M('coachs_store_relation');
        $relation_id =$coachs_store_relation->where(['coach_id'=>$id,'store_id'=>$this->store_id])->getField('id');
        if(empty($relation_id) ){
            $this->error('相关的数据不存在!');
            //$this->error($coachs_store_relation->getLastSql());
        }else{
            if($coachs_store_relation->where(['id'=>$relation_id])->setField('relation_status',1)){
                $this->success('绑订成功!');
            }else{
                $this->error('绑订失败!');
            }
        
        }
    }
    
    /**
     * 编辑 暂时不用
     */
    public function edit()
    {  
        $coach_id = (int)$_REQUEST['coach_id'];
        if(empty($coach_id)){
            $this->error('教练的id不存在!');
            
        }
        $model = D('Store/Coachs');
        if(IS_POST){
            //检查唯一性验证手机格式是否正确
            if (! check_mobile($_POST['mobile'])) {
                $this->error('教练手机号号码错误!');
            } else {
                // 检查唯一性
                $map =[
                    'mobile' => $_POST['mobile'],
                    'id'=>['neq',$coach_id]
                ];
                if (M('members')->where($map)->count()) {
                    $this->error('教练手机号被占用!');
                }
            }
          
            $model->startTrans();//事务
             
            $member_data = [
                'nick_name'=>dhtmlspecialchars($_POST['coach_name']),
                'mobile'=>$_POST['mobile'],
                'sex' =>$_POST['coach_sex'],
                'age' =>(int)$_POST['coach_age'],
                'reg_ip'=>get_client_ip(),
                'reg_time'=>time(),
                'avatar'=>empty($_POST['img_url'][0])?'':$_POST['img_url'][0],
                'address'=>empty($_POST['address'])?'':dhtmlspecialchars($_POST['address']),
                'birth' =>empty($_POST['birth'])?time():strtotime($_POST['birth'])
            ];
            $map = [
                'id'=>$coach_id,
            ];
            $member_result = M('members')->where($map)->save($member_data);
            if(empty($member_result)){
                $model->rollback();
                $this->error('教练基础信息插入失败!');
               
                 
            }
            $coachs_data = [
                'motto'=>empty($_POST['motto'])?'':dhtmlspecialchars($_POST['motto']),
                'honor' =>empty($_POST['honor'])?'':dhtmlspecialchars($_POST['honor']),
                'resume' => empty($_POST['resume'])?'':dhtmlspecialchars($_POST['resume']),
                'exp' =>empty($_POST['exp'])?'':dhtmlspecialchars($_POST['exp']),
                'operator_time'=>time(),
            ];
             
            $coach_result =$model->where(['member_id' =>$coach_id])->save($coachs_data);
           
            if(empty($coach_result)){
                $model->rollback();
                $this->error('教练其他信息插入失败!');
            }
            $coach_id =$model->where(['member_id' =>$coach_id])->getField('coach_id');
            $relation_map = [
                'coach_id'=>$coach_id,
                'brand_id'=>$this->brand_id
                
            ];
            $relation_data = [
                'relation_statu'=>(int)$_POST['relation_status'],
                'time'=>time()
            ];
            $relation_result = M('coachs_store_relation')->where($relation_map)->save($relation_data);
            //echo M('coachs_store_relation')->getLastSql();exit;
            if(empty($relation_result)){
                $model->rollback();
                $this->error('教练与道馆绑定信息插入失败!');
            }
            if($relation_result && $member_result && $coach_result){
            
                $model->commit();
                $this->success('更新教练数据成功!');
            }else{
                $model->rollback();
                $this->error('更新教练数据失败!');
            
            }
            
        }else{
            $map = [
                'm.is_del' =>0,
                'm.status' =>1,
                'm.id'=>$coach_id
            ];
            $field = 'm.id as coach_id,cs.relation_status,m.avatar,
            m.mobile,m.sex,m.birth,m.age,m.nick_name,m.address,m.reg_time,c.*';
            $row = $model->getCoachsList($map, $field, $search,$page_now);
            $coach_info = $row['list'][0];
            //头像
            if(!empty($coach_info['avatar'])){
            
                $album="<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$coach_info['avatar']}' name='img_url[]' />
                <img src='{$coach_info['avatar']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
                $this->assign('album',$album);
            }
            $coach_info['birth'] = date('Y-m-d',$coach_info['birth']);
            $coach_info['coach_id'] = $coach_id;
            $this->assign('coach_info', $coach_info);
            $this->display('add');
        }
    }
    
    
    public function del()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = M('members');
            /* 
            $coach_id = 
            M('coachs_store_relation')->where(['brand_id'=>$this->brand_id,]); */
            //这边需要优化 做严谨判断
            $map = [
                'utype' =>2,
                'id'=>$id
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            }
        }
    }
    
   
}