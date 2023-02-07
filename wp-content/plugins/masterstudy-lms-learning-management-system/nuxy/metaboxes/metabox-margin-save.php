<?php 
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
global $wpdb;

$code = $_POST['code'];
$margin = $_POST['margin'];

$startyear = $_POST['startyear'];
$startmonth = $_POST['startmonth'];

$endyear = $_POST['endyear'];
$endmonth = $_POST['endmonth'];

$nowdate = date("Y-m");

// 시작날짜와 끝날짜 간격 구하기
// endyear이 startyear보다 크면 에러 , 같거나 커야함 
if($endyear === $startyear){ // 시작년도 끝년도 동일
    if($startmonth === $endmonth){ // 시작 달, 끝 달 동일할 경우
        $sql1 = "SELECT * FROM vetschool.wp_shoppingmall_margin 
                where code = {$code} 
                and date_setting like '{$startyear}-{$startmonth}%'";
        $result = $wpdb->get_results($wpdb->prepare($sql1));
        if($result){ // 있으면 업데이트
            $sql2 = "UPDATE wp_shoppingmall_margin SET margin = {$margin} WHERE (id = {$result[0]->id})";
            $wpdb->get_results($wpdb->prepare($sql2));

        }else { // 없으면 인서트 
            $sql3 = "INSERT INTO wp_shoppingmall_margin (code, margin, date_setting) 
                    VALUES ({$code},{$margin}, '{$startyear}-{$startmonth}-01')";
            $wpdb->get_results($wpdb->prepare($sql3));

        }
    }else { // 끝 달이 더 큰 경우 
        $interval = $endmonth - $startmonth;
        for($i=0; $i<=$interval; $i++) {
            $month = $startmonth+$i;
            if($month < 10){
                $month = "0".strval($month);
            }
            $sql1 = "SELECT * FROM vetschool.wp_shoppingmall_margin 
                    where code = {$code} 
                    and date_setting like '{$startyear}-{$month}%'";
            $result = $wpdb->get_results($wpdb->prepare($sql1));
            if($result){ // 있으면 업데이트
                $sql2 = "UPDATE wp_shoppingmall_margin SET margin = {$margin} WHERE (id = {$result[0]->id})";
                $wpdb->get_results($wpdb->prepare($sql2));
            }else { // 없으면 인서트 
                $sql3 = "INSERT INTO wp_shoppingmall_margin (code, margin, date_setting) 
                        VALUES ({$code},{$margin}, '{$startyear}-{$month}-01')";
                $wpdb->get_results($wpdb->prepare($sql3));
            }
        }
    }
} else { // 끝 년도가 큰 경우 
    $intervalyear = $endyear - $startyear;
        for($i=0; $i<=$intervalyear; $i++){
            if($i === 0){ // 첫 년도
                $year = $startyear + $i;
                $intervalmonth = 12 - $startmonth;
                for($j=0; $j<=$intervalmonth; $j++) {
                    $month = $startmonth+$j;
                    if($month < 10){
                        $month = "0".strval($month);
                    }


                    $sql1 = "SELECT * FROM vetschool.wp_shoppingmall_margin 
                            where code = {$code} 
                            and date_setting like '{$year}-{$month}%'";
                    $result = $wpdb->get_results($wpdb->prepare($sql1));
                    if($result){ // 있으면 업데이트
                        $sql2 = "UPDATE wp_shoppingmall_margin SET margin = {$margin} WHERE (id = {$result[0]->id})";
                        $wpdb->get_results($wpdb->prepare($sql2));
                    }else { // 없으면 인서트 
                        $sql3 = "INSERT INTO wp_shoppingmall_margin (code, margin, date_setting) 
                                VALUES ({$code},{$margin}, '{$year}-{$month}-01')";
                        $wpdb->get_results($wpdb->prepare($sql3));
                    }
                }
            }else if($i === $intervalyear) { // 마지막 년도
                $year = $startyear + $i;
                for($j=1; $j<=$endmonth; $j++) {
                    $month = $j;
                    if($month < 10){
                        $month = "0".strval($month);
                    }
                    $sql1 = "SELECT * FROM vetschool.wp_shoppingmall_margin 
                            where code = {$code} 
                            and date_setting like '{$year}-{$month}%'";
                    $result = $wpdb->get_results($wpdb->prepare($sql1));
                    if($result){ // 있으면 업데이트
                        $sql2 = "UPDATE wp_shoppingmall_margin SET margin = {$margin} WHERE (id = {$result[0]->id})";
                        $wpdb->get_results($wpdb->prepare($sql2));
                    }else { // 없으면 인서트 
                        $sql3 = "INSERT INTO wp_shoppingmall_margin (code, margin, date_setting) 
                                VALUES ({$code},{$margin}, '{$year}-{$month}-01')";
                        $wpdb->get_results($wpdb->prepare($sql3));
                    }
                }
            } else { // 중간 년도 
                $year = $startyear + $i;
                for($j=1; $j<=12; $j++) {
                    $month = $j;
                    if($month < 10){
                        $month = "0".strval($month);
                    }
                    $sql1 = "SELECT * FROM vetschool.wp_shoppingmall_margin 
                            where code = {$code} 
                            and date_setting like '{$year}-{$month}%'";
                    $result = $wpdb->get_results($wpdb->prepare($sql1));
                    if($result){ // 있으면 업데이트
                        $sql2 = "UPDATE wp_shoppingmall_margin SET margin = {$margin} WHERE (id = {$result[0]->id})";
                        $wpdb->get_results($wpdb->prepare($sql2));
                    }else { // 없으면 인서트 
                        $sql3 = "INSERT INTO wp_shoppingmall_margin (code, margin, date_setting) 
                                VALUES ({$code},{$margin}, '{$year}-{$month}-01')";
                        $wpdb->get_results($wpdb->prepare($sql3));
                    }
                }
            }
        }
}

