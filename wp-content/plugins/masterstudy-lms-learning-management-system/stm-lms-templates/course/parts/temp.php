
<!-- <link rel="stylesheet" href="/Applications/MAMP/htdocs/wp-content/plugins/duplicator/assets/css/fontawesome-all.min.css"> -->
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
        #box>p{
            padding: 8px 11px;
            font-size: 16px;
            background-color: black;
            margin-bottom: 0;
            color: tan;
        }
        
        #box>div {
            justify-content: space-between;
            display: flex;
            background-color: rgb(19, 21, 24);
            align-items: center;
            padding: 2px 22px 2px 17px;
            border-top: solid black;
            font-size: 15px;
            font-weight: 500;
            color: #d6d6d6;
            height: 90px;
        }
        #box>div:hover {
            background-color: rgb(59, 62, 59);
        }
        .box-cart{
            font-size: 20px;
        }
        .box-cart i{
            color: #d6d6d6;
        }
    </style>

    <p> <i class="fa-solid fa-store"></i> store</p>
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
                    <div class="box-cart">
                        <a href=<?php echo $results[$i]->product_link ?> target="_blank"> <i class="fa-solid fa-cart-shopping"></i> </a>
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



