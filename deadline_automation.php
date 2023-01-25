<?php 

require_once( '/Applications/MAMP/htdocs/wp-load.php' );

$wpdb;
// 현재 날짜
$this_year = date("Y");
$this_month = date("m");
// $this_year = '2022';
// $this_month = '12';


$sql = "SELECT * FROM wp_shoppingmall where state = 1";
$results = $wpdb->get_results($wpdb->prepare($sql));

// 활성화되어져있는 쇼핑몰들 마감기한 확인
for($i=0; $i<count($results); $i++){
    $end_year = substr($results[$i]->end_date,0,4);
    $end_month = substr($results[$i]->end_date,5,2);
    //현재 년도가더 크거나
    // 년도가 같으면서 현재 달이 더 큰 경우 
    if( ($this_year > $end_year) || ($this_year === $end_year) && ($this_month > $end_month) ){
        // 쇼핑몰 광고 비활성화
        $sql2 = "UPDATE wp_shoppingmall SET state = 0 WHERE code =".$results[$i]->code;
        $wpdb->get_results($wpdb->prepare($sql2));

        // 쇼핑몰 상품들 하나하나 다 광고 비활성화 
        $sql3 = "SELECT * FROM wp_product_list where mall_code =".$results[$i]->code." and adv_state = 1";
        $product = $wpdb->get_results($wpdb->prepare($sql3));
        for($j=0; $j<count($product); $j++){
            $sql4 = "UPDATE wp_product_list SET adv_state = 0 WHERE code =".$product[$j]->code;
            $wpdb->get_results($wpdb->prepare($sql4));
            
            // 배포쇼핑몰로 광고 비활성화 api 보내기
            $link = $results[$i]->link;
            $link = $link.'/product_list.php';
            $product_code = $product[$j]->product_code;
    
            $postdata = http_build_query(
                array(
                    'delete_code' => $product_code,
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
            file_get_contents($link, false, $context);
        }
    } 
}



// 해당 쇼핑몰, 쇼핑몰에 해당하는 광고상품들 모두 비활성화, 배포 쇼핑몰에 api 보내기

?>