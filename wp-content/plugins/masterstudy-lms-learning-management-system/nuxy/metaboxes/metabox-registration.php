<?php
    global $wpdb, $post;
    $mallResults = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where state=1 and del=0"));
    $mallResults[0]->code;
    $mallResults[0]->name;

    $now_year = date('Y-m');

    $category = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_category"));


// 쇼핑몰명 option
$option = '';
for($i=0; $i<count($mallResults); $i++){
    $option = $option."<option value='{$mallResults[$i]->code}'>{$mallResults[$i]->name}</option>";
}

// 상품 중분류 option 
$cate_option = '';
for($i=0; $i<count($category); $i++){
    $cate_option = $cate_option."<option value='{$category[$i]->ca_code}'>{$category[$i]->category}</option>";
}
?>

<form method="post" class="registrationform" onsubmit="return deletebtn()">
    <?php

        global $wpdb, $post;

        function productlist($sql) {
            global $wpdb; 
            return $wpdb->get_results($wpdb->prepare($sql));
        }
        // 등록한 제품 , 쇼핑몰 이름 join하여 list 불러오기 
        $allsql = "select wp_product_list.*, wp_shoppingmall.name, wp_product_category.category
                    from wp_product_list
                    join wp_shoppingmall
                    on wp_product_list.mall_code = wp_shoppingmall.code
                    join wp_product_category
                    on wp_product_list.ca_code = wp_product_category.ca_code
                    where adv_state = 1";
        $results = productlist($allsql);

        // 등록한 상품이 있는지 체크 
        $num = count($results);

        // 등록한 상품이 없을때
        if(!$results){ ?> 
        <div class="registration-container">
            <div class="shoppingmall_title">
                <div>shoppingmall</div>
                <div class="shoppingmall_select">
                    <select name="" id="">
                        <option value="">쇼핑몰명</option>
                        <?php echo $option ?>
                    </select>
                </div>
            </div>
            <div class="registration_empty">
                등록한 제품이 없습니다.
            </div>
            <div class="registration-add" onclick="create_registration_Tag()">
                <div>+</div>
                <div>신규</div>
            </div>

            <div class="registration-inputbox">
                <input class="registration_delete_btn" type="submit"
                value="DELETE" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-delete.php">
            </div> 
        </div>
        <?php $num++; ?>
        <!-- 등록한 상품들 조회 -->
        <?php } else { 
            
            function productlist_filter ($num, $results, $cate_option) {
                $all_registration = '';
                for($i = 0; $i < $num; $i++){
                    $one_registration = ' 
                        <div class="registration-div">
                            <input type="checkbox" onclick="eachCheck(this)" class="registration-checkbox" name="deletecheck[]" value="'.$results[$i]->ID.'">
                            <div class="registration-num">'.($i+1).'</div>
                            <input type="hidden" name="registrationNum[]" value="'.($i+1).'">
                            <input type="hidden" name="registrationID[]" value="'.$results[$i]->ID.'">
                            <input type="hidden" class="m_code" name="shoppingMallList[]" value="'.$results[$i]->mall_code.'">
                            <input type="hidden" name="registrationcategory[]" value="'.$results[$i]->category.'">
                            <input type="hidden" name="registrationname[]" value="'.$results[$i]->product_name.'">
                            <input type="hidden" name="registrationlink[]" value="'.$results[$i]->product_code.'">

                            <div class="registration-mall" id="registration-mall2">
                                <input type="text" name="shoppingMallList[]" value="'.$results[$i]->name.'" disabled>
                            </div>
                            <div class="registration-category">
                                <select name="registrationcategory[]" disabled>
                                    <option value="'.$results[$i]->ca_code.'" selected>'.$results[$i]->category.'</option>
                                    '.$cate_option.'
                                </select>
                            </div>
                            <div class="registration-name" id="registration-name2">
                                <input type="text" name="registrationname[]" value="'.$results[$i]->product_name.'" disabled>
                            </div>
                            <div class="registration-link" id="registration-link2" onclick="pagelink(this)">
                                <input type="text" name="registrationlink[]" value="'.$results[$i]->product_code.'" disabled>
                                
                            </div>
                            <div class="registration-check">
                                <button class="edit" onclick="editfunc('.($i).')"><i class="fas fa-pen"></i></button>
                                <button class="reset none" onclick="backfunc('.($i).')"><i class="fas fa-chevron-left"></i></button>
                                <button class="save none" onclick="savefunc('.($i).','.$results[$i]->ID.')"><i class="far fa-save fa-lg"></i></button>

                            </div>
                        </div>
                        ';
                    $all_registration = $all_registration.$one_registration ;
                }
                return $all_registration;
            }


            echo ('<div class="registration-container">
                        <div class="shoppingmall_title">
                            <div>shoppingmall</div>
                            <div class="shoppingmall_select">
                                <select name="shoppingmall_change" id="" onchange="javascript:listchange(this)">
                                    <option value="">쇼핑몰명</option>
                                    '.$option.' 
                                </select>
                            </div>
                        </div>
                        <div class="registration-title">
                            <input type="checkbox" id="registration-title-checkbox" onclick="allcheck(this)" name="deletecheck[]" value="">
                            <div id="registration-title-mall"> shoppingmall_name </div>
                            <div class="registration-title-category"> category </div>
                            <div class="registration-title-name"> product_name </div>
                            <div class="registration-title-link"> product_code </div>
                        </div>
                        <div class="registration-list">
                            '.productlist_filter($num, $results, $cate_option).'
                        </div>
                        <div align="right" class="guidetext">
                            <span>▲ 각 상품별 상세페이지연결 확인</span>
                        </div>
                        <div class="registration-add" onclick="create_registration_Tag()">
                            <div>+</div>
                            <div>신규</div>
                        </div>

                        <div class="registration-inputbox">
                            <input class="registration_delete_btn" type="submit"
                            value="DELETE" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-delete.php">
                        </div>
                    </div>'
                );
        
            }
    ?>


