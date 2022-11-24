
<?php
include_once('./_common.php');

$margin = json_encode($_POST['margin']);
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

if($_POST['product_code']){ //벳스쿨에 광고제품 등록했을때
    // 벳스쿨에서 등록한 제품ID가 그누보드쪽 제품ID와 일치하는지 체크
    $sql = "SELECT * FROM {$g5['g5_shop_item_table']} where it_id = {$_POST['product_code']}";
    $item = sql_query($sql);
    
    if($item){ // 일치하면 it_1_subj( 광고여부필드 ) 1로 업데이트 
        $sql2 = "UPDATE {$g5['g5_shop_item_table']} 
        SET `it_1_subj` = '1', `it_margin`='{$margin}', `it_margin_start`='{$start_date}', `it_margin_end`='{$end_date}'
        where it_id = {$_POST['product_code']}";
        sql_query($sql2);
    
    }else { // 없으면? 잘못들어온 데이터 
        return;
    }
}else if($_POST['delete_code']){ // 벳스쿨에 광고제품 삭제했을때 or 계약기간 만료되었을때
    // 벳스쿨에서 삭제 제품ID가 그누보드쪽 제품인지 체크
    $sql = "SELECT * FROM {$g5['g5_shop_item_table']} where it_id = {$_POST['delete_code']}";
    $item = sql_query($sql);

    if($item){ // 맞으면 it_1_subj( 광고여부필드 ) 0으로 변경
        $sql2 = "UPDATE {$g5['g5_shop_item_table']} SET `it_1_subj` = '0' where it_id = {$_POST['delete_code']}";
        sql_query($sql2);
    }else { // 없으면? 잘못들어온 데이터 
        return;
    }
}



?>