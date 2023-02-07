<?php
    global $wpdb;
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where state = 1 and del = 0"));
    $d_results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where state = 0 and del = 0"));

    if($results){ // 벳스쿨쪽으로 광고의뢰한 쇼핑몰이 있을 경우 
        $mall_lists = "";
        for($i=0; $i<count($results); $i++){
            $mall_list = '<tr id="shoppingmall-row">
            <td>'.($i+1).'</td>
            <td>'.$results[$i]->name.'</td>
            <td>'.$results[$i]->link.'</td>
            <td>'.substr($results[$i]->start_date, 0, 7).' ~ '.substr($results[$i]->end_date, 0, 7).'</td>
            <td id="shoppingmall-box-btn">
                <button class="shoppingmall-box-edit" onclick="edit(this)" value="'.$results[$i]->name.'"><i class="fas fa-edit"></i></button>
                <button class="shoppingmall-box-del" onclick="del(this)" value="'.$results[$i]->code.'"><i class="fas fa-times-circle"></i></button>
            </td>
            </tr>';
            $mall_lists = $mall_lists.$mall_list;
        }
    }else { // 벳스쿨쪽으로 광고의뢰한 쇼핑몰이 없을 경우 
        $mall_lists="<tr>
                        <td colspan='5'>현재 광고를 의뢰한 쇼핑몰이 없습니다.</td>
                    <tr>";
    }

    if($d_results) {
        $d_mall_lists = "";
        for($i=0; $i<count($d_results); $i++){
            $mall_list = '<tr id="shoppingmall-row">
            <td>'.($i+1).'</td>
            <td>'.$d_results[$i]->name.'</td>
            <td>'.$d_results[$i]->link.'</td>
            <td>'.substr($d_results[$i]->start_date, 0, 7).' ~ '.substr($d_results[$i]->end_date, 0, 7).'</td>
            <td id="shoppingmall-box-btn">
                <button class="shoppingmall-box-edit" onclick="edit(this)" value="'.$d_results[$i]->name.'"><i class="fas fa-edit"></i></button>
                <button class="shoppingmall-box-del" onclick="del(this)" value="'.$d_results[$i]->code.'"><i class="fas fa-times-circle"></i></button>
            </td>
            </tr>';
            $d_mall_lists = $d_mall_lists.$mall_list;
        }
    }
    $today = date("Ym");
    $year = substr($today,0,4);
    $month = substr($today,4,2);


    // 상품 중분류 테이블 db 
    $categorydb = $wpdb->get_results($wpdb->prepare("SELECT * from wp_product_category"));
    $categoryoption ="";
    for($i=0; $i<count($categorydb); $i++){
        $categoryoption = $categoryoption.'<option value="">'.$categorydb[$i]->category.'</option>';
    }

?>


<div class="shoppingmall-container">
    <div class="container-title">
        <h3> shopping mall list </h3>
    </div>
    <div class="shoppingmall-box">
        <label for="toggle" class="toggleSwitch" onclick="togglechange()"> 
            <span class="toggleButton"></span>
        </label>
        <span class="toggletitle">광고 ON list</span>
        <table style="text-align:center;" class="shoppingmalltable">
            <colgroup>
                <col width="5%">
                <col width="16%">
                <col width="34%">
                <col width="34%">
                <col width="13%">
            </colgroup>
            <thead>
                <tr> 
                    <th></th>
                    <th>name</th>
                    <th>link</th>
                    <th>start date - end date</th>
                    <th></th>
                </tr>
            </thead>
            <form action="http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-save.php" method="post">
                <tbody id="name" class="shoppingmalltable_body">
                    <?php echo $mall_lists ?>
                </tbody>
            </form>
        </table>
        <div class="edit-btn" onclick="add()">add</div>
    </div>

    <!-- 추가할때 -->
    <div class="shoppingmall-box2 none" id="popup">
        <form action="http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-save.php" method="post" name="box2form" onsubmit="return box2submit(this)">
            <div class="shoppingmall-box2-header">
                add shoppingmall
            </div>
            <div class="shoppingmall-box2-body">
                 <div class="shoppingmall-box2-row">
                    <div class="shoppingmall-box2-name">
                        <label for="mallname">쇼핑몰 이름 : </label>
                        <input type="text" name="name" id="mallname" onchange="mallnamecheck(this)">
                        <div class="overlapno none">* 사용할 수 있는 쇼핑몰명 입니다.</div>
                        <div class="overlap none">* 중복된 쇼핑몰명 입니다.</div>
                    </div>
                    <div class="shoppingmall-box2-link">
                        <label for="link">쇼핑몰 url : </label>
                        <input type="text" name="link" id="link" onchange="linkcheck(this)">
                        <div class="overlapno none">* 사용할 수 있는 링크주소 입니다.</div>
                        <div class="overlap none">* 중복된 링크주소 입니다.</div>
                    </div>
                    <div class="shoppingmall-box2-date">
                        <label for="year">기간 : </label>
                        <div>
                            <input name="new_startday" type="month" value="<?php echo $year.'-'.$month ?>">
                            ~
                            <input name="new_endday" type="month" value="<?php echo $year.'-'.$month ?>">
                        </div>


                    </div>
                </div>
            </div>
            <div class="back-btn" onclick="back()">close</div>
            <div class="save-btn" ><input type="submit" id="" value="save"></div>
            <div class="blankcheck none">빈 칸을 채워주세요.</div>
        </form>
    </div>
