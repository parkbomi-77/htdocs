<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;

$code = $_POST['code'];

$sql = "SELECT * from wp_product_list where adv_state = 1 and mall_code =".$code;
$productcheck = $wpdb->get_results($wpdb->prepare($sql));

if($productcheck){ // 광고중인 상품있으면 삭제 막기
    echo true;
}



?>