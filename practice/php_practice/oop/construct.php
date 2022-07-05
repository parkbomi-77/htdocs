<!-- 
construct
인스턴스에 state로 넣어줄 변수를 넣어줘야하는데 까먹을경우, 에러를 일으킬 수 있다 
때문에 에러를 일으키지않기위해 생성자 입장에서 필수적으로 필요한 정보를 강제받게하는 방법을 보자
-->

<?php
class MyFileObject{
    function __construct($fname){
        $this->filename = $fname; 
    }
    function isFile(){
        return is_file($this->filename); // this를 붙여줌으로서 이 클래스를 인스턴스화한 곳의 변수로 작동할수 있게해줌
    }
}

$file = new MyFileObject("../index.php"); 
// $file->filename = "../index.php"; 
var_dump($file->isFile());
var_dump($file->filename); // $file안에 지정된 filename의 값 확인하기 
?>



