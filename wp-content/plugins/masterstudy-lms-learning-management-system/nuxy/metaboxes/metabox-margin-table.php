<?php 
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
global $wpdb;



// $date = $year.'-'.$month.'%';


$sql1 ="SELECT wp_shoppingmall.code, wp_shoppingmall.name 
FROM wp_shoppingmall
where state = 1";
$mallNamedata = $wpdb->get_results($wpdb->prepare($sql1));

function tabledata($mallNamedata) {
    $year = $_POST['year'];
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

        $code = $mallNamedata[$i]->code; 
        $data = tablemargindata($code, $year); 

        for($j=0; $j<count($data); $j++){
            $month = $data[$j]->date_setting; 
            $extraction = substr($month, 5, 2);
            ${"td".$extraction} = '<td>'.$data[$j]->margin.'</td>';
        }
        $tablerow = $tablerow.$td01.$td02.$td03.$td04.$td05.$td06.$td07.$td08.$td09.$td10.$td11.$td12.'</tr>';
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