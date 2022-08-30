<?php
$deletecheck = $_POST['deletecheck'];
$num = count($deletecheck);

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

for($i=0; $i<$num; $i++){
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where ID={$deletecheck[$i]}"));
    $results2 = $results[0] -> product_code;
    $postdata = http_build_query(
        array(
            'delete_code' => $results2
        )
    );
    $opts = array('http' =>
        array(
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context = stream_context_create($opts);
    file_get_contents('http://localhost:8888/practice/gnuboard/product_list.php', false, $context);


    $playtimerow = $wpdb->get_results($wpdb->prepare("SELECT * from wp_play_time where product_list_id ={$deletecheck[$i]}"));

    for($i=0; $i<count($playtimerow); $i++){
        // 영상 재생시간에 맞는 제품 노출 리스트 DB에서 삭제 
        $wpdb->delete('wp_play_time', 
        array(
            'ID' => $playtimerow[$i]->ID
        ));
    }
    
    //유저 장바구니 리스트에서도 삭제하기
    $cartrow = $wpdb->get_results($wpdb->prepare("SELECT * from wp_wish_list where item_id ={$deletecheck[$i]}"));
    for($i=0; $i<count($cartrow); $i++){
        // 영상 재생시간에 맞는 제품 노출 리스트 DB에서 삭제 
        $wpdb->delete('wp_wish_list', 
        array(
            'ID' => $cartrow[$i]->ID
        ));
    }

    // 벳스쿨 광고상품 DB에서 광고여부 0으로 변경 
    $wpdb->update( 
        'wp_product_list', 
        array(
            'adv_state' => 0,
        ), 
        array( 'ID' => $deletecheck[$i]
        ));
}

$prevPage = $_SERVER['HTTP_REFERER'];
$location ='location:'.$prevPage.'#registrationbox';
header($location);

?>