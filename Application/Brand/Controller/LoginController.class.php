<?php
/**
 * 登录控制器
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月9日 下午2:43:31
 */
namespace Brand\Controller;

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
            $brand_info = M('brand_user')->field('id,password,user_name,brand_id,expire_time')
                ->where($map)
                ->find();
            if ($brand_info) {
                if ($brand_info['password'] == md5($user_pass)) {
                    //检测授权时间是否过期
                    if($brand_info['expire_time']<time()){
                        $this->error('对不起您的账户已经到期!请联系平台管理员');
                    }
                    //该终端要检测上一级的品牌是否被禁用
                    $map =[
                         'brand_id'=>$brand_info['brand_id'],
                         'status'=>1,
                         'is_del'=>0 
                    ];
                    if(M('brand')->where($map)->count()){
                        // 更新登录日志
                        session('brand_admin_id', $brand_info['id']);
                        session('brand_admin_name', $brand_info['user_name']);
                        $data = array(
                                'login_time' => time(),
                                'login_ip' => get_client_ip()
                        );
                        M('brand_user')->where("id={$brand_info['id']}")->save($data);
                        $this->success('登录成功!', '/brand/index.html');
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
        unset($_SESSION['brand_admin_id']);
        unset($_SESSION['brand_admin_name']);
        $this->success('退出成功!', '/brand/Login/index.html');
    }
    public function verify($vid = 1) {
        $verify = new Verify();
        $verify->length = 4;
        $verify->entry($vid);
    }
}