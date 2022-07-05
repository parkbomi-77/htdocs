<!-- 네임스페이스. 다른 언어에서는 패키지라고도 불림 -->

<?php
// 하나의 클래스를 정의하면 이 클래스명은 php 파일에서 한번밖에 사용을 못함
// 같은 이름의 클래스가 하나의 파일안에 공존하는 경우 
// 이름의 충돌을 해결하기위해 쓰이는 것이 네임스페이스. 


namespace greeting\en;
class Hi{
  function __construct(){
    echo '<h1>hi</h1>';
  }
}
namespace greeting\ko;
class Hi{
  function __construct(){
    echo '<h1>안녕</h1>';
  }
}
use \greeting\en\Hi as HiEn;
use \greeting\ko\Hi as HiKo;
new HiEn();
new HiKo();

?>

