<?php 
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
global $wpdb;


$sql1 ="SELECT code, name, start_date, end_date
FROM wp_shoppingmall
where state = 1";
$mallNamedata = $wpdb->get_results($wpdb->prepare($sql1));

function tabledata($mallNamedata) {
    $years = $_POST['year'];
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
        }else {
            for($j=0; $j<12; $j++){
                $tdarr[$j] = "<td style='background-color:#aaaaaa'>0</td>";
            }
        }
        
        $code = $mallNamedata[$i]->code; // 1028 
        $data = tablemargindata($code, $years); // 3개가 들어오고 .. 

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
function tablemargindata($code, $year) {
    global $wpdb;
    //표에 조회되는 디폴트 년도

    $sql4 ="SELECT * FROM vetschool.wp_shoppingmall_margin where code =";
    $sql4 = $sql4.$code." and date_setting like '{$year}%'";

    $data = $wpdb->get_results($wpdb->prepare($sql4));
    return $data;
}
echo tabledata($mallNamedata);

?>