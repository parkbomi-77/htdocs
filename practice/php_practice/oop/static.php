<!-- static 은 정적인 . 이라는뜻
static이 붙은 변수,함수는 클래스 소속 변수가됨
static이 붙지않은 변수는 인스턴스 소속 변수 

this 는 인스턴스의 셀프. ->화 함께쓰임 
self:: 는 클래스의 셀프를 원할때 씀. 콜론2개와 함께쓰임
-->


<?php
class Person{
  private static $count = 0;
  private $name;
  function __construct($name){
    $this->name = $name;
    self::$count = self::$count + 1;
  }
  function enter(){
    echo "<h1>Enter ".$this->name." ".self::$count."th</h1>";
  }
  static function getCount(){
    return self::$count;
  }
}
$p1 = new Person('egoing');
$p1->enter();
$p2 = new Person('leezche');
$p2->enter();
$p3 = new Person('duru');
$p3->enter();
$p4 = new Person('taiho');
$p4->enter();
echo Person::getCount();