<?php
/**
 * 广告幻灯片管理
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月13日 上午10:11:44
 */
namespace Store\Controller;

class AdController extends CommonController
{

    /**
     * 列表
     */
    public function index ()
    {
        
        // dump(import_excel(WEB_PATH.'/uploads/excel/demo.xls'));
        $model = D('Ad');
        $map = [
                'is_del' => 0,
                'store_id' => $this->store_id
        ]
        ;
        $page_now = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        
        $row = $model->getAdList($map, '*', $page_now);
        
        // dump($row);exit;
        $this->assign('ad_list', $row['list']);
        $this->assign('page_show', $row['page_show']);
        
        $this->display();
    }

    /**
     * 添加
     */
    public function add ()
    {
        if (IS_POST) {
            $model = D('Ad');
            $data = [
                    'ad_title' => dhtmlspecialchars($_POST['ad_title']),
                    'ad_image' => $_POST['img_url'][0],
                    'ad_type' => I('ad_type'),
                    'ad_url' => I('ad_url'),
                    'content' => I('content'),
                    'status' => I('status'),
                    'store_id' => $this->store_id,
                    'time' => time()
            ];
            if ($model->add($data)) {
                
                $this->success('添加成功!');
            } else {
                $this->error('添加失败!');
            }
        } else {
            
            $this->display('edit');
        }
    }

    /**
     * 编辑
     */
    public function edit ()
    {
        $ad_id = (int) I('ad_id');
        if (empty($ad_id)) {
            $this->error('id 不存在!');
        }
        $model = D('Ad');
        if (IS_POST) {
            
            $data = [
                    'ad_title' => dhtmlspecialchars($_POST['ad_title']),
                    'ad_image' => $_POST['img_url'][0],
                    'ad_type' => I('ad_type'),
                    'ad_url' => I('ad_url'),
                    'content' => I('content'),
                    'status' => I('status'),
                    'store_id' => $this->store_id,
                    'time' => time()
            ];
            if ($model->where([
                    'ad_id' => $ad_id
            ])->save($data)) {
                
                $this->success('编辑成功!');
            } else {
                $this->error('编辑失败!');
            }
        } else {
            
            $ad_info = $model->getAdInfo($ad_id, $this->store_id);
            $album = '';
            
            if (! empty($ad_info['ad_image'])) {
                
                $album = "<div style='width:120px;float:left;margin:8px;position:relative;'>
                <i class='icon-times-circle text-dot' onclick='delImage(this)' style='cursor:pointer;position:absolute;top:-6px;right:28px;'></i>
                <input type='hidden' value='{$ad_info['ad_image']}' name='img_url[]' />
                <img src='{$ad_info['ad_image']}' alt=''  class='radius-big' width='88' height='88' />
                </div>";
            }
            
            $this->assign('album', $album);
            $this->assign('ad_info', $ad_info);
            $this->display('edit');
        }
    }
    
    public function del()
    {
        $id=(int)$_REQUEST['id'];
        if(empty($id)){
            $this->error('数据id不存在！');
        }else{
            $model = D('Store/Ad');
            $map = [
                'ad_id' =>$id
                 
            ];
            if($model->where($map)->setField('is_del','1')){
                $this->success('删除成功!');
            }else{
                $this->error('删除失败！');
            }
        }
    }
}