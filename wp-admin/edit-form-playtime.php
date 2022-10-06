<style>
.playbox-container {
    width: 100%;
    padding: 15px 0;
}
.playbox-container>div {
    margin: 2px 0;
}
.playbox{
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
.playbox-mall select{
    width: 100%;
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

    // 해당 lesson 포스터 영상 재생시간에 설정한 제품리스트가 있으면 play_time 테이블, 제품명, 쇼핑몰명 가져오기
    $resultrow = $wpdb->get_results($wpdb->prepare("
        SELECT t.ID, t.posts_lesson_id, t.play_idx, t.product_time, t.product_list_id, l.product_name, s.code, s.name
        FROM wp_play_time as t
        join wp_product_list as l
        on t.product_list_id = l.ID
        join wp_shoppingmall as s
        on l.mall_code = s.code
        where t.posts_lesson_id =".$post->ID
    ));
    $num = count($resultrow);

    // 광고활성화 되어있는 쇼핑몰 리스트 불러오기
    $shoppingmall = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where state = 1"));
    $shoppingmallnum = count($shoppingmall);
    // 제품 선택 리스트 생성. 옵션태그. 
    $sp_option = '';
    for($i=0; $i<$shoppingmallnum; $i++){
        $add_option = '<option value = "'.$shoppingmall[$i]->code.'">'.$shoppingmall[$i]->name.'</option>';
        $sp_option = $sp_option.$add_option;
    }



    // 해당 lesson 포스터 영상에 제품 첫 등록일때
    if(!$resultrow){ ?> 
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

                <div class="playbox-mall">
                    <select name = "playmall[]" onchange="productfilter(this)">
                        <option value = "" selected>쇼핑몰 선택</option>
                        <?php
                            echo $sp_option;
                        ?>
                    </select>
                </div>

                <div class="playbox-name">
                    <select name = "playname[]" >
                        <option value = "" selected>제품 선택</option>
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

    <!-- 해당 lesson 포스터 영상에 제품이 등록되어있을때 -->
    <?php } else { 
        // play_box -> 영상재생시간에 제품 등록하는 행(row)들 
        $play_box = '';

        // num -> 해당 포스터에 등록된 제품 수 
        for($i = 0; $i < $num; $i++){
            $add_play_box = '<div class="playbox">
                                <div class="playbox-num">'.($i+1).'</div>
                                <input type="hidden" name="playboxNum[]" value="'.$resultrow[$i]->play_idx.'">
                                <div class="playbox-time">
                                    <input type="text" id="" name="playtime[]" value="'.$resultrow[$i]->product_time.'" maxlength="8" placeholder="00:00" onKeyup="inputTimeColon(this)" required>
                                </div>
                                <div class="playbox-mall">
                                    <select name = "playmall[]" onchange="productfilter(this)">
                                        <option value = "'.$resultrow[$i]->code.'" selected>'.$resultrow[$i]->name.'</option>
                                        '.$sp_option.'
                                    </select>
                                </div>
                                <div class="playbox-name">
                                    <select name = "playname[]">
                                        <option value = "'.$resultrow[$i]->product_list_id.'" selected>'.$resultrow[$i]->product_name.'</option>
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

    // 신규추가버튼 함수
    function create_boxTag(){
    let playboxList = document.querySelector('.playbox-list');
    let new_pTag = document.createElement('div');

    // 신규로 추가할때 
    new_pTag.setAttribute('class', 'playbox');
    new_pTag.innerHTML = 
                `<div class="playbox-num">${idxnum+1}</div>
                <input type="hidden" name="playboxNum[]" value=${idxnum+1}>
                <div class="playbox-time">
                    <input type="text" id="" name="playtime[]" value="<?php echo esc_attr( $post->playtime ); ?>" maxlength="8" placeholder="00:00" onKeyup="inputTimeColon(this)" required>
                </div>
                <div class="playbox-mall">
                    <select name = "playmall[]" onchange="productfilter(this)">
                        <option value = "" selected>쇼핑몰 선택</option>
                        <?php
                            echo $sp_option;
                        ?>
                    </select>
                </div>
                <div class="playbox-name">
                    <select name = "playname[]" onchange="" class= "product-list">
                            <option value = "" selected>제품 선택</option>
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
        let numtag = document.querySelectorAll(".playbox-num")
        // num 다시 정렬 
        for(let i=0; i<numtag.length; i++){
            console.log(numtag[i]);
            numtag[i].innerHTML = i+1;
        }
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

    // 쇼핑몰
    // 벳스쿨 쇼핑몰일 경우 or 타 쇼핑몰일 경우
    function productfilter(event){
        let playboxName = event.parentElement.nextElementSibling.firstElementChild;
        $.ajax({
                url: "http://localhost:8888/wp-admin/edit-form-productlist.php",
                type: "post",
                dataType : 'json',
                data: {
                    code : event.value,
                },
            }).done((data) => {
                playboxName.innerHTML = data;
            })
    }


</script>

