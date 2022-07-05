<?php include  $_SERVER['DOCUMENT_ROOT']."/practice/firstapp/db.php"; ?>
<!doctype html>
<head>
<meta charset="UTF-8">
<title>게시판</title>
<link rel="stylesheet" type="text/css" href="./style.css" />
</head>
<body>
<div id="board_area"> 
  <h1>자유게시판</h1>
  <h4>자유롭게 글을 쓸 수 있는 게시판입니다.</h4>
    <table class="list-table">
      <thead>
          <tr>
                <th width="70">번호</th>
                <th width="500">제목</th>
                <th width="120">글쓴이</th>
                <th width="100">작성일</th>
                <th width="100">조회수</th>
            </tr>
        </thead>
        <?php
        // board테이블에서 idx를 기준으로 내림차순해서 10개까지 표시
          $sql = "SELECT *  FROM `board` "; //쿼리문
          $result = mysqli_query($conn, $sql); 
            while($row = mysqli_fetch_assoc($result))
            {
              //title변수에 DB에서 가져온 title을 선택
              $title=$row["title"]; 
              if(strlen($title)>10)
              { 
                //title이 30을 넘어서면 ...표시
                $title=str_replace($row["title"],mb_substr($row["title"],0,30,"utf-8")."...",$row["title"]);
              }
        ?>
      <tbody>
        <tr>
          <td width="70"><?php echo $row['idx']; ?></td>
          <td width="500"><a href="./read.php?idx=<?php echo $row["idx"];?>"><?php echo $title;?></a></td>
          <td width="120"><?php echo $row['name']?></td>
          <td width="100"><?php echo $row['date']?></td>
          <td width="100"><?php echo $row['boardcol']?></td>


        </tr>
      </tbody>
      <?php } ?>
    </table>
    <div id="write_btn">
      <a href="./page/board/write.php"><button>글쓰기</button></a>
    </div>
  </div>
</body>
</html> 