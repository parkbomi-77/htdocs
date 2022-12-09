<?php
include_once('./_common.php');

define("_ORDERINQUIRY_", true);

$order_info = array();
$request_pwd = isset($_POST['od_pwd']) ? $_POST['od_pwd'] : '';
$od_pwd = get_encrypt_string($request_pwd);
$od_id = isset($_POST['od_id']) ? safe_replace_regex($_POST['od_id'], 'od_id') : '';

// 회원인 경우
if ($is_member)
{
    $sql_common = " from {$g5['g5_shop_order_table']} where mb_id = '{$member['mb_id']}' ";
}
else if ($od_id && $od_pwd) // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
{
    if( defined('G5_MYSQL_PASSWORD_LENGTH') && strlen($od_pwd) === G5_MYSQL_PASSWORD_LENGTH ) {
        $sql_common = " from {$g5['g5_shop_order_table']} where od_id = '$od_id' and od_pwd = '$od_pwd' ";
    } else {
        $sql_common = " from {$g5['g5_shop_order_table']} where od_id = '$od_id' ";

        $order_info = get_shop_order_data($od_id);
        if (!check_password($request_pwd, $order_info['od_pwd'])) {
            run_event('password_is_wrong', 'shop', $order_info);
            alert('주문이 존재하지 않습니다.');
            exit;
        }

    }
}
else // 그렇지 않다면 로그인으로 가기
{
    goto_url(G5_BBS_URL.'/login.php?url='.urlencode(G5_SHOP_URL.'/orderinquiry.php'));
}

$calendarFromDate = $_POST['calendarFromDate'];
$calendarToDate = $_POST['calendarToDate'];
$appointed = "and DATE(od_time) >= '{$calendarFromDate}' and DATE(od_time) <= '{$calendarToDate}' ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
if($calendarFromDate) {
    $sql = " select count(*) as cnt " . $sql_common. $appointed;
}
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 비회원 주문확인시 비회원의 모든 주문이 다 출력되는 오류 수정
// 조건에 맞는 주문서가 없다면
// if ($total_count == 0)
// {
//     if ($is_member) // 회원일 경우는 메인으로 이동
//         alert('주문이 존재하지 않습니다.', G5_SHOP_URL);
//     else // 비회원일 경우는 이전 페이지로 이동
//         alert('주문이 존재하지 않습니다.');
// }

$rows = $config['cf_mobile_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


// 비회원 주문확인의 경우 바로 주문서 상세조회로 이동
if (!$is_member)
{
    if( defined('G5_MYSQL_PASSWORD_LENGTH') && strlen($od_pwd) === G5_MYSQL_PASSWORD_LENGTH ) {
        $sql = " select od_id, od_time, od_ip from {$g5['g5_shop_order_table']} where od_id = '$od_id' and od_pwd = '$od_pwd' ";
        $row = sql_fetch($sql);
    } else if( $order_info ){
        if (check_password($request_pwd, $order_info['od_pwd'])) {
            $row = $order_info;
        }
    }

    if ($row['od_id']) {
        $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);
        set_session('ss_orderview_uid', $uid);
        goto_url(G5_SHOP_URL.'/orderinquiryview.php?od_id='.$row['od_id'].'&amp;uid='.$uid);
    }
}

$g5['title'] = '주문내역조회';
include_once(G5_MSHOP_PATH.'/_head.php');
?>

