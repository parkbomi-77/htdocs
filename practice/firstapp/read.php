<?php
    include  $_SERVER['DOCUMENT_ROOT']."/practice/firstapp/db.php";
?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="./style.css" />
</head>
<body>
	<?php
		$bno = $_GET['idx']; /* bno함수에 idx값을 받아와 넣음*/
		$boardcol = mysqli_fetch_assoc(mysqli_query($conn, "select * from board where idx ='".$bno."'"));
        $boardcol = $boardcol["boardcol"] + 1;
        
		$fet = mysqli_query($conn, "update board set boardcol = '".$boardcol."' where idx = '".$bno."'");
		// var_dump($fet);
        $sql = mysqli_query($conn, "select * from board where idx='".$bno."'"); /* 받아온 idx값을 선택 */
		// var_dump($sql);
		$board = $sql->fetch_array();
		//var_dump($board);

	?>
<!-- 글 불러오기 -->
<div id="board_read">
	<h2><?php echo $board['title']; ?></h2>
		<div id="user_info">
			<?php echo $board['name']; ?> <?php echo $board['date']; ?> 조회:<?php echo $board['boardcol']; ?>
				<div id="bo_line"></div>
			</div>
			<div id="bo_content">
				<?php echo nl2br("$board[content]"); ?>
			</div>
	<!-- 목록, 수정, 삭제 -->
	<div id="bo_ser">
		<ul>
			<li><a href="/practice/firstapp">[목록으로]</a></li>
			<li><a href="modify.php?idx=<?php echo $board['idx']; ?>">[수정]</a></li>
			<li><a href="delete.php?idx=<?php echo $board['idx']; ?>">[삭제]</a></li>
		</ul>
	</div>
</div>
</body>
</html>