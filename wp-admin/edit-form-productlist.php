<?php
global $wpdb;
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );


// 광고활성화 되어있는 제품 리스트 불러오기
$code = $_POST['code'];
$product = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where adv_state = 1 and mall_code =".$code));
$productnum = count($product);
// 제품 선택 리스트 생성. 옵션태그. 
$option = '<option value = "" selected>제품 선택</option>';
for($i=0; $i<$productnum; $i++){
        $add_option = '<option value = "'.$product[$i]->ID.'">'.$product[$i]->product_name.'</option>';
        $option = $option.$add_option;
}
header('Content-type: application/json');
echo json_encode($option);



?>