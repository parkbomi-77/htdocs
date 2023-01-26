<?php
$sub_menu = '400910';
include_once('./_common.php');

auth_check_menu($auth, $sub_menu, "r");

// $doc = isset($_GET['doc']) ? clean_xss_tags($_GET['doc'], 1, 1) : '';
// $sfl = in_array($sfl, array('it_name', 'it_id')) ? $sfl : '';

$g5['title'] = '광고 현황';
include_once (G5_ADMIN_PATH.'/admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$od_status = isset($_GET['od_status']) ? get_search_string($_GET['od_status']) : '';

$fr_date = (isset($_GET['fr_date']) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $_GET['fr_date'])) ? $_GET['fr_date'] : '';
$to_date = (isset($_GET['to_date']) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $_GET['to_date'])) ? $_GET['to_date'] : '';

if ($fr_date && $to_date) {
    $where[] = " ct_select_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
}

if ($od_status) {
    switch ($od_status) {
        case '주문' :
            $where[] = " ct_status = '주문' ";
            break;
        case '완료' :   // 거래완료
            $where[] = " ct_status = '완료' ";
            break;
        case '배송' :   // 배송중
            $where[] = " ct_status = '배송' ";
            break;
        case '취소':
            $where[] = " ct_status = '취소' ";
            break;
        default:
            $where[] = " ct_status = '$od_status' ";
            break;
    }
}

if ($where) {
    $sql_search = ' where '.implode(' and ', $where).' and ct_vetcode="vet" ';
}else {
    $sql_search = ' where ct_vetcode="vet" ';
}

$sql_common = " from {$g5['g5_shop_cart_table']} $sql_search ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql  = "select g5_shop_cart.mb_id,
        g5_shop_cart.it_id,
        g5_shop_cart.it_name,
        g5_shop_cart.ct_price,
        g5_shop_cart.ct_qty,
        g5_shop_cart.ct_status,
        g5_shop_cart.ct_select_time,
        g5_shop_item.it_margin
        from g5_shop_cart
        left join g5_shop_item
        on g5_shop_cart.it_id = g5_shop_item.it_id
        $sql_search
        limit $from_record, $rows ";
$result = sql_query($sql);

$total = " select FORMAT(sum(ct_price),'#,#') 
        $sql_common
       
        ";
$result2 = sql_fetch($total);
$result2["FORMAT(sum(ct_price),'#,#')"];

$qstr  = $qstr.'&amp;sca='.$sca.'&amp;page='.$page.'&amp;save_stx='.$stx;

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>

<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
        <span class="btn_ov01"><span class="ov_txt">전체 상품</span><span class="ov_num">  <?php echo $total_count; ?>개</span></span>
</div>

<form class="local_sch03 local_sch">
    <div>
        <strong>주문상태</strong>
        <input type="radio" name="od_status" value="" id="od_status_all"    <?php echo get_checked($od_status, '');     ?>>
        <label for="od_status_all">전체</label>
        <input type="radio" name="od_status" value="주문" id="od_status_odr" <?php echo get_checked($od_status, '주문'); ?>>
        <label for="od_status_odr">주문</label>
        <input type="radio" name="od_status" value="배송" id="od_status_dvr" <?php echo get_checked($od_status, '배송'); ?>>
        <label for="od_status_dvr">배송</label>
        <input type="radio" name="od_status" value="완료" id="od_status_done" <?php echo get_checked($od_status, '완료'); ?>>
        <label for="od_status_done">완료</label>
        <input type="radio" name="od_status" value="취소" id="od_status_cancel" <?php echo get_checked($od_status, '전체취소'); ?>>
        <label for="od_status_cancel">취소</label>
    </div>

    <div class="sch_last">
        <strong>주문일자</strong>
        <input type="text" id="fr_date"  name="fr_date" value="<?php echo $fr_date; ?>" class="frm_input" size="10" maxlength="10"> ~
        <input type="text" id="to_date"  name="to_date" value="<?php echo $to_date; ?>" class="frm_input" size="10" maxlength="10">
        <button type="button" onclick="javascript:set_date('오늘');">오늘</button>
        <button type="button" onclick="javascript:set_date('어제');">어제</button>
        <button type="button" onclick="javascript:set_date('이번주');">이번주</button>
        <button type="button" onclick="javascript:set_date('이번달');">이번달</button>
        <button type="button" onclick="javascript:set_date('지난주');">지난주</button>
        <button type="button" onclick="javascript:set_date('지난달');">지난달</button>
        <button type="button" onclick="javascript:set_date('전체');">전체</button>
        <input type="submit" value="검색" class="btn_submit">
    </div>
</form>

