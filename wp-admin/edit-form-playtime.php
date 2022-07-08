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


<div class="playbox-container">
    <form action="/Applications/MAMP/htdocs/wp-admin/playpost.php" method="post" id="playbox-form">
        <p>vimeo 영상시간 : 상품등록</p>
        <div class="playbox-list">
            <div class="playbox">
                <div class="playbox-num">1</div>
                <div class="playbox-time">
                    <input type="text" id="" name="playtime" value="<?php echo esc_attr( $post->playtime ); ?>" placeholder="00:00">
                </div>
                <div class="playbox-name">
                    <input type="text" id="" name="playname" placeholder="제품명 입력란(40)" value="<?php echo esc_attr( $post->playname ); ?>">
                </div>
                <div class="playbox-trash" onclick="close_boxTag()">✖︎</div>
            </div>
        </div>
        <div class="playbox-add" onclick="create_boxTag()">
            <div>+</div>
            <div>신규</div>
        </div>
    </form> 
</div>



<script>

    let pTagCount = 2;

    function create_boxTag(){
    let playboxList = document.querySelector('.playbox-list');
    let new_pTag = document.createElement('div');
    
    new_pTag.setAttribute('class', 'playbox');
    new_pTag.innerHTML = 
                `<div class="playbox-num">${pTagCount}</div>
                <div class="playbox-time">
                    <input type="text" id="" name="playtime" value="<?php echo esc_attr( $post->playtime ); ?>" placeholder="00:00">
                </div>
                <div class="playbox-name">
                    <input type="text" id="" name="playname" placeholder="제품명 입력란(40)" value="<?php echo esc_attr( $post->playname ); ?>">
                </div>
                <div class="playbox-trash" onclick="close_boxTag()">✖︎</div>`
    
     playboxList.appendChild(new_pTag);
    
     pTagCount++;
    }

    function close_boxTag(){
        let playboxList = document.querySelector('.playbox-list');
        let deletebox = document.querySelector('.playbox-list').lastChild;
        playboxList.removeChild(deletebox);
        pTagCount = pTagCount-1;
    }
</script>

