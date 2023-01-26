<?php

    define( 'SHORTINIT', true );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

    global $wpdb;
    $code = $_POST['code']; // 쇼핑몰 코드
    // 해당 쇼핑몰 마진율 불러오기 
    $sql ="SELECT s.*, m.margin
            FROM vetschool.wp_purchase_status as s
            join wp_shoppingmall_margin as m
            on s.status_time like CONCAT('%', left(m.date_setting,7), '%')
            where mall_code ={$code}
            and status in ('완료','배송','주문','취소')";
    $results = $wpdb->get_results($wpdb->prepare($sql));


    $aaa = count($results);
    header("Content-Type: application/json");

    $dataarr = array();
    for($i=0; $i<$aaa; $i++){
        array_push($dataarr, $results[$i]);
        }

    echo json_encode($dataarr, JSON_UNESCAPED_UNICODE);
?>