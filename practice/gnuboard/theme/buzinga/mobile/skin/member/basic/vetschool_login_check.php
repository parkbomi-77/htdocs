<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../../../../../../common.php';

// 벳스쿨 계정으로 가입한 이력 확인
$vet_user = $_POST['response'];
$vet_user_id = $vet_user['user_login'];
$vet_user_code = $vet_user['user_uuid'];

$sql = "select * from {$g5['member_table']}
where mb_id = '{$vet_user_id}' 
and mb_vetcode = '{$vet_user_code}'";
$result = sql_query($sql);
$row = $result->fetch_assoc();

// 가입한 계정이 있으면 1
$res = 0;
if($row) {
    $res = 1;
}

echo $res;

