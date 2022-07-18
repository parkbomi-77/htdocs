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
            height: 658px;
            background-color: rgb(17, 17, 19);
            border: 2px solid rgb(17, 17, 19);
            margin-top: 135px;
            margin-left: -15px;
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
            padding: 24px 22px 2px 17px;
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
        .box-cart{
            font-size: 20px;
        }
        .box-cart i{
            color: #d6d6d6;
        }
        .box-cart button{
            background-color: rgba(16,17,19,0);
            border: 0;
        }
    </style>


    <p> <i class="fa-solid fa-store"></i> store</p>
    <?php
        global $wpdb, $post;
        // lesson 글에 해당하는 재생시간등록 결과 불러오기 
        $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_play_time where posts_lesson_id = $post->ID"));
        $current_user = wp_get_current_user();

        if($results){
            $num = count($results);
            for($i=0; $i<$num; $i++){ ?>
                <div id= <?php echo $results[$i]->play_idx ?> style="display:none"> 
                <form id="wishform" method='POST' target="iframe1" 

                action='/wp-content/plugins/masterstudy-lms-learning-management-system/stm-lms-templates/course/parts/temp_submit.php'>
                    <iframe id="iframe1" name="iframe1" style="display:none"></iframe>
                    <div class="box-flex">
                        <div>
                            <?php echo $results[$i]->product_name ?> 
                        </div>
                        <div class="box-cart">
                            <!-- 유저 아이디, 상품명 디비로 전달 -->
                            <input type="hidden" name="user_id" value="<?php echo $current_user->ID ?>">
                            <input type="hidden" name="item_id" value="<?php echo $results[$i]->product_name ?>">
                            <button type='submit'><i class="fa-solid fa-cart-shopping"></i></button>
                        </div>
                    </div>
                </form>
                </div>
                
    <?php   }
        } ?>
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
        console.log(currentTime)
        console.log(TotalPlayingTime)

            <?php 
            $num = count($results);
            for($i=0; $i<$num; $i++){ ?>
                document.getElementById(<?= $results[$i]->play_idx ?>).style.display = "none"
            <?php 
                }
                
            for($i=0; $i<$num; $i++){ 
            $time = $results[$i]->product_time;
           // var_dump($results[$i]->product_time);
                $minute = substr($time, 0, 2); 
                $seconds = substr($time, 3, 2); 
                $play_time = $minute*60 + $seconds; ?> //초로 변환하기 
                if(<?= $play_time ?> <= currentTime && currentTime <= <?= $play_time+4 ?>) { // 시작시간, 끝시간 설정 
                    document.getElementById(<?= $results[$i]->play_idx ?>).style.display = "block"
                }
            <?php
            } ?>
           

        });   
    }

   let interval = setInterval(getCurrentTime, 500);

    // function confirm() {
    //     alert("장바구니에 담겼습니다!")
    // }
    // function checkIt(){
    //     if(){

    //     }
    // }

</script>