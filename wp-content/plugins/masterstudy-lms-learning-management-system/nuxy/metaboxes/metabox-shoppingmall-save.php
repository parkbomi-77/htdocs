<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;


$mall = $_POST['name'];
$link = $_POST['link'];

$delcode = $_POST['code'];
$editcode = $_POST['editcode'];
$newcode = $_POST['newcode'];
$newname = $_POST['newname'];
$newlink = $_POST['newlink'];



if($delcode){ // 삭제 요청
    $wpdb->get_results($wpdb->prepare("DELETE FROM wp_shoppingmall where (code ='".$delcode."')"));
}else if($editcode){ // 수정 사항 보여주기 요청
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code ='".$editcode."' "));
    // echo $results['code'];
    // echo $results['name'];
    // echo $results['link'];
    $dataArray[0] = '삽입 성공';
    echo json_encode($dataArray);
}else if($newcode){ // 수정사항 저장하기 
    $zzx = "UPDATE wp_shoppingmall set name= '".$newname."', link='".$newlink."' where code ='".$newcode."' ";
    $wpdb->get_results($wpdb->prepare("UPDATE wp_shoppingmall set name= '".$newname."', link='".$newlink."' where code ='".$newcode."' "));
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
        $ppp = $wpdb->get_results($wpdb->prepare("INSERT INTO wp_shoppingmall (name,link) VALUES ('".$newmall[$i]."','".$link[$i]."');"));
    }
}


$prevPage = $_SERVER['HTTP_REFERER'];
// 변수에 이전페이지 정보를 저장
$location ='location:'.$prevPage.'#shopping_mall';
header($location);

?>