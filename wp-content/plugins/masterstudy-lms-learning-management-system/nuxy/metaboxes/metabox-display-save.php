<?php

    define( 'SHORTINIT', true );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
    
    $registrationID = $_POST['registrationID'];
    $registrationNum = $_POST['registrationNum'];
    $registrationmall = $_POST['shoppingMallList']; // 쇼핑몰코드
    $registrationname = $_POST['registrationname'];
    $registrationlink = $_POST['registrationlink'];

    $deletebuttone = $_POST['deletecheck'];


    global $wpdb;

    // 벳스쿨 판매제품 리스트 wp_product_list
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list"));
    $totalNum = count($results); // 기존에 등록되어있던 전체 제품 수
    // $id = count($registrationID); // 그대로인 항목
    $num = count($registrationname); // 입력란을 통해 들어온 항목
    
    if($results){ 

        for($i=0; $i<$num; $i++){
            if($registrationID[$i] === "0"){ // 새로들어온 거면? 인서트 
                // 쇼핑몰코드로 쇼핑몰링크 얻어내기
                $mallcode = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code =".$registrationmall[$i]));
                $link = $mallcode[0]->link2;

                $wpdb->insert('wp_product_list', 
                array(
                    'product_id' => $i+1,
                    'product_name' => $registrationname[$i],
                    'product_code' => $registrationlink[$i],
                    'mall_code' => $registrationmall[$i],
                    'adv_state' => 1,
                ));

                // 그누보드 쇼핑몰 shop_item DB에 광고여부 1로 업데이트 시켜주는 로직  http://localhost:8888/practice/gnuboard/product_list 
                $postdata = http_build_query(
                    array(
                        'product_code' => $registrationlink[$i]
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


            } else {
                // 쇼핑몰코드로 쇼핑몰링크 얻어내기
                $mallcode = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code =".$registrationmall[$i]));
                $link = $mallcode[0]->link2;

                $wpdb->update( 
                'wp_product_list', 
                array('product_id' => $i+1,
                    'product_name' => $registrationname[$i],
                    'product_code' => $registrationlink[$i],
                    'mall_code' => $registrationmall[$i],
                ), 
                array( 'ID' => $registrationID[$i]
                ));

                $postdata = http_build_query(
                    array(
                        'product_code' => $registrationlink[$i]
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
        
    // 없으면? insert로 새로 생성해주기 
    } else {  
        $num = count($registrationNum);
        // 배열값으로 들어온 데이터들 디비에 넣기 
        for($i=0; $i<$num; $i++){
                // 쇼핑몰코드로 쇼핑몰링크 얻어내기
                $mallcode = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code =".$registrationmall[$i]));
                $link = $mallcode[0]->link2;

            $wpdb->insert('wp_product_list', 
                    array(
                        'product_id' => $i+1,
                        'product_name' => $registrationname[$i],
                        'product_code' => $registrationlink[$i],
                        'mall_code' => $registrationmall[$i],
                        'adv_state' => 1,
                    ));
            $postdata = http_build_query(
                array(
                    'product_code' => $registrationlink[$i]
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
    $location ='location:'.$prevPage.'#registrationbox';
    header($location);
?>