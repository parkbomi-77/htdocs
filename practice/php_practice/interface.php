<?php
interface ContractInterface
{
    public function promiseMethod(array $param):int; //인자의 타입은 array이고, 리턴값은 int 
}
interface ContractInterface2
{
    public function promiseMethod2(array $param):int;
}

 // ConcreateClass는 ContractInterface, ContractInterface2에 정의되어있는 메소드를 반드시 구현해야한다. 
 // 상속은 하나밖에 안되고, 인터페이스는 2개도 가능하다 
class ConcreateClass implements ContractInterface, ContractInterface2
{
    public function promiseMethod(array $param):int
    {
        return 1;
    }
    public function promiseMethod2(array $param):int
    {
        return 1;
    }
}
$obj = new ConcreateClass();
$obj->promiseMethod([1,2]);