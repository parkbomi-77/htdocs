<?php
global $wpdb;
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );


// 광고활성화 되어있는 제품 리스트 불러오기
$code = $_POST['code'];

// if($code === "1029"){ // 벳스쿨 쇼핑몰코드일 경우 
//     // 벳스쿨에서 판매하는 상품 목록 가져오기
//     // 2 -> simple 단순상품
//         $vetproductlist = $wpdb->get_results($wpdb->prepare("
//                 SELECT p.ID, p.post_title
//                 FROM wp_posts as p
//                 join wp_term_relationships as r
//                 on p.ID = r.object_id
//                 join wp_terms as t
//                 on r.term_taxonomy_id = t.term_id
//                 where t.term_id = 2 and p.post_status = 'publish';
//         "));
//         $vetproductlistnum = count($vetproductlist);
//         $option = '<option value = "" selected>제품 선택</option>';
//         for($i=0; $i<$vetproductlistnum; $i++){
//                 $add_option = '<option value = "'.$vetproductlist[$i]->ID.'">'.$vetproductlist[$i]->post_title.'</option>';
//                 $option = $option.$add_option;
//         }
//         header('Content-type: application/json');
//         echo json_encode($option);




// } else { // 타 쇼핑몰 코드일 경우 
        $product = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where adv_state = 1 and mall_code =".$code));
        $productnum = count($product);
        // 제품 선택 리스트 생성. 옵션태그. 
        $option = '<option value = "" selected>제품 선택</option>';
        for($i=0; $i<$productnum; $i++){
                $add_option = '<option value = "'.$product[$i]->ID.'">'.$product[$i]->product_name.'</option>';
                $option = $option.$add_option;
        }
        header('Content-type: application/json');
        echo json_encode($option);
// }



?>