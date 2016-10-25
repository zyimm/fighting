<?php
/**
 * 登录控制器
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月9日 下午2:43:31
 */
namespace Store\Controller;

use Think\Controller;
use Think\Verify;

class LoginController extends Controller
{
    public function index()
    {
        layout(false);
        $this->display('login');
    }

    public function doLogin()
    {
        if (IS_POST) {
            $user_name = I('post.user_name', '', 'addslashes');
            $user_pass = I('post.password', '', 'addslashes');
            $map = [
                    'user_name' =>$user_name,
                    'status' =>1,
                    'is_del'=>0
            ];
            $store_info = M('store_user')->field('id,password,user_name,brand_id')
                ->where($map)
                ->find();
               
            if ($store_info) {
                if ($store_info['password'] == md5($user_pass)) {
                    //该终端要检测上一级的品牌是否被禁用
                    $map =[
                            'brand_id'=>$store_info['brand_id'],
                            'status'=>1,
                            'is_del'=>0
                    ];
                    if(M('brand')->where($map)->count()){
                        // 更新登录日志
                        session('store_admin_id', $store_info['id']);
                        session('store_admin_name', $store_info['user_name']);
                        $data = array(
                                'login_time' => time(),
                                'login_ip' => get_client_ip()
                        );
                        M('store_user')->where("id={$store_info['id']}")->save($data);
                        $this->success('登录成功!', '/store/index.html');
                    }else{
                        $this->error('该品牌已经被禁用或删除! 请联系平台管理员!');
                    }
                   
                } else {
                    $this->error('密码错误!');
                }
            } else {
              // echo  M('admin_user')->getLastSql();
                $this->error('该账户不存在或者账户被禁用!');
            }
        }
    }

    public function loginOut()
    {
        unset($_SESSION['store_admin_id']);
        unset($_SESSION['store_admin_name']);
        //session_destroy();
        $this->success('退出成功!', '/store/Login/index.html');
    }
    public function verify($vid = 1) {
        $verify = new Verify();
        $verify->length = 4;
        $verify->entry($vid);
    }
}