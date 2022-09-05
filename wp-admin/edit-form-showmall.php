<?php
global $wpdb;
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

$id = $_POST['ID'];
$result = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where ID =".$id));
$code = $result[0]->mall_code;

$result = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code =".$code));
// echo $result[0]->name;
header('Content-Type: application/json');
echo json_encode($result[0]->name);


?>