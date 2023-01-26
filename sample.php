

<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

global $wpdb;

$_POST['host'];

//ct_id값 찾아서 
$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_purchase_status where ID = ".$_POST['ct_id']));

//그누보드에서 보내온 제품ID를 광고제품 테이블에서 찾아서 mall code 얻어서 유입현황테이블에 넣기
$sql3 = "select p.*, s.link 
from wp_product_list as p
join wp_shoppingmall as s
on p.mall_code = s.code
where product_code ={$_POST['it_id']} 
and adv_state =1    
and s.link like '%{$_POST['host']}%' ";
$mallCodeResults = $wpdb->get_row($wpdb->prepare($sql3));
$productMallCode = $mallCodeResults->mall_code;

$sql = "INSERT INTO wp_purchase_status (ID, user_id, it_id, it_name, status, price, qty, status_time, mall_code) VALUES ('".$_POST['ct_id']."','".$_POST['mb_id']."',".$_POST['it_id'].",'".$_POST['it_name']."','".$_POST['ct_status']."',".$_POST['ct_price'].",".$_POST['ct_qty'].",'".$_POST['ct_status_time']."',{$productMallCode});";
$sql2 = "UPDATE wp_purchase_status set status='".$_POST['ct_status']."', qty=".$_POST['ct_qty']." where ID =".$_POST['ct_id'];
if($results){ //있으면 업데이트
    $results1 = $wpdb->query($wpdb->prepare($sql2));
}else { //없으면 인서트 
    // 등록된 제품만 insert 하도록 ! 등록한 제품이 아니면 패스
    $itItem = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_product_list where product_code =".$_POST['it_id'] ));
    if($itItem){
        $results2 = $wpdb->get_results($wpdb->prepare($sql));
    }
}


?>