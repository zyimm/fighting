<?php

/**
 * 公共函数
 */

/**
 * 导入excel文件
 * 
 * @param string $file            
 * @return array excel文件内容数组
 */
function import_excel ($file = '')
{
    // 判断文件是什么格式
    $type = pathinfo($file);
    $type = strtolower($type["extension"]);
    
    set_time_limit(0);
    import('IOFactory', APP_PATH . 'Lib/PHPExcel', '.php');
    // 判断使用哪种格式
    if ($type == 'csv') {
        $reader = PHPExcel_IOFactory::createReader($type);
        $reader->setDelimiter(',')
            ->setInputEncoding('GBK')
            ->setEnclosure('"')
            ->setLineEnding("\r\n")
            ->setSheetIndex(0);
        $php_excel = $reader->load($file);
    } else {
        $php_excel = PHPExcel_IOFactory::load($file);
    }
    
    $sheet = $php_excel->getSheet(0);
    // 取得总行数
    $total_rows = $sheet->getHighestRow();
    // 取得总列数
    $total_columns = $sheet->getHighestColumn();
    // 循环读取excel文件,读取一条,插入一条
    $excel_data = array();
    // 从第一行开始读取数据
    for ($j = 3; $j <= $total_rows; $j ++) {
        // 从A列读取数据
        for ($k = 'A'; $k <= $total_columns; $k ++) {
            // 读取单元格
            $value = trim(
                    $php_excel->getActiveSheet()
                        ->getCell("{$k}{$j}")
                        ->getValue());
            if (! empty($value)) {
                $excel_data[$j][] = $value;
            } else {
                continue;
            }
        }
    }
    return $excel_data;
}

/**
 * 上传文件
 * 
 * @param string $path
 *            上传路径
 * @param array $config
 *            上传的配置
 * @param array $ext
 *            允许后缀
 * @return string|boolean|string[]|mixed[] 保存路径
 */
function file_upload ($path = '', $config = array(), $ext = array())
{
    if (empty($path))
        $path = './Public/Uploads/';
    if (empty($ext))
        $ext = [
                'jpg',
                'gif',
                'png',
                'jpeg'
        ];
    if (empty($config)) {
        $config = [
                'maxSize' => 3145728,
                'saveName' => [
                        'uniqid',
                        ''
                ],
                'autoSub' => true,
                'subName' => [
                        'date',
                        'Ymd'
                ]
        ];
    }
    $config['rootPath'] = './';
    $config['savePath'] = $path;
    $config['exts'] = $exts;
    $upload = new \Think\Upload($config); // 实例化上传类
    $info = $upload->upload();
    // 上传错误提示错误信息
    if (! $info) {
        return $upload->getError();
    } else {
        // 上传成功
        return $info;
    }
}

/**
 * 验证手机号
 *
 * @param string $mobile            
 * @return boolean
 */
