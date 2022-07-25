<?php 
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );


$user_id = $_POST['user_id'];
$item_id = $_POST['item_id'];


global $wpdb;
$results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where ID =".$item_id));

//디비에 있는 링크
$bb = $results[0]->product_link;

//쇼핑몰 상세페이지링크 하드코딩
$aa = 'http://localhost:8888/practice/gnuboard/shop/item.php?it_id=1658398347';

//현재 페이지 "location.href='링크 주소'"

//팝업차단하라고 안내해줘야함 
echo "<script>window.open('".$aa."')</script>";


$prevPage = $_SERVER['HTTP_REFERER'];
$location = $prevPage.'#registrationbox';

echo "<script>
document.location.href='".$location."';
</script>";

?>
