

<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );


global $wpdb;

$vetid = $_POST['val'];

// 벳스쿨 유저 DB 테이블에서 아이디있으면 -> 회원 등급 보내기 
$sql = "SELECT m.user_id, u.user_login, m.meta_key, m.meta_value
        FROM wp_users as u
        join wp_usermeta as m
        on u.ID = m.user_id
        where u.user_login = '{$vetid}'
        and m.meta_key = 'wp_capabilities'";

$result = $wpdb->get_results($wpdb->prepare($sql));


// 회원등급 구분 
if($result){
    $capabilities = $result[0]->meta_value;
    
    if(strpos($capabilities,'vet_role')){ // 수의사일 경우
        echo 4;
    }else if(strpos($capabilities,'student_role')) { // 수의대생일 경우 
        echo 3;
    }else if(strpos($capabilities,'general_members')) { // 일반회원일 경우 
        echo 2;
    }else {
        echo 1;
    }
} else {
    echo 1;
}





?>