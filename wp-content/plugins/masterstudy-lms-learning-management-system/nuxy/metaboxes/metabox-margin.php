<?php
    global $wpdb;

    
    //현재 년도, 월 
    $today = date("Ym");
    $year = substr($today,0,4);
    $month = substr($today,4,2);
    $date = $year.'-'.$month.'%';

    // $sql = "SELECT a.code, a.name, b.margin, b.date_setting
    // from wp_shoppingmall as a
    // left outer join wp_shoppingmall_margin as b
    // on a.code = b.code
    // where a.state = 1
    // and (b.date_setting like '".$date."' 
    // or b.date_setting is null)";
    // $mallName = $wpdb->get_results($wpdb->prepare($sql));

    // if(!$mallName){ // 새로운 달 데이터 없는 경우 
    //     $sql2 = "SELECT wp_shoppingmall.code, wp_shoppingmall.name 
    //             FROM wp_shoppingmall
    //             where state = 1 ";
    //     $mallName = $wpdb->get_results($wpdb->prepare($sql2));
    // }

    $sql3 ="SELECT code, name, start_date, end_date
            FROM wp_shoppingmall
            where state = 1";
    $mallNamedata = $wpdb->get_results($wpdb->prepare($sql3));

    function datesetting ($mallNamedata) {
        $datearr = array();
        for($i=0; $i<count($mallNamedata); $i++){
            $startyear = substr($mallNamedata[$i]->start_date, 0, 4);
            $startmonth = substr($mallNamedata[$i]->start_date, 5, 2);
            $endyear = substr($mallNamedata[$i]->end_date, 0, 4);
            $endmonth = substr($mallNamedata[$i]->end_date, 5, 2);
            $datearr[$i] = [$startyear, $startmonth, $endyear, $endmonth];
        }
        return $datearr;
    }


    // 쇼핑몰 이름
    function mall_name($date) {
        $mallname = "";
        for($i=0; $i<count($date); $i++) {
            $mallname = $mallname.'<option value="'.$date[$i]->code.'">'.$date[$i]->name.'</option>';
        }
        return $mallname;
    }

    // function years($year) {
    //     $allyears = '';
    //     for($i=2022; $i<=2032; $i++){
    //         if($i === (int)$year){
    //             $allyears = $allyears.'<option value='.($i).' selected>'.($i).년도.'</option>';
    //         }else {
    //             $allyears = $allyears.'<option value='.($i).'>'.($i).년도.'</option>';
    //         }
    //     }
    //     return $allyears;
    // }
    
    // function month($month) {
    //     $allmonth = '';
    //     for($i=1; $i<=12; $i++){
    //         if($i === (int)$month){
    //             $allmonth = $allmonth.'<option value='.($i).' selected>'.($i).월.'</option>';
    //         }else {
    //             $allmonth = $allmonth.'<option value='.($i).'>'.($i).월.'</option>';
    //         }
    //     }
    //     return $allmonth;
    // }
    
    function margin_percent() {
        $marginoption = "";
        for($i=0; $i<=20; $i++) {
            $marginoption = $marginoption.'<option value='.($i*5).'>'.($i*5).'</option>';
        }
        return $marginoption;
    }
    // 테이블 헤드 날짜
    function tablemonth() {
        $tablemonth = "";
        for($i=1; $i<=12; $i++){
            $tablemonth = $tablemonth.'<th>'.$i.'월</th>';
        }
        return $tablemonth;
    }
    // 테이블 바디 데이터
    function tabledata($mallNamedata) {
        $years = date("Y");

        $tablelist = "";
        for($i=0; $i<count($mallNamedata); $i++){
            $startyear = substr($mallNamedata[$i]->start_date, 0, 4);
            $startmonth = substr($mallNamedata[$i]->start_date, 5, 2);
            $endyear = substr($mallNamedata[$i]->end_date, 0, 4);
            $endmonth = substr($mallNamedata[$i]->end_date, 5, 2);

            $tablerow = '<tr> <td>'.$mallNamedata[$i]->name.'</td>';
            $tdarr = array();
            
            // 시작년도보다 크고 끝년도보다 작으면 all
            if(($startyear < $years) && ($years < $endyear)){
                for($j=0; $j<12; $j++){
                    $tdarr[$j] = "<td style='background-color:white'>0</td>";
                }
            // 시작년도와 같고 끝년도와 같으면 달을 비교
            }else if(($startyear === $years) && ($years === $endyear)) {
                for($j=0; $j<12; $j++){
                    if($j+1 >= $startmonth && $j+1 <= $endmonth){
                        $tdarr[$j] = "<td style='background-color:white'>0</td>";
                    }else {
                        $tdarr[$j] = "<td style='background-color:#aaaaaa'>0</td>";
                    }
                }
            // 시작년도와 같고 끝년도보다 작으면
            }else if(($startyear === $years) && ($years < $endyear)){
                for($j=0; $j<12; $j++){
                    if($j+1 >= $startmonth){
                        $tdarr[$j] = "<td style='background-color:white'>0</td>";
                    }else {
                        $tdarr[$j] = "<td style='background-color:#aaaaaa'>0</td>";
                    }
                }
            }
            // 시작년도보다 크고 끝년도와 같으면
            else if(($startyear < $years) && ($years === $endyear)){
                for($j=0; $j<12; $j++){
                    if($j+1 <= $endmonth){
                        $tdarr[$j] = "<td style='background-color:white'>0</td>";
                    }else {
                        $tdarr[$j] = "<td style='background-color:#aaaaaa'>0</td>";
                    }
                }
            }
            
            $code = $mallNamedata[$i]->code; // 1028 
            $data = tablemargindata($code); // 3개가 들어오고 .. 

            for($j=0; $j<count($data); $j++){
                $date = $data[$j]->date_setting; // 10 
                $extraction_m = substr($date, 5, 2); // 달이 들어옴 

                $marginadd = substr_replace($tdarr[$extraction_m-1], $data[$j]->margin, -6, 1);
                $tdarr[$extraction_m-1] = $marginadd;
            }
            $tablerow = $tablerow.$tdarr[0].$tdarr[1].$tdarr[2].$tdarr[3].$tdarr[4].$tdarr[5].$tdarr[6].$tdarr[7].$tdarr[8].$tdarr[9].$tdarr[10].$tdarr[11].'</tr>';
            $tablelist = $tablelist.$tablerow;
            
        }
        return $tablelist;

    }
    function tablemargindata($code) {
        global $wpdb;
        //표에 조회되는 디폴트 년도
        $years = date("Y");

        $sql4 ="SELECT * FROM vetschool.wp_shoppingmall_margin where code =";
        $sql4 = $sql4.$code." and date_setting like '{$years}%'";

        $data = $wpdb->get_results($wpdb->prepare($sql4));
        return $data;
    }

