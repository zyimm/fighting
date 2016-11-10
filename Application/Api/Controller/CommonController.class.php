<?php
/**
 * 
 * Api公共管理器
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月9日 下午2:40:38
 */
namespace Api\Controller;

use Think\Controller;
use Think\Think;

class CommonController extends Controller
{

    protected static  $member_id;

    protected static  $member_info;

    protected static  $student_info;

    protected static  $student_id;

    protected static  $brand_id;

    protected static  $store_id;
    
    protected static  $coach_id;

    public function __construct()
    {
        parent::__construct();
        // 检测登录
        $this->isLogin();
    }

    /**
     * 判断是否登录
     */
    private function isLogin()
    {
        $token = I('token');
        $map = [
            'token' => $token,
            'status'=>1,
            'is_del'=>0
        ];
        $member_info = M('members')->where($map)->find();
        if (empty($token) || ($token != $member_info['token'])) {
            self::jsonData('还未登录!',1);
        } else {
            self::$member_id = (int)$member_info['id'];
            self::$member_info = $member_info;
            self::$store_id = (int)$member_info['store_id']; 
            if(!empty(self::$store_id)){
                self::$brand_id = M('store')->where(['store_id'=>self::$store_id])->getField('brand_id');
            }
            //检测是否绑定学生
            if(!empty($member_info['student_id'])){
                $data = self::getBingStudent($member_info['student_id']);
                if($data){
                    self::$student_info = $data;
                    self::$student_id = $data['student_id'];
                } 
            }
            //教练
            if($member_info['utype']==2){
                $coach_id = M('coachs')->where(['member_id'=>$member_info['id']])->getField('coach_id');
                if($coach_id){
                    self::$coach_id = $coach_id;
                }else{
                    echo json_encode('当前是教练类型用户登录但相关数据丢失请联系管理员!',1);
                }
                
            }
        }
    }
    /**
     * json 返回信息
     * @param string/array $msg
     * @param number $error
     */
    public static function jsonData($msg = "", $error = 0)
    {
        $json['error'] = $error;
        if (is_array($msg)) {
            foreach ($msg as $key => $v) {
                $json['data'][$key] = $v;
            }
           
        } elseif (!empty($msg)) {
            $json['data'] = $msg;
        }
        if(is_mobile_client()){
            echo json_encode($json);
        }else{
            $view = Think::instance('Think\View');
            $view->assign('json',json_encode($json));
            $debug = trace();
            unset($debug['INFO']);
            $debug = var_export($debug,true);
            //unset($debug);
            $view->assign('debug',highlight_string($debug,true));
            $view->display('Json/json');
        } 
        exit;
    }
    /**
     * 获取绑定学生
     */
    private function getBingStudent($student_id = 0)
    {
        if(empty($student_id)){
            return false;
        }
        $map = [
            'students_id'=>$student_id,
            'student_status'=>1,
            'is_del'=>0
        ];
        $data = M('students')->where($map)->find();
        
        if(empty($data)){
           return false;
        }else{
            return $data;
        }
    }

}