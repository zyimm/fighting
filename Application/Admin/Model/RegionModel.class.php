<?php
/**
 * 
 * 地区模型
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月27日 下午1:38:58
 */
namespace Admin\Model;
use Think\Model;

class RegionModel extends Model
{
    public  function getAll(){
        return $this->select();
    }
    /**
     * 获取省份
     */
    public function getProvince()
    {
        return $this->where("parent_id = 1")->select();
    }
    
    /**
     * 获取指定省份的地级市
     */
    public function getCity($province_id = 0)
    {
        if(empty($province_id)){
           return false;
        }else{
            return $this->where("parent_id = {$province_id}")->select();
        }
    }
    
    /**
     * 获取指定地级市的县/区
     */
    public function getDistrict($city_id = 0)
    {
        if(empty($city_id)){
            return false;
        }else{
            return $this->where("parent_id = {$city_id}")->select();
        }
    }
    
  
}
