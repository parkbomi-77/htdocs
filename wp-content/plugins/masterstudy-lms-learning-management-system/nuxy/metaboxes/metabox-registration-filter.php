<?php 
require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );

$code = $_POST['code'];

$allsql = "select wp_product_list.*, wp_shoppingmall.name 
from wp_product_list
join wp_shoppingmall
on wp_product_list.mall_code = wp_shoppingmall.code
where adv_state = 1";

function productlist($sql) {
    global $wpdb; 
    return $wpdb->get_results($wpdb->prepare($sql));
}

function printrow($results) {
    $all_registration = '';
    for($i = 0; $i < count($results); $i++){
        $one_registration = ' 
            <div class="registration-div">
                <input type="checkbox" name="deletecheck[]" value="'.$results[$i]->ID.'">
                <div class="registration-num">'.($i+1).'</div>
                <input type="hidden" name="registrationNum[]" value="'.($i+1).'">
                <input type="hidden" name="registrationID[]" value="'.$results[$i]->ID.'">
                <input type="hidden" name="shoppingMallList[]" value="'.$results[$i]->mall_code.'">
                <input type="hidden" name="registrationname[]" value="'.$results[$i]->product_name.'">
                <input type="hidden" name="registrationlink[]" value="'.$results[$i]->product_code.'">
    
                <div class="registration-mall" id="registration-mall2">
                    <input type="text" name="shoppingMallList[]" value="'.$results[$i]->name.'" disabled>
                </div>
                <div class="registration-name" id="registration-name2">
                    <input type="text" name="registrationname[]" value="'.$results[$i]->product_name.'" disabled>
                </div>
                <div class="registration-link" id="registration-link2">
                    <input type="text" name="registrationlink[]" value="'.$results[$i]->product_code.'" disabled>
                    
                </div>
                <div class="registration-check">
                    <button class="edit" onclick="editfunc('.($i).')"><i class="fas fa-pen"></i></button>
                    <button class="reset none" onclick="backfunc('.($i).')"><i class="fas fa-chevron-left"></i></button>
                    <button class="save none" onclick="savefunc('.($i).','.$results[$i]->ID.')"><i class="far fa-save fa-lg"></i></button>
    
                </div>
            </div>
            ';
        $all_registration = $all_registration.$one_registration ;
    }
    return $all_registration;
}


if($code === ""){ // 전체 리스트 불러와야할때
    $results = productlist($allsql);
    $data = printrow($results);
    echo $data;

} else { // 특정 쇼핑몰 리스트 불러오기
    $filtersql = " and mall_code=";
    $allsql =  $allsql.$filtersql.$code;
    $results = productlist($allsql);
    
    $data = printrow($results);
    echo $data;

}

?>
