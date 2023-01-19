<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;

$code = $_POST['mallcode'];
$product = $_POST['productcode'];

$mall = $wpdb->get_results($wpdb->prepare("SELECT*FROM wp_shoppingmall where code =".$code));

// mall link 얻기
$link = $mall[0]->link;

if($code === '1029'){ // 벳스쿨일 경우 
    echo $link."product/".$product;
}else { // 타 쇼핑몰일 경우 
    $addvet = 'vet'.$product;
    $encryption = str_replace("=", "",base64_encode(openssl_encrypt($addvet, "AES-256-CFB", 'vetschoolsecretkey', 0)));
    
    $shoppingmallurl = $link.$product.'&vc='.$encryption;

    echo $shoppingmallurl;
}



// 쇼핑몰 코드로 link 얻어내서 상품ID랑 붙혀서 링크보내기

?>