<!-- 부모클래스가 가진 메소드를 자식클래스가 상속받지못하게 하고싶을때 사용하는것 
final 을 class앞이나 메소드앞에 붙혀주면됨 
class가 완성이 된게 아니라 계속해서 수정이 되어야할때 누군가가 이 클래스를 상속받아버리면 안되기때문에 
그럴때 final을 씀 . 
-->

<?php
class ParentClass{
  function a(){
    echo 'Parent';
  }
  final function b(){
    echo 'Parent B';
  }
}
class ChildClass extends ParentClass{
  function a(){
    echo 'Child';
  }
  function b(){
    echo 'Child B';
  }
}
$obj = new ChildClass();
$obj->a();
?>