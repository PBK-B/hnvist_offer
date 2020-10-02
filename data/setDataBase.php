<?php

include "./Env.php";
Env::loadFile('../.env');

$servername = Env::get('database.hostname');
$username = Env::get('database.username');
$password = Env::get('database.password');
$dbname = Env::get('database.database');

// 创建连接
$conn = mysqli_connect($servername, $username, $password, $dbname);

// 检测连接
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// 使用 sql 创建数据表
// $sql = "CREATE TABLE offer_2020 (
//     id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     offer_id VARCHAR(30) NOT NULL,
//     user_name VARCHAR(30) NOT NULL,
//     identity_end VARCHAR(30) NOT NULL,
//     courier_id VARCHAR(30) NOT NULL,
//     user_sex VARCHAR(1),
//     reg_date TIMESTAMP
//     )";

// if ($conn->query($sql) === true) {
//     echo "Table MyGuests created successfully";
// } else {
//     echo "创建数据表错误: " . $conn->error;
// }

$data_string = file_get_contents('./data_04.json');
$data_array = json_decode($data_string, true);

foreach ($data_array as $value) {
    // print_r($value['user_name'] . PHP_EOL);

    $sql = "INSERT INTO offer_2020 (offer_id, user_name, identity_end, identity, courier_id, user_sex)
VALUES ('" . $value['offer_id'] . "', '" . $value['user_name'] . "', '" . $value['identity_end'] . "', '','"
        . $value['courier_id'] . "', '" . $value['user_sex'] . "')";

    if ($conn->query($sql) === true) {
        echo PHP_EOL . $value['user_name'] . " 新记录插入成功";
    } else {
        echo "Error: " . $sql . PHP_EOL . $conn->error;
    }
}

echo PHP_EOL . "连接成功" . PHP_EOL;
$conn->close();
