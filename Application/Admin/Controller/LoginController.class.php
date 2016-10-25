<?php
/**
 * 登录控制器
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月9日 下午2:43:31
 */
namespace Admin\Controller;

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
            $admin_info = M('admin_user')->field('id,password,user_name')
                ->where("user_name='{$user_name}'")
                ->find();
            if ($admin_info) {
                if ($admin_info['password'] == md5($user_pass)) {
                    // 更新登录日志
                    session('admin_id', $admin_info['id']);
                    session('admin_name', $admin_info['user_name']);
                    $data = array(
                        'login_time' => time(),
                        'login_ip' => get_client_ip()
                    );
                    M('admin_user')->where("id={$admin_info['id']}")->save($data);
                    $this->success('登录成功!', '/admin/index.html');
                } else {
                    $this->error('密码错误!');
                }
            } else {
              // echo  M('admin_user')->getLastSql();
                $this->error('管理员不存在!');
            }
        }
    }

    public function loginOut()
    {
        unset($_SESSION);
        session_destroy();
        $this->success('退出成功!', '/admin/Login/index.html');
    }
    public function verify($vid = 1) {
        $verify = new Verify();
        $verify->length = 4;
        $verify->entry($vid);
    }
}