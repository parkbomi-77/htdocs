<?php 
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );


$user_id = $_POST['user_id'];
$item_id = $_POST['item_id'];


global $wpdb;
$results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where ID =".$item_id));

//디비에 있는 링크
$code = $results[0]->product_code;

//쇼핑몰 상세페이지링크 하드코딩. 아이템ID 동적으로 넣어야함 .. product_id
$aa = 'http://localhost:8888/practice/gnuboard/shop/item.php?it_id='.$code.'&code=vet';

//현재 페이지 "location.href='링크 주소'"

//팝업차단하라고 안내해줘야함 
echo "<script>window.open('".$aa."')</script>";


$prevPage = $_SERVER['HTTP_REFERER'];
$location = $prevPage.'#registrationbox';

// echo "<script>
// document.location.href='".$location."';
// </script>";

?>
