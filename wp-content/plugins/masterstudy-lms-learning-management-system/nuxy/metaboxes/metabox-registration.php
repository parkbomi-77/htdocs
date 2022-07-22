

<form method="post" class="registrationform">
    <?php

        global $wpdb, $post;
        // 등록한 제품 list 불러오기
        $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_list"));
        $num = count($results);
        
        
        // 첫 등록할시 !  
        if(!$results){
        ?> 
        <div class="registration-container">
            <p> product list </p>
            <div class="registration-title">
                <div class="registration-title-num"> no. </div>
                <div class="registration-title-name"> < product name > </div>
                <div class="registration-title-link"> < link > </div>
            </div>
            <div class="registration-list">
                <div class="registration-div">
                    <input type="checkbox" name="deletecheck">
                    <div class="registration-num">1.</div>
                    <input type="hidden" name="registrationNum[]" value="1">
                    <input type="hidden" name="registrationID[]" value="0">
                    <div class="registration-name" id="registration-name2">
                        <input type="text" id="" name="registrationname[]" placeholder="제품명 입력(40)" value="">
                    </div>
                    <div class="registration-link" id="registration-link2">
                        <input type="text" id="" name="registrationlink[]" placeholder="제품 링크(40)" value="">
                    </div>
                </div>
            </div>
            <div class="registration-add" onclick="create_registration_Tag()">
                <div>+</div>
                <div>신규</div>
            </div>

            
        </div>
        <?php $num++; ?>
        <!-- 추가로 등록할시 먼저 db에서 list 출력-->
        <?php } else { 
            $registration_box = '';
            for($i = 0; $i < $num; $i++){
                $add_registration_box = ' <div class="registration-div">
                                    <input type="checkbox" name="deletecheck[]" value="'.$results[$i]->ID.'">
                                    <div class="registration-num">'.($i+1).'.</div>
                                    <input type="hidden" name="registrationNum[]" value="'.($i+1).'">
                                    <input type="hidden" name="registrationID[]" value="'.$results[$i]->ID.'">
                                    <div class="registration-name" id="registration-name2">
                                        <input type="text" id="" name="registrationname[]" placeholder="제품명 입력란(40)" value="'.$results[$i]->product_name.'">
                                    </div>
                                    <div class="registration-link" id="registration-link2">
                                        <input type="text" id="" name="registrationlink[]" placeholder="제품링크(40)" value="'.$results[$i]->product_link.'">
                                    </div>
                                </div>';
                $registration_box = $registration_box.$add_registration_box ;
            }
                echo ('<div class="registration-container">
                            <p> product list </p>
                            <div class="registration-title">
                                <div class="registration-title-num"> no. </div>
                                <div class="registration-title-name"> < product name > </div>
                                <div class="registration-title-link"> < link > </div>
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
                                <input class="registration_save_btn" type="submit" onclick="savebtn()"
                                value="SAVE" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-display-save.php" >
                            </div>
                        </div>');
        
            }
    ?>


</form>





<script src="https://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

    var Count = <?php echo $num; ?>+1;

    function create_registration_Tag(){
    let registrationList = document.querySelector('.registration-list');
    let new_pTag = document.createElement('div');
    
    new_pTag.setAttribute('class', 'registration-div');
    new_pTag.innerHTML = 
                `<input type="checkbox" name="deletecheck">
                <div class="registration-num">${Count}.</div>
                <input type="hidden" name="registrationNum[]" value=${Count}>
                <input type="hidden" name="registrationID[]" value="0">
                <div class="registration-name" id="registration-name2">
                    <input type="text" id="" name="registrationname[]" placeholder="제품명 입력란(40)" value="<?php echo esc_attr( $post->playname ); ?>" required>
                </div>
                <div class="registration-link" id="registration-link2">
                    <input type="text" id="" name="registrationlink[]" placeholder="제품링크(40)" value="<?php echo esc_attr( $post->playlink ); ?>" required>
                </div>
                <div class="playbox-trash2" onclick="close_registrationTag(this)" style="font-size:23px;">➖</div>`
    
    registrationList.appendChild(new_pTag);
    
     Count++;
    }
    function close_registrationTag(e){
        let registrationList = document.querySelector('.registration-list');
        registrationList.removeChild(e.parentNode);
        Count = Count-1;
    }

    function savebtn() {
        alert("저장합니다.");
    }

    function deletebtn() {
        alert("삭제합니다.");
    }


</script>
