<?php 
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
global $wpdb, $post;

$mallcode = $_POST['m_code'];
$cate_code = $_POST['c_code'];
$user = $_POST['user'];


$sql = "SELECT t.*, m.code, m.name, c.*
        FROM wp_wish_list as t
        join wp_product_list as l
        on t.item_id = l.ID
        join wp_shoppingmall as m
        on l.mall_code = m.code
        join wp_product_category as c
        on l.ca_code = c.ca_code
        where t.user_id = {$user}
        and l.adv_state = 1";

$updatesql = $sql;
if($mallcode){
    $updatesql = $updatesql." and m.code = ".$mallcode;
}
if($cate_code){
    $updatesql = $updatesql." and c.ca_code = ".$cate_code;
}
$results = $wpdb->get_results($wpdb->prepare($updatesql));
$num = count($results);

$tr = "";
for($i = 0; $i<$num; $i++){
    $course_name = $wpdb->get_var($wpdb->prepare("SELECT post_title FROM wp_posts where ID =".$results[$i]->course_id));
    $lessons_name = $wpdb->get_var($wpdb->prepare("SELECT post_title FROM wp_posts where ID =".$results[$i]->lessons_id));

    $tr = $tr.
    "
    <form name='wishlist' method='POST' onsubmit='return true'>
        <tr>
            <input type='hidden' name='user_id' value='".$results[$i]->user_id ."'>
            <input type='hidden' name='item_id' value='".$results[$i]->item_id ."'>
            <td>".($i+1)."</td>
            <td class='wish_table_item'>".$results[$i]->name."</td>
            <td class='wish_table_item'>".$results[$i]->category."</td>
            <td class='wish_table_item'>{$results[$i]->product_name} <br> <span>{$course_name} - {$lessons_name}</span></td>
            <td><button class='wish_table_trash' type='submit' formaction='../page-delete.php'><i class='fa-solid fa-trash'></i></button></td>
            <td><button class='wish_table_link' type='submit' formaction='../page-shop.php'><i class='fa-solid fa-shop'></i></button></td>
        </tr>
    </form>
    ";
}
header("Content-Type: text/html; charset=utf-8");

echo $tr;

?>