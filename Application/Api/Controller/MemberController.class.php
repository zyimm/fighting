<?php
/**
 * 会员中心
 * 
 * @author zyimm <799783009@qq.com>
 * Copyright (c) 2016 http://www.zyimm.com All rights reserved.
 * 2016年10月31日 下午4:12:11
 */

namespace Api\Controller;
use Lib\Pinyin;
class MemberController extends CommonController
{
    
    /**
     * 个人中心首页
     * 
     * @param http://www.fighting.com/api/Member?token=1234567890
     */
    public function index()
    {

        $data = [];
        $data = [
           'student_name'=>static::$student_info['student_name'],
           'level_id'=>static::$student_info['level_id'], 
           'level_name'=>get_Level_name(static::$student_info['level_id']),
           'bedge_nums'=>M('students_badge')->where(['store_id'=>static::$store_id,'student_id'=>static::$student_id])->count(),  
           'train_nums'=>M('students_train')->where(['store_id'=>static::$store_id,'student_id'=>static::$student_id])->count(),
           'store_name'=>get_store_name(static::$store_id)   
        ];
        static::jsonData($data);

    }
    /**
     * 个人资料
     */
    public function info()
    {
        $data = M('members')->where(['id'=>static::$member_id])->find();
        $province = get_region_info([$data['province']]);
        $city = get_region_info([$data['city']]);
        $data['province'] = empty($province)?'北京':$province;
        $data['city'] = empty($city)?'北京':$city;
        if(!empty(static::$student_info)){
            $student_info =static::$student_info;
            $data['student_name'] = $student_info['student_name'];
        }else{
            $data['student_name'] = '';
        }
       
        static::jsonData($data);
    }
    /**
     * 个人资料更新
     */
    public function infoUpdate()
    {
        $nick_name = I('nick_name');
        if(empty($nick_name)){
            static::jsonData('姓名必填!',1);
        }
        $sex = (int)I('sex');
        $birth =(int)I('birth');
        $province_id = (int)I('province_id');
        $city_id = (int)I('city_id');
        $address = I('address');
        $sign = I('sign');
        $data =[
            'nick_name'=>dhtmlspecialchars($nick_name),
            'sex'=>$sex,
            'birth'=>$birth,
            'province_id'=>$province_id,
            'city_id'=>$city_id,
            'sign'=>$sign,
            'address'=>$address,
            'last_login_time'=>time()
            
        ];
        if(M('members')->where(['id'=>static::$member_id])->save($data)){
            static::jsonData('更新成功!');
        }else{
            static::jsonData('更新失败',1);
        }
    }
    /**
     * 训练历程
     */
    public function trainStatistics()
    {
        $year = (int)I('year');
        $month = (int)I('month');
        if(empty($year) || empty($month)){
            static::jsonData('时间不对!',1);
        }
        //本月训练次数
        $month_time = month_time($year,$month);
        $model = D('Store/Students');
        if(empty(static::$student_id)){
            static::jsonData('学生信息不存在!',1);
        }
      
        $map = [
            'student_id' =>static::$student_id,
            
        ];
     
        $month_train_nums = [];
        $row = $model->getTrain($map,$field='time,type',1,1000000);
        $train_nums = 0;
        $praise_nums = 0;
        foreach ($row['list'] as $k=>$v){
           if($v['type'] == 1 ){//训练次数
              
               if($v['time']>$month_time[0] && $v['time']<$month_time[1]){
                   $month_train_nums[] = $v;
               }
               ++$train_nums;
           }
           if($v['type'] == 3 ){//好评次数
               ++$praise_nums;
           } 
        }
        $data = [];
        $data['month_train'] =  $month_train_nums;
        $data['month_train_nums'] = count($month_train_nums);
        $data['train_nums'] = $train_nums;
        $data['praise_nums'] = $praise_nums;
        M('students')->where(['student_id'=>static::$student_id])->setField('train_index',($train_nums+$praise_nums));
        $data['top'] = 1;
        $temp = M('students')->field('student_id')->where(['is_del'=>0,'store_id'=>static::$store_id])->order('train_index desc')->select();
        $data['card_info'] = D('Store/Students')->getCardInfo(['student_id'=>static::$student_id,'card_status'=>1]);
        if(!empty($data['card_info'])){
            $data['card_status'] = 1;
            unset($data['card_info']['info']);
        }else{
            $data['card_status'] = 0;
            $data['card_info'] = '卡处于被禁用状态!';
        }
       
        foreach ($temp as $v){
            if($v['student_id'] == static::$student_id){
                break;
            }
            ++$data['top'];
        }
        static::jsonData($data);
    }
    
