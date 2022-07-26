<?php 
    // 그누보드 사이트 

    // g5_shop_cart 장바구니 테이블 가져오기 
    include_once('./_common.php');
    $sql = "select * from g5_shop_cart";
    $result = sql_query($sql);

    header("Content-Type: application/json");

    $dataarr = array();
    for($a = 0; $row = sql_fetch_array($result); $a++){
        // row변수안에 배열로 값이들어온다.
        $json = array(
            'userID' => $row['mb_id'], 
            'product_name' => $row['it_name']
        );

        //dataarr배열에 담기
        array_push($dataarr, $json);

        }

    echo json_encode($dataarr, JSON_UNESCAPED_UNICODE);

?>