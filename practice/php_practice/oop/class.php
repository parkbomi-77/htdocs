<!-- 용어정리 
MyFileObject2 : class. 설계도와 같다 
$file : instance가 담기는 변수
isFile() : method. 클래스안에 소속된 함수 
클래스안에 담기는 값 : state. 상태 
$this->filename : instance variable, instance field, instance property
-->

<!-- class 
MyFileObject 소속의 isFile이라는 메소드.
class안에 소속되어있기때문에 각 클래스마다 중복되는 메소드명을 사용할 수 있다. 
-->
<?php
class MyFileObject{
    function isFile(){
        return is_file('../index.php');
    }
}
$file = new MyFileObject(); 
var_dump($file->isFile());
?>

<!-- 위와같이 하드코딩된 메소드말고, 상태에 따라 각 인스턴스를 활용할 수 있는 법을 알아보자 -->

<?php
class MyFileObject2{
    function isFile(){
        return is_file($this->filename); // this를 붙여줌으로서 이 클래스를 인스턴스화한 곳의 변수로 작동할수 있게해줌
    }
}

$file = new MyFileObject2(); 
$file->filename = "../index.php"; // instance의 변수를 할당
var_dump($file->isFile());
var_dump($file->filename); // $file안에 지정된 filename의 값 확인하기 

$file2 = new MyFileObject2(); 
$file2->filename = "data.php";
var_dump($file2->isFile());
var_dump($file2->filename);
?>


