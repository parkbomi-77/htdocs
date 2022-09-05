<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;

$mallname = $_POST['mallname'];
$link = $_POST['link'];

if($mallname){ // 쇼핑몰 이름 중복체크
    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_shoppingmall where name ='{$mallname}' and state = 1"));
    if($result){ // 중복이 있을 경우
        echo '중복';
    }else { // 중복이 없을 경우 
        echo '통과';
    }
}else if($link){ // 링크 중복체크 
    $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_shoppingmall where link ='{$link}' and state = 1"));
    if($result){ // 중복이 있을 경우
        echo '중복';
    }else { // 중복이 없을 경우 
        echo '통과';
    }
}



?>