// 해당 쇼핑몰의 광고활성화되어있는 상품
$sql4 = "SELECT * FROM wp_product_list where mall_code = {$code} and adv_state =1";
$activate = $wpdb->get_results($wpdb->prepare($sql4)); // 갯수대로 가져옴 .. 
if($activate && ($code !== 1029 )){ // 광고활성화된 상품이 있고, 벳스쿨이 아닐 경우 
    for($i=0; $i<count($activate); $i++){
        // 그누보드 쇼핑몰 shop_item DB에 광고여부 1로 업데이트 시켜주는 로직  http://localhost:8888/practice/gnuboard/product_list 
        $mall = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code =".$code));
        $start_date = $mall[0]->start_date;
        $end_date = $mall[0]->end_date;
        $link = $mall[0]->link;
        $link = $link.'/product_list.php';
    
        // 마진율 오브젝트에 담아서 보내기
        $sql = "SELECT * FROM vetschool.wp_shoppingmall as a
                left join wp_shoppingmall_margin as b
                on a.code = b.code
                where a.code = {$code}
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
        }
    
        $postdata = http_build_query(
            array(
                'product_code' => $activate[$i]->product_code,
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
}

// 해당월에 삭제한 상품
$sql5 = "SELECT * FROM wp_product_list where (mall_code = {$code} and del = 1 and state_date like '{$nowdate}%')";
$result = $wpdb->get_results($wpdb->prepare($sql5)); 
if($result && ($code !== 1029 )){ // 광고활성화된 상품이 있고, 벳스쿨이 아닐 경우 
    for($i=0; $i<count($result); $i++){
        $mall = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code =".$code));
        $link = $mall[0]->link;
        $link = $link.'/product_list.php';
    
        // 마진율 오브젝트에 담아서 보내기
        $sql = "SELECT * FROM vetschool.wp_shoppingmall as a
                left join wp_shoppingmall_margin as b
                on a.code = b.code
                where a.code = {$code}
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
        }
    
        $postdata = http_build_query(
            array(
                'del_product_code' => $result[$i]->product_code,
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

}






$prevPage = $_SERVER['HTTP_REFERER'];
// 변수에 이전페이지 정보를 저장
$location ='location:'.$prevPage.'#margin_setting';
header($location);
?>