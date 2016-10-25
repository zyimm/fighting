<?php
/**
 * 
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月10日 上午9:26:57
 */
namespace Brand\Model;

use Think\Model;

class BrandModel extends Model
{

    protected $tableName = 'brand_user';

    /**
     * 获取指定后台用户的信息
     * @param number $admin_id
     * @return unknown
     */
    public function getBrandAdminInfo($admin_id = 0)
    {
        $id = (int) $admin_id;
        $pre = C('DB_PREFIX');
        return $this->field('b.*,r.title as role_name')
            ->alias('b')
            ->join($pre . 'brand_auth_group as r on b.role_id=r.id', 'left')
            ->where("b.id={$id}")
            ->find();
    }

    /**
     * 获取道馆的信息
     * 
     * @param string $store_id            
     * @return mixed|boolean|string|NULL|unknown|object
     */
    public function getStoreInfo($store_id = '')
    {
        $store_id = trim($store_id);
        return M('store')->where([
            'store_id' => [
                'eq',
                $store_id
            ]
        ])->find();
    }

    /**
     * 获取后台用户列表
     * 
     * @param array $map            
     * @param string $field            
     * @return array
     */
    public function getAdminList($map = [], $field = '*')
    {
        $row = [];
        $pre = C('DB_PREFIX');
        $row = $this->field($field)
            ->alias('a')
            ->join($pre . 'store_auth_group as r on a.role_id=r.id', 'left')
            ->where($map)
            ->select();
        return $row;
    }

    /**
     * 获取道馆的角色
     * 
     * @param unknown $map            
     * @param unknown $field            
     * @return mixed|boolean|string|NULL|unknown|object
     */
    public function getRoleList($map, $field)
    {
        $row = [];
        $row = M('store_auth_group')->field($field)
            ->where($map)
            ->select();
        return $row;
    }

    /**
     * 生成树状的 权限
     * 
     * @param number $role_id            
     */
    public function getAuthList($role_id = 0)
    {
        $model = M('store_auth_rule');
        $temp_auth_list = $model->where([
            'status' => 1
        ])
            ->order('id asc')
            ->select();
        
        $auth_list = [];
        foreach ($temp_auth_list as $v) {
            $auth_list[$v['id']] = $v;
        }
        foreach ($auth_list as $v) {
            $auth_list[$v['pid']]['node'][$v['id']] = &$auth_list[$v['id']];
        }
        
        return $auth_list[0]['node'];
    }

    /**
     * 绑定角色
     * 
     * @param number $admin_id            
     * @param number $role_id            
     * @return boolean
     */
    public function bingRole($admin_id = 0, $role_id = 0)
    {
        if ($admin_id == 0 || $role_id == 0) {
            return false;
        } else {
            $model = M('store_auth_group_access');
            //
            if ($model->where([
                'uid' => $admin_id
            ])->count()) {
                if ($model->where([
                    'uid' => $admin_id
                ])->setField('group_id', $role_id)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $data = [
                    'uid' => $admin_id,
                    'group_id' => $role_id
                ];
                if ($model->add($data)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * 检测邮箱
     * 
     * @param number $admin_id            
     * @param string $email            
     * @return boolean
     */
    public function checkEmail($admin_id = 0, $email = '')
    {
        $map = [
            'email' => $email,
            'id' => [
                'neq',
                $admin_id
            ]
        ];    
        if ($this->where($map)->count()) {
            return false;
        } else {
            return true;
        }
    }
    /**
     * 
     */
    public function getStoreIdGather($brand_id = 0)
    {
        $map =[
            'brand_id'=>$brand_id,
            'is_del'=>0,
            'store_status'=>1
            
        ];
      $store_id_arr =  M('store')->field('store_id')->where($map)->select();
      if(empty($store_id_arr)){
          return false;
      }else{
          $store_id_gather =[];
          $store_id_gather = array_column($store_id_arr,'store_id');
          $store_id_gather=join(',', $store_id_gather);
          return $store_id_gather;
      }
    }
}