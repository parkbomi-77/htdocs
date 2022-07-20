<style>
.playbox-container {
    width: 100%;
    padding: 15px 0;
}
.playbox-container>div {
    margin: 2px 0;
}
.playbox{
    width: 900px;
    display: flex;
    align-items : center;
}
.playbox-num{
    text-align: center;
    width: 21px;
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
    width: 45%;
}
.playbox-name select{
    width: 100%;
}
.playbox-trash{
    font: 30px;
}
.playbox-trash:hover{
    cursor: pointer;
}
/* 신규 추가 버튼 */
.playbox-add{
    display: flex;
    justify-content: center;
    align-items : center;
    border : 1px solid rgba(23, 27, 29, 0.4);
    border-radius: 5px;
    width: 606px;
    height: 30px;
}
</style>


<?php

    global $wpdb, $post;
    // lesson 글에 해당하는 재생시간등록 결과 불러오기 
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_play_time where posts_lesson_id = $post->ID"));
    $num = count($results);
    $product = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list"));
    $productnum = count($product);
    // $zzz =  $product[0]->ID;

    $option = '';
    for($i=0; $i<$productnum; $i++){
        $add_option = '<option value = "'.$product[$i]->ID.'">'.$product[$i]->product_name.'</option>';
        $option = $option.$add_option;
    }

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
                    value="<?php echo esc_attr( $post->playtime ); ?>" placeholder="00:00" maxlength="8"
                    onKeyup="inputTimeColon(this)" required>
                </div>
                <div class="playbox-name">
                    <select name = "playname[]">
                        <option value = "" selected>제품 선택</option>
                        <?php
                            echo $option;
                        ?>
                    </select>
                </div>
                <div class="playbox-trash" onclick="close_boxTag(this)" style="font-size:23px;">✖︎</div>
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
        $option = '';
        for($i=0; $i<$productnum; $i++){
            $add_option = '<option value = "'.$product[$i]->ID.'">'.$product[$i]->product_name.'</option>';
            $option = $option.$add_option;
        }

        $play_box = '';
        for($i = 0; $i < $num; $i++){
            $productname = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where ID =".$results[$i]->product_list_id ));

            $add_play_box = ' <div class="playbox">
                                <div class="playbox-num">'.$results[$i]->play_idx.'</div>
                                <input type="hidden" name="playboxNum[]" value="'.$results[$i]->play_idx.'">
                                <div class="playbox-time">
                                    <input type="text" id="" name="playtime[]" value="'.$results[$i]->product_time.'" maxlength="8" placeholder="00:00" onKeyup="inputTimeColon(this)" required>
                                </div>
                                <div class="playbox-name">
                                    <select name = "playname[]">
                                        <option value = "'.$results[$i]->product_list_id.'" selected>'.$productname[0]->product_name.'</option>
                                        '.$option.'
                                    </select>
                                </div>
                                <div class="playbox-trash" onclick="close_boxTag(this)" style="font-size:23px;">✖︎</div>
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
    var idxnum = <?php echo $num; ?>;
    function create_boxTag(){
    let playboxList = document.querySelector('.playbox-list');
    let new_pTag = document.createElement('div');
    
    new_pTag.setAttribute('class', 'playbox');
    new_pTag.innerHTML = 
                `<div class="playbox-num">${idxnum+1}</div>
                <input type="hidden" name="playboxNum[]" value=${idxnum+1}>
                <div class="playbox-time">
                    <input type="text" id="" name="playtime[]" value="<?php echo esc_attr( $post->playtime ); ?>" maxlength="8" placeholder="00:00" onKeyup="inputTimeColon(this)" required>
                </div>
                <div class="playbox-name">
                    <select name = "playname[]">
                            <option value = "" selected>제품 선택</option>
                            <?php
                                echo $option;
                            ?>
                    </select>
                </div>
                <div class="playbox-trash" onclick="close_boxTag(this)" style="font-size:23px;">✖︎</div>`
    
     playboxList.appendChild(new_pTag);
    
     idxnum++;
    }

    function close_boxTag(e){
        let playboxList = document.querySelector('.playbox-list');
        playboxList.removeChild(e.parentNode);
        idxnum = idxnum-1;
    }


    // 재생시간 콜론(:) 으로 입력받는 함수 
    function inputTimeColon(time) {
        let replaceTime = time.value.replace(/\:/g, "");
        
        let minute = replaceTime.substring(0, 2);      
        let seconds = replaceTime.substring(2, 4);    

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
        // if(time.length === 5){
        //     let replaceTime = time.value.replace(/\:/g, "");
    
        //     let minute = replaceTime.substring(0, 2);      
        //     let seconds = replaceTime.substring(2, 4);    
    
        //     if(isFinite(minute + seconds) == false) {
        //         alert("문자는 입력하실 수 없습니다.");
        //         time.value = "00:00";
        //         return false;
        //     }
    
        //     if(seconds > 59 ) {
        //             alert("초는 1분단위 아래로 입력해주세요.");
        //             return false;
        //     }
        //     time.value = minute + ":" + seconds;
        // }else {
        //     let replaceTime = time.value.replace(/\:/g, "");
    
        //     let hour = replaceTime.substring(0, 2);      
        //     let minute = replaceTime.substring(2, 4);      
        //     let seconds = replaceTime.substring(4, 6);    
    
        //     if(isFinite(minute + seconds) == false) {
        //         alert("문자는 입력하실 수 없습니다.");
        //         time.value = "00:00";
        //         return false;
        //     }
        //     if(minute > 59 ) {
        //             alert("분은 60분 단위 아래로 입력해주세요.");
        //             return false;
        //     }
    
        //     if(seconds > 59 ) {
        //             alert("초는 60초 단위 아래로 입력해주세요.");
        //             return false;
        //     }
        //     time.value = hour + ":" + minute + ":" + seconds;
        // }

    }

</script>

