<?php

    define( 'SHORTINIT', true );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

    global $wpdb;
    $code = $_POST['code']; // 쇼핑몰 코드
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_purchase_status where mall_code =".$code." and status in ('완료', '배송', '주문', '취소');" ));

    $aaa = count($results);
    header("Content-Type: application/json");

    $dataarr = array();
    for($i=0; $i<$aaa; $i++){
        array_push($dataarr, $results[$i]);
        }

    echo json_encode($dataarr, JSON_UNESCAPED_UNICODE);
?>