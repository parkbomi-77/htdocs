<!-- 
추상

상속과 interface 즉 하위클래스에게 구현을 강제하는 기능을 하나로 갖고있는 클래스


 -->

 <?php
abstract class ParentClass
{
    public function a()
    {
        echo 'a';
    }
    public abstract function b(); // 자식이 반드시 구현하도록
}
class ChildClass extends ParentClass
{
    public function b()
    {
         
    }
}
?>

<!-- 템플릿 메소드 패턴  -->