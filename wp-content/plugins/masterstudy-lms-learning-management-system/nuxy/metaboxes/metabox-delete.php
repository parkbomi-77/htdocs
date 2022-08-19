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


    // 벳스쿨 광고상품 DB에서 삭제 
    $wpdb->delete('wp_product_list', 
    array(
        'ID' => $deletecheck[$i]
    ));
}

$prevPage = $_SERVER['HTTP_REFERER'];
$location ='location:'.$prevPage.'#registrationbox';
header($location);

?>