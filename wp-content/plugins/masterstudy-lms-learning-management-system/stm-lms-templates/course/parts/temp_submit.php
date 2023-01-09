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
$sql = "SELECT * from wp_wish_list where user_id =".$user_id." and item_id='".$item_id."'";
$results = $wpdb->get_results($wpdb->prepare($sql));
var_dump($results);
if($results){ // 중복이 있으면 ?????? 
    echo '<script>alert("이미 장바구니에 담겨있는 상품입니다 \n 메뉴탭 -> 장바구니 -> 이웃 쇼핑몰 장바구니에서 확인해주세요 !");</script>';
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
        echo '<script>alert("상품을 장바구니에 담았습니다 \n 메뉴탭 -> 장바구니 -> 이웃 쇼핑몰 장바구니에서 확인해주세요 !");</script>';
}


?>