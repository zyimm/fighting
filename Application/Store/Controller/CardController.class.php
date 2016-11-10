<?php
/**
 * 
 * 卡管理
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年11月3日 下午5:21:15
 */
namespace Store\Controller;
class CardController extends CommonController
{
    public function index()
    {
        
        $model = D('Store/Students');
        $map= [
            'c.is_del' =>0,
            'c.store_id'=>$this->store_id    
                
        ];
        $field = 'c.*,s.student_name';
        $page_now = empty($_REQUEST['p'])?1:$_REQUEST['p'];
        $data = $model->cardList($map,$field,[],$page_now);
        $this->assign('card_list',$data['list']);
        $this->assign('page_show',$data['page_show']);
        $this->display();
    }
    
    public function add()
    {
        if(IS_POST){
           $student_no_alias = I('student_no_alias');
           if(empty($student_no_alias)){
               $this->error('学生学号不存在!');
           }
           $model = M('students_card');
           $student_id = M('students')->where(['student_no_alias'=>$student_no_alias,'store_id'=>$store_id])->getField('student_id');
           if(!empty($student_id)){
               if($model->where(['stundent_id'=>$student_id,'is_del'=>0])->count()){
                   $this->error('该学生已绑定卡');
               }else{
                    $data = [
                            'student_id' => $student_id,
                            'store_id' => $this->store_id,
                            'card_type' => I('card_type'),
                            'card_nums' => I('card_nums'),
                            'card_start_time' => strtotime(I('card_start_time')),
                            'card_end_time' => strtotime(I('card_end_time')),
                            'card_status' => I('card_status')
                    ]; 
                    if($model->add($data)){
                        $this->success('添加成功!');
                    }else{
                        $this->error('添加失败!');
                    }
               }
           }else{
               $this->error('学生不存在!');
           }
        }else{
            $card_type = [
                    0=>'次卡',
                    1=>'期卡',
                    2=>'全通卡'     
            ];
            $this->assign('card_type', $card_type);
            $this->display('edit');
        }
    }
    
    public function del()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = M('students_card');
            $map = [
                    'card_id' =>$id,
                    'store_id' =>$this->store_id
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            }
        }
    }
    
    public function edit()
    {
        $card_id = I('card_id');
        if(empty($card_id)){
            $this->error('卡的id不存在');
        }
        $model = M('students_card');
        if(IS_POST){  
            $data = [
                    'card_type' => I('card_type'),
                    'card_nums' => I('card_nums'),
                    'card_start_time' => strtotime(I('card_start_time')),
                    'card_end_time' => strtotime(I('card_end_time')),
                    'card_status' => I('card_status')
            ];
            if($model->where(['card_id'=>$card_id,'store_id'=>$this->store_id])->save($data)){
                $this->success('编辑成功!');
            }else{
                $this->error('编辑失败!');
            }
        }else{
            $card_info = M('students_card')->where(['card_id'=>$card_id,'store_id'=>$this->store_id])->find();
            $card_type = [
                    0=>'次卡',
                    1=>'期卡',
                    2=>'全通卡'
            ];
            $card_info['card_start_time'] = date('Y-m-d',$card_info['card_start_time']);
            $card_info['card_end_time'] = date('Y-m-d',$card_info['card_end_time']);
            $this->assign('card_info', $card_info);
            $this->assign('card_type', $card_type);
            $this->assign('edit',1);
            $this->display('edit');
        }
    }
}