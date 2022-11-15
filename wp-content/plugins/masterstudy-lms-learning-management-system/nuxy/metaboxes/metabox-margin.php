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

    $sql3 ="SELECT wp_shoppingmall.code, wp_shoppingmall.name 
            FROM wp_shoppingmall
            where state = 1";
    $mallNamedata = $wpdb->get_results($wpdb->prepare($sql3));




    // 쇼핑몰 이름
    function mall_name($date) {
        $mallname = "";
        for($i=0; $i<count($date); $i++) {
            $mallname = $mallname.'<option value="'.$date[$i]->code.'">'.$date[$i]->name.'</option>';
        }
        return $mallname;
    }

    function years($year) {
        $allyears = '';
        for($i=2022; $i<=2032; $i++){
            if($i === (int)$year){
                $allyears = $allyears.'<option value='.($i).' selected>'.($i).년도.'</option>';
            }else {
                $allyears = $allyears.'<option value='.($i).'>'.($i).년도.'</option>';
            }
        }
        return $allyears;
    }
    
    function month($month) {
        $allmonth = '';
        for($i=1; $i<=12; $i++){
            if($i === (int)$month){
                $allmonth = $allmonth.'<option value='.($i).' selected>'.($i).월.'</option>';
            }else {
                $allmonth = $allmonth.'<option value='.($i).'>'.($i).월.'</option>';
            }
        }
        return $allmonth;
    }
    
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
        $tablelist = "";
        for($i=0; $i<count($mallNamedata); $i++){
            $tablerow = '<tr> <td>'.$mallNamedata[$i]->name.'</td>';
            $td01 = '<td>0</td>';
            $td02 = '<td>0</td>';
            $td03 = '<td>0</td>';
            $td04 = '<td>0</td>';
            $td05 = '<td>0</td>';
            $td06 = '<td>0</td>';
            $td07 = '<td>0</td>';
            $td08 = '<td>0</td>';
            $td09 = '<td>0</td>';
            $td10 = '<td>0</td>';
            $td11 = '<td>0</td>';
            $td12 = '<td>0</td>';

            $code = $mallNamedata[$i]->code; // 1028 
            $data = tablemargindata($code); // 3개가 들어오고 .. 

            for($j=0; $j<count($data); $j++){
                $month = $data[$j]->date_setting; // 10 
                $extraction = substr($month, 5, 2);
                ${"td".$extraction} = '<td>'.$data[$j]->margin.'</td>';
            }
            $tablerow = $tablerow.$td01.$td02.$td03.$td04.$td05.$td06.$td07.$td08.$td09.$td10.$td11.$td12.'</tr>';
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
    // function table_defaultyear_option($num) { // 현재년도 대비 5년 전~후까지 출력 
    //     $option = "";
    //     for($i=$num-5; $i<$num+5; $i++){

    //     }
    // }

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
                        <select name="code" id="margin_mallname">
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
                            <select name="startyear" id="">
                                <?php echo years($year); ?>
                            </select>
                            <select name="startmonth" id="">
                                <?php echo month($month); ?>
                            </select>
                        </div>
                        <span>-</span>
                        <!-- <i class="far fa-calendar-check fa-lg"></i> -->
                        <div class="month_select">
                            <select name="endyear" id="">
                                <?php echo years($year); ?>
                            </select>
                            <select name="endmonth" id="">
                                <?php echo month($month); ?>
                            </select>
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
        console.log(year);
        console.log(month);
        if((startyear < year) || (startmonth > month)){
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
</script>