<!-- 
public은 부모, 자식 모두 접근가능
private 부모에서만 접근할수있음 
protected 부모자식간의 메소드들끼리는 서로 접근이 가능하면서 외부호출은 불가능하게 만들고싶을때 사용 
-->

<?php
class ParentClass{
  public $_public = '<h1>public</h1>';
  protected $_protected = '<h1>protected</h1>';
  private $_private = '<h1>private</h1>';
}
class ChildClass extends ParentClass{
  function callPublic(){
    echo $this->_public;
  }
  function callProtected(){
    echo $this->_protected;
  }
  function callPrivate(){
    echo $this->_private;
  }
}
$obj = new ChildClass();
// echo $obj->_public;
// echo $obj->_protected;
// echo $obj->_private;
// $obj->callPublic();
$obj->callProtected();
// $obj->callPrivate();
?>