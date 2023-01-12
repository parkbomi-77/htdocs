<?php
global $wpdb;
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );


// 쇼핑몰코드, 중분류 코드 
$mall_code = $_POST['mall_code'];
$ca_code = $_POST['ca_code'];


if($ca_code){
        // 중분류 선택했을시, 광고활성화 되어있는 제품 리스트 넘기기
        $sql = "SELECT * from wp_product_list where adv_state = 1 and mall_code =".$mall_code." and ca_code=".$ca_code;
        $product = $wpdb->get_results($wpdb->prepare($sql));
        $productnum = count($product);
        // 제품 선택 리스트 생성. 옵션태그. 
        $option = '<option value = "" selected>제품 선택</option>';
        for($i=0; $i<$productnum; $i++){
                $add_option = '<option value = "'.$product[$i]->ID.'">'.$product[$i]->product_name.'</option>';
                $option = $option.$add_option;
        }
        header('Content-type: application/json');
        echo json_encode($option);
}else { // 쇼핑몰 선택했을시 해당하는 중분류만 넘기기 
        $sql = "SELECT distinct c.*
                from wp_product_list as l
                join wp_product_category as c
                on l.ca_code = c.ca_code
                where adv_state = 1 and mall_code = ".$mall_code."
                order by c.ca_code";
        $category = $wpdb->get_results($wpdb->prepare($sql));
        $option = '<option value = "" selected>중분류 선택</option>';
        for($i=0; $i<count($category); $i++) {
                $add_option = '<option value = "'.$category[$i]->ca_code.'">'.$category[$i]->category.'</option>';
                $option = $option.$add_option;
        }
        header('Content-type: application/json');
        echo json_encode($option);
}


?>