<form name="fitemtypelist" method="post" action="">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="tbl_head01 tbl_wrap">
    <table>
        <colgroup>
            <col width="20%">
            <col width="10%">
            <col width="20%">
            <col width="10%">
            <col width="5%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
            <col width="10%">
        </colgroup>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col"><?php echo subject_sort_link("ct_select_time", $qstr, 1); ?>주문날짜</a></th>
        <th scope="col"><?php echo subject_sort_link("mb_id"); ?>유저 ID</a></th>
        <th scope="col"><?php echo subject_sort_link("it_name"); ?>상품명</a></th>
        <th scope="col"><?php echo subject_sort_link("ct_price"); ?>가격</a></th>
        <th scope="col"><?php echo subject_sort_link("ct_qty"); ?>수량</a></th>
        <th scope="col"><?php echo subject_sort_link(""); ?>총 가격</a></th>
        <th scope="col"><?php echo subject_sort_link(""); ?>광고 수수료 비율</a></th>
        <th scope="col"><?php echo subject_sort_link(""); ?>수수료</a></th>
        <th scope="col"><?php echo subject_sort_link("ct_status", $qstr, 1); ?>주문<br>상태</a></th>
    </tr>
    </thead>
    <tbody>
    <?php for ($i=0; $row=sql_fetch_array($result); $i++) {
        $href = shop_item_url($row['it_id']);

        $margin = $row['it_margin'];
        // margin json 다시 obj로 변경 
        $obj = json_decode($margin);

        // 키값 추출
        $monthkey = substr($row['ct_select_time'], 0, 7); 
        $monthkey2 = str_replace('-', "", $monthkey).'01';

        // 마진율 추출 
        $onemargin = $obj->$monthkey2;

        $bg = 'bg'.($i%2);
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_code">
            <!-- <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['g5_shop_cart']; ?>"> -->
            <?php echo $row['ct_select_time']; ?>
        </td>
        <td class="td_chk2">
            <label for="type1_<?php echo $i; ?>" class="sound_only"></label>
            <?php echo $row['mb_id']; ?>
        </td>
        <td class="td_left"><?php // echo get_it_image($row['it_id'], 50, 50); ?><?php echo cut_str(stripslashes($row['it_name']), 60, "&#133"); ?></a></td>
        <td class="td_mng td_mng_s">
            <?php echo $row['ct_price']; ?>
         </td>
         <td class="td_code">
            <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
            <?php echo $row['ct_qty']; ?>
        </td>
        <td class="td_code">
            <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
            <?php echo ($row['ct_price'] * $row['ct_qty']) ?> 
        </td>
        <!-- 마진율 -->
        <td class="td_code">
            <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $onemargin; ?>">
            <?php echo ($onemargin/100); ?> %
        </td>
        <!-- 수수료 -->
        <td class="td_code">
            <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
            <?php echo (($row['ct_price'] * $row['ct_qty'])*$onemargin/100).'원'; ?> 
        </td>
        <td class="td_code">
            <input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
            <?php echo $row['ct_status']; ?>
        </td>
    </tr>
    <?php
    }

    if (!$i)
        echo '<tr><td colspan="8" class="empty_table"><span>자료가 없습니다.</span></td></tr>';
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th scope="row" colspan='4'>Totals</th>
            <td colspan='5'><?php echo $result2["FORMAT(sum(ct_price),'#,#')"] ?>원</td>
        </tr>
    </tfoot>
    </table>
</div>

<!-- <div class="btn_confirm03 btn_confirm">
    <input type="submit" value="일괄수정" class="btn_submit">
</div> -->
</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
    $(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
    });

    function set_date(today)
    {
        <?php
        $date_term = date('w', G5_SERVER_TIME);
        $week_term = $date_term + 7;
        $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
        ?>
        if (today == "오늘") {
            document.getElementById("fr_date").value = "<?php echo G5_TIME_YMD; ?>";
            document.getElementById("to_date").value = "<?php echo G5_TIME_YMD; ?>";
        } else if (today == "어제") {
            document.getElementById("fr_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
            document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
        } else if (today == "이번주") {
            document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-'.$date_term.' days', G5_SERVER_TIME)); ?>";
            document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
        } else if (today == "이번달") {
            document.getElementById("fr_date").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
            document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
        } else if (today == "지난주") {
            document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-'.$week_term.' days', G5_SERVER_TIME)); ?>";
            document.getElementById("to_date").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 6).' days', G5_SERVER_TIME)); ?>";
        } else if (today == "지난달") {
            document.getElementById("fr_date").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
            document.getElementById("to_date").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
        } else if (today == "전체") {
            document.getElementById("fr_date").value = "";
            document.getElementById("to_date").value = "";
        }
    }
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');