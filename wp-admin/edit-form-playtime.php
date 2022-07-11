<style>
.playbox-container {
    width: 100%;
    padding: 15px 0;
}
.playbox-container>div {
    margin: 2px 0;
}
.playbox{
    width: 70%;
    display: flex;
    align-items : center;
}
.playbox>div{
    margin: 0 1px;
}
.playbox-time {
    width: 20%;
}
.playbox-time input{
    width: 100%;
}
.playbox-name {
    width: 30%;
}
.playbox-name input{
    width: 100%;
}
.playbox-link{
    width: 70%;
}
.playbox-link input{
    width: 100%;
}
.playbox-trash{
    font: 30px;
}
/* 신규 추가 버튼 */
.playbox-add{
    display: flex;
    justify-content: center;
    align-items : center;
    border : 1px solid rgba(23, 27, 29, 0.4);
    border-radius: 5px;
    width: 70%;
    height: 30px;
}
</style>


<?php

    global $wpdb, $post;
    // lesson 글에 해당하는 재생시간등록 결과 불러오기 
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_play_time where posts_lesson_id = $post->ID"));
    $num = count($results);
    
    // 새로 등록할시 !  
    if(!$results){
?> 
    <div class="playbox-container">
        <p>vimeo 영상시간 : 상품등록</p>
        <div class="playbox-list">
            <div class="playbox">
                <div class="playbox-num">1</div>
                <input type="hidden" name="playboxNum[]" value="1">
                <div class="playbox-time">
                    <input type="text" id="" name="playtime[]" 
                    value="<?php echo esc_attr( $post->playtime ); ?>" placeholder="00:00" maxlength="5"
                    onKeyup="inputTimeColon(this)" required>
                </div>
                <div class="playbox-name">
                    <input type="text" id="" name="playname[]" placeholder="제품명 입력란(40)" value="<?php echo esc_attr( $post->playname ); ?>" required>
                </div>
                <div class="playbox-link">
                    <input type="text" id="" name="playlink[]" placeholder="제품링크(40)" value="<?php echo esc_attr( $post->playlink ); ?>" required>
                </div>
                <div class="playbox-trash" onclick="close_boxTag()">✖︎</div>
            </div>
        </div>
        <div class="playbox-add" onclick="create_boxTag()">
            <div>+</div>
            <div>신규</div>
        </div>
    </div>
    <?php $num++; ?>
    <!-- 기존 설정 수정할시 화면에 띄우기 -->
    <?php } else { 
    // play_box를 붙히는 형식 
        $play_box = '';
        for($i = 0; $i < $num; $i++){
            $add_play_box = ' <div class="playbox">
                                <div class="playbox-num">'.$results[$i]->play_idx.'</div>
                                <input type="hidden" name="playboxNum[]" value="'.$results[$i]->play_idx.'">
                                <div class="playbox-time">
                                    <input type="text" id="" name="playtime[]" value="'.$results[$i]->product_time.'" maxlength="5" placeholder="00:00" onKeyup="inputTimeColon(this)" required>
                                </div>
                                <div class="playbox-name">
                                    <input type="text" id="" name="playname[]" placeholder="제품명 입력란(40)" value="'.$results[$i]->product_name.'" required>
                                </div>
                                <div class="playbox-link">
                                    <input type="text" id="" name="playlink[]" placeholder="제품링크(40)" value="'.$results[$i]->product_link.'" required>
                                </div>
                                <div class="playbox-trash" onclick="close_boxTag()">✖︎</div>
                            </div>';
            $play_box = $play_box.$add_play_box ;
        }
            echo ('<div class="playbox-container">
                        <p>vimeo 영상시간 : 상품등록</p>
                        <div class="playbox-list">
                            '.$play_box.'
                        </div>
                        <div class="playbox-add" onclick="create_boxTag()">
                            <div>+</div>
                            <div>신규</div>
                        </div>
                    </div>');

    }
    ?>



<script src="https://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

    var Count = <?php echo $num; ?>+1;

    function create_boxTag(){
    let playboxList = document.querySelector('.playbox-list');
    let new_pTag = document.createElement('div');
    
    new_pTag.setAttribute('class', 'playbox');
    new_pTag.innerHTML = 
                `<div class="playbox-num">${Count}</div>
                <input type="hidden" name="playboxNum[]" value=${Count}>
                <div class="playbox-time">
                    <input type="text" id="" name="playtime[]" value="<?php echo esc_attr( $post->playtime ); ?>" maxlength="5" placeholder="00:00" onKeyup="inputTimeColon(this)" required>
                </div>
                <div class="playbox-name">
                    <input type="text" id="" name="playname[]" placeholder="제품명 입력란(40)" value="<?php echo esc_attr( $post->playname ); ?>" required>
                </div>
                <div class="playbox-link">
                    <input type="text" id="" name="playlink[]" placeholder="제품링크(40)" value="<?php echo esc_attr( $post->playlink ); ?>" required>
                </div>
                <div class="playbox-trash" onclick="close_boxTag()">✖︎</div>`
    
     playboxList.appendChild(new_pTag);
    
     Count++;
    }

    function close_boxTag(){
        let playboxList = document.querySelector('.playbox-list');
        let deletebox = document.querySelector('.playbox-list').lastChild;
        playboxList.removeChild(deletebox);
        Count = Count-1;
    }


    // 재생시간 콜론(:) 으로 입력받는 함수 
    function inputTimeColon(time) {
        let replaceTime = time.value.replace(/\:/g, "");

        let minute = replaceTime.substring(0, 2);      // 선언한 변수 hours에 시간값을 담는다.
        let seconds = replaceTime.substring(2, 4);    // 선언한 변수 minute에 분을 담는다.

        if(isFinite(minute + seconds) == false) {
            alert("문자는 입력하실 수 없습니다.");
            time.value = "00:00";
            return false;
        }

        if(seconds > 59 ) {
                alert("초는 1분단위 아래로 입력해주세요.");
                return false;
        }
        time.value = minute + ":" + seconds;
    }

</script>

