<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

$user_id = $_POST['user_id'];
$item_id = $_POST['item_id'];
$product_name = $_POST['product_name'];
$course_id = $_POST['course_name'];
$lessons_id = $_POST['lessons_name'];


global $wpdb;

//중복찾기 
$sql = "SELECT * from wp_wish_list where user_id =".$user_id." and item_id='".$item_id."'";
$results = $wpdb->get_results($wpdb->prepare($sql));
if($results){ // 중복이 있으면 ?????? 
    echo '중복';
}else { // 없으면
    $wpdb->insert(
        'wp_wish_list', 
        array(
            'user_id' => $user_id,
            'item_id' => $item_id,
            'product_name' => $product_name,
            'course_id' => $course_id,
            'lessons_id' => $lessons_id,
        )
        );
        echo '저장';
}


?>