function check_mobile ($mobile = '')
{
    if (strlen($mobile) != 11) {
        return false;
    }
    if (preg_match(
            "/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|14[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/", 
            $mobile)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 邮箱验证
 *
 * @param string $email            
 * @return boolean
 */
function check_email ($email = '')
{
    if (mb_strlen($email) < 5) {
        return false;
    }
    $res = "/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/";
    if (preg_match($res, $email)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 检测输入的验证码是否正确，$code为用户输入的验证码字符串
 *
 * @author zyimm
 * @param string $code            
 * @param string $id            
 * @return boolean
 */
function check_verify ($code, $id = '')
{
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**
 * 对内容进行安全处理
 *
 * @param string|array $string        要处理的字符串或者数组
 * @param $string $flags     指定标记
 */
function dhtmlspecialchars ($string, $flags = null)
{
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = dhtmlspecialchars($val, $flags);
        }
    } else {
        if ($flags === null) {
            $string = str_replace(
                    array(
                            '&',
                            '"',
                            '<',
                            '>'
                    ), 
                    array(
                            '&',
                            '"',
                            '<',
                            '>'
                    ), $string);
            if (strpos($string, '&#') !== false) {
                $string = preg_replace('/&((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', 
                        '&\\1', $string);
            }
        } else {
            if (PHP_VERSION < '5.4.0') {
                $string = htmlspecialchars($string, $flags);
            } else {
                if (strtolower(CHARSET) == 'utf-8') {
                    $charset = 'UTF-8';
                } else {
                    $charset = 'ISO-8859-1';
                }
                $string = htmlspecialchars($string, $flags, $charset);
            }
        }
        $string = trim($string);
    }
    return $string;
}

/**
 * 手机邮箱通用验证
 *
 * @param string $str            
 * @return boolean
 */
function format_verify ($str = '', $is_one = 0)
{
    if (! is_phone($str, $is_one)) {
        return false;
    }
    if (! is_email($str, $is_one)) {
        return false;
    }
    return true;
}

/**
 * 是否是手机号码
 *
 * @param string $mobile
 *            手机号码
 * @param number $is_one
 *            是否检测唯一 默认不检测
 * @return boolean
 */
function is_mobile ($mobile, $is_one = 0)
{
    if (strlen($mobile) != 11 ||
             ! preg_match('/^1[3|4|5|7|8][0-9]\d{4,8}$/', $mobile)) {
        return false;
    } else {
        if ($is_one != 0) {
            if (M('users')->where('mobile_phone=' . $mobile)->count('user_id') >
                     0) {
                // echo M('users')->getLastSql();
                return false;
            }
        }
        return true;
    }
}

/**
 * 是否为一个合法的email
 *
 * @param sting $email            
 * @param number $is_one
 *            是否检测唯一 默认不检测
 * @return boolean
 */
function is_email ($email, $is_one = 0)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && $is_one == 0) {
        return true;
    } else {
        if ($is_one != 0) {
            if (M('users')->where("email='{$email}'")->count('user_id') > 0) {
                // echo M('users')->getLastSql();exit;
                return false;
            }
        }
        return true;
    }
}

/**
 * 快捷获取inc
 *
 * @param string $name            
 * @param string $path            
 * @return boolean|NULL|unknown|boolean|string
 */
function get_inc ($name, $path)
{
    static $_inc_cache = array();
    // 获取缓存数据
    if (isset($_inc_cache[$name]))
        return $_inc_cache[$name];
    $filename = $path . $name . '.php';
    if (is_file($filename)) {
        $value = include $filename;
        if (! $value) {
            $value = false;
        }
    } else {
        $value = false;
    }
    return $value;
}

/**
 * 发送邮件(默认html格式)
 *
 * @param unknown $email
 *            array /string $email 邮件地址
 * @param unknown $title
 *            邮件标题
 * @param unknown $message
 *            邮件内容
 * @param number $is_html            
 */
function send_email ($email, $title, $message, $is_html = 1)
{
    import('Lib.Email.PHPMailer');
    $mail = new PHPMailer();
    if (S('email_conf')) {
        $email_conf = S('email_conf');
    } else {
        $email_conf = include CONF_PATH . 'email.inc.php';
        S('email_conf', $email_conf);
    }
    $port = 25;
    $smtpServer = $email_conf['smtp_host'];
    $smtpUser = $email_conf['user'];
    $smtpPwd = $email_conf['pass'];
    // 设置PHPMailer使用SMTP服务器发送Email
    $mail->IsSMTP();
    // 设置邮件的字符编码，若不指定，则为'UTF-8'
    $mail->CharSet = 'UTF-8';
    // 添加收件人地址，可以多次使用来添加多个收件人
    if (is_array($email)) {
        foreach ($email as $v) {
            $mail->AddAddress($v);
        }
    } else {
        $mail->AddAddress($email);
    }
    // 设置邮件正文
    // $mail->Body=$message;
    $mail->MsgHTML($message);
    // 设置邮件头的From字段。
    $mail->From = $email_conf['email'];
    // 设置发件人名字
    $mail->FromName = $email_conf['from_name'];
    // 设置邮件标题
    $mail->Subject = $title;
    // 设置SMTP服务器。
    $mail->Host = $smtpServer;
    // 设置为“需要验证”
    $mail->SMTPAuth = true;
    // 设置用户名和密码。
    $mail->Username = $smtpUser;
    $mail->Password = $smtpPwd;
    // 发送邮件。
    return ($mail->Send());
    // return false;
}

/**
 * 发送短信验证码
 *
 * @param number $tel            
 * @param string $content            
 * @return number
 */
function send_sms ($tel = 0, $content = '')
{
    $code = rand(100000, 999999);
    $content = C('SMS_SIGN') . $content . $code;
    if (! empty(session('smstime._send_sms_time'))) {
        if (time() - session('smstime._send_sms_time') <= 60) { // 60s发送频率
            $resCode = - 2;
            return $resCode;
            exit();
        }
    }
    $sms_api = C('SMS_ULR');
    $data = [
            'appid' => C('SMS_APPID'),
            'to' => $tel,
            'content' => $content,
            'timestamp' => time(),
            'signature' => C('SMS_SIGNATURE')
    ];
    $rs = send_curl_request($sms_api, $data);
    $rs = json_decode($rs);
    if ($rs->status == 'success') {
        session_start();
        $lifeTime = 3 * 60;
        setcookie(session_name(), session_id(), time() + $lifeTime, "/");
        session('smstime', 
                array(
                        '_send_sms_time' => time(),
                        '_code' => $code
                ));
        $resCode = 1;
        return $resCode;
    } else {
        $resCode = - 1;
        return $resCode;
    }
}

/**
 *
 * @param unknown $url            
 * @param array $data            
 * @return string|mixed
 */
function send_curl_request ($url, array $data = [])
{
    if (empty($url)) {
        return '';
    }
    $result = '';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/**
 *
 * @param unknown $url            
 * @param unknown $data            
 * @return mixed
 */
function curl_post ($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // post数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // post的变量
    $post_data = "";
    foreach ($data as $key => $value) {
        $post_data .= "$key=$value&";
    }
    $post_data = substr($post_data, 0, strlen($post_data) - 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

/**
 * 面包屑导航1/2
 * 
 * @param number $menu_id            
 * @return string
 */
function crumbs_menu ($menu_id = 0, $module = 'admin_menu')
{
    if (empty($menu_id))
        return false;
    $crumbs = '';
    $menu_info = M($module)->where([
            'status' => 1,
            'id' => $menu_id
    ])->find();
    
    if (! empty($menu_info['pid'])) {
        $crumbs .= crumbs_menu($menu_info['pid'], $module);
    }
    $crumbs .= "<li><a href='#'>{$menu_info['name']}</a> </li>";
    
    return $crumbs;
}

/**
 * 面包屑导航2/2
 * 
 * @param number $menu_id            
 * @param string $tag    当前操作的标识
 * @return string
 */
function crumbs ($menu_id = 0, $tag = '内容', $module = 'admin_menu')
{
    $crumbs = "<ul class='bread'><li><a href='#' class='icon-home'>首页</a> </li>" .
             crumbs_menu($menu_id, $module) . "<li>{$tag}</li> </ul>";
    return $crumbs;
}

/**
 * level_name
 * 
 * @param number $level_id            
 */
function get_Level_name ($level_id = 0)
{
    if (empty($level_id)) {
        return false;
    } else {
        return M('level')->where([
                'level_id' => $level_id
        ])->getField('level_name');
    }
}
/**
 * 获取道馆名称
 * @param number $store_id
 */
function get_store_name($store_id = 0)
{
    if (empty($store_id)) {
        return '平台';
    } else {
        $store_name = M('store')->where([ 'store_id' => $store_id])->getField('store_name'); 
        if(empty($store_name)){
            return '平台';
        }
        return $store_name;
    }
}