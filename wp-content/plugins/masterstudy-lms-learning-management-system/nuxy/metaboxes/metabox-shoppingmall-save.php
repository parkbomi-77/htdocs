<?php

define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
 global $wpdb;

 $thisyear = date('Y');
 $thismonth = date('m');

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
    // 쇼핑몰리스트 활성화0 으로
    $wpdb->get_results($wpdb->prepare("UPDATE wp_shoppingmall set state=0, del=1 where (code ='".$delcode."')"));

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
    // 마감날짜가 안지난 경우 쇼핑몰 광고활성화 state 1로 변경하기 
    if( ($thisyear < $endyear) || (($thisyear === $endyear) && ($thismonth <= $endmonth )) ){
        $sql1 = "UPDATE wp_shoppingmall 
        set name= '".$newname."', link='".$newlink."', link2='".$newlink2."', 
        state=1, start_date='".$start_date."', end_date='".$end_date."' where code ='".$newcode."' ";
        $wpdb->get_results($wpdb->prepare($sql1));
        
        // 쇼핑몰코드로 쇼핑몰링크 얻어내기
        $mallcode = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code =".$newcode));
        $link = $mallcode[0]->link2;

        // 쇼핑몰 상품들도 활성화시키기 adv_state 0이면서 del 0인것들 adv_state 1로 변경 
        $sql3 = "SELECT * FROM wp_product_list where mall_code = {$newcode} and del = 0";
        $results = $wpdb->get_results($wpdb->prepare($sql3)); // 쇼핑몰 상품들 리스트
        for($i=0; $i<count($results); $i++){ // 36개 .. 
            $sql4 = "UPDATE wp_product_list SET adv_state = 1 WHERE ID =".$results[$i]->ID;
            $wpdb->get_results($wpdb->prepare($sql4));
            
            // 배포쇼핑몰에 다시 광고활성화 api 보내기 
            // 마진율 오브젝트에 담아서 보내기
            $sql = "SELECT * FROM vetschool.wp_shoppingmall as a
                    left join wp_shoppingmall_margin as b
                    on a.code = b.code
                    where a.code = {$results[$i]->mall_code}
                    order by date_setting";
            $margin_date = $wpdb->get_results($wpdb->prepare($sql)); 

            // for문으로 돌면서 
            // 키 : date_setting의 년도,월,일 '00000000'
            // 값 : 마진율
            $obj = (object)[];
            for($j=0; $j<count($margin_date); $j++){

                $date = $margin_date[$j]->date_setting;
                $datekey = str_replace('-', "", $date);

                $margin = $margin_date[$j]->margin;
                $obj->$datekey = $margin;
                $code = $results[$i]->product_code;

            }

            $postdata = http_build_query(
                array(
                    'product_code' => $code,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'margin' => $obj,
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

    }else {
    // 마감날짜가 지난 경우 광고 비활성화
        $wpdb->get_results($wpdb->prepare("UPDATE wp_shoppingmall 
        set name= '".$newname."', link='".$newlink."', link2='".$newlink2."', 
        state=0, start_date='".$start_date."', end_date='".$end_date."' where code ='".$newcode."' "));

    // 해당 쇼핑몰 광고활성화되어있는 제품들 -> 비활성화 
        $sql5 ="SELECT * FROM wp_product_list where mall_code = {$newcode} and adv_state = 1";
        $result = $wpdb->get_results($wpdb->prepare($sql5));
        for($i=0; $i<count($result); $i++){
            $sql6 = "UPDATE wp_product_list SET adv_state = 0 WHERE ID =".$result[$i]->ID;
            $wpdb->get_results($wpdb->prepare($sql6));

            // 배포쇼핑몰로 광고 비활성화 api 보내기
            $product_code = $result[$i]->product_code;
    
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
            file_get_contents($newlink2, false, $context);
        }
    
    

    }
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