</div>

<div class="category-container">
    <div class="container-title">
        <h3> category </h3>
    </div>
    <div class="category-box">
        <div>
            <span>Middle category of products</span>
            <select name="" id="">
                <?php echo $categoryoption ?>
            </select>
        </div>
    </div>

</div>

<script src="//code.jquery.com/jquery.min.js"></script>
<!-- <script src="/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/test.js" defer></script> -->
<script>
    let state = ''
    let shoppingmall_tabletag = document.querySelector('.shoppingmalltable_body');
  
    function add(){
        document.querySelector(".shoppingmall-box2").classList.remove('none');

        $("#popup").draggable({
            // containment : "window",
            revert: true
        });
      
    }
    function edit(e) {
        if(state === ''){
            let trtag = e.parentElement.parentElement;
            let name = trtag.children[1];
            let link = trtag.children[2];
            let date = trtag.children[3];
            let btn = trtag.children[4];
            let code = btn.children[1].value;

            let datetext = trtag.children[3].innerText;
            let datedivide = datetext.split("~");
            let startyear = datedivide[0].split('-')[0].trim();
            let startmonth = datedivide[0].split('-')[1].trim();

            let endyear = datedivide[1].split('-')[0].trim();
            let endmonth = datedivide[1].split('-')[1].trim();

      
            name.innerHTML = `<input type="text" style="width:98%;" name="name" value="${e.value}">`
            link.innerHTML = `<input type="text" style="width:100%" name="link" value="${link.innerHTML}">`
            date.innerHTML = `<div class="editdate">
                                <input name="startyear" type="month" value="${startyear}-${startmonth}">-
                                <input name="endyear" type="month" value="${endyear}-${endmonth}">
                            </div>`
            btn.innerHTML = 
            `<button type="submit" class="shoppingmall-edit-confirm" onclick="editsave(this)" value="${code}"><i class="fas fa-check"></i></button>
            <button type="submit" class="shoppingmall-edit-confirm" onclick="reset(this,${startyear},${startmonth},${endyear},${endmonth})" value="${code}"><i class="fas fa-redo"></i></i></button>`
            state = e.value;
        }else {
            alert("수정중인 항목이 있습니다")
        }
    }
    function del(e) {
        if(window.confirm("정말 삭제하시겠습니까?")){
            $.ajax({
                url: "http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-deletecheck.php",
                type: "post",
                dataType : 'text',
                data: {
                    code : e.value,
                },
                aync: false,
                success: function(data) {
                    if(data){ // 광고중인 제품이 있는 경우
                        // console.log(date);
                        alert("해당 쇼핑몰의 광고중인 제품이 있습니다. 제품광고 비활성화 후 다시 시도해주세요.");
                    }else { // 광고중인 제품이 없는 경우 
                        $.ajax({
                            url: "http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-save.php",
                            type: "post",
                            dataType : 'json',
                            data: {
                                code : e.value,
                            },
                            success: function(data) {
                                console.log(data);
                            }
                        })
                        window.location.reload();
                    }
                }
            })
        } else {
            return;
        }
        event.preventDefault();
    }
    function editsave(e) {
        let trtag = e.parentElement.parentElement;
        let name = trtag.children[1];
        let new_name = name.children[0].value;
        let link = trtag.children[2];
        let new_link = link.children[0].value;
        let newcode = e.value;

        let startdate = trtag.children[3].children[0].children[0].value;
        let enddate = trtag.children[3].children[0].children[1].value;

        if(window.confirm("수정하시겠습니까?")){
            if(startdate > enddate){
                alert("기한을 확인해주세요.")
            }else {
                $.ajax({
                    url: "/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-save.php",
                    type: "post",
                    dataType : 'json',
                    data: {
                        newcode,
                        newname : new_name,
                        newlink : new_link,
                        startdate,
                        enddate
                    },
                })
                state = "";
                window.location.reload();
            }
        } else {
            return;
        }

    }
    function reset(e,startyear,startmonth,endyear,endmonth) {
        let trtag = e.parentElement.parentElement;

        let num = trtag.children[0];
        let reset_num = num.innerHTML

        // 두자리 
        if(startmonth < 10) {
            startmonth = "0"+startmonth;
        }   
        if(endmonth < 10) {
            endmonth = "0"+endmonth;
        }   

        let name = trtag.children[1];
        let reset_name = name.children[0].value;
        let link = trtag.children[2];
        let reset_link = link.children[0].value;
        // let date = trtag.children[3];
        // console.log(date)
        // let reset_date = date.innerText;
        // console.log(reset_date)
        let btn = trtag.children[4];
        let code = e.value;

        // let daterow = trtag.nextElementSibling.children[0];
        // date.innerHTML = `${startyear}-${startmonth} ~ ${endyear}-${endmonth}`;

        trtag.innerHTML = `<td>${reset_num}</td>
            <td>${reset_name}</td>
            <td>${reset_link}</td>
            <td>${startyear}-${startmonth} ~ ${endyear}-${endmonth}</td>
            <td id="shoppingmall-box-btn">
                <button class="shoppingmall-box-edit" onclick="edit(this)" value="${reset_name}"><i class="fas fa-edit"></i></button>
                <button class="shoppingmall-box-del" onclick="del(this)" value="${code}"><i class="fas fa-times-circle"></i></button>
            </td>`;
        state = "";
        
    }
    function back() {
        // 입력값 빈칸으로 초기화
        let mallname = document.getElementById("mallname")
        let link = document.getElementById("link")

        mallname.value = null;
        link.value = null;

        //중복체크 초기화
        let overlapno = document.querySelectorAll(".overlapno")
        overlapno.forEach(el => el.classList.add('none'))
        let overlap = document.querySelectorAll(".overlap")
        overlap.forEach(el => el.classList.add('none'))
        
        //빈칸 체크 초기화
        document.querySelector(".blankcheck").classList.add('none');

        document.querySelector(".shoppingmall-box2").classList.add('none');
    }
    function mallnamecheck(e) {
        if(e.value === ""){ // 입력값이 없을 경우 
            e.parentElement.children[3].classList.add('none')
            e.parentElement.children[2].classList.add('none')
        }else { // 입력값이 들어왔을 경우 
            $.ajax({
                url: "http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-check.php",
                type: "post",
                dataType : 'text',
                data: {
                    mallname : e.value,
                },
            }).done((data) => {
                if(data === '통과'){
                    e.parentElement.children[2].classList.remove('none')
                    e.parentElement.children[3].classList.add('none')
                }else if(data === '중복'){
                    e.parentElement.children[3].classList.remove('none')
                    e.parentElement.children[2].classList.add('none')
                }
            })
        }
    }
    function linkcheck(e) {
        if(e.value === ""){ // 입력값이 없을 경우 
            e.parentElement.children[3].classList.add('none')
            e.parentElement.children[2].classList.add('none')
        }else {
            $.ajax({
                url: "http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-check.php",
                type: "post",
                dataType : 'text',
                data: {
                    link : e.value,
                },
            }).done((data) => {
                if(data === '통과'){
                    e.parentElement.children[2].classList.remove('none')
                    e.parentElement.children[3].classList.add('none')
                }else if(data === '중복'){
                    e.parentElement.children[3].classList.remove('none')
                    e.parentElement.children[2].classList.add('none')
                }
            })
        }
    }
    // 엔터로 submit 막기 
    document.addEventListener('keydown', function(event) {
    if (event.keyCode === 13) {
        event.preventDefault();
    };
    }, true);
 
    function box2submit(e) {
        let namevalue = document.getElementById('mallname').value;
        let linkvalue = document.getElementById('link').value;
        let blankcheck = document.querySelector(".blankcheck");

        let startD = document.querySelector(".shoppingmall-box2-date").children[1].children[0].value;
        // let startmonth = document.querySelector(".shoppingmall-box2-date").children[1].children[1].value;
        let endD = document.querySelector(".shoppingmall-box2-date").children[1].children[1].value;
        // let endmonth = document.querySelector(".shoppingmall-box2-date").children[1].children[3].value;


        if(!(namevalue && linkvalue)){ // 빈 칸이 있을 경우
            blankcheck.classList.remove('none')
            return false;
        }else { // 빈칸없이 다 들어왔을 경우 중복검사 
            blankcheck.classList.add('none')
            // 시작년도가 크거나 
            // 년도가 같을때 시작달이 더 크면 안됨
            if(startD > endD){
                alert("기한을 확인해주세요.");
                return false;
            }else {
                let overlapcheck = [...document.querySelectorAll(".overlapno")]; 
                let overlap = overlapcheck.every((data) => {
                    return !(data.classList.contains('none'))
                })
                if(overlap){ // 중복검사 통과
                    return true;
                }else {
                    return false;
                }
            }
        }
    }
    

    function togglechange() {
        state = "";
        let label = document.querySelector(".toggleSwitch");
        let activeState = label.classList.contains('active')
        label.classList.toggle('active');
        if(activeState){
            document.querySelector(".toggletitle").innerText = "광고 ON list";

            shoppingmall_tabletag = document.querySelector('.shoppingmalltable_body');
            shoppingmall_tabletag.innerHTML = `<?php  echo $mall_lists ?>`;
        }else {
            document.querySelector(".toggletitle").innerText = "광고 OFF list";
            
            shoppingmall_tabletag = document.querySelector('.shoppingmalltable_body');
            shoppingmall_tabletag.innerHTML = `<?php  echo $d_mall_lists ?>`; 
        }


    }




</script>


