<?php
/**
 * 
 * 课程管理
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月20日 上午10:38:29
 */
namespace Store\Controller;

class CourseController extends CommonController
{
    public function index()
    {
        $this->display();
    }
    
    public function add()
    {
        $this->display('edit');
    }
}