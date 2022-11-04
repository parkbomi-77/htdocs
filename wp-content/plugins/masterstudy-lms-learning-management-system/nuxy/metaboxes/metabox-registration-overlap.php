<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;

$name = $_POST['name'];

if(!$name){
    echo 'empty';
} else {
    $result = "SELECT*FROM wp_product_list where product_name = '".$name."' and adv_state=1";
    $overlap = $wpdb->get_results($wpdb->prepare($result));
    
    if($overlap){ // 제품명 중복이 있을시
        echo 'yes';
    }else { // 제품명 중복이 없을시
        echo 'no';
    }
}



?>