

<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

// $Table_Name    = $wpdb->prefix.'wp_posts';
// $sql_query     = $wpdb->prepare("SELECT * FROM $Table_Name");
// $result        = $wpdb->query( $sql_query ); 
// var_dump($result);

global $wpdb;

$_POST;

//ct_id값 찾아서 
$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_purchase_status where ID = ".$_POST['ct_id']));
$sql = "INSERT INTO wp_purchase_status (ID, user_id, it_id, it_name, status, price, qty, status_time, mall_code) VALUES ('".$_POST['ct_id']."','".$_POST['mb_id']."',".$_POST['it_id'].",'".$_POST['it_name']."','".$_POST['ct_status']."',".$_POST['ct_price'].",".$_POST['ct_qty'].",'".$_POST['ct_status_time']."',1000);";
$sql2 = "UPDATE wp_purchase_status set status='".$_POST['ct_status']."', qty=".$_POST['ct_qty']." where ID =".$_POST['ct_id'];
if($results){ //있으면 업데이트
    $results1 = $wpdb->get_results($wpdb->prepare($sql2));
}else { //없으면 인서트 
    $results2 = $wpdb->get_results($wpdb->prepare($sql));
}


?>