</form>

<div class="new_registration none">
    <form  method="post">
        <div class="shoppingmall-box2-header">
            new product registration
        </div>
        <div class="shoppingmall-box2-body">
            <div class="shoppingmall-box2-row">
                <div class="shoppingmall-box2-name">
                    쇼핑몰 : 
                    <select name="shoppingMallList" class="editformmallcode" required>
                        <option value="">쇼핑몰을 선택해주세요</option>
                        <?php echo $option ?>
                    </select>
                </div>
                <div class="shoppingmall-box2-category">
                    중분류 : 
                    <select name="productCategory" class="editformmallcode" required>
                        <option value="">중분류를 선택해주세요</option>
                        <?php echo $cate_option ?>
                    </select>
                </div>
                <div class="shoppingmall-box2-link">
                    상품명 : 
                    <input type="text" name="registrationname" id="editformmallname" onchange="overlapchange(this)" required>
                    <div class="possible none">* 사용할 수 있는 제품명 입니다.</div>
                    <div class="impossible none">* 중복된 제품명 입니다.</div>
                </div>
                <div class="shoppingmall-box2-link">
                    제품코드 : 
                    <input type="number" name="registrationlink" class="editformproductcode" required>
                </div>
                <div class="move">
                    <button onclick="check()">Connection check</button>
                </div>
            </div>
        </div>
        <div class="editform-back-btn" onclick="modalbackbtn()">close</div>
        <div class="editform-save-btn" ><input type="submit" id="editformsave" value="save" onclick="savemodal()" formaction="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-display-save.php"></div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">
    // 등록할때 중복검사 
    function overlapchange(data) {

        let pass = data.parentNode.children[1];
        let nopass = data.parentNode.children[2];

        $.ajax({
            url: 'http://192.168.0.16:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-registration-overlap.php',
            type: 'POST',
            data: { //
                name: data.value,
            },
            dataType: 'text',
            success: function(result) {
                if(result === 'empty'){
                    pass.classList.add('none');
                    nopass.classList.add('none');

                }else if(result === 'no'){ // 중복이 없을 경우 
                    pass.classList.remove('none');
                    nopass.classList.add('none');
                } else { // 중복일 경우 
                    pass.classList.add('none');
                    nopass.classList.remove('none');
                }
            }, // 요청 완료 시    
            error: function(jqXHR) {}, // 요청 실패.    
            complete: function(jqXHR) {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
        });
    }
    // 신규 추가버튼 누르면 모달창 띄우기 
    function create_registration_Tag(){
        let new_registration = document.querySelector('.new_registration');
        new_registration.classList.remove('none');

        // 모달창 초기화  
        let mallcode = document.querySelector(".editformmallcode");
        let name = document.querySelector("#editformmallname");
        let pdcode = document.querySelector(".editformproductcode");
        mallcode.value = "";
        name.value = "";
        pdcode.value ="";

        let possible = document.querySelector(".possible");
        let impossible = document.querySelector(".impossible");
        possible.classList.add('none');
        impossible.classList.add('none');

        $(".new_registration").draggable({
            scroll: false,
            revert: true
        });

        $(document).mouseup(function (e){
            var LayerPopup = $(".new_registration");
            if(LayerPopup.has(e.target).length === 0){
                LayerPopup.addClass("none");
            }
        });
    }
    // 수정사항 저장
    let savefunc = function savebtn(num,id) { 
        // 새로들어온 제품 이름 값 얻기 
        let list = document.querySelector(".registration-list")
        let row = list.children[num]
        let categoryinput = row.querySelector(".registration-category").children;
        let newcategory = categoryinput[0].value;
        let nameinput = row.querySelector(".registration-name").children;
        let newname = nameinput[0].value;

        $.ajax({
            url: 'http://192.168.0.16:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-display-save.php',
            type: 'POST',
            data: { 
                saveid : id,
                savecategory : newcategory,
                savename : newname,
            },
            dataType: 'text',
            success: function(result) {
                if(result === '중복'){ // 중복일 경우
                    alert("중복된 상품명입니다. 다시 입력해주세요.")

                } else { // 중복이 아닐 경우 
                    categoryinput[0].disabled = true;
                    nameinput[0].disabled = true;
                    let btn = row.querySelector(".registration-check").children;
                    btn[0].classList.remove('none')
                    btn[1].classList.add('none')
                    btn[2].classList.add('none')
                    alert("수정되었습니다")
                    
                }
            }, // 요청 완료 시    
            error: function(jqXHR) {}, // 요청 실패.    
            complete: function(jqXHR) {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
        });

        event.preventDefault();


    }
    function deletebtn() {
        let deletecheck = confirm('삭제하시겠습니까?');

        return deletecheck;
    }
    // 신규등록할때 링크확인 잘 되었는지 체크
    function check() {
        let val = document.querySelector(".shoppingmall-box2-row")
        let mallcode = val.querySelector(".editformmallcode").value;
        let productcode = val.querySelector(".editformproductcode").value;

        $.ajax({
            url: 'http://192.168.0.16:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-registration-check.php',
            type: 'POST',
            data: { 
                mallcode,
                productcode,
            },
            dataType: 'text',
            success: function(data) {
                window.open(data);
            }, // 요청 완료 시    
            error: function(jqXHR) {
            }, // 요청 실패.    
            complete: function(jqXHR) {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
        });
        event.preventDefault();
    }
    // 수정할때 
    let editfunc = function editbtn(num) {
        // 상품이름 수정할수있도록 input 비활성화 풀기 
        let list = document.querySelector(".registration-list")
        let row = list.children[num]
        let categoryinput = row.querySelector(".registration-category").children;
        categoryinput[0].disabled = false;
        let nameinput = row.querySelector(".registration-name").children;
        nameinput[0].disabled = false;

        let btn = row.querySelector(".registration-check").children;

        btn[0].classList.add('none')
        btn[1].classList.remove('none')
        btn[2].classList.remove('none')

        event.preventDefault();
    }
    let backfunc = function backbtn(num) {
        // 상품이름 입력란 비활성화로 돌리기 
        let list = document.querySelector(".registration-list")
        let row = list.children[num]
        let categoryinput = row.querySelector(".registration-category").children;
        let before = categoryinput[0].options[0].value
        categoryinput[0].value = before;
        categoryinput[0].disabled = true;
        let nameinput = row.querySelector(".registration-name").children;
        let before2 = nameinput[0].defaultValue;
        nameinput[0].value = before2;
        nameinput[0].disabled = true;

        let btn = row.querySelector(".registration-check").children;

        btn[0].classList.remove('none')
        btn[1].classList.add('none')
        btn[2].classList.add('none')

        event.preventDefault();
    }
    function listchange (data) {
        // 전체삭제 체크박스 초기화하기 
        let checkbox = document.querySelector('#registration-title-checkbox')
        checkbox.checked = false;
        // 쇼핑몰 코드
        let list = document.querySelector(".registration-list")
        // 리스트 다 지우고
        while(list.hasChildNodes()){
            list.removeChild(list.firstChild);
        }
        list.innerHTML = "<div class='waitdiv'>리스트를 불러오는 중입니다..</div>"

        $.ajax({
            url: '/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-registration-filter.php',
            type: 'POST',
            data: { //
                code: data.value,
            },
            dataType: 'text',
            // async: false,
            success: function(data) {
                console.log(data.length);
                if(data.length <= 1){
                    list.innerHTML = "<div class='waitdiv'><i class='far fa-laugh fa-2x'></i></br>등록된 상품이 없습니다.</div>"
                }else {
                    list.innerHTML = data;
                }
            }, // 요청 완료 시    
            error: function(jqXHR) {}, // 요청 실패.    
            complete: function(jqXHR) {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
        });

    }
    function modalbackbtn () {
        let new_registration = document.querySelector('.new_registration');
        new_registration.classList.add('none');

    }
    function savemodal () {
        confirm("저장하시겠습니까? \n저장 이후에는 제품 코드 수정이 불가합니다.");
    }
    function pagelink(data) {
        let mallcode = data.parentNode.querySelector(".m_code").value;
        let productcode = data.firstChild.value;

        $.ajax({
            url: 'http://192.168.0.16:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-registration-check.php',
            type: 'POST',
            data: { 
                mallcode,
                productcode,
            },
            dataType: 'text',
            success: function(data) {
                window.open(data);
            }, // 요청 완료 시    
            error: function(jqXHR) {
            }, // 요청 실패.    
            complete: function(jqXHR) {} // 요청의 실패, 성공과 상관 없이 완료 될 경우 호출
        });
        event.preventDefault();


    }
    let checkbox;
    function allcheck(data) {
        checkbox = document.querySelectorAll(".registration-checkbox");
        for(let i=0; i<checkbox.length; i++) {
            checkbox[i].checked = data.checked;
            checkbox[i].addEventListener('click', function(e) {
                console.log(e.target.checked)
                if(e.target.checked === false){
                    data.checked = false;
                }
            })
        } 
    }
    function eachCheck(data) {
        console.log(data.checked)
        if(data.checked === true) {
            checkbox = document.querySelectorAll(".registration-checkbox");
            let ho = true;
            for(let i=0; i<checkbox.length; i++) {
                if(!checkbox[i].checked){
                    ho = false;
                }
            }
            console.log(ho)
            if(ho) { // 다 체크상태면
                document.querySelector("#registration-title-checkbox").checked = true;
            }
        }
    }

</script>
