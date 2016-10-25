<?php
/**
 * 
 * 学生管理
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月18日 上午9:25:59
 */
namespace Admin\Controller;

class StudentsController extends CommonController
{

    /**
     * 列表
     */
    public function index()
    {
        $model = D('Store/students');
        $map = [
            's.is_del' => 0,
            //'s.store_id' => $this->store_id
        ];
        //搜索
        if (!empty($_REQUEST['student_name'])) {
            $map['s.student_name'] = array(
                'like',
                "%{$_REQUEST['student_name']}%"
            );
            $search['student_name'] = urlencode($_REQUEST['student_name']);
        }
        if (!empty($_REQUEST['level_id'])) {
            $map['s.level_id'] = intval($_REQUEST['level_id']);
            $search['level_id'] = $map['level_id'];
        }
        
        
        
        $_REQUEST['star_time'] = empty($_REQUEST['star_time'])?'1970-12-01':$_REQUEST['star_time'];
        $search['star_time'] = $_REQUEST['star_time'];
        $_REQUEST['end_time'] = empty($_REQUEST['end_time'])?date('Y-m-d',time()):$_REQUEST['end_time'];
        $search['end_time'] = $_REQUEST['end_time'];
        $map['s.operator_time'] = [
            'BETWEEN',
            [
                strtotime($_REQUEST['star_time'] . " 00:00:00"),
                strtotime($_REQUEST['end_time'] . " 23:59:59")
            ]
        ];
        if (!empty($_REQUEST['mobile'])) {
            $map['s.parent_mobile'] = $_REQUEST['mobile'];
           // $map['s.self_mobile'] = $_REQUEST['mobile'];   
            $search['mobile'] = $_REQUEST['mobile'];
        }
        $this->assign('map',$_REQUEST);
        // 会员等级
        $level_temp = [];
        $level_temp = M('level')->select();
        $level = [];
        $level['0'] = '请选择段位';
        foreach ($level_temp as $key => $val) {
            $level[$val['level_id']] = $val['level_name'];
        }
        
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $field = 's.*,st.store_name,su.user_name';
        $row = $model->getStudentsList($map, $field, $search,$page_now,'','s.student_id desc');
        
        // dump($row);exit;
        $this->assign('students_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
        $this->assign('level', $level);
        $this->display();
    }

    /**
     * 学生添加
     */
    public function add()
    {
        $model = D('Store/students');
        if (IS_POST) {
          //  print_r($_POST);
          
            $student_no = substr(strtotime(date("Y-m-d", time())), 0, 2) . substr(strrev(microtime()), 0, 2) . substr(mt_rand(), 0, 5) . substr(rand(), 0, 2);
            
            $student_data = [
                'student_name' =>dhtmlspecialchars($_POST['student_name']),
                'student_sex' => (int)$_POST['student_sex'] ,
                'student_avatar' =>empty($_POST['img_url'][0])?'':$_POST['img_url'][0],
                'student_age' =>(int)$_POST['student_age'] ,
                'store_id' => $this->store_id,
                'birth' => strtotime($_POST['birth']),
                'parent_mobile' =>$_POST['parent_mobile'],
                'self_mobile' => $_POST['self_mobile'],
                'level_id' => $_POST['level_id'],
                'address' => dhtmlspecialchars($_POST['address']),
                'student_no' => $student_no,
                'student_no_alias' => dhtmlspecialchars($_POST['student_no_alias']),
                'operator_id' => $this->store_admin_id,
                'operator_time' => time()
            ];
            
            // 检查唯一性
            if (! empty($student_data['parent_mobile'])) {
                //验证手机格式是否正确
                if (! check_mobile($student_data['parent_mobile'])) {
                    $this->error('家长手机号号码错误');
                } else {
                    // 检查唯一性
                    if ($model->where(['parent_mobile' => $student_data['parent_mobile']])->count()) {
                        $this->error('家长手机号被占用!');
                    }
                }
            }
            // 检查唯一性
            if (!empty($student_data['self_mobile'])) {
                // 验证手机格式是否正确
                if (! check_mobile($student_data['self_mobile'])) {
                    $this->error('学生自己手机号号码错误!');
                } else {
                    // 检查唯一性
                    if ($model->where([
                        'self_mobile' => $student_data['self_mobile']
                    ])->count()) {
                     
                        $this->error('学生自己手机号被占用!');
                    }
                }
            }
            if (!empty($student_data['student_no_alias'])) {
                // 检查唯一性
                if ($model->where([
                    'student_no_alias' => $student_data['student_no_alias'],
                    'store_id' => $this->store_id
                ])->count()) { 
                   $this->error('学生学号号被占用!');
                }
            }
            $model->startTrans(); // 事务
            $student_id = $model->add($student_data);
            if (empty($student_id)) {
                $model->rollback(); 
                $this->error('学生帐号添加失败!');
            } else {
                $students_card = [
                    'student_id' => $student_id,
                    'card_type' => ($_POST['card_id'] == 1) ? 0 : 1,
                    'card_nums' => (int)$_POST['card_nums'],
                    'card_start_time' =>strtotime($_POST['card_start_time']),
                    'card_end_time' =>strtotime($_POST['card_end_time']),
                    'card_status' =>1
                ];
                $card_id = null;
                $card_id = M('students_card')->add($students_card);
                if ($card_id && $student_id) {
                   $model->commit();
                   $this->success('学生帐号添加成功!');
                } else {
                    $model->rollback();
                    $this->error('学生帐号添加失败!');
                }
            }
   
            
        } else {
            
            // 会员等级
            $level_temp = [];
            $level_temp = M('level')->select();
            $level = [];
            foreach ($level_temp as $key => $val) {
                $level[$val['level_id']] = $val['level_name'];
            }
            $this->assign('card',['0'=>'次卡','1'=>'期卡']);
            $this->assign('level', $level);
            $this->display();
        }
    }
    /**
     * 编辑
     */
    public function edit()
    {
      $id=(int)$_REQUEST['student_id'];
      if(empty($id)){
          $this->error('id不存在!');
      }
      $model = D('Store/students');
      if (IS_POST) {
            $student_data = [
                'student_name' =>dhtmlspecialchars($_POST['student_name']),
                'student_sex' => (int)$_POST['student_sex'] ,
                'student_age' =>(int)$_POST['student_age'] ,
                'student_avatar' =>$_POST['img_url'][0],
                'store_id' => $this->store_id,
                'birth' => strtotime($_POST['birth']),
                'parent_mobile' =>$_POST['parent_mobile'],
                'self_mobile' => $_POST['self_mobile'],
                'level_id' => $_POST['level_id'],
                'address' => dhtmlspecialchars($_POST['address']),
                'student_no_alias' => dhtmlspecialchars($_POST['student_no_alias']),
                'operator_id' => $this->store_admin_id
             
            ];
            // 检查唯一性
            if (! empty($student_data['parent_mobile'])) {
                // 验证手机格式是否正确
                if (!check_mobile($student_data['parent_mobile'])) {
                    $this->error('家长手机号号码错误');
                } else {
                    // 检查唯一性
                    $map = [
                        'parent_mobile' => $student_data['parent_mobile'],
                        'store_id' => $this->store_id,
                        'student_id'=>['neq',$id],
                        'is_del' =>0
                    ];
                    if ($model->where($map)->count()) {
                        $this->error('家长手机号被占用!');
                    }
                }
            }
            // 检查唯一性
            if (! empty($student_data['self_mobile'])) {
                // 验证手机格式是否正确
                if (! check_mobile($student_data['self_mobile'])) {
                    $this->error('学生自己手机号号码错误!');
                } else {
                    // 检查唯一性
                    $map =[
                        'self_mobile' =>$student_data['self_mobile'],
                        'student_id'=>['neq',$id],
                        'is_del' =>0
                    ];
                    if ($model->where($map)->count()) {   
                        $this->error('学生自己手机号被占用!');
                    }
                }
            }
            if (! empty($student_data['student_no_alias'])) {
                // 检查唯一性
                $map = [
                    'student_no_alias' => $student_data['student_no_alias'],
                    'store_id' => $this->store_id,
                    'student_id'=>['neq',$id],
                    'is_del' =>0
                ];
                if ($model->where($map)->count()) {
                   $this->error('学生学号号被占用!');
                }
            }
            $model->startTrans(); // 事务
            $map = [
                'student_id'=>$id,
                'store_id'=>$this->store_id,
                'is_del' =>0
            ];
            $student_id = $model->where($map)->save($student_data);
            if (empty($student_id)) {
                $model->rollback();
                $this->error('学生帐号修改失败!');
            } else {
             /*    
                $students_card = [
                    'student_id' =>$student_id,
                    'card_type' => ($_POST['card_id'] == 1)? 0 : 1,
                    'card_nums' => (int)$_POST['card_nums'],
                    'card_start_time' =>strtotime($_POST['card_start_time']),
                    'card_end_time' =>strtotime($_POST['card_end_time']),
                    'card_status' => 1
                ];
                $card_id = null;
                $card_id = M('students_card')->add($students_card); */
                if ($student_id) {
                    $model->commit();
                   $this->success('学生帐号修改成功!');
                } else {
                    $model->rollback();
                    $this->error('学生帐号修改失败!');
                }
            }
   
            
        } else {
            $field = 's.*,st.store_name,su.user_name';
            $map = [
                's.is_del' => 0,
                's.store_id' => $this->store_id
            ];
            $row = $model->getStudentsList($map,$field);
            if(empty($row['list'][0])){
                $this->error('数据不存在!');
            }else{
                $student_info = $row['list'][0];
            }
            //格式化数据
            $student_info['birth'] = date('Y-m-d',$student_info['birth']);
            
            //头像
            if(!empty($student_info['student_avatar'])){
            
                $album="<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$student_info['student_avatar']}' name='img_url[]' />
                <img src='{$student_info['student_avatar']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
            }
            $this->assign('album',$album);
            // 会员等级
            $level_temp = [];
            $level_temp = M('level')->select();
            $level = [];
            foreach ($level_temp as $key => $val) {
                $level[$val['level_id']] = $val['level_name'];
            }
            $this->assign('card',['0'=>'次卡','1'=>'期卡']);
            $this->assign('level', $level);
            $this->assign('student_info',$student_info);
            $this->display('add');
        }
    }

    /**
     * 学生导入
     * 
     * @param  student_no 年月日秒+随机
     * @param excel 文件的命名 唯一
     */
    public function excel()
    {
        set_time_limit(0);
        $level_temp = M('level')->field('level_id,level_name')->select();
        $level = [];
        foreach ($level_temp as $v) {
            $level[$v['level_name']] = $v['level_id'];
        }
        
        if (IS_POST) {
            // 文件上传
            $info = file_upload(APP_PATH . '../public/uploads/excel/', [], [
                'xls'
            ]);
            
            if (! is_array($info)) { // 上传错误提示错误信息
                $this->error($info);
            } else { // 上传成功
                $file = $info['excel']['savepath'] . $info['excel']['savename'];
                if (is_file($file)) {
                    // 获取解析的excel数据
                    $data = import_excel($file);
                    dump($data);exit;
                    $error_msg = [];
                    $success_msg = [];
                    if (! empty($data)) {
                        $model = D('students');
                        foreach ($data as $k => $v) {
                            if (empty($v['1']) || empty($v['2']) || empty($v['3'])) {
                                unset($data[$k]);
                                continue;
                            }
                            $student_no = substr(strtotime(date("Y-m-d", time())), 0, 2) . substr(strrev(microtime()), 0, 2) . substr(mt_rand(), 0, 5) . substr(rand(), 0, 2);                 
                            $student_data = [
                                'student_name' => $v[0],
                                'student_sex' => (trim($v[1]) == '女') ? '0' : '1',
                                'student_age' => (date('Y') - floatval(trim($v[2]))) + 1,
                                'store_id' => $this->store_id,
                                'birth' => strtotime(trim($v[2])),
                                'parent_mobile' => trim($v[3]),
                                'self_mobile' => trim($v[4]),
                                'level_id' => empty($level[trim($v[5])]) ? 1 : $level[trim($v[5])],
                                'address' => trim($v[6]),
                                'student_no' => $student_no,
                                'student_no_alias' => trim($v[11]),
                                'operator_id' => $this->store_admin_id,
                                'operator_time' => time()
                            ];
                            
                            // 检查唯一性
                            if (! empty($student_data['parent_mobile'])) {
                                // 验证手机格式是否正确
                                if (! check_mobile($student_data['parent_mobile'])) {
                                    $error_msg[$k] = "第{$k}行数据家长手机号号码错误!->" . $student_data['parent_mobile'];
                                    continue;
                                } else {
                                    // 检查唯一性
                                    if ($model->where([
                                        'parent_mobile' => $student_data['parent_mobile']
                                    ])->count()) {
                                        $error_msg[$k] = "第{$k}行数据家长手机号被占用!->" . $student_data['parent_mobile'];
                                        continue;
                                    }
                                }
                            }
                            // 检查唯一性
                            if (! empty($student_data['self_mobile'])) {
                                // 验证手机格式是否正确
                                if (! check_mobile($student_data['self_mobile'])) {
                                    $error_msg[$k] = "第{$k}行数据学生自己手机号号码错误!";
                                    continue;
                                } else {
                                    // 检查唯一性
                                    if ($model->where([
                                        'self_mobile' => $student_data['self_mobile'],   
                                    ])->count()) {
                                        $error_msg[$k] = "第{$k}行数据家长学生自己手机号被占用!";
                                        continue;
                                    }
                                }
                            }
                            if (! empty($student_data['student_no_alias'])) {
                                // 检查唯一性
                                if ($model->where([
                                    'student_no_alias' => $student_data['student_no_alias'],
                                    'store_id' => $this->store_id
                                ])->count()) {
                                    $error_msg[$k] = "第{$k}行数据学生学号号被占用!";
                                    continue;
                                }
                            }
                            $model->startTrans(); // 事务
                            $student_id = $model->add($student_data);
                            if (empty($student_id)) {
                                $model->rollback();
                                $error_msg[$k] = "第{$k}行数据学生导入失败!";
                                continue;
                            } else {
                                if(!empty($v[7])){
                                    $v[9] = empty($v[9]) ? time() : $v[9];
                                    $v[10] = empty($v[10]) ? time() : $v[10];
                                    $students_card = [
                                        'student_id' => $student_id,
                                        'card_type' => (trim($v[7]) == '次卡') ? 0 : 1,
                                        'card_nums' => (int) $v[8],
                                        'card_start_time' => strtotime(trim($v[9])),
                                        'card_end_time' => strtotime(trim($v[10])),
                                        'card_status' => 1
                                    ];
                                    $card_id = null;
                                    $card_id = M('students_card')->add($students_card);
                                    if ($card_id && $student_id) {
                                        $model->commit();
                                        $success_msg[$k] = 'success';
                                    } else {
                                        $model->rollback();
                                        $error_msg[$k] = "第{$k}行数据学生导入失败!";
                                    }
                                    
                                }else{
                                    if ($student_id) {
                                        $model->commit();
                                        $success_msg[$k] = 'success';
                                    } else {
                                        $model->rollback();
                                        $error_msg[$k] = "第{$k}行数据学生导入失败!";
                                    }
                                    
                                }
                            }
                        }
                        $this->assign('error_msg', $error_msg);
                        $this->assign('success_msg', count($success_msg));
                        $this->display('excel_result');
                    } else {
                        $this->error('上传文件数据丢失!');
                    }
                } else {
                    $this->error('上传文件丢失!');
                }
            }
        } else {
            
            $this->display();
        }
    }
    
    /**
     * 删除
     */
    public function del()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){  
           $this->error('数据id不存在！'); 
        }else{
            $model = D('Store/students');
            $map = [
                'student_id' =>$id,
                'store_id' =>$this->store_id
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            }
        }
    }
}