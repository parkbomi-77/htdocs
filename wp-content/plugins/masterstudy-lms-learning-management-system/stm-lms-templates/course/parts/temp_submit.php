<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

$user_id = $_POST['user_id'];
$item_id = $_POST['item_id'];
$product_name = $_POST['product_name'];

//수량,가격 일단 하드코딩
$quantity = 1;
$price = 1000;

global $wpdb;

//중복찾기 
$results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_wish_list where user_id =".$user_id." and item_id='".$item_id."'"));
var_dump($results);
if($results){ // 중복이 있으면 ?????? 
    echo "<script>alert('이미 장바구니에 담겨있는 상품입니다');</script>";
}else { // 없으면
    $wpdb->insert(
        'wp_wish_list', 
        array(
            'user_id' => $user_id,
            'item_id' => $item_id,
            'product_name' => $product_name,
            'quantity' => $quantity,
            'price' => $price,
        )
        );
        echo "<script>alert('장바구니에 담았습니다!');</script>";
}


?>