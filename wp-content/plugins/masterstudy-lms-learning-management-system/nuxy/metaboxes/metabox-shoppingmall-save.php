<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;


$mall = $_POST['name'];
// 광고의뢰 쇼핑몰 리스트 빈 값은 걸러내기
function empty_ ($var) {
    if($var !== ""){
        return $var;
    }
}
$newmall = array_filter($mall, "empty_");

$results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall"));
$num = count($results)-1;
$nodenum = $results[$num]->code;

for($i=0; $i<count($newmall); $i++){
    $wpdb->get_results($wpdb->prepare("INSERT INTO wp_shoppingmall (`name`) VALUES ('".$newmall[$i]."');"));
}


$prevPage = $_SERVER['HTTP_REFERER'];
// 변수에 이전페이지 정보를 저장
$location ='location:'.$prevPage.'#shopping_mall';
header($location);
?>