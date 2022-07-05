<div class="stm-lms-course__lesson-content__box">
        <style>
            #box {
                width: 100%;
                height: 250px;
                background-color: rgb(74, 60, 56);
                padding: 10px;
            }
            #box div {
                margin: 2px;
                justify-content: space-between;
            }
            #no1 {
                width: 100%;
                background-color: rgb(97,84,80);
                border-color: darkcyan;
                display : none;
            }
            #no2 {
                width: 100%;
                background-color: rgb(97,84,80);
                border-color: darkcyan;
                display : none;
            }
            #no3 {
                width: 100%;
                background-color: rgb(97,84,80);
                border-color: darkcyan;
                display : none;
            }
        </style>
    
        <div id="box">
            <p> 제품 구입하기 </p>
            <div id="no1" style="display:none">강아지 선그라스<a href="https://www.naver.com/" target="_blank">장바구니 담기</a></div>
            <div id="no2" style="display:none">강아지 모자 <a href="https://www.daum.net/" target="_blank">장바구니 담기</a></div>
            <div id="no3" style="display:none">강아지 신발 <a href="https://www.nate.com/" target="_blank">장바구니 담기</a></div>
        </div>
</div>

<?php
echo '

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
        player.getCurrentTime().then((seconds) => { // 현재 재생시간
        seconds = Math.round(seconds) // 반올림하여 정수로 통일
        console.log(seconds)
        console.log(TotalPlayingTime)

            document.getElementById("no1").style.display = "none"
            document.getElementById("no2").style.display = "none"
            document.getElementById("no3").style.display = "none"

            if(1 <= seconds && seconds <=4) { // 시작시간, 끝시간 설정
                document.getElementById("no1").style.display = "flex"
            }
            if(2 <= seconds && seconds <=5) { // 시작시간, 끝시간 설정
                document.getElementById("no2").style.display = "flex"
            }
            if(7 <= seconds && seconds <=10) { // 시작시간, 끝시간 설정
                document.getElementById("no3").style.display = "flex"
            }

        });   
    }

   // let interval = setInterval(getCurrentTime, 500);
</script>
'
?>


