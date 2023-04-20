
<div id="vet_login_frm">
    <div class="vet_login_notice">
        <span>vetschool 통합 로그인으로 쇼핑몰의 서비스를<br> 이용하실 수 있습니다.</span>
    </div>
    <form name="vlogin" method="post" id="vlogin">

        <div id="login_frm">
            <label for="vet_login_id" class="sound_only">아이디<strong class="sound_only"> 필수</strong></label>
            <input type="text" name="mb_id" id="vet_login_id" placeholder="아이디" required class="frm_input required">
            <label for="vet_login_pw" class="sound_only">비밀번호<strong class="sound_only"> 필수</strong></label>
            <input type="password" name="mb_password" id="vet_login_pw" placeholder="비밀번호" required class="frm_input required">
            
            <!-- <div id="login_info" class="chk_box">
                <input type="checkbox" name="auto_login" id="login_auto_login" class="selec_chk">
                <label for="login_auto_login"><span></span> 자동로그인</label>
            </div> -->
            <div class="misspelled">
                <span>아이디 또는 비밀번호를 잘못입력했습니다.<br>입력하신 내용을 다시 확인해주세요.</span>
            </div>
            <button type="button" class="vet-wrap">vetschool 간편 로그인</button>
        </div>
    </form>
</div>

<script>
    let memberurl = "<?php echo $member_skin_url ?>";
    let bbs_url = "<?php echo G5_BBS_URL ?>";
    let redirect_url = "<?php echo G5_URL ?>";

    jQuery(function($){
        $(".vet-wrap").on("click", function(e){
            e.preventDefault();
            let username = $("#vet_login_id").val(); 
            let password = $("#vet_login_pw").val();
            let apiEndpoint = "http://localhost:8888/wp-json/myplugin/v1/login";

            // 벳스쿨로 아이디, 패스워드 보내서 회원인증 받기 
            $.ajax({
                type: "POST",
                url: apiEndpoint,
                data: {
                    user_login: username,
                    user_password: password
                },
                success: function(response){ // 성공시 
                    console.log(response);
                    $(".misspelled").css('display','none'); 

                    // 벳스쿨 계정으로 가입한 제휴쇼핑몰 계정이 있는지 체크
                    $.ajax({
                        url: memberurl+"/vetschool_login_check.php",
                        type: "POST",
                        data: {response},
                        success: function(res) {
                            // 있으면 로그인처리 - id, uuid 
                            if(res == 1) {
                                $.ajax({
                                    url: bbs_url+"/login_check.php",
                                    type: "POST",
                                    data: {
                                        mb_id: response.user_login,
                                        mb_password: "pass",
                                        url: redirect_url
                                    },
                                    success: function(result) {
                                        location.reload();
                                    },
                                    error: function() {
                                        console.log("3 error");
                                    }
                                });
                            } else if(res == 2) { // 벳스쿨 대기회원일시
                                alert('벳스쿨 회원가입 대기단계입니다. 회원가입 완료 후 다시 시도해주세요.');
                                return;
                            } else if(res == 3) { // 벳스쿨 customer 등급
                                alert('벳스쿨 회원등급이 명확하지않습니다. 관계자 문의 후 다시 시도해주세요.');
                                return;
                            } else { // 회원가입 - id, 회원등급, uuid 은 세션에 저장해둠 
                                window.location.href = bbs_url+"/vet_register.php";
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown){

                        }
                    });
                },
                error: function(jqXHR, textStatus, errorThrown){ // 실패시 안내메시지 
                    $(".misspelled").css('display','block'); 
                }
            });
            

        });
    });
</script>