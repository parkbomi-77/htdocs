<?php
$sub_menu = '400900';
include_once('./_common.php');

check_demo();

auth_check_menu($auth, $sub_menu, "w");

check_admin_token();

$count_post_it_id = (isset($_POST['it_id']) && is_array($_POST['it_id'])) ? count($_POST['it_id']) : 0;

$search = isset($_REQUEST['search']) ? get_search_string($_REQUEST['search']) : '';
$sort1 = isset($_REQUEST['sort1']) ? clean_xss_tags($_REQUEST['sort1'], 1, 1) : '';
$sort2 = isset($_REQUEST['sort2']) ? clean_xss_tags($_REQUEST['sort2'], 1, 1) : '';
$sel_ca_id = isset($_REQUEST['sel_ca_id']) ? clean_xss_tags($_REQUEST['sel_ca_id'], 1, 1) : '';
$sel_field = isset($_REQUEST['sel_field']) ? clean_xss_tags($_REQUEST['sel_field'], 1, 1) : '';

// 광고상태 일괄 수정 
for ($i=0; $i<$count_post_it_id; $i++)
{
    $it_1_subj = isset($_POST['it_1_subj'][$i]) ? (int) $_POST['it_1_subj'][$i] : 0;

    $sql = "update {$g5['g5_shop_item_table']}
               set it_1_subj = '".$it_1_subj."'
             where it_id = ".$it_id[$i]." ";
    $aa = sql_query($sql);
}

// goto_url("./itemadvertisementlist.php?sort1=$sort1&amp;sort2=$sort2&amp;sel_ca_id=$sel_ca_id&amp;sel_field=$sel_field&amp;search=$search&amp;page=$page");
goto_url("./itemadvertisementlist.php");
