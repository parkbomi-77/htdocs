<!-- 객체에 메소드나 변수를 추가하고싶을때,
근데 내가 만든 객체가 아니라 변경하기가 어려울때 사용하는것이 상속
기반이 되는 오브젝트 : Parent Object
상속받아서 새롭게 만들어진 오브젝트 : Child Object
차일드 오브젝트를 만들어서 기존의 객체엔 영향을 주지않고
원하는 메소드나 변수를 추가하여 사용할 수 있다.

extends를 이용하여 상속받을 수 있다. 
-->

<?php
class Animal{
    function run(){
        print('running...');
    }
    function breathe(){
        print('breathing...');
    }
}

class Human extends Animal{
    function think(){
        print('thinking...<br>');
    }
}

$dog = new Human();
$dog->breathe();

?>