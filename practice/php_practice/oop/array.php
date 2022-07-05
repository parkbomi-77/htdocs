<!-- 배열이라는 데이터를 다루는법 
1. 함수를 이용한다
    함수는 독립적인 존재이기때문에 각각의 함수를 호출할때마다 인자를 일일히 넣어줘야함
2. 객체를 이용한다 
    객체를 처음지정할때 넣어준 상태를 기반으로 메소드가 적용된다 
-->


<h1>Funcion Style</h1>
<?php
// $adata = ['a', 'b', 'c'];
$adata = array('a', 'b', 'c');
// 배열을 화면에 출력하는 방법 
// $adata에 있는 값들을 하나하나 꺼내서 item이라는 변수로서 중괄호안에서 사용할수 있도록 해줌
foreach($adata as $item){
    echo $item.'<br>';
}
var_dump(count($adata));
?>

<h1>Object Style</h1>
<?php
// 배열을 객체로
$odata = new ArrayObject(array('a', 'b', 'c'));
foreach($adata as $item){
    echo $item.'<br>';
}
var_dump($odata->count());
?>

