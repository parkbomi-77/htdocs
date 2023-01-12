<style>
.playbox-container {
    width: 100%;
    padding: 15px 0;
}
.registrationtitle {
    font-size: large;
    font-weight: 700;
    padding: 0px 10px 10px;
}
.playbox{
    max-width: 900px;
    margin-left: 13px;
    display: flex;
    border-bottom: 1px solid white;
}
.playbox-num{
    text-align: center;
    width: 21px;
    line-height: 30px;
}
.playbox>div{
    margin: 0 1px;
}
.playbox-time {
    width: 15%;
}
.playbox-time input{
    width: 100%;
    text-align: center;
}
.playbox-mall{
    width: 25%;
    border-bottom: 1px solid #ffffff;

}
.playbox-mall select{
    width: 100%;
    text-align: center;
}
.playbox-category{
    width:15%;
}
.playbox-category select{
    width: 100%;
    text-align: center;
}

.playbox-name {
    width: 45%;
}
.playbox-name select{
    width: 100%;
    text-align: center;
}
.playbox-trash{
    font-size: 20px;
    line-height: 30px;
    padding-left: 5px;
}
.playbox-trash:hover{
    cursor: pointer;
}
/* 신규 추가 버튼 */
.playbox-add{
    display: flex;
    align-items: center;
    height: 30px;
    background-color: white;
    padding: 0 0 30px;
    border-bottom: 1px solid #c3c4c7;
    border-left: 1px solid #c3c4c7;
    border-right: 1px solid #c3c4c7;
}
.playbox-add:hover{
    cursor: pointer;
}
.playbox-list{
    padding: 30px 0 0;
    background-color: white;
    border-top: 1px solid #c3c4c7;
    border-left: 1px solid #c3c4c7;
    border-right: 1px solid #c3c4c7;

}
.addbtn{
    margin: 5px 22px 0 37px;
    width: 856px;
    background-color: #3c424a94;
    text-align: center;
    border-radius: 3px;
    line-height: 30px;
    color: whitesmoke;
}

</style>


<?php
    global $wpdb, $post;

    // 해당 lesson 포스터 영상 재생시간에 설정한 제품리스트가 있으면 play_time 테이블, 제품명, 쇼핑몰명 가져오기
    $sql = "SELECT t.ID, t.posts_lesson_id, t.play_idx, t.product_time, t.product_list_id, l.product_name, s.code, s.name, c.*
            FROM wp_play_time as t
            join wp_product_list as l
            on t.product_list_id = l.ID
            join wp_shoppingmall as s
            on l.mall_code = s.code
            join wp_product_category as c
            on l.ca_code = c.ca_code
            where t.posts_lesson_id =".$post->ID." and l.adv_state = 1";
    $resultrow = $wpdb->get_results($wpdb->prepare($sql));
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

    // 상품 중분류 option list 
    $categoryrow = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_product_category;"));
    $ca_option = '';
    for($i=0; $i<count($categoryrow); $i++){
        $ca_option = $ca_option."<option value =".$categoryrow[$i]->ca_code.">".$categoryrow[$i]->category."</option>";
    }

    // 해당 lesson 포스터 영상에 제품 첫 등록일때
    if(!$resultrow){ ?> 
    <div class="playbox-container">
        <div class="registrationtitle">vimeo 영상시간 : 상품등록</div>
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
                    <select name = "playmall[]" onchange="loadcatagory(this)">
                        <option value = "" selected>쇼핑몰 선택</option>
                        <?php
                            echo $sp_option;
                        ?>
                    </select>
                </div>
                <div class="playbox-category">
                    <select name = "category[]" onchange="productfilter(this)">
                        <option value = "" selected>중분류 선택</option>
                    </select>
                </div>

                <div class="playbox-name">
                    <select name = "playname[]" >
                        <option value = "" selected>제품 선택</option>
                    </select>
                </div>

                <div class="playbox-trash" onclick="close_boxTag(this)"><i class="fas fa-times"></i></div>
            </div>
        </div>
        <div class="playbox-add" onclick="create_boxTag()">
            <div class="addbtn">+</div>
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
                                    <select name = "playmall[]" onchange="loadcatagory(this)">
                                        <option value = "'.$resultrow[$i]->code.'" selected>'.$resultrow[$i]->name.'</option>
                                        '.$sp_option.'
                                    </select>
                                </div>
                                <div class="playbox-category">
                                    <select name = "category[]" onchange="productfilter(this)">
                                        <option value = "'.$resultrow[$i]->ca_code.'" selected>'.$resultrow[$i]->category.'</option>
                                        '.$ca_option.'
                                    </select>
                                </div>
                                <div class="playbox-name">
                                    <select name = "playname[]">
                                        <option value = "'.$resultrow[$i]->product_list_id.'" selected>'.$resultrow[$i]->product_name.'</option>
                                    </select>
                                </div>
                                <div class="playbox-trash" onclick="close_boxTag(this)"><i class="fas fa-times"></i></div>
                            </div>';
            $play_box = $play_box.$add_play_box ;

        }


        echo ('<div class="playbox-container">
                    <div class="registrationtitle">vimeo 영상시간 : 상품등록</div>
                    <div class="playbox-list">
                        '.$play_box.'
                    </div>
                    <div class="playbox-add" onclick="create_boxTag()">
                        <div class="addbtn">+</div>
                    </div>
                </div>');

    }
    ?>



<script src="https://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
    let idxnum = <?php echo $num; ?>;

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
                    <select name = "playmall[]" onchange="loadcatagory(this)">
                        <option value = "" selected>쇼핑몰 선택</option>
                        <?php
                            echo $sp_option;
                        ?>
                    </select>
                </div>
                <div class="playbox-category">
                    <select name = "category[]" onchange="productfilter(this)">
                        <option value = "" selected>중분류 선택</option>
                    </select>
                </div>
                <div class="playbox-name">
                    <select name = "playname[]" onchange="" class= "product-list">
                            <option value = "" selected>제품 선택</option>
                    </select>
                </div>
                <div class="playbox-trash" onclick="close_boxTag(this)"><i class="fas fa-times"></i></div>`
    
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
    function loadcatagory(event) {
        let nextBox = event.parentElement.nextElementSibling.firstElementChild;
        nextBox.innerHTML = '<?php echo $ca_option?>';
    }

    function productfilter(event){
        // 상품 나열하는 El 
        let nextBox = event.parentElement.nextElementSibling.firstElementChild;
        // 쇼핑몰 값
        let mall = event.parentElement.previousElementSibling.firstElementChild;

        // 중분류
        $.ajax({
            url: "http://localhost:8888/wp-admin/edit-form-productlist.php",
            type: "post",
            dataType : 'json',
            data: {
                mall_code : mall.value, // 쇼핑몰코드
                ca_code : event.value, // 중분류 코드
            },
        }).done((data) => {
            nextBox.innerHTML = data;
        })
    }


</script>

