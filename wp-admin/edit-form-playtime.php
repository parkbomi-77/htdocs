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
    width: 70%;
}
.playbox-name input{
    width: 100%;
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
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_play_time where posts_lesson_id = $post->ID"));


    // 새로 등록할시 !  

        $num = count($results);
    if(!$results){
?> 
    <div class="playbox-container">
        <p>vimeo 영상시간 : 상품등록</p>
        <div class="playbox-list">
            <div class="playbox">
                <div class="playbox-num">1</div>
                <input type="hidden" name="playboxNum[]" value="1">
                <div class="playbox-time">
                    <input type="text" id="" name="playtime[]" value="<?php echo esc_attr( $post->playtime ); ?>" placeholder="00:00">
                </div>
                <div class="playbox-name">
                    <input type="text" id="" name="playname[]" placeholder="제품명 입력란(40)" value="<?php echo esc_attr( $post->playname ); ?>">
                </div>
                <div class="playbox-trash" onclick="close_boxTag()">✖︎</div>
            </div>
        </div>
        <div class="playbox-add" onclick="create_boxTag()">
            <div>+</div>
            <div>신규</div>
        </div>
    </div>
    <!-- 기존 설정 수정할시 화면에 띄우기 -->
    <?php } else { 
    // $num = count($results);
        $play_box = '';
        for($i = 0; $i < $num; $i++){
            $add_play_box = ' <div class="playbox">
                                <div class="playbox-num">'.$results[$i]->play_idx.'</div>
                                <input type="hidden" name="playboxNum[]" value="'.$results[$i]->play_idx.'">
                                <div class="playbox-time">
                                    <input type="text" id="" name="playtime[]" value="'.$results[$i]->product_time.'" placeholder="00:00">
                                </div>
                                <div class="playbox-name">
                                    <input type="text" id="" name="playname[]" placeholder="제품명 입력란(40)" value=" '.$results[$i]->product_name.'">
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
    console.log(Count)

    function create_boxTag(){
    let playboxList = document.querySelector('.playbox-list');
    let new_pTag = document.createElement('div');
    
    new_pTag.setAttribute('class', 'playbox');
    new_pTag.innerHTML = 
                `<div class="playbox-num">${Count}</div>
                <input type="hidden" name="playboxNum[]" value=${Count}>
                <div class="playbox-time">
                    <input type="text" id="" name="playtime[]" value="<?php echo esc_attr( $post->playtime ); ?>" placeholder="00:00">
                </div>
                <div class="playbox-name">
                    <input type="text" id="" name="playname[]" placeholder="제품명 입력란(40)" value="<?php echo esc_attr( $post->playname ); ?>">
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

</script>

