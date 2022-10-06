<?php 
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );


$user_id = $_POST['user_id'];
$item_id = $_POST['item_id'];


global $wpdb;
$results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where ID =".$item_id));

//디비에 있는 링크
$code = $results[0]->product_code; 
$addvet = 'vet'.$code;

// 쇼핑몰 링크 가져오기
$mallcode = $results[0]->mall_code; 
$mall = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code =".$mallcode));
$mallLink = $mall[0]->link;
if($mallcode === "1029"){ // 벳스쿨 제품일 경우 벳스쿨 상세페이지로 이동
    $shoppingmallurl = $mallLink."product/".$code;
} else { // 타 사이트 제품일 경우 타 사이트 상세페이지로 이동
    $encryption = str_replace("=", "",base64_encode(openssl_encrypt($addvet, "AES-256-CFB", 'vetschoolsecretkey', 0)));
    
    //쇼핑몰 상세페이지링크 하드코딩. 아이템ID 동적으로 넣어야함 .. product_id
    $shoppingmallurl = $mallLink.$code.'&vc='.$encryption;
    
}
$prevPage = $_SERVER['HTTP_REFERER'];
$location = $prevPage.'#registrationbox';


//팝업차단하라고 안내해줘야함 
echo "<script>window.open('".$shoppingmallurl."')</script>";

//로케이션 잠시 꺼두려면 주석
echo "<script>
document.location.href='".$location."';
</script>";

?>
