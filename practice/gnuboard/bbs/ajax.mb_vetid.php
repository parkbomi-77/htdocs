<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/register.lib.php');

$mb_vetid = isset($_POST['reg_mb_vetid']) ? trim($_POST['reg_mb_vetid']) : '';

set_session('ss_check_mb_vetid', '');

if ($msg = exist_vet_id($mb_vetid))     die($msg);

set_session('ss_check_mb_vetid', $mb_vetid);