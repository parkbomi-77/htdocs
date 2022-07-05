<?php
include  $_SERVER['DOCUMENT_ROOT']."/practice/firstapp/db.php";
$username = $_POST["name"];
$userpw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
$title = $_POST['title'];
echo $title;
$content = $_POST['content'];
$date = date('Y-m-d');

if($username && $userpw && $title && $content){
    $sql = "insert into board (name,pw,title,content,date) values('".$username."','".$userpw."','".$title."','".$content."','".$date."')";
    mysqli_query($conn, $sql);
    echo "<script>
    alert('글쓰기 완료되었습니다.');
    location.href='/practice/firstapp/';</script>";
}else{
    echo "<script> 
    alert('글쓰기에 실패했습니다.');
    history.back();</script>";
}
?> 