
<?php
include_once('./_common.php');

$_POST['product_code'];


// 벳스쿨에서 등록한 제품ID가 그누보드쪽 제품ID와 일치하는지 체크
$sql = "SELECT * FROM {$g5['g5_shop_item_table']} where it_id = {$_POST['product_code']}";
$item = sql_query($sql);

if($item){ // 일치하면 it_1_subj( 광고여부필드 ) 1로 업데이트 
    $sql2 = "UPDATE {$g5['g5_shop_item_table']} SET `it_1_subj` = '1' where it_id = {$_POST['product_code']}";
    sql_query($sql2);

}else { // 없으면? 잘못들어온 데이터 
    return;
}

?>