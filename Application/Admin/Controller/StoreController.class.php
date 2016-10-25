<?php
/**
 * 道馆控制器
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月12日 上午9:40:42
 */
namespace Admin\Controller;
use Lib\QRcode;
class StoreController extends CommonController
{
    /**
     * 二维码
     * @param number $store_id
     */
    public function getStoreCode($store_id=0)
    {
        $store_id=I("store_id");
        //http://gnd.chengpai.net.cn/reg.html?store_id=4
        //生成推荐二维码
        $qrcodeRedirect='';
        $qrcodeRedirect="http://".$_SERVER['HTTP_HOST'].'/reg.php?store_id='.$store_id;
        QRcode::png($qrcodeRedirect,false,'H',4,2);
    }
    
    /**
     * 列表
     */
    public function index()
    {
        $model = D('store');
        $field='s.*,b.brand_name';
        $map['s.is_del']=0;
        $store_list=$model->getStoreList($map,$field);
        $this->assign('store_list',$store_list);
        $this->display();
    }

    /**
     * 添加
     */
    public function add()
    {
        $model = D('store');
        if (IS_POST) {
            if ($model->create()) {
                $model->time = time();
                if ($model->add()) {
                    $this->success('数据修改成功!', __CONTROLLER__);
                } else {
                    $this->error('数据修改失败!');
                }
            } else {
                $this->error('数据对象创建失败!');
            }
        } else {
            $map = [
                //'brand_id' =>$this->brand_id,
                'brand_status' => 1,
                'is_del' => 0
            ];
            $brand_temp = M('brand')->where($map)->select();
            $brand = [];
            foreach ($brand_temp as $k => $v) {
                $brand[$v['brand_id']] = $v['brand_name'];
            }
            // dump($brand);
            $this->assign('brand', $brand);
            $this->display('edit');
        }
    }
    /**
     * 编辑
     */
    public function edit()
    {
        $model = D('store');
        if (IS_POST) {
            if ($model->create()) {
                $model->time = time();
                if ($model->save()) {
                    $this->success('数据修改成功!');
                } else {
                    
                    $this->error('数据修改失败!');
                }
            } else {
                $this->error('数据对象创建失败!');
            }
        } else {
            $store_id = (int) $_REQUEST['store_id'];
            if (empty($store_id))
                $this->error('id不存在!');
            $store_info = $model->where([
                'store_id' => $store_id,
                'is_del' => 0
            ])->find();
            $brand_temp = M('brand')->where([
                'brand_status' => 1,
                'is_del' => 0
            ])->select();
            $brand = [];
            foreach ($brand_temp as $k => $v) {
                $brand[$v['brand_id']] = $v['brand_name'];
            }
            // dump($brand);
            $this->assign('brand', $brand);
            $this->assign('store_info', $store_info);
            $this->display();
        }
    }
    
    public function del()
    {
       
        $store_id = (int) $_REQUEST['store_id'];
        if (empty($store_id))  $this->error('id不存在!');
        $model = D('store');
        if($model->where(['store_id'=>$store_id])->setField('is_del',1)){
            $this->success('数据修改成功!');
        }else{
            //echo $model->getLastSql();
            $this->error('数据修改失败!');
        }
    }
}