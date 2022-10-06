<?php
    global $wpdb, $post;
    $mallResults = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where state =1"));
    $mallResults[0]->code;
    $mallResults[0]->name;

$option = '';
for($i=0; $i<count($mallResults); $i++){
    $option = $option."<option value='{$mallResults[$i]->code}'>{$mallResults[$i]->name}</option>";
}
?>

<form method="post" class="registrationform" >
    <?php

        global $wpdb, $post;
        // 등록한 제품 list 불러오기
        $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list where adv_state = 1"));
        $num = count($results);

        // 첫 등록할시 !  
        if(!$results){
        ?> 
        <div class="registration-container">
            <p> product list </p>
            <div class="registration-title">
                <div class="registration-title-num"> no. </div>
                <div class="registration-title-mall"> shoppingmall_name </div>
                <div class="registration-title-name"> product_name </div>
                <div class="registration-title-link"> product_code </div>
            </div>
            <div class="registration-list">
                <div class="registration-div">
                    <input type="checkbox" name="deletecheck[]">
                    <div class="registration-num">1.</div>
                    <input type="hidden" name="registrationNum[]" value="1">
                    <input type="hidden" name="registrationID[]" value="0">
                    <div class="registration-mall" id="registration-mall2">
                        <select name="shoppingMallList[]" required>
                            <option value="">쇼핑몰을 선택해주세요</option>
                            <?php echo $option ?>
                        </select>
                    </div>
                    <div class="registration-name" id="registration-name2">
                        <input type="text" id="" name="registrationname[]" placeholder="제품명 입력란(40)" onchange="overlapchange(this)" required>
                    </div>
                    <div class="registration-link" id="registration-link2">
                        <input type="text" id="" name="registrationlink[]" placeholder="제품 코드(40)" required>
                        <div class="possible none">●</div>
                        <div class="impossible none">●</div>
                    </div>
                    <div class="playbox-trash2" onclick="close_registrationTag(this)" style="font-size:23px;"><i class="fas fa-minus"></i></div>
                </div>
            </div>
            <div class="registration-add" onclick="create_registration_Tag()">
                <div>+</div>
                <div>신규</div>
            </div>

            <div class="registration-inputbox">
                <input class="registration_delete_btn" type="submit" onclick="deletebtn()"
                value="DELETE" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-delete.php">
                <input class="registration_save_btn" type="submit" onclick="savebtn(event)"
                value="SAVE" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-display-save.php" >
            </div>

            
        </div>
        <?php $num++; ?>
        <!-- 추가로 등록할시 먼저 db에서 list 출력-->
        <?php } else { 
            
            $registration_box = '';
            for($i = 0; $i < $num; $i++){
                $results2 = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where code ={$results[$i]->mall_code}"));
                
                $add_registration_box = ' 
                                <div class="registration-div">
                                    <input type="checkbox" name="deletecheck[]" value="'.$results[$i]->ID.'">
                                    <div class="registration-num">'.($i+1).'.</div>
                                    <input type="hidden" name="registrationNum[]" value="'.($i+1).'">
                                    <input type="hidden" name="registrationID[]" value="'.$results[$i]->ID.'">
                                    <input type="hidden" name="shoppingMallList[]" value="'.$results[$i]->mall_code.'">
                                    <input type="hidden" name="registrationname[]" value="'.$results[$i]->product_name.'">
                                    <input type="hidden" name="registrationlink[]" value="'.$results[$i]->product_code.'">

                                    <div class="registration-mall" id="registration-mall2">
                                        <select name="shoppingMallList[]" disabled>
                                            <option value="'.$results[$i]->mall_code.'">'.$results2[0]->name.'</option>
                                        </select>
                                    </div>
                                    <div class="registration-name" id="registration-name2">
                                        <input type="text" name="registrationname[]" value="'.$results[$i]->product_name.'" disabled>
                                        <div class="possible none">●</div>
                                        <div class="impossible none">●</div>
                                    </div>
                                    <div class="registration-link" id="registration-link2">
                                        <input type="text" name="registrationlink[]" value="'.$results[$i]->product_code.'" disabled>
                                    </div>
                                    <div class="registration-check">
                                        <div style="display:none">'.$results[$i]->mall_code.'</div>
                                        <div style="display:none">'.$results[$i]->product_code.'</div>
                                        <div style="display:none">'.$results[$i]->product_name.'</div>
                                        <div style="display:none">'.($i+1).'</div>
                                        <div style="display:none">'.$results[$i]->ID.'</div>


                                        <button class="move" onclick="check(event)"><i class="fas fa-share"></i></button>
                                        <button class="edit" onclick="editbtn(event)"><i class="fas fa-pen"></i></button>
                                    </div>
                                </div>
                                ';
                $registration_box = $registration_box.$add_registration_box ;
            }
                echo ('<div class="registration-container">
                            <p> product list </p>
                            <div class="registration-title">
                                <div class="registration-title-num"> no. </div>
                                <div class="registration-title-mall"> shoppingmall_name </div>
                                <div class="registration-title-name"> product_name </div>
                                <div class="registration-title-link"> product_code </div>
                            </div>
                            <div class="registration-list">
                                '.$registration_box.'
                            </div>
                            <div class="registration-add" onclick="create_registration_Tag()">
                                <div>+</div>
                                <div>신규</div>
                            </div>

                            <div class="registration-inputbox">
                                <input class="registration_delete_btn" type="submit" onclick="deletebtn()"
                                value="DELETE" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-delete.php">
                                <input class="registration_save_btn" type="submit" onclick="savebtn(event)"
                                value="SAVE" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-display-save.php" >
                            </div>
                        </div>');
        
            }
    ?>


</form>

<div class="editform none" id="popup2" >
    <form  method="post" name="" >
    <input type="hidden" name="registrationNum[]" id="editformnum" value="">
    <input type="hidden" name="registrationID[]" id="editformid" value="">
        <div class="shoppingmall-box2-header">
            product_name edit
        </div>
        <div class="shoppingmall-box2-body">
            <div class="shoppingmall-box2-row">
                <div class="shoppingmall-box2-name">
                    쇼핑몰 : 
                    <select name="shoppingMallList[]" id="editformmallcode" required>
                        <option value="">쇼핑몰을 선택해주세요</option>
                        <?php echo $option ?>
                    </select>
                </div>
                <div class="shoppingmall-box2-link">
                    상품명 : 
                    <input type="text" name="registrationname[]" id="editformmallname" value="" onchange="overlapchange(this)">
                    <div class="possible none">* 사용할 수 있는 제품명 입니다.</div>
                    <div class="impossible none">* 중복된 제품명 입니다.</div>
                </div>
                <div class="shoppingmall-box2-link">
                    제품코드 : 
                    <input type="text" name="registrationlink[]" id="editformproductcode" value="" >
                </div>
            </div>
        </div>
        <div class="editform-back-btn" onclick="backbtn(event)">back</div>
        <div class="editform-save-btn" ><input type="submit" id="editformsave" value="save" onclick="savebtn(event)" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-display-save.php"></div>
    </form>
</div>




<script src="https://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

    function overlapchange(data) {

        $.ajax({
            url: 'http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-registration-overlap.php',
            type: 'POST',
            data: { //
                name: data.value,
            },
            dataType: 'text',
            success: function(result) {
                if(result === 'no'){ // 중복이 없을 경우 
                    let pass = data.parentNode.children[1];
                    let nopass = data.parentNode.children[2];

                    pass.classList.remove('none');
                    nopass.classList.add('none');

                } else { // 중복일 경우 
                    // 중복입니다. 
                    let pass = data.parentNode.children[1];
                    let nopass = data.parentNode.children[2];

                    pass.classList.add('none');
                    nopass.classList.remove('none');
                }
            }, // 요청 완료 시    
            error: function(jqXHR) {}, // 요청 실패.    
            complete: function(jqXHR) {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
        });
    }

    var Count = <?php echo $num; ?>+1;

    function create_registration_Tag(){
        console.log('dd')
        let registrationList = document.querySelector('.registration-list');
        let new_pTag = document.createElement('div');
        
        new_pTag.setAttribute('class', 'registration-div');
        // 신규로 추가하는 행
        new_pTag.innerHTML = 
                    `<input type="checkbox" name="deletecheck[]">
                    <div class="registration-num">${Count}.</div>
                    <input type="hidden" name="registrationNum[]" value=${Count}>
                    <input type="hidden" name="registrationID[]" value="0">
                    <div class="registration-mall" id="registration-mall2">
                        <select name="shoppingMallList[]">
                            <option value="">쇼핑몰을 선택해주세요</option>
                            <?php echo $option?>
                        </select>
                    </div>
                    <div class="registration-name" id="registration-name2">
                        <input type="text" id="" name="registrationname[]" placeholder="제품명 입력란(40)" value="<?php echo esc_attr( $post->playname ); ?>" onchange="overlapchange(this)" required>
                        <div class="possible none">●</div>
                        <div class="impossible none">●</div>
                    </div>
                    <div class="registration-link" id="registration-link2">
                        <input type="text" id="" name="registrationlink[]" placeholder="제품 코드(40)" value="<?php echo esc_attr( $post->playlink ); ?>" required>
                    </div>
                    <div class="playbox-trash2" onclick="close_registrationTag(this)" style="font-size:23px;"><i class="fas fa-minus"></i></div>`
    
        registrationList.appendChild(new_pTag);
        // registrationList.appendChild(passbtn);
        // registrationList.appendChild(nonpassbtn);

        Count++;
    }
    function close_registrationTag(e){
        let registrationList = document.querySelector('.registration-list');

        registrationList.removeChild(e.parentNode);
        Count = Count-1;
    }

    // $('#editformsave').click(function() {
    //     $('#popup2').unbind();
    // });

    function savebtn(e) { 
        $('.edit').unbind("click");
        let possible = e.target.parentNode.parentNode.getElementsByClassName("possible") ;
        let impossible = e.target.parentNode.parentNode.getElementsByClassName("impossible") ;

        let result = true;
        for(let i=0; i<impossible.length; i++){
            let pass = impossible[i].classList[1] === 'none' && possible[i].classList[1] === 'none';
            if(impossible[i].classList[1] === 'none' || pass){ // 중복이 없으면
                continue;
            }else {
                result = false;
            }
        }
        if(result){
            alert("저장합니다.");
            
            // e.preventDefault();
        }else {
            alert("중복을 확인해주세요.");
            e.preventDefault();
        }

    }

    function deletebtn() {
        alert("삭제합니다.");
    }
    function check(event) {
        // 클릭한 행의 쇼핑몰 코드
        let mallConnect = event.target.parentNode.children;
        console.log(mallConnect);
        event.preventDefault();

        $.ajax({
            url: 'http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-registration-check.php',
            type: 'POST',
            data: { //
                code: mallConnect[0].innerText,
                productId: mallConnect[1].innerText
            },
            dataType: 'text',
            success: function(data) {
                // console.log(data);
                window.open(data);
            }, // 요청 완료 시    
            error: function(jqXHR) {}, // 요청 실패.    
            complete: function(jqXHR) {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
        });

    }

    
    function editbtn(event) {
        let val = event.currentTarget.parentNode.children
        document.querySelector(".editform").classList.remove('none');
        $("#popup2").draggable({containment : "window"});

        let edit1 = document.querySelector("#editformmallcode");
        let edit2 = document.querySelector("#editformmallname");
        let edit3 = document.querySelector("#editformproductcode");
        let edit4 = document.querySelector("#editformnum");
        let edit5 = document.querySelector("#editformid");


        edit1.value = val[0].innerText;
        edit2.value = val[2].innerText;
        edit3.value = val[1].innerText;
        edit4.value = val[3].innerText;
        edit5.value = val[4].innerText;


        event.preventDefault();

    }
    function backbtn(event) {
        document.querySelector(".editform").classList.add('none');
        let possible = event.target.parentNode.parentNode.getElementsByClassName("possible") ;
        let impossible = event.target.parentNode.parentNode.getElementsByClassName("impossible") ;

        possible[0].classList.add("none")
        impossible[0].classList.add("none")
    }



</script>
