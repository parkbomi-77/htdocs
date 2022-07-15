<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );


$user_id = $_POST['user_id'];
$item_id = $_POST['item_id'];


global $wpdb;
$results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_wish_list where user_id =".$user_id." and item_id=".$item_id));
var_dump($results[0]->ID);
$delResult = $wpdb->query($wpdb->prepare("DELETE FROM wp_wish_list WHERE id =".$results[0]->ID));


$prevPage = $_SERVER['HTTP_REFERER'];
// 변수에 이전페이지 정보를 저장

header('location:'.$prevPage);
?>



