<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once '../../../../../../common.php';

// 벳스쿨 계정으로 가입한 이력 확인
$vet_user = $_POST['response'];
$vet_user_id = $vet_user['user_login'];
$vet_user_code = $vet_user['user_uuid'];
$vet_user_class = $vet_user['user_class'];

// 현재 벳스쿨 wait, customer 등급일 경우 
if($vet_user_class === 'wait') {
    echo 2;
    exit;
}else if($vet_user_class === 'customer') {
    echo 3;
    exit;

}

// 회원의 아이디, UUID, 회원등급 세션에 저장
set_session("ss_mb_vetid", $vet_user_id);
set_session("ss_mb_uuid", $vet_user_code);
set_session("ss_mb_class", $vet_user_class);

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

