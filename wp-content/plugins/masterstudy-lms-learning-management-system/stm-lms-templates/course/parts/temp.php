<div id="box">
    <style>
        .stm-lms-course__content{
            display:flex;
            justify-content: center;
        }
        .sample111{
            width: 1200px;
        }
        #box {
            width: 28%;
            background-color: rgb(17, 17, 19);
            border: 2px solid rgb(17, 17, 19);
            margin-top: 135px;
            margin-left: -15px;
            margin-right: 13px;
        }
        @media (max-width: 1245px) {
            .stm-lms-course__content{
                flex-direction: column;
                align-items: center;
            }
            .sample111{
                width: 100%;
            }
            #box {
                width: 92%;
                margin: 0px;
            }
            .sample111>.container {
                height: 55px;
            }
        }
        #box>p i {
            font-size: 17px;
        }
        #box>p{
            padding:9px 15px 5px;
            font-size: 18px;
            background-color: black;
            margin-bottom: 0;
            color: #e1b475;
            font-weight: 600;
        }
        
        #box>div {
            display: block;
            background-color: rgb(19, 21, 24);
            padding: 10px 5px 2px 17px;
            border-top: solid black;
            font-size: 15px;
            font-weight: 500;
            color: #d6d6d6;
            height: 90px;
        }
        #box>div:hover {
            background-color: rgb(59, 62, 59);
        }
        .box-flex{
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
        .box-name{
            display:flex;
            align-items: flex-end;
            justify-content:space-between;
            width: 100%;
            margin-bottom:5px;
            flex:1;
        }
        .box-shop{
            line-height:25px;
            font-size:smaller;
        }
        .box-name .box-title{
            line-height: 22px;
            font-size: initial;
        }
        .box-time{
            font-size: inherit;
            color: #8e8e8e;
            line-height: 40px;
            margin-right: 14px;
            margin-top:0px
        }
        .box-cart{
            /* display: flex; */
            font-size: 20px;
            width: 150px;
        }

        .box-cart .quantity{
            display: none;
        }
        .box-cart i{
            color: #d6d6d6;
        }
        .box-cart button{
            background-color: hwb(14deg 23% 73% / 88%);
            border: 0;
            height: 67px;
            min-width: 50px;
            width: 70px;
            font-size: 13px;
            border-radius: 12px;
            font-weight: 600;
        }
        .box-cart button:last-child {
            background-color: rgb(235,183,48);
            font-weight: 600;
            color: white;
            padding: 0;
        }

        @media screen and (max-width: 1632px) {

        }
        .vetcart{
            display: inline;
        }
        /* modal창 */
        .none {
            display:none;
        }
        .vetmodal {
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */                                  
            background-color: rgb(0,0,0); /* Fallback color */   
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */   
            padding-top: 30%;
        }
        .vetmodal>div{
            width: 380px;
            height: 200px;
            background-color: #e3e3e3;
            margin: auto;
            padding: 3%;
            border-radius: 2px;
        }
        .vetmodal p{
            color: grey;
            margin-bottom: 0px;
            line-height: 1.4em;
            font-size: smaller;
        }
        .vetmodal p i{
            color: #22bd61;
        }
        .vetmodal .buttons{
            display: inline-block;
            float: right;
            margin-top: 10px;
            font-size: smaller;
        }
        .close-btn{
            border: none;
            background-color: #e3e3e3;
            color: #424242;
        }
        .ok-btn{
            border: none;
            background-color: #eb9f38;
            color: #f1f1f1;
            border-radius: 4px;
            padding: 2px 18px;
        }
    </style>

    <p> <i class="fa-solid fa-store"></i> store</p>

    <?php
        // defined('ABSPATH') || exit;
        global $wpdb, $post;

        // $classname = "WC_Product_Simple";
        // $product = new $classname(10685);

        //  do_action( 'woocommerce_' . $product->get_type() . '_add_to_cart' );

        // lesson 글에 해당하는 재생시간등록 결과 && 현재 광고중인 제품만 노출되도록 하기
        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_play_time inner join wp_product_list on wp_play_time.product_list_id = wp_product_list.ID where wp_product_list.adv_state = 1 and wp_play_time.posts_lesson_id = $post->ID"));
        $current_user = wp_get_current_user();

        if($results){
            $num = count($results);
            for($i=0; $i<$num; $i++){ 
                $productname = $wpdb->get_results($wpdb->prepare("SELECT a.*, b.name from wp_product_list as a
                                                                join wp_shoppingmall as b
                                                                on a.mall_code = b.code where ID =".$results[$i]->product_list_id));
                if($productname[0]->mall_code === "1029"){ // 벳스쿨 제품일경우 벳스쿨 장바구니로 ?>
                    <?php      
                        $classname = "WC_Product_Simple";
                        $product = new $classname($productname[0]->product_code);
                    ?>
                    <div id= <?php echo $results[$i]->play_idx ?> style="display:none"> 
                        <form id="wishform" method='POST' target="iframe1"
                        action='#'>
                            <iframe id="iframe1" name="iframe1" style="display:none"></iframe>
                            <div class="box-flex">
                                <div class="box-name">
                                    <div>
                                        <div class="box-shop" style="color: #d6ba4a;">
                                            <?php echo $productname[0]->name ?> 
                                        </div>
                                        <div class="box-title">
                                            <?php echo $productname[0]->product_name ?> 
                                        </div>
                                    </div>
                                    <div class="box-time"> 
                                        time
                                    </div>
                                </div>
                                <div class="box-cart">
                                    <input type="hidden" name="user_id" value="<?php echo $current_user->ID ?>">
                                    <input type="hidden" name="item_id" value="<?php echo $results[$i]->product_list_id ?>">
                                    <input type="hidden" class="p_code" name="product_code" value="<?php echo $productname[0]->product_code ?>">
                                    <!-- 쇼핑몰로 바로가기 -->
                                    <button type="submit" formaction="/page-shop.php">바로가기</button> 
                                    <!-- 장바구니 담기 -->
                                    <div class="vetcart">
                                        <button type='button' onclick="vetbtn(this)">장바구니</button>
                                    </div>

                                    <!-- <button onclick="vetsubmit()"><i class="fa-solid fa-cart-shopping"></i></button> -->
                                </div>
                            </div>
                        </form>
                    </div>


                <?php
                } else { // 타 사이트 제품일 경우 임시 장바구니로 ?>
                    <div id= <?php echo $results[$i]->play_idx ?> style="display:none"> 
                    <form id="wishform" method='POST' target="iframe1" 
                    action='/wp-content/plugins/masterstudy-lms-learning-management-system/stm-lms-templates/course/parts/temp_submit.php'>
                        <iframe id="iframe1" name="iframe1" style="display:none"></iframe>
                        <div class="box-flex">
                            <div class="box-name">
                                <div>
                                    <div class="box-shop" style="color: cadetblue;">
                                        <?php echo $productname[0]->name ?> 
                                    </div>
                                    <div class="box-title">
                                        <?php echo $productname[0]->product_name ?> 
                                    </div>
                                </div>
                                <div class="box-time"> 
                                    time
                                </div>
                            </div>
                            <div class="box-cart">
                                <!-- 유저 아이디, 상품명 디비로 전달 -->
                                <input type="hidden" name="user_id" value="<?php echo $current_user->ID ?>">
                                <input type="hidden" name="item_id" value="<?php echo $results[$i]->product_list_id ?>">
                                <input type="hidden" name="product_name" value="<?php echo $productname[0]->product_name ?>">
                                <input type="hidden" name="course_name" value="<?php echo $course->ID; ?>">
                                <input type="hidden" name="lessons_name" value="<?php echo $post->ID; ?>">

                                <!-- 쇼핑몰로 바로가기 -->
                                <button type="submit" formaction="/page-shop.php">바로가기</button> 
                                <!-- 장바구니 담기 -->
                                <button type='submit' onclick="otherbtn(this)">장바구니</button>
                            </div>
                        </div>
                    </form>
                    </div>
                <?php 
                } ?>
                
    <?php   }
        } ?>
</div>

<div id="vetcart_modal" class="vetmodal none">
    <div>
        <p>이미 장바구니에 담은 상품입니다</p>
        <p>메뉴탭 - 장바구니 - 벳스쿨 장바구니</p>
        <p>장바구니로 이동하시겠습니까?</p>
        <div class="buttons">
            <button class="close-btn" onclick="">CLOSE</button>
            <button class="vet ok-btn">CART</button>
        </div>
    </div>
</div>

<div id="new_vetcart_modal" class="vetmodal none">
    <div>
        <p>상품을 장바구니에 담았습니다 <i class="far fa-check-circle"></i></p>
        <p>메뉴탭 - 장바구니 - 벳스쿨 장바구니</p>
        <p>장바구니로 이동하시겠습니까?</p>
        <div class="buttons">
            <button class="close-btn">CLOSE</button>
            <button class="vet ok-btn">CART</button>
        </div>
    </div>
</div>

<div id="othercart_modal" class="vetmodal none">
    <div>
        <p>이미 장바구니에 담은 상품입니다</p>
        <p>메뉴탭 - 장바구니 - 제휴 쇼핑몰 장바구니</p>
        <p>장바구니로 이동하시겠습니까?</p>
        <div class="buttons">
            <button class="close-btn" onclick="">CLOSE</button>
            <button class="ok-btn">CART</button>
        </div>
    </div>
</div>

<div id="new_othercart_modal" class="vetmodal none">
    <div>
        <p>상품을 장바구니에 담았습니다 <i class="far fa-check-circle"></i></p>
        <p>메뉴탭 - 장바구니 - 제휴 쇼핑몰 장바구니</p>
        <p>장바구니로 이동하시겠습니까?</p>
        <div class="buttons">
            <button class="close-btn">CLOSE</button>
            <button class="ok-btn">CART</button>
        </div>
    </div>
</div>

<script src="https://player.vimeo.com/api/player.js"></script>
<script src="https://code.jquery.com/jquery-latest.js"></script>
<script> 
    const iframe = document.querySelector("iframe");
    const player = new Vimeo.Player(iframe);

    let TotalPlayingTime = 0; //전체 재생시간 
    player.getDuration().then((duration) => {
        TotalPlayingTime = duration;
    })

    const getCurrentTime = () => {
        player.getCurrentTime().then((currentTime) => { // 현재 재생시간

        currentTime = Math.round(currentTime) // 반올림하여 정수로 통일

            <?php 
            $num = count($results);
            for($i=0; $i<$num; $i++){ ?>
                document.getElementById(<?= $results[$i]->play_idx ?>).style.display = "none"
            <?php }
            for($i=0; $i<$num; $i++){ 
            $time = $results[$i]->product_time;
                $minute = substr($time, 0, 2); 
                $seconds = substr($time, 3, 2); 
                $play_time = $minute*60 + $seconds; ?> //초로 변환하기 
                if(<?= $play_time ?> <= currentTime && currentTime <= <?= $play_time+4 ?>) { // 시작시간, 끝시간 설정 
                    let block = document.getElementById(<?= $results[$i]->play_idx ?>);
                    block.style.display = "block";
                    block.querySelector('.box-time').innerText = "<?php echo $time ?>"
                }
            <?php
            } ?>
        });   
    }
   let interval = setInterval(getCurrentTime, 500);

   let vetmodal = document.querySelector("#vetcart_modal");
   let newvetmodal = document.querySelector("#new_vetcart_modal");

   let othermodal = document.querySelector("#othercart_modal");
   let newothermodal = document.querySelector("#new_othercart_modal");


    function vetbtn(event) {
        let product_code = event.parentElement.parentElement.querySelector(".p_code").value;

        $.ajax({
            method: "POST",
            url: "/wp-content/plugins/masterstudy-lms-learning-management-system/stm-lms-templates/course/parts/temp_vetsubmit.php",
            data: {
                product_code
            },
            dataType: "text",
            success: function(data) {
                // console.log( typeof data)
                let result = $.trim(data);

                if(result == '중복') {
                    vetmodal.classList.remove('none')
                }else{
                    newvetmodal.classList.remove('none')
                }
            }
        });
    }

    function otherbtn(event) {
        let product_code = event.parentElement.children;

        $.ajax({
            method: "POST",
            url: "/wp-content/plugins/masterstudy-lms-learning-management-system/stm-lms-templates/course/parts/temp_submit.php",
            data: {
                user_id: product_code[0].value,
                item_id: product_code[1].value,
                product_name: product_code[2].value,
                course_name: product_code[3].value,
                lessons_name: product_code[4].value,
            },
            dataType: "text",
            success: function(data) {
                let result = $.trim(data);
                if(result == '중복') {
                    othermodal.classList.remove('none')
                }else{
                    newothermodal.classList.remove('none')
                }
            }
        });
    }

    let closebtn = document.querySelectorAll(".close-btn")
    for(let i=0; i<closebtn.length; i++){
        closebtn[i].addEventListener('click',function close(){
            vetmodal.classList.add('none')
            newvetmodal.classList.add('none')
            othermodal.classList.add('none')
            newothermodal.classList.add('none')
        }) 
    }
    let okbtn = document.querySelectorAll(".ok-btn")
    for(let i=0; i<okbtn.length; i++){
        okbtn[i].addEventListener('click',function ok(e){
            console.log(e.target.classList[0])
            let url;
            if(e.target.classList[0] === 'vet') {
                url = window.location.protocol+'//'+window.location.host+'/cart/';
            }else {
                url = window.location.protocol+'//'+window.location.host+'/affiliateshoppingmall/';
            }
            window.open(url)
            vetmodal.classList.add('none')
            newvetmodal.classList.add('none')
            othermodal.classList.add('none')
            newothermodal.classList.add('none')
        })
    }

    




</script>