    /**
     * 我的微章
     * @param http://www.fighting.com/api/Member/badge?type=1&token=1234567890
     */
    
    public function badge()
    {
        $type = (int)I('type');
        if(empty($type)){
            static::jsonData('类型为空!',1);
        }
        $data = [];
        $model = D('Store/Students');
        $field = 's.time,b.badge_name,b.badge_icon,b.desc,b.badge_type';
        $map = ['status'=>1,
                'is_del'=>0,
                'badge_type'=>$type,
                'is_sys'=>['in','0,1'],
                'store_id'=>['in',[static::$store_id,0]]
        ];
        $data['badge'] = M('badge')
                         ->field('badge_name,badge_icon_disable,desc,badge_type')
                         ->where($map)->select();
        $data['badge_light']= $model->badge(static::$student_id,$field,$type);
        static::jsonData($data);
    }
    /**
     * 绑定学生
     */
    public  function bingStudent()
    {
        $student_no_alias = I('student_no');
        
        if(empty($student_no_alias)){
            static::jsonData('学号为空!',1);
        }
        $map = [
            'student_status'=>1,
            'is_del'=>0,
            'student_no_alias'=>$student_no_alias,
            'store_id'=>static::$store_id
            
        ];
        $student_info = M('students')->where($map)->find();
        if(empty($student_info['student_id'])){
            static::jsonData('无法找指定学号学生!',1);
        }else{
            //
            $member_info = static::$member_info;
            if($student_info['student_id'] == static::$student_id){
                static::jsonData('无需重复绑定！',1);
            }
            if($student_info['parent_mobile'] == $member_info['mobile'] || $student_info['self_mobile'] == $member_info['mobile']){
               if(M('members')->where(['id'=>static::$member_id])->setField('student_id',$student_info['student_id'])){    
                   
                   static::jsonData($student_info['student_name']);
               }else{
                   static::jsonData('绑定失败!',1);
               }
                
            }else{
                static::jsonData('预留手机号码不匹配！',1);
            }
            
        }
    }
    /**
     * 我的关注
     */
    public function follows()
    {
        $temp = M('follow')->where(['uid'=>static::$member_id])->select();
        if(empty($temp)){
            static::jsonData('暂无数据-1!',0);
        }
        $list = [];
        foreach ($temp as $k=>$v){
            
            switch ($v['table']){
                case 'article':
                    $article_model = D('Store/Article');
                    $map = [
                            'is_del' => 0,
                            'article_id'=>$v['table_id'],
                            'store_id' => static::$store_id
                    ];
                    $article_field = 'article_id as id,article_title as title,article_image as image,time';
                    $article_data = $article_model->getArticleList($map,$article_field,1,1);
                    $article_data = $article_data['list'];
                    if(!empty($article_data)){
                        foreach ($article_data as $k=>$v){
                            $article_data[$k]['type'] = 'article';
                            $article_data[$k]['url'] ='http://'.$_SERVER['HTTP_HOST'].'/api/Article/view?article_id='.$v['id'];
                        }
                        $list[] = $article_data;
                    }
                break;
                case 'talk':
                $talk_model = D('Brand/Talk');
                $map = [
                        't.is_del'=>0,
                        'status' =>1,
                        't.talk_id'=>$v['table_id'],
                        't.brand_id'=>static::$brand_id
                ];
                $filed ='t.talk_id as id,t.talk_title as title,t.talk_image as image ,t.time';
                $talk_data =  $talk_model->getTalkList($map,$filed,[],1,1);
                $talk_data =  $talk_data['list'];
                if(!empty($talk_data)){
                    foreach ($talk_data as $k=>$v){
                        $talk_data[$k]['type'] = 'talk';
                    }
                    $list[] = $talk_data;
                }
                break;
            }
        }
        if(empty($list)){
            static::jsonData('暂无数据!',0);
        }else{
            static::jsonData($list);
        }
    }

