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

$encryption = str_replace("=", "",base64_encode(openssl_encrypt($addvet, "AES-256-CFB", 'vetschoolsecretkey', 0)));
// $decryption = openssl_decrypt(base64_decode($encryption),"AES-256-CFB", 'vetschoolsecretkey', 0);

//쇼핑몰 상세페이지링크 하드코딩. 아이템ID 동적으로 넣어야함 .. product_id
$aa = 'http://localhost:8888/practice/gnuboard/shop/item.php?it_id='.$code.'&vc='.$encryption;

$prevPage = $_SERVER['HTTP_REFERER'];
$location = $prevPage.'#registrationbox';


//팝업차단하라고 안내해줘야함 
echo "<script>window.open('".$aa."')</script>";

//로케이션 잠시 꺼두려면 주석
echo "<script>
document.location.href='".$location."';
</script>";

?>