?>

<div class="margin-container">
    <div class="margin_title">
        <div>type</div>
        <div class="margin_select">
            <select name="" id="">
                <option value="">쇼핑몰 명</option>
                <!-- <option value="">가격대 별</option> -->
            </select>
        </div>
    </div>
    <form action="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-margin-save.php" 
          method="post"
          onsubmit="return margin_submit(event);">
        <div class="margin_main">
            <div class="margin_setting">
                <div class="margin_shoppingmallname">
                    <div>
                        쇼핑몰
                    </div>
                    <div>
                        <select name="code" id="margin_mallname" onchange="changemall(event)">
                            <?php echo mall_name($mallNamedata); ?>
                        </select> 
                    </div>
                </div>
                <div class="margin_date">
                    <div>
                        날짜
                    </div>
                    <div id="margin_calendar">
                        <i class="far fa-calendar-check fa-lg"></i>
                        <div class="month_select">
                            <input name="startyear" class="startyear" type="number" min=<?php echo datesetting($mallNamedata)[0][0]?> max=<?php echo datesetting($mallNamedata)[0][2]?> value=<?php echo datesetting($mallNamedata)[0][0]?> >
                            <input name="startmonth" class="startmonth" type="number" min=1 max=12 value=<?php echo (int)datesetting($mallNamedata)[0][1]?> >
                        </div>
                        <span>-</span>
                        <div class="month_select">
                            <input name="endyear" class="endyear" type="number" min=<?php echo datesetting($mallNamedata)[0][0]?> max=<?php echo datesetting($mallNamedata)[0][2]?> value=<?php echo datesetting($mallNamedata)[0][2]?>>
                            <input name="endmonth" class="endmonth" type="number" min=1 max=12 value=<?php echo (int)datesetting($mallNamedata)[0][3]?>>
                        </div>
                    </div>
                </div>
                <div class="margin_set">
                    <div>
                        마진 설정
                    </div>
                    <div id="margin_set_select">
                        <select name="margin" id="margin_mallname">
                            <?php echo margin_percent(); ?>
                        </select><span>%</span>
                    </div>
                </div>

            </div>
        </div>
        <div class = "margin_btn">
            <input type="submit" value="SAVE">
        </div>
        <div class="margin_table_div">
                <div class="margin_table_top"><?php echo $year; ?>년도 ( 단위 % )</div>


                <table class="margin_table">
                    <div class="movetable_left" onclick="moveyear(1)"><i class="fas fa-chevron-left"></i></div>
                    <div class="movetable_right" onclick="moveyear(2)"><i class="fas fa-chevron-right"></i></div>
                    <thead>
                        <tr>
                            <th>쇼핑몰명</th>
                            <?php echo tablemonth(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo tabledata($mallNamedata);?>
                    </tbody>
                </table>
            </div>
    </form>
</div>


<script src="https://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

    function margin_submit(event) {
        // 시작날짜 끝날짜 예외처리 
        let startyear = event.target.querySelectorAll(".month_select")[0].children[0].value;
        let startmonth = event.target.querySelectorAll(".month_select")[0].children[1].value;

        let endyear = event.target.querySelectorAll(".month_select")[1].children[0].value;
        let endmonth = event.target.querySelectorAll(".month_select")[1].children[1].value;

        let today = new Date();
        let year = today.getFullYear();
        let month = ('0' + (today.getMonth() + 1)).slice(-2);

        console.log(startmonth);
        console.log(month);

        // 시작날짜가 현재 년도, 달보다 같거나 커야 변경가능하도록 
        // if(){

        // }

        if((startyear < year) || ((startyear === endyear) && (Number(startmonth) < Number(month))) ){
            alert("지나간 기한은 수정할 수 없습니다.")
            return false;
        }else if(startyear > endyear){
            alert("기한을 다시 한번 확인해주세요.")
            return false;
        }else if((startyear === endyear) && (Number(startmonth) > Number(endmonth))){
            alert("기한을 다시 한번 확인해주세요.")
            return false;
        }
        return true;


    }
    function moveyear(num) {
        
        let yeardata = document.querySelector(".margin_table_top");
        let year = Number(yeardata.innerText.substr(0,4))
        if(num === 1){ // 왼쪽으로 이동
            year = year-1;
        } else if(num === 2){ // 오른쪽으로 이동
            year = year+1;
        }
        let tbody = document.querySelector("tbody");
        tbody.innerHTML ="";

        $.ajax({
            url: '/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-margin-table.php',
            type: 'POST',
            data: { 
                year,
            },
            dataType: 'text',
            success: function(data) {
                tbody.innerHTML = data;
                yeardata.innerText = (year)+'년도 ( 단위 % )';
            },
        });
    }

    // 쇼핑몰 선택시 해당 날짜 불러오기 
    function changemall(event) {
        let num = event.target.selectedIndex;
        let date = <?php echo json_encode(datesetting($mallNamedata)); ?> 

        let starty = date[num][0];
        let startm = date[num][1];
        let endy = date[num][2];
        let endm = date[num][3];

        let startyinput = document.querySelector(".startyear")
        let startminput = document.querySelector(".startmonth")
        let endyinput = document.querySelector(".endyear")
        let endminput = document.querySelector(".endmonth")

        startyinput.value = starty;
        startminput.value = startm;
        endyinput.value = endy;
        endminput.value = endm;

    }
</script>