<style>
.playbox-container {
    padding: 15px 0;
}
.playbox{
    width: 100%;
    display: flex;
    align-items : center;
 
}

.playbox-add{
    display: flex;
    align-items : center;

    height: 30px;
}
.playbox-save{
    width: 100%;
    height: 30px;
    background-color: rgb(30,91,162);
    display: flex;
    align-items : center;
    justify-content: center;
    color: white;
}


</style>


<div class="playbox-container">
    <form action="/Applications/MAMP/htdocs/wp-admin/playpost.php"  method="post">
        <p>vimeo 영상시간 : 상품등록</p>
        <div class="playbox">
            <div class="playbox-num">num</div>
            <div class="playbox-time">
                <input type="text" id="" name="playtime" value="<?php echo esc_attr( $post->playtime ); ?>" placeholder="00:00">
            </div>
            <div class="playbox-name">
                <input type="text" id="" name="playname" placeholder="제품명 입력란(40)" value="<?php echo esc_attr( $post->playname ); ?>">
            </div>
            <div class="playbox-trash">✖︎</div>
        </div>
        <div class="playbox-add">
            <div>+</div>
            <div>신규</div>
        </div>
        <!-- <input type="submit" name="" value="상품등록시간 저장" class="playbox-save"> -->
    </form> 
</div>