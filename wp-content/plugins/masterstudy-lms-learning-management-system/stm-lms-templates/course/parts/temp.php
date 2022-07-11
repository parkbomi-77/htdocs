<div class="stm-lms-course__lesson-content__box">
        <style>
            #box {
                width: 100%;
                height: 250px;
                background-color: rgb(228, 171, 37);
                padding: 10px;
            }
            #box div {
                margin: 2px;
                justify-content: space-between;
                display: flex;
            }
            /* #no1 {
                width: 100%;
                background-color: rgb(97,84,80);
                border-color: darkcyan;
                display : none;
            } */
        </style>
    <!-- 재생시간 테이블 가져와서 뿌리기 -->
</div>
        <div id="box">
            <p> 제품 구입하기 </p>
            <?php
                global $wpdb, $post;
                // lesson 글에 해당하는 재생시간등록 결과 불러오기 
                $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_play_time where posts_lesson_id = $post->ID"));
                // var_dump($results[0]->product_time);

                if($results){
                    $num = count($results);
                    for($i=0; $i<$num; $i++){ ?>
                        <div id= <?php echo $results[$i]->play_idx ?> style="display:none"> 
                            <div>
                                <?php echo $results[$i]->product_name ?> 
                            </div>
                            <div>
                                <a href=<?php echo $results[$i]->product_link ?> target="_blank">장바구니 담기</a>
                            </div>
                        </div>
                        
            <?php   }
                } ?>
        </div>


<script src="https://player.vimeo.com/api/player.js"></script>
<script> 
    const iframe = document.querySelector("iframe");
    const player = new Vimeo.Player(iframe);

    const show = (idNum) => {
        document.getElementById(idNum).style.display = "flex"
    }
    const hidden = (idNum) => {
        document.getElementById(idNum).style.display = "none"
    }

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
                    document.getElementById(<?= $results[$i]->play_idx ?>).style.display = "flex"
                }
            <?php
            } ?>
           


            // if(1 <= seconds && seconds <=4) { // 시작시간, 끝시간 설정
            //     document.getElementById("no1").style.display = "flex"
            // }
            // if(2 <= seconds && seconds <=5) { // 시작시간, 끝시간 설정
            //     document.getElementById("no2").style.display = "flex"
            // }
            // if(7 <= seconds && seconds <=10) { // 시작시간, 끝시간 설정
            //     document.getElementById("no3").style.display = "flex"
            // }

        });   
    }

   let interval = setInterval(getCurrentTime, 500);
</script>



