<?php 
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
global $wpdb;

$code = $_POST['code'];
$margin = $_POST['margin'];

$startyear = $_POST['startyear'];
$startmonth = $_POST['startmonth'];
$startdate = $year.'-'.$month.'-01';

$endyear = $_POST['endyear'];
$endmonth = $_POST['endmonth'];
$enddate = $year.'-'.$month.'-01';


// 날짜, 쇼핑몰코드, 마진율 -> wp_shoppingmall_margin 테이블에 인서트 
// for($i=0; $i<count($code); $i++){

//     // 년월에 해당하는 데이터가 있으면 업데이트
//     $sql1 = "select * from wp_shoppingmall_margin 
//     where date_setting like '".$date."'
//     and code = ".$code[$i];
//     $check = $wpdb->get_results($wpdb->prepare($sql1));
//     $checkmargin = $check[0]->margin;
//     if(!$checkmargin){ // 등록되어져있는 값이 없을 경우 
//         $sql2 = "INSERT INTO wp_shoppingmall_margin (code, margin, date_setting) 
//         VALUES ('{$code[$i]}', '{$margin[$i]}', '{$date}' )";
//         $wpdb->get_results($wpdb->prepare($sql2));
//     }else if($checkmargin === $margin[$i]){ // 입력으로 들어온 마진이 기존 마진과 동일한 경우 
//         continue;
//     }else { // 기존 마진과 다를 경우 
//         $sql3 = "UPDATE wp_shoppingmall_margin SET margin = ".$margin[$i]." WHERE (id = ".$check[0]->id.")";
//         $wpdb->get_results($wpdb->prepare($sql3));
//     }

// }

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










$prevPage = $_SERVER['HTTP_REFERER'];
// 변수에 이전페이지 정보를 저장
$location ='location:'.$prevPage.'#margin_setting';
header($location);
?>