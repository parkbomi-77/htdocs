<!-- 
oop에서의 캡슐화. 불필요한 것을 감추는. 정보은닉 기능. 접근 제어자 
access modifier. Property visibility

클래스안에 정의된 변수 혹은 상수, 또는 메소드를 외부에 노출시킬것인지 아니면 내부적을뫈 사용할 것인지 결정하는것 
 -->


 <?php
// "../index.php" 이 파일이 있는지 확인하고 없다면 프로그램을 종료시키는 코드 
// 들어오면 안되는 파일이 들어왔을시 그것을 거절하는 코드 
// private : 외부에서 filename의 값을 직접 지정하지못하도록 규제하는 방법 
// public : 외부에서 접근할수있도록 허용하는 것 

class MyFileObject{
    private $filename;
    function __construct($fname){
        $this->filename = $fname; 
        if(!file_exists($this->filename)){ // 파일이 존재하는지 확인하는 php 함수
            die('There is no file'.$this->filename); //프로그램을 종료시키면서 입력값을 화면에 출력
        }
    }
    function isFile(){
        return is_file($this->filename); // this를 붙여줌으로서 이 클래스를 인스턴스화한 곳의 변수로 작동할수 있게해줌
    }
}

$file = new MyFileObject("../index.php"); 
// $file = new MyFileObject(); 
// $file->filename = "../index.php"; 
var_dump($file->isFile());
var_dump($file->filename); // $file안에 지정된 filename의 값 확인하기 
?>

<!-- 외부에서 인스턴스에 변수를 지정하지못하게하고
set, get 메소드를 통해서 변수를 지정히거나 접근할수있도록 할수있다.
-->

<?php
class Person{
  private $name;
  function sayHi(){
    print("Hi, I'm {$this->name}.");
  }
  function setName($_name){
    if(empty($_name)){
      die('I need name');
    }
    $this->name = $_name;
  }
  function getName(){
    return $this->name;
  }
}
$egoing = new Person();
$egoing->setName('egoing');
$egoing->sayHi();
print($egoing->getName());
?>



