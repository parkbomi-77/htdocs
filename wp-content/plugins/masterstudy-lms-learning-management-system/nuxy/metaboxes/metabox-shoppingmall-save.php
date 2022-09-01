<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;


$mall = $_POST['name'];
$link = $_POST['link'];
$link2 = $_POST['link2'];


$delcode = $_POST['code'];
$editcode = $_POST['editcode'];
$newcode = $_POST['newcode'];
$newname = $_POST['newname'];
$newlink = $_POST['newlink'];
$newlink2 = $_POST['newlink2'];




if($delcode){ // 삭제 요청
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


}else if($editcode){ // 수정 사항 보여주기 요청
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code ='".$editcode."' "));
    $dataArray[0] = '삽입 성공';
    echo json_encode($dataArray);
}else if($newcode){ // 수정사항 저장하기 
    $wpdb->get_results($wpdb->prepare("UPDATE wp_shoppingmall set name= '".$newname."', link='".$newlink."', link2='".$newlink2."' where code ='".$newcode."' "));
}else {
    // 광고의뢰 쇼핑몰 리스트 빈 값은 걸러내기
    function empty_ ($var) {
        if($var !== ""){
            return $var;
        }
    }
    $newmall = array_filter($mall, "empty_");
    
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall"));
    $num = count($results)-1;
    $nodenum = $results[$num]->code;
    
    for($i=0; $i<count($newmall); $i++){
        $ppp = $wpdb->get_results($wpdb->prepare("INSERT INTO wp_shoppingmall (name,link, link2, state) VALUES ('{$newmall[$i]}','{$link[$i]}','{$link2[$i]}',1);"));
    }
}


$prevPage = $_SERVER['HTTP_REFERER'];
// 변수에 이전페이지 정보를 저장
$location ='location:'.$prevPage.'#shopping_mall';
header($location);

?>