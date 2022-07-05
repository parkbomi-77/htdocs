<!-- 
부모클래스가 갖고있던 메소드를 자식클래스가 똑같이 사용하고싶을때 혹은 덮어쓰기. 재정의 하고싶을때 사용하는 기법 override
형식이 갖게 메소드를 재정의 하면 덮어쓰기 된다. 

자식클래스에서 부모클래스를 칭하는 표현은 'parent::'
 -->


 <?php
class ParentClass{
  function callMethod($param){
    echo "<h1>Parent {$param}</h1>";
  }
}
class ChildClass extends ParentClass{
  function callMethod($param){
    parent::callMethod($param); //부모클래스에 있는 callMethod 메소드를 호출한다. 
    echo "<h1>Child {$param}</h1>";
  }
}
$obj = new ChildClass();
$obj->callMethod('method');
?>