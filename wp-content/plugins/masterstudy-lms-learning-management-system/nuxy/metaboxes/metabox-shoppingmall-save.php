<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;


$mall = $_POST['name'];
$link = $_POST['link'];
$link2 = $_POST['link2'];
$startyear = $_POST['startyear'];
$startmonth = $_POST['startmonth'];
$endyear = $_POST['endyear'];
$endmonth = $_POST['endmonth'];
$start_date = $startyear.'-'.$startmonth.'-01';
$end_date = $endyear.'-'.$endmonth.'-01';



$newcode = $_POST['newcode'];
$newname = $_POST['newname'];
$newlink = $_POST['newlink'];
$newlink2 = $_POST['newlink2'];


$delcode = $_POST['code'];
$editcode = $_POST['editcode'];




if($delcode){ // 삭제 요청
    // 해당 쇼핑몰에 등록된 광고중인 상품이 있는지 확인
    $sql = "SELECT * from wp_product_list where adv_state = 1 and mall_code =".$delcode;
    $productcheck = $wpdb->get_results($wpdb->prepare($sql));
    if($productcheck){ // 광고중인 상품있으면 삭제 막기
        echo "광고중인 상품이 있습니다. 상품광고 비활성화 후 다시 시도해주세요.";
        
    } else {
        // 쇼핑몰리스트 활성화0 으로
        $wpdb->get_results($wpdb->prepare("UPDATE wp_shoppingmall set state=0 where (code ='".$delcode."')"));
    
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_product_list where (mall_code ='".$delcode."')"));
        for($i=0; $i<count($result); $i++){
            // 광고제품 리스트 광고활성화 0으로
            $wpdb->get_results($wpdb->prepare("UPDATE wp_product_list set adv_state=0 where (ID ='".$result[$i] -> ID."')"));
            // 영상에 등록되어져있는 제품들 삭제
            $result2 = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_play_time where product_list_id =".$result[$i] -> ID));
            for($j=0; $j<count($result2); $j++){
                $wpdb->get_results($wpdb->prepare("DELETE FROM wp_play_time where (ID ='".$result2[$j]->ID."')"));
            }
            // 장바구니에 있는제품들 삭제
            $result3 = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_wish_list where item_id =".$result[$i]->ID));
            for($k=0; $k<count($result3); $k++){
                $wpdb->get_results($wpdb->prepare("DELETE FROM wp_wish_list where (ID ='".$result3[$k]->ID."')"));
            }
        }
    }
}else if($editcode){ // 수정 사항 보여주기 요청
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code ='".$editcode."' "));
    $dataArray[0] = '삽입 성공';
    echo json_encode($dataArray);
}else if($newcode){ // 수정사항 저장하기 
    $wpdb->get_results($wpdb->prepare("UPDATE wp_shoppingmall 
        set name= '".$newname."', link='".$newlink."', link2='".$newlink2."', start_date='".$start_date."', end_date='".$end_date."' where code ='".$newcode."' "));
}else { // 새 쇼핑몰 등록
    $sql2="INSERT INTO wp_shoppingmall (name,link, link2, state, start_date, end_date) 
    VALUES ('{$mall}','{$link}','{$link2}',1, '{$start_date}', '{$end_date}')";
    $wpdb->get_results($wpdb->prepare($sql2));
}


$prevPage = $_SERVER['HTTP_REFERER'];
// 변수에 이전페이지 정보를 저장
$location ='location:'.$prevPage.'#shopping_mall';
header($location);

?>