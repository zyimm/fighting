<?php
/**
 * 
 * 道馆模型
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月12日 上午9:42:22
 */
namespace Admin\Model;

use Think\Model;

class StoreModel extends Model
{

    protected $tableName = 'store';

    /**
     * 获取道馆列表
     * 
     * @param array $map            
     * @param string $field            
     * @return array
     */
    public function getStoreList($map = [], $field = '*')
    {
        $row = [];
        $prefix = C('DB_PREFIX');
        $row = $this->alias('s')
            ->join($prefix . 'brand as b on s.brand_id=b.brand_id', 'left')
            ->field($field)
            ->where($map)
            ->select();
        
        return $row;
    }
    
    /**
     * 获取品牌列表
     * @param array $map
     * @param string $field
     */
    public function getBrandList($map = [], $field = '*')
    {
        $row = [];
        $prefix = C('DB_PREFIX');
        $model = M('brand');
        $row = $model->field($field)->where($map)->select();
        
        return $row;
    }
    
    public function getBrandAdminList($map = [], $field = '*')
    {
        $row = [];
        $prefix = C('DB_PREFIX');
        $model = M('brand_user');
        $row = $model->alias('bu')->field($field)
        ->join($prefix.'brand as b on bu.brand_id = b.brand_id','left')
        ->where($map)->select();
        return $row;
    }
    
    public function  getStoreAdminList($map = [], $field = '*')
    {
        $row = [];
        $prefix = C('DB_PREFIX');
        $model = M('store_user');
        $row = $model->alias('su')->field($field)
            ->join($prefix.'store as s on s.store_id = su.store_id','left')    
        ->where($map)->select();
        return $row;
    }
}