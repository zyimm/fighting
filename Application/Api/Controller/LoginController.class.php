<?php
/**
 * 
 * 登录接口
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月28日 下午3:15:50
 */
namespace Api\Controller;

use Think\Think;

class LoginController 
{
    
    /**
     * 登录
     */
    public function signIn()
    {
        $store_id=(int)I('store_id');
        $mobile = I('mobile');
        $mobile_code = I('mobile_code');
        if(empty($mobile) || empty($mobile_code)){
            self::jsonData('参数不全!',1);
        }
        if(!is_mobile($mobile)){
            self::jsonData('手机号码不正确!',1);
        }
        $verify_model = M('verifycode');
        $map = [
            'client'=>$mobile,
            'expire_time'=>['gt',time()],
            'verifycode'=>(int)$mobile_code
        ];
        if($verify_model->where($map)->count()){
            //登录成功!
            $verify_model->where($map)->delete();
            //检测登录
            $map = [
                'mobile'=>$mobile,
                'is_del'=>0
            ];
           $member_model = M('members');
           $member_info = $member_model->where($map)->find();
           if($member_info){
               if($member_info['status']==0){//冻结账户
                   self::jsonData('该账户被冻结无法再次登录!',1);   
               }else{
                   $data = [
                       'token'=>session_id(),
                       'reg_ip'=>get_client_ip(),
                       'reg_time'=>time(),
                       'last_login_ip'=>get_client_ip(),
                       'last_login_time'=>time(),
                   ];
                   $member_model->where($map)->save($data);
                   $member_info= $member_model->where(['mobile'=>$mobile])->find();
                   self::jsonData($member_info);
               }
           }else{
               //新用户
               $token = session_id();
               $data = [
                   'mobile'=>$mobile,
                   'nick_name'=>$mobile,
                   'token'=>$token,
                   'store_id'=>$store_id,
                   'reg_ip'=>get_client_ip(),
                   'reg_time'=>time(),
                   'last_login_ip'=>get_client_ip(),
                   'last_login_time'=>time(),
                   'utype'=>1,
                   'status'=>1 
               ];
               $member_id= $member_model->add($data);
               if($member_id){
                   $member_info = $member_model->where(['id'=>$member_id])->find();
                   self::jsonData($member_info);
               }else{
                   self::jsonData('新用户注册失败!!',1);
               }
           }
            
        }else{
            self::jsonData('验证码不对或过期请重新获取!',1);
        }
    }
  
    
    public function signUp()
    {
       
    }
    /**
     * 发送验证码
     */
    public function sendVerificationCode()
    {
        $mobile = I('mobile');
        if(is_mobile($mobile)){
            $message = "欢迎使用 Let’s fighting，您的验证码是：";
            $result = send_sms($mobile,$message);
            if(true===$result){
                self::jsonData('获取成功!');
            }else{
                self::jsonData($result,1);
            }
        }else{
            self::jsonData('手机号不正确!',1);
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
            $view->display('Json/json');
        }
        exit;
    }
    
}