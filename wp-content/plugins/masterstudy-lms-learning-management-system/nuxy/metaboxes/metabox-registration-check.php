<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;

$code = $_POST['code'];
$product = $_POST['productId'];

$mall = $wpdb->get_results($wpdb->prepare("SELECT*FROM wp_shoppingmall where code =".$code));

// mall link 얻기
$link = $mall[0]->link;

echo $link.$product;



// 쇼핑몰 코드로 link 얻어내서 상품ID랑 붙혀서 링크보내기

?>