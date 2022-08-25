<style>
.playbox-container {
    width: 100%;
    padding: 15px 0;
}
.playbox-container>div {
    margin: 2px 0;
}
    max-width: 900px;
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
.playbox-mall{
    width: 30%;
    background-color: #d2d2d3;
    line-height: 1.8rem;
    border-bottom: 1px solid #ffffff;
    border-radius: 3px;
    text-align: center;
    font-weight: 700;
    color: #8e8c8c;
}
.playbox-name {
    width: 44.5%;
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
    max-width: 900px;
    height: 30px;
}
.playbox-add:hover{
    cursor: pointer;
}
</style>


<?php

    global $wpdb, $post;
    // lesson 글에 해당하는 재생시간등록 결과 불러오기 
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_play_time where posts_lesson_id = $post->ID"));
    $num = count($results);
    $product = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list"));
    $productnum = count($product);

    // $product = array_filter($product, )


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
                <div class="playbox-mall">
                    <span>--shopping mall name--</span>
                </div>
                <div class="playbox-time">
                    <input type="text" id="" name="playtime[]" 
                    value="<?php echo esc_attr( $post->playtime ); ?>" placeholder="00:00" maxlength="8"
                    onKeyup="inputTimeColon(this)" required>
                </div>
                <div class="playbox-name">
                    <select name = "playname[]" onchange="product(this)">
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
        // 디비에 저장되어있는 제품 목록 꺼내올때
        for($i = 0; $i < $num; $i++){
            $productname = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where ID =".$results[$i]->product_list_id ));
            $code = $productname[0]->mall_code;
            $mallname = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code =".$code ));


            $add_play_box = ' <div class="playbox">
                                <div class="playbox-num">'.$results[$i]->play_idx.'</div>
                                <input type="hidden" name="playboxNum[]" value="'.$results[$i]->play_idx.'">
                                <div class="playbox-mall">
                                    <span>'.$mallname[0]->name.'</span>
                                </div>
                                <div class="playbox-time">
                                    <input type="text" id="" name="playtime[]" value="'.$results[$i]->product_time.'" maxlength="8" placeholder="00:00" onKeyup="inputTimeColon(this)" required>
                                </div>
                                <div class="playbox-name">
                                    <select name = "playname[]" onchange="product(this)">
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

    // 신규로 추가할때 
    new_pTag.setAttribute('class', 'playbox');
    new_pTag.innerHTML = 
                `<div class="playbox-num">${idxnum+1}</div>
                <input type="hidden" name="playboxNum[]" value=${idxnum+1}>
                <div class="playbox-mall">
                    <span>--shopping mall name--</span>
                </div>
                <div class="playbox-time">
                    <input type="text" id="" name="playtime[]" value="<?php echo esc_attr( $post->playtime ); ?>" maxlength="8" placeholder="00:00" onKeyup="inputTimeColon(this)" required>
                </div>
                <div class="playbox-name">
                    <select name = "playname[]" onchange="product(this)">
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
    }

    function product(e) {
        let playbox_mall = e.parentNode.parentNode.children[2];
        console.log(playbox_mall);
        $.ajax({
                url: "http://localhost:8888/wp-admin/edit-form-showmall.php",
                type: "post",
                dataType : 'json',
                data: {
                    ID : e.value,
                },
            }).done((data) => {
                playbox_mall.innerHTML = data;
            })
    }

</script>