    /**
     * 头像更新
     */
    public function avatar ()
    {
        $config = array(
                'maxSize' => 3145728,
                'savePath' => './uploads/avatar/',
                'saveName' => array(
                        'uniqid',
                        ''
                ),
                'exts' => array(
                        'jpg',
                        'gif',
                        'png',
                        'jpeg'
                ),
                'autoSub' => true,
                'subName' => array(
                        'date',
                        'Ymd'
                )
        );
        $upload = new \Think\Upload();
        $info = $upload->uploadOne($_FILES['avatar']);
        if (! $info) {
            // 上传错误提示错误信息
            static::jsonData($upload->getError(), 1);
        } else {
            $info['avatar'] = "/uploads/avatar/" . $info['savename'];
            $res = M("members")->where([
                    'id' => static::$member_id
            ])->save([
                    'avatar' => $info['avatar']
            ]);
            if ($res) {
                static::jsonData('更新成功!');
            } else {
                static::jsonData('更新失败!', 1);
            }
        }
    }
    
    public function getProvince()
    {
        
        $model =D('Admin/Region');
        $data = $model->getProvince();
        if(!empty($data)){
            static::jsonData($data);
        }else{
            static::jsonData('暂无数据!',1);
        }
    }
    
    public function getCity()
    {
        $province_id = (int)I('province_id');
        $model =D('Admin/Region');
        $data = $model->getCity($province_id);
        if(!empty($data)){
            static::jsonData($data);
        }else{
            static::jsonData('暂无数据!',1);
        }
    }
    
    
    /**
     * 通讯录列表
     */
    public function addressBook()
    {
        if(static::$member_info['utype'] == 1)
           static::jsonData('不是教练或是管理员',1);
            $where = [
                'store_id' => static::$store_id
            ];
            $keyword = I("keyword");
            if ($keyword) {
                $where['name'] =[
                    'like',
                    '%' . $keyword . '%'
                ];
            }
    
            $pinyin = new Pinyin;
            $field  = 'parent_mobile as mobile,store_id,student_name as name,student_sex as sex,student_avatar as avatar';
            $students = M('students')->field($field)->where($where)->select();
            $list = array();
            foreach ($students as $s) {
                $s['type'] = 1;
                $s['szm'] = $pinyin->getFirstPY($s['name']);
                $s['store_name'] = get_store_name($s['store_id']);
                $list[] = $s;
            }
            $where['utype'] = 2;
            $coach_list = M('members')->where($where)
                ->field('mobile,nick_name as name,sex,avatar,store_id')
                ->select();
            foreach ($coach_list as $v) {
                $v['type'] = 2;
                $v['szm'] = $pinyin->getFirstPY($v['name']);
                $s['store_name'] = get_store_name($s['store_id']);
                $list[] = $v;
            }
            if ($list) {
                $data = list_sort_by($list, 'szm');
            } else {
                $data = '';
                static::jsonData('暂无数据!',1);
            }
            
            static::jsonData($data);
    }
    /**
     * 根据教练获取课程
     */
    public function getCoursesByCoach()
    {
        if(empty(static::$course_id)){
            static::jsonData('当前用户不是教练类型!',1);
        }
        //获得该教练课程
        $model =D('Store/Coachs');
        $field = 'cc.coach_id,cc.course_id,c.course_title,c.course_image,
              c.course_home,c.course_students,c.course_time';
        $data = $model->getCourses(static::$coach_id,static::$store_id,$field);
        
        $week_array=["日","一","二","三","四","五","六"];
        if(!empty($data)){
            foreach ($data as $k=>$v){
                $temp_course_time = explode('#',$v['course_time']);
                foreach ($temp_course_time as $val){
                    $_t = explode(',',$val);
                    $data[$k]['course_time_formation'] = empty($data[$k]['course_time_formation'])?'':$data[$k]['course_time_formation'];
                    $data[$k]['course_time_formation'].= '每周'.$week_array[$_t[0]]."的{$_t[1]}:{$_t[2]}-{$_t[3]}:{$_t[4]} <br />";
                    unset($data[$k]['course_time']);
                }
                $map_students = [
                    'c.course_id'=>$v['course_id'],
                    's.is_del'=>0,
                    's.student_status'=>1,
                    'c.store_id'=>static::$store_id
                ];
                $course_model = D('Store/courses');
                $temp = $course_model->getCourseStudents($map_students,'c.id');
                $data[$k]['apply_num'] =$temp['count'];
                $temp_coachs  = $course_model->getCourseCoachs($v['course_id'],'cc.coach_id,m.nick_name');
                $coachs = [];
                foreach ($temp_coachs['list'] as $key=>$val){
                    $coachs[$val['coach_id']] = $val['nick_name'];
                }
                $data[$k]['coachs_nums'] = count($coachs);
            }
            static::jsonData($data);
        }else{
            static::jsonData('没有相关数据!',1);
        }
    }
    /**
     * 根据课程获取学生列表
     */
    public function getStudentsByCourse()
    {
        $course_id = (int)I('course_id');
        $type = (int)I('type');
        if(empty($type)){
            static::jsonData('考勤的类型缺失',1);
        }
        if(empty($course_id)){
            static::jsonData('课程的id不存在!',1);
        }
        $time_start = time()-(3600*1);
        $time_interval = "{$time}";
        switch ($type) {
            case 0://签入
                $map = [
                    'time' => [
                        'between',
                        $time
                    ],
                    'store_id' => static::$store_id,
                    'type'=>1
                    
                    
                ];
                M('students_train')->field('student_id')->where($map)->select();
                break;
        }        
        $model = D('Store/courses');
        $map_students = [
            'c.course_id'=>$course_id,
            's.is_del'=>0,
            's.student_status'=>1
        ];
        
        $pinyin = new Pinyin;
        $data = $model->getCourseStudents($map_students,'c.id,s.student_name,s.student_avatar,s.store_id');
        foreach ($data['list'] as $k=>$v){
            $data['list'][$k]['store_name'] = get_store_name($v['store_id']);
            $data['list'][$k]['szm'] = $pinyin->getFirstPY($v['student_name']);
        }
        static::jsonData($data['list']);
    }
   /**
    * 学生考勤
    */
    public function studentAttence()
    {
        //获得该教练课程
        $type = (int)I('type');
        $student_ids =I('student_ids');
        
        if(empty($type)){
            static::jsonData('考勤的类型缺失',1);
        }
        if(static::$member_info['utype'] != 2 ){
            //
            static::jsonData('当前用户不是教练!',1);
        }
        if(empty($student_ids)){
            static::jsonData('学生id集合缺失!',1);
        }
        if(empty($course_id)){
            static::jsonData('课程的id不存在!',1);
        }
        $student_arr = explode(',',$student_ids);
        $time = time();
        $pre = C('DB_PREFIX');
        foreach ($student_arr as $student_id) {
            $info = M("members")->alias('m')
                    ->field("m.mobile,m.student_id,s.student_name")
                    ->join($pre.'students as s on s.student_id = m.student_id','left')
                    ->where(["m.student_id" =>$student_id])->find();
            $store_name = get_store_name(static::$store_id);
            switch ($type) {
                case 1:
                    $name = "签入";
                    $insert = array(
                        'store_id' => static::$store_id,
                        'student_id' => $student_id,
                        'type' => $type,
                        'time' => $time  
                    );
                    M('students_train')->add($insert);
                    //训练次数
                    $data=[];
                    $data['train_nums']=M('students_train')->where(['type'=>1,'student_id'=>$student_id])->count();
                    light_badge($student_id,1,static::$store_id,$data);
                    // 极光推送
                    message_push([$info['mobile']], "尊敬的" .$info['student_name']. "同学，" . date("Y年m月d日H时i分", $time) . "在" . $this->memberinfo['store_name'] . "道馆开始训练了！");
                    break;
                case 2:
                    $name = "签出";
                    $insert = array(
                        'store_id' => static::$store_id,
                        'student_id' => $student_id,
                        'type' => $type,
                        'time' => $time
                    );
                    M('students_train')->add($insert);
                    // 极光推送
                    $phones = M("members")->field("phone,sname")->where(['type'=>2,'student_id'=>$student_id])->select();
                    message_push([$info['mobile']], "尊敬的" .$info['student_name']. "同学，" . date("Y年m月d日H时i分", $time) . "在" . $store_name . "道馆开始训练了！");
                    break;
                case 3:
                    $name = "好评";
                    $name = "签出";
                    $insert = array(
                        'store_id' => static::$store_id,
                        'student_id' => $student_id,
                        'type' => $type,
                        'time' => $time
                    );
                    M('students_train')->add($insert);
                    // 极光推送
                 
                    $data['assess_nums']=M('students_train')->where(['type' => 3,'student_id'=>$student_id])->count();
                    light_badge($student_id,1,static::$store_id,$data);
                    // 极光推送
                    message_push([$info['mobile']],"恭喜" . $info['student_name'] . "同学在" . date("Y年m月d日H时i分", $time) . "在" . $store_name . "的训练中获得了教练员好评，Let's fighting!");
                    break;
            }
        }
       
       static::jsonData('考勤成功!');
    }
}