<?php
/**
 * 
 * 用户管理
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月11日 上午10:20:31
 */
namespace Brand\Controller;

class MembersController extends CommonController
{
    
    /**
     * 列表
     */
    public function index()
    {
        $model = D('Store/Members');
        $map = [
            'm.is_del' => 0,
            'm.store_id' =>['in',$this->store_id_gather],
            'm.utype' => 1,
        ];
        //搜索
        if (!empty($_REQUEST['nick_name'])) {
            $map['m.nick_name'] = array(
                'like',
                "%{$_REQUEST['nick_name']}%"
            );
            $search['nick_name'] = urlencode($_REQUEST['nick_name']);
        }
        if (isset($_REQUEST['is_bing'])) {
            $map['m.student_id'] = ['neq',0];
            $search['is_bing'] = $_REQUEST['is_bing'];
        }
        if(!empty($_REQUEST['store'])){
            $map['m.store_id'] = (int)$_REQUEST['store'];
            $search['store_id'] = $map['m.store_id'];
        }
   
        $_REQUEST['star_time'] = empty($_REQUEST['star_time'])?'2015-01-01':$_REQUEST['star_time'];
        $search['star_time'] = $_REQUEST['star_time'];
        $_REQUEST['end_time'] = empty($_REQUEST['end_time'])?date('Y-m-d',time()):$_REQUEST['end_time'];
        $search['end_time'] = $_REQUEST['end_time'];
        $map['m.reg_time'] = [
            'BETWEEN',
            [
                strtotime($_REQUEST['star_time'] . " 00:00:00"),
                strtotime($_REQUEST['end_time'] . " 23:59:59")
            ]
        ];
        if (!empty($_REQUEST['mobile'])) {
            $map['m.mobile'] = $_REQUEST['mobile'];
            // $map['s.self_mobile'] = $_REQUEST['mobile'];
            $search['mobile'] = $_REQUEST['mobile'];
        }
        
        $this->assign('map',array_merge($_REQUEST,$search));
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $field = 'm.id,m.mobile,m.sex,m.age,m.nick_name,m.avatar,
                m.last_login_time, m.reg_time,
            su.student_name,su.student_sex,su.student_age,s.store_name,su.student_name';
        $row = $model->getMembersList($map, $field,$search,$page_now);
       
         //dump($row);exit;
        $this->assign('member_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
        $this->assign('store', $this->getStoreArray());
        $this->display();
     
    }
    
    
    public function add()
    {
        
        $this->display();
    }
    /**
     * 
     */
    
    public function edit()
    {
        $member_id = (int)$_REQUEST['id'];
        if(empty($member_id)){
            $this->error('id不存在!');
        }
        
        $model = D('Store/Members');
        if(IS_POST){
            //dump($_POST);exit;
            $member_data = [
                'sex'=>(int)$_POST['sex'],
                'age'=>(int)$_POST['age'],
                'birth' =>empty($_POST['birth'])?0:strtotime($_POST['birth']),
                'status' =>(int)$_POST['status'],
                'avatar' => $_POST['img_url'][0],
                'last_login_time'=>time()
            ];
            if(empty($_POST['student_status'])){
                $member_data['student_id'] =0;
            }
            
            if($model->where(['id'=>$member_id,'store_id'=>$this->store_id])->save($member_data)){
                
                $this->success('会员数据更新成功!');
            }else{
                $this->error('会员数据更新失败或者!');
            }
        }else{
            $field = 'm.*,su.student_name';
            $map = [
                'm.id'=>$member_id,
                'm.is_del' => 0,
                'm.utype' => 1
            ];
            $row = $model->getMembersList($map,$field);
            if(empty($row['list'][0])){
                $this->error('数据不存在!');
            }else{
                $member_info = $row['list'][0];
            }
            //格式化数据
            $member_info['birth'] = date('Y-m-d',$member_info['birth']);
            
            //头像
            if(!empty($member_info['avatar'])){
            
                $album="<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$member_info['avatar']}' name='img_url[]' />
                <img src='{$member_info['avatar']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
                $this->assign('album',$album);
            }
 
            $this->assign('member_info',$member_info);
            $this->display('add');
        }
        
    }
}