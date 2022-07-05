<?php
	include  $_SERVER['DOCUMENT_ROOT']."/practice/firstapp/db.php";
	$bno = $_GET['idx'];
	// var_dump($bno);
	$sql = mysqli_query($conn, "delete from board where idx='$bno';");
?>
<script type="text/javascript">alert("삭제되었습니다.");</script>
<meta http-equiv="refresh" content="0 url=/practice/firstapp/" />