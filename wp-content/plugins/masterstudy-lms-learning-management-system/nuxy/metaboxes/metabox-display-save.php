<?php

    define( 'SHORTINIT', true );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
    
    $registrationID = $_POST['registrationID'];
    $registrationNum = $_POST['registrationNum'];
    $registrationname = $_POST['registrationname'];
    $registrationlink = $_POST['registrationlink'];

    $deletebuttone = $_POST['deletecheck'];


    global $wpdb;
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list"));
    $totalNum = count($results); // 기존에 등록되어있던 전체 제품 수
    // $id = count($registrationID); // 그대로인 항목
    $num = count($registrationname); // 입력란을 통해 들어온 항목
    
    if($results){ 

        for($i=0; $i<$num; $i++){
            if($registrationID[$i] === "0"){ // 새로들어온 거면? 인서트 
                $wpdb->insert('wp_product_list', 
                array(
                    'product_id' => $i+1,
                    'product_name' => $registrationname[$i],
                    'product_code' => $registrationlink[$i],
                ));
            } else {
                $wpdb->update( 
                'wp_product_list', 
                array('product_id' => $i+1,
                    'product_name' => $registrationname[$i],
                    'product_code' => $registrationlink[$i],
                ), 
                array( 'ID' => $registrationID[$i]
                ));
            }
        }
        
    // 없으면? insert로 새로 생성해주기 
    } else {  
        $num = count($registrationNum);
        // 배열값으로 들어온 데이터들 디비에 넣기 
        for($i=0; $i<$num; $i++){
            $wpdb->insert('wp_product_list', 
                    array(
                        'product_id' => $i+1,
                        'product_name' => $registrationname[$i],
                        'product_code' => $registrationlink[$i],
                    ));
        }
        
    }

    $prevPage = $_SERVER['HTTP_REFERER'];
    // 변수에 이전페이지 정보를 저장
    $location ='location:'.$prevPage.'#registrationbox';
    header($location);
?>