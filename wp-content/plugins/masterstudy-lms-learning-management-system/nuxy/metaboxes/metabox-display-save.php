<?php

    define( 'SHORTINIT', true );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
    global $wpdb;

    $registrationmall = $_POST['shoppingMallList']; // 쇼핑몰 코드
    $registrationname = $_POST['registrationname']; // 상품명
    $registrationlink = $_POST['registrationlink']; // 상품 코드


    // 개별수정사항 저장
    $saveid = $_POST['saveid'];
    $savename = $_POST['savename'];

    if($saveid){
        // 디비에서 활성화되어있는 것중에 이름중복있는지 체크
        $overlap = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where product_name ='".$savename."' and adv_state = 1"));
        
       // 중복 있으면 팅겨내기
        if($overlap){
            echo "중복";
            
        }else { // 중복없으면 업데이트
            $wpdb->get_results($wpdb->prepare("UPDATE wp_product_list SET product_name = '".$savename."' WHERE ID =".$saveid));
            echo "등록";
        }
    // 신규등록 저장 
    // 쇼핑몰코드, 상품이름, 상품아이디 
    } else if($registrationmall && $registrationname && $registrationlink) {
        global $wpdb;
        // 벳스쿨 상품 테이블디비에 저장하는 sql
        $sql = "INSERT INTO wp_product_list (`product_name`, `product_code`, `mall_code`, `adv_state`) 
            VALUES ('{$registrationname}', '{$registrationlink}', '{$registrationmall}',1)";
        $wpdb->get_results($wpdb->prepare($sql));
    
        if($registrationmall !== 1029){ // 벳스쿨 쇼핑몰 아닌 타 쇼피몰일 경우에만 광고 업데이트 api 보내기 
            // 그누보드 쇼핑몰 shop_item DB에 광고여부 1로 업데이트 시켜주는 로직  http://localhost:8888/practice/gnuboard/product_list 
            $mall = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code =".$registrationmall));
            $start_date = $mall[0]->start_date;
            $end_date = $mall[0]->end_date;
            $link = $mall[0]->link2;

            // 마진율 오브젝트에 담아서 보내기
            $sql = "SELECT * FROM vetschool.wp_shoppingmall as a
                    left join wp_shoppingmall_margin as b
                    on a.code = b.code
                    where a.code = {$registrationmall}
                    order by date_setting";
            $margin_date = $wpdb->get_results($wpdb->prepare($sql)); 

            // for문으로 돌면서 
            // 키 : date_setting의 년도,월 '000000'
            // 값 : 마진율
            $obj = (object)[];
            for($i=0; $i<count($margin_date); $i++){

                $date = $margin_date[$i]->date_setting;
                $datekey = str_replace('-', "", $date);

                $margin = $margin_date[$i]->margin;
                $obj->$datekey = $margin; // 여기서 문제 ~~ 

            }


            $postdata = http_build_query(
                array(
                    'product_code' => $registrationlink,
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

        $prevPage = $_SERVER['HTTP_REFERER'];
        // 변수에 이전페이지 정보를 저장
        $location ='location:'.$prevPage.'#registrationbox';
        header($location);
    }


?>