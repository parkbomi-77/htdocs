<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;

$mallname = $_POST['mallname'];
$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_shoppingmall where name ='{$mallname}'"));
if($result){ // 중복이 있을 경우
    echo '중복';
}else { // 중복이 없을 경우 
    echo '통과';
}



?>