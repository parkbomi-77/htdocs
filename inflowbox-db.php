<?php
    define( 'SHORTINIT', true );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

    global $wpdb;

    $siteCode = $_POST['siteCode'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    $orderState = $_POST['orderState'];

    // 해당 쇼핑몰 마진율 불러오기 
    $sql ="SELECT s.*, m.margin, p.name
            FROM wp_purchase_status as s
            join wp_shoppingmall_margin as m
            on s.status_time like CONCAT('%', left(m.date_setting,7), '%')
            join wp_shoppingmall as p
            on s.mall_code = p.code";

    $condition1;
    $condition2;
    $condition3;

    if($siteCode) { // 쇼핑몰 코드 
        $condition1 = "mall_code = {$siteCode}";
    }
    if($year && $month) { // 둘다 있을 경우 
        $condition2 = "status_time like '{$year}-{$month}%'";
    }
    if($year && !$month) { // 년도만 들어온 경우 
        $condition2 = "status_time like '{$year}%'";
    }
    if($orderState) { // 주문상태 
        $condition3 = "status = '{$orderState}'";
    }
    if(!$orderState) {
        $condition3 = "status in ('완료', '배송', '주문', '취소')";
    }

    // 들어온 값에 따라 쿼리문 만들기 
    if($condition1) {
        $condition1 = ' where '.$condition1;
        if($condition2) {$condition2 = ' and '.$condition2;}
        if($condition3) {$condition3 = ' and '.$condition3;}
        $sql = $sql.$condition1.$condition2.$condition3;
    }else if($condition2) {
        $condition2 = ' where '.$condition2;
        if($condition3) {$condition3 = ' and '.$condition3;}
        $sql = $sql.$condition2.$condition3;
    }else if($condition3) {
        $condition3 = ' where '.$condition3;
        $sql = $sql.$condition3;
    }

    $results = $wpdb->get_results($wpdb->prepare($sql));


    $aaa = count($results);
    header("Content-Type: application/json");

    $dataarr = array();
    for($i=0; $i<$aaa; $i++){
        array_push($dataarr, $results[$i]);
        }

    echo json_encode($dataarr, JSON_UNESCAPED_UNICODE);
?>