<div id="sod_v">
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div id="month_area">
            <div class="month_list">
                <div class="monthSelectorli"></div>
                <div class="monthSelectorli"></div>
                <div class="monthSelectorli"></div>
                <div class="monthSelectorli"></div>
                <div class="monthSelectorli"></div>
                <div class="monthSelectorli"></div>
            </div>
            <div id="rangeDate_area">
                <div class="rangeDate">
                    <input type="text" id="rangeFromDate" name="calendarFromDate" value="<?php echo $calendarFromDate ? $calendarFromDate : Date("Y-m-d")?>">
                    <span><i class="far fa-calendar "></i></span> 
                </div>
                <span>~</span>
                <div class="rangeDate">
                    <input type="text" id="rangeToDate" name="calendarToDate" value="<?php echo $calendarToDate ? $calendarToDate : Date("Y-m-d") ?>">
                    <span><i class="far fa-calendar"></i></span>
                    
                </div>
            </div>
            <div class="btnbox">
                <button type="submit" class="btn">
                    <span>조회</span><i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>


    <?php
    $limit = " limit $from_record, $rows ";
    include G5_MSHOP_PATH.'/orderinquiry.sub.php';
    ?>

    <?php  echo get_paging($config['cf_mobile_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_orange.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ko.js"></script>
<script>
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth()+1; // 이번 달 
    let day = date.getDate();

    let month_area = document.querySelector(".month_list");
    let list = month_area.children
    for(let i=0; i<=5; i++){
        if(month-i <= 0){
            list[i].innerText = month-i+12+'월';
        }else {
            list[i].innerText = month-i+'월';
        }
    }
    // flatpickr(document.getElementsByClassName("rangeDate"), {
    //     'monthSelectorType' : 'static',
    //     "locale": "ko",
    //     "defaultDate": `${year}-${month}-${day}`
    // });
    flatpickr(document.getElementById("rangeFromDate"), {
        'monthSelectorType' : 'static',
        "locale": "ko",
        // "defaultDate": `${year}-${month}-${day}`
    });
    flatpickr(document.getElementById("rangeToDate"), {
        'monthSelectorType' : 'static',
        "locale": "ko",
        // "defaultDate": `${year}-${month}-${day}`
    });

    let container = document.querySelector("#sod_inquiry")
    let rangeFromDate = document.getElementById("rangeFromDate");
    let rangeToDate = document.getElementById("rangeToDate");

    let monthSelectorList = document.getElementsByClassName("monthSelectorli");
    let selectmonth;
    let lastmonthDate;
    for(let i=0; i<monthSelectorList.length; i++) {
        monthSelectorList[i].addEventListener("click", function(e) {
            // 해당 버튼만 ! 색깔 들어오도록 하기 

            // 클릭한 달
            selectmonth = e.target.innerText.slice(0,-1);
            if(selectmonth > month) {   // 지난년도일 경우 
                year = date.getFullYear()-1;
            }else {
                year = date.getFullYear();
            }

            if(selectmonth.length === 1){
                selectmonth = '0'+selectmonth;
            }

            lastmonthDate = new Date(year, selectmonth, 0).getDate();

            // 달력 첫날 ~ 끝날 변경
            rangeFromDate.value = year+'-'+selectmonth+'-01';
            rangeToDate.value = year+'-'+selectmonth+'-'+lastmonthDate;

            // 달력 디폴트 날짜 변경하기
            flatpickr(document.getElementById("rangeFromDate"), {
                'monthSelectorType' : 'static',
                "locale": "ko" 
            });
            flatpickr(document.getElementById("rangeToDate"), {
                'monthSelectorType' : 'static',
                "locale": "ko" 
            });

            // $.ajax({
            // type: "POST",
            // url: "/practice/gnuboard/mobile/shop/orderinquiry_select.php", 
            // data: { month: e.target.innerText },
            // dataType: "text",
            // success: function(data) {
            //     container.innerHTML = data;
            //     }
            // });
        })
    }
    document.querySelector(".btn").addEventListener("click", function () {


        $.ajax({
            type: "POST",
            url: "/practice/gnuboard/mobile/shop/orderinquiry_select.php", 
            // cache: false,
            // async: false,
            data: { 
                calendarFromDate: rangeFromDate.value,
                calendarToDate: rangeToDate.value
            },
            dataType: "text",
            success: function(data) {
                container.innerHTML = data;
            }
        });
    })


    


</script>

<?php
include_once(G5_MSHOP_PATH.'/_tail.php');