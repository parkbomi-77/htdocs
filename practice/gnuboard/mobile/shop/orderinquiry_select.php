<?php 
include_once('./_common.php');



$post_month = $_POST['month'];
$calendarFromDate = $_POST['calendarFromDate'];
$calendarToDate = $_POST['calendarToDate'];

if($post_month) { // 월별 버튼 클릭해서 들어온 경우 
    $month = str_replace('월','',$post_month);
    $year;
    
    // 달 두자리로 만들어주기
    if(mb_strlen($month, 'utf-8') === 1) {
        $month = '0'.$month;
    }
    
    //  현재 달보다 크면? 작년 년도로 .. 
    $nowmonth = date("m");
    
    if((int)$nowmonth < (int)$month){
        $year = date('Y')-1;
    }else {
        $year = date('Y');
    }
    $monthyear = $year.'-'.$month;
    $appointed = "and od_time like '{$monthyear}%'";

    
}else { // 달력날짜선택해서 들어온 경우 
    $appointed = "and od_time > '{$calendarFromDate}' and od_time < '{$calendarToDate}' ";
}




$ultext = '<ul>';
?>



    <?php
    $sql = " select *,
                (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
                from {$g5['g5_shop_order_table']}
                where mb_id = '{$member['mb_id']}' {$appointed}
                order by od_id desc";
    $result = sql_query($sql);

    //  상품 갯수 
    // $count = $result->num_rows;
    // $rows = $config['cf_mobile_page_rows'];
    // $total_page  = ceil($count / $rows); 

    for ($i=0; $row=sql_fetch_array($result); $i++)
    {
        // 주문상품
        $sql = " select it_name, ct_option
                    from {$g5['g5_shop_cart_table']}
                    where od_id = '{$row['od_id']}'
                    order by io_type, ct_id
                    limit 1 ";
        $ct = sql_fetch($sql);
        $ct_name = get_text($ct['it_name']).' '.get_text($ct['ct_option']);

        $sql = " select count(*) as cnt
                    from {$g5['g5_shop_cart_table']}
                    where od_id = '{$row['od_id']}' ";
        $ct2 = sql_fetch($sql);
        if($ct2['cnt'] > 1)
            $ct_name .= ' 외 '.($ct2['cnt'] - 1).'건';

        switch($row['od_status']) {
            case '주문':
                $od_status = '<span class="status_01">입금확인중</span>';
                break;
            case '입금':
                $od_status = '<span class="status_02">입금완료</span>';
                break;
            case '준비':
                $od_status = '<span class="status_03">상품준비중</span>';
                break;
            case '배송':
                $od_status = '<span class="status_04">상품배송</span>';
                break;
            case '완료':
                $od_status = '<span class="status_05">배송완료</span>';
                break;
            default:
                $od_status = '<span class="status_06">주문취소</span>';
                break;
        }

        $od_invoice = '';
        if($row['od_delivery_company'] && $row['od_invoice'])
            $od_invoice = '<span class="inv_inv"><i class="fa fa-truck" aria-hidden="true"></i> <strong>'.get_text($row['od_delivery_company']).'</strong> '.get_text($row['od_invoice']).'</span>';

        $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);


        $ultext = $ultext.'<li>
                    <div class="inquiry_idtime">
                        <span class="idtime_time">'.substr($row['od_time'],0,10).'</span>
                        <a href="'.G5_SHOP_URL.'/orderinquiryview.php?od_id='.$row['od_id'].'&amp;uid='.$uid.'" class="idtime_link">주문 상세보기 ></a>
                    </div>
                    <div class="inquiry_name">
                        '.$ct_name.'
                    </div>
                    <div class="inq_wr">
                        <div class="inquiry_price">
                            '.display_price($row['od_cart_price']).'
                        </div>
                        <div class="inv_status">'.$od_status.'</div>
                    </div>
                    <div class="inquiry_inv">
                        '.$od_invoice.'
                    </div>
                </li>';
    }
    $ultext = $ultext.'</ul>';

    if ($i == 0){
        echo '<li class="empty_list">주문 내역이 없습니다.</li>';
    }else {
        echo $ultext;
    }
    ?>

