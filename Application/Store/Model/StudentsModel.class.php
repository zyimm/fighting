<?php
/**
 * 
 * 学生管理模型
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月10日 上午9:26:57
 */
namespace Store\Model;

use Think\Model;
use Think\Page;

class StudentsModel extends Model
{

    protected $tableName = 'students';

    /**
     * 学生列表
     * @param array $map
     * @param string $field
     * @param array $search
     * @param number $page_now
     * @param number $page_size
     * @param string $order
     * @return array
     */
    public function getStudentsList(array $map = [], $field = '*', array $search = [],$page_now = 1, $page_size = 0,$order ='')
    {
        $row = [];
        if ($page_size < 1) {
            $page_size = C('PAGE_SIZE');
        }
        $pre = C('DB_PREFIX');
        $count = $this->alias('s')
            ->field($field)
            ->join($pre . 'store as st on s.store_id = st.store_id', 'left')
            ->where($map)->count();
        $page = new Page($count, $page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $page->show(); // 分页显示输出
        $list = $this->alias('s')
            ->field($field)
            ->join($pre . 'store as st on s.store_id = st.store_id', 'left')
            ->join($pre . 'store_user as su on s.store_id = su.store_id', 'left')
            ->where($map)->group('s.student_id')
            ->page("{$page_now},{$page_size}")
            ->order($order)->select();
        $map_card = [
            'is_del' => 0,
             'card_status'=>1   
        ];
        foreach ($list as $k => $v) {
            $map_card['student_id'] = (int) $v['student_id'];
            $list[$k]['card_info'] = $this->getCardInfo($map_card);
            $list[$k]['card_info'] = empty($list[$k]['card_info'])?['info'=>'该卡失效或不存在!']:$list[$k]['card_info'];
            if ((int) $list[$k]['card_info']['card_type'] == 1) {
                $list[$k]['card_type'] = '期卡';
            }elseif ($list[$k]['card_info']['card_type'] == 2) {
                $list[$k]['card_type'] = '全卡通';
            }else {
                $list[$k]['card_type'] = '次卡';
            }
        }
        $row = [
            'list' => $list,
            'page_show' => $show
        ]; 
        return $row;
    }

    /**
     * 获取会员卡的信息
     * 
     * @param array $map            
     */
    public function getCardInfo(array $map = [])
    {
        $model = M('students_card');
        $field = 'card_id,card_type,card_nums,card_start_time,card_end_time,card_status';
        $data = $model->field($field)
            ->where($map)
            ->find();
        if (!empty($data)) {
           
            if ($data['card_type'] == 1) { // 期卡
                $start_time = date('Y-m-d', $data['card_start_time']);
                $end_time = date('Y-m-d', $data['card_end_time']);
                $data['info'] = "有效时间：{$start_time}至{$end_time}";
            } else { // 次卡
                $nums = M('students_card_log')->where([
                    'card_id' => $data['card_id']
                ])->count('log_id');
                $nums = (int) $nums;
                $data['info'] = "次数使用：{$nums}/{$data['card_nums']}";
                $data['has_nums'] = $nums;
            }
            return $data;
        } else {
            
            return false;
        }
    }
    /**
     * 获取学生训练历程
     * @param array $map
     * @param string $field
     * @param number $page_now
     * @param number $page_size
     * @param string $order
     */
    public function getTrain(array $map=[],$field='*',$page_now =1,$page_size = 0,$order ='')
    {
        if ($page_size < 1) {
            $page_size = C('PAGE_SIZE');
        }
        $model = M('students_train');
        $count = $model->field($field)->where($map)->count();
        $page = new Page($count, $page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $page->show(); // 分页显示输出
        $list = $model->field($field)->where($map)
            ->page("{$page_now},{$page_size}")
            ->order($order)->select();
        $row = [
            'list' => $list,
            'page_show' => $show
        ];
        return $row;
    }
    /**
     * 
     * @param number $student_id
     * @param string $field
     * @param number $badge_type
     * @return array
     */
    public function badge($student_id = 0,$field ='*',$badge_type=1)
    {
        $pre = C('DB_PREFIX');
        $map = ['s.student_id'=>$student_id,'b.badge_type'=>$badge_type];
        return M('students_badge')->alias('s')->field($field)
        ->join($pre.'badge as b on s.badge_id = b.badge_id','left')
        ->where($map)->select();
    }
    /**
     * 获取会员卡
     * @param array $map
     * @param string $field
     * @param array $search
     * @param number $page_now
     * @param number $page_size
     * @param string $order
     * @return array
     */
    public function cardList(array $map = [], $field = '*', array $search = [],$page_now = 1, $page_size = 0,$order ='')
    {
        $pre = C('DB_PREFIX');
        $model = M('students_card');
        if ($page_size < 1) {
            $page_size = C('PAGE_SIZE');
        }
        $count = $model->alias('c')
            ->join($pre.'students as s on c.student_id=s.student_id ')
            ->where($map)->count();
        $page = new Page($count, $page_size); // 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $page->show(); // 分页显示输出
        $list = $model->alias('c')
            ->join($pre . 'students as s on c.student_id=s.student_id ')
            ->where($map)
            ->page("{$page_now},{$page_size}")
            ->order($order)
            ->select();
        $row = [
                'list' => $list,
                'page_show' => $show
        ];
        return $row;
    }
}