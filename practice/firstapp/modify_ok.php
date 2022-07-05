<?php
include  $_SERVER['DOCUMENT_ROOT']."/practice/firstapp/db.php";

$bno = $_GET['idx'];
$username = $_POST['name'];
$userpw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
$title = $_POST['title'];
$content = $_POST['content'];
$sql = mysqli_query($conn, "update board set name='".$username."',pw='".$userpw."',title='".$title."',content='".$content."' where idx='".$bno."'"); ?>

<script type="text/javascript">alert("수정되었습니다."); </script>
<meta http-equiv="refresh" content="0 url=/practice/firstapp/">de