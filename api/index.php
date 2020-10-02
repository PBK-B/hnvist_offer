<?php

if (defined('DEBUG')) {
    //检测到处于开发模式
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

include "../data/Env.php";
Env::loadFile('../.env');

$b_servername = Env::get('database.hostname');
$b_username = Env::get('database.username');
$b_password = Env::get('database.password');
$b_dbname = Env::get('database.database');

$u_code = isset($_GET['code']) ? $_GET['code'] : null;
$u_name = isset($_GET['name']) ? $_GET['name'] : null;

if ($u_code == null || $u_name == null) {
    echo json_encode(['code' => -1, 'msg' => '姓名或身份证号输入错误！']);
    exit();
}

// 创建连接
$conn = mysqli_connect($b_servername, $b_username, $b_password, $b_dbname);

// 防止被注入
$u_name = mysqli_real_escape_string($conn, $u_name);
$u_code = mysqli_real_escape_string($conn, $u_code);
$u_code_end = substr($u_code, -4);

// 检测连接
if (!$conn) {
    die("server error!!!");
}

$sql = "SELECT * FROM offer_2020 WHERE user_name='" . $u_name . "' AND identity_end='" . $u_code_end . "'";
$result = mysqli_query($conn, $sql);

$user = null;

while ($user_data = mysqli_fetch_array($result)) {
    $user = $user_data;
}

if (!$user) {
    // 未查询到信息
    echo json_encode(['code' => -1, 'msg' => '查询失败，没有找到 ' . $u_name . ' 同学你的录取通知书快递单号，目前只支持统招录取同学的通知书快递单号查询，如有疑问请质询学校招生老师！']);
} else {

    // echo json_encode(['code' => 1, 'msg' => $user["user_name"] . ' 同学你好，你的录取通知书快递单号是：' . $user["courier_id"] . '<br><a href="https://m.kuaidi100.com/result.jsp?com=ems&nu=' . $user["courier_id"] . '">点击查看【' . $user["courier_id"] . '】物流信息</a>']);
    echo json_encode(['code' => 1, 'msg' => $user["user_name"] . ' 同学你好，你的录取通知书快递单号是：' . $user["courier_id"] . '<br><form id="goMail" method="post" action="http://www.ems.com.cn/ems/order/singleQuery_t" target="_blank"><input type="hidden" name="mailNum" value="' . $user["courier_id"] . '"/><a href="javascript:goMail();" οnclick="goMail();" id="agree">点击查看【' . $user["courier_id"] . '】物流信息</a></form>']);
}

// 关闭连接
$conn->close();

// 判断身份证有效性
function is_idcard($id)
{
    $id = strtoupper($id);
    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
    $arr_split = array();
    if (!preg_match($regx, $id)) {
        return false;
    }
    if (15 == strlen($id)) // 检查15位
    {
        $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
        @preg_match($regx, $id, $arr_split);
        // 检查生日日期是否正确
        $dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if (!strtotime($dtm_birth)) {
            return false;
        } else {
            return true;
        }
    } else // 检查18位
    {
        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
        @preg_match($regx, $id, $arr_split);
        $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if (!strtotime($dtm_birth)) // 检查生日日期是否正确
        {
            return false;
        } else {
            // 检验18位身份证的校验码是否正确。
            // 校验位按照 ISO 7064:1983.MOD 11-2 的规定生成，X 可以认为是数字 10。
            $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            $sign = 0;
            for ($i = 0; $i < 17; $i++) {
                $b = (int) $id{$i};
                $w = $arr_int[$i];
                $sign += $b * $w;
            }
            $n = $sign % 11;
            $val_num = $arr_ch[$n];
            if ($val_num != substr($id, 17, 1)) {
                return false;
            } else {
                return true;
            }
        }
    }
}
