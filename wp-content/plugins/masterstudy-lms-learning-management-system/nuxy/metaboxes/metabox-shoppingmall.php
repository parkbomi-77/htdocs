<?php
    global $wpdb;
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall where state = 1"));

    if($results){ // 벳스쿨쪽으로 광고의뢰한 쇼핑몰이 있을 경우 
        $mall_lists = "";
        for($i=0; $i<count($results); $i++){
            $mall_list = '<tr id="shoppingmall-row">
            <td>'.($i+1).'</td>
            <td>'.$results[$i]->name.'</td>
            <td>'.$results[$i]->link.'</td>
            <td>'.$results[$i]->link2.'</td>
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
?>


<div class="shoppingmall-container">
    <div class="shoppingmall-title">
        <h3> shopping mall list </h3>
    </div>
    <div class="shoppingmall-box">
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
                    <th>no.</th>
                    <th>name</th>
                    <th>link</th>
                    <th>link2</th>
                    <th></th>
                </tr>
            </thead>
            <form action="http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-save.php" method="post">
                <tbody id="name">
                    <?php echo $mall_lists ?>
                </tbody>
            </form>
        </table>
        <div class="edit-btn" onclick="add()">add</div>
    </div>

    <!-- 수정할때 -->
    <div class="shoppingmall-box2 none">
        <form action="http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-save.php" method="post">
            <div class="shoppingmall-box2-header">
                <!-- <div class="shoppingmall-box2-name">name</div>
                <div class="shoppingmall-box2-link">link</div>
                <div class="shoppingmall-box2-link">link2</div> -->
                add shoppingmall
            </div>
            <div class="shoppingmall-box2-body">
                 <div class="shoppingmall-box2-row">
                    <div class="shoppingmall-box2-name">
                        <label for="mallname">쇼핑몰 이름 : </label>
                        <input type="text" name="name[]" id="mallname" onchange="mallnamecheck(this)">
                        <div class="overlapno none">* 사용할 수 있는 쇼핑몰명 입니다.</div>
                        <div class="overlap none">* 중복된 쇼핑몰명 입니다.</div>
                    </div>
                    <div class="shoppingmall-box2-link">
                        <label for="link">쇼핑몰 url (상세페이지용) : </label>
                        <input type="text" name="link[]" id="link">
                        <div class="overlapno none">* 사용할 수 있는 링크주소 입니다.</div>
                        <div class="overlap none">* 중복된 링크주소 입니다.</div>
                    </div>
                    <div class="shoppingmall-box2-link">
                        <label for="link2">쇼핑몰 api링크 (임시) : </label>
                        <input type="text" name="link2[]" id="link2">
                    </div>
                </div>
            </div>
            <div class="back-btn" onclick="back()">back</div>
            <div class="save-btn" ><input type="submit" id="" value="save"></div>
        </form>
    </div>
</div>


<script src="//code.jquery.com/jquery.min.js"></script>
<script>
    let state = ''

    function add(){
        document.querySelector(".shoppingmall-box").classList.add('none');
        document.querySelector(".shoppingmall-box2").classList.remove('none');
    }
    // function inadd(){
    //     let parent = document.querySelector(".shoppingmall-box2-body");
    //     let child = parent.childElementCount + 1;
    //     let addChild = ''
    //     for(let i=child; i<child+10; i++){
    //         addChild = addChild + `<div>
    //             <div class="shoppingmall-box2-num">${i}</div>
    //             <div class="shoppingmall-box2-name"><input type="text"></div>
    //             <div class="shoppingmall-box2-link"><input type="text"></div>
    //             <div class="shoppingmall-box2-link"><input type="text"></div>
    //         </div>`
    //     }
    //     // document.parent.appendChild(addChild);
    //     $(parent).append(addChild);
    // }
    function edit(e) {
        if(state === ''){
            let trtag = e.parentElement.parentElement;
            let name = trtag.children[1];
            let link = trtag.children[2];
            let link2 = trtag.children[3];
            let btn = trtag.children[4];
            let code = btn.children[1].value;
            console.log(trtag)
      
            name.innerHTML = `<input type="text" style="width:98%;" name="name" value="${e.value}">`
            link.innerHTML = `<input type="text" style="width:100%" name="link" value="${link.innerHTML}">`
            link2.innerHTML = `<input type="text" style="width:100%" name="link" value="${link2.innerHTML}">`
            btn.innerHTML = 
            `<button type="submit" class="shoppingmall-edit-confirm" onclick="editsave(this)" value="${code}"><i class="fas fa-check"></i></button>
            <button type="submit" class="shoppingmall-edit-confirm" onclick="reset(this)" value="${code}"><i class="fas fa-redo"></i></i></button>`
            state = e.value;
        }else {
            alert("1개씩 수정해주세요")
        }
    }
    function del(e) {
        if(window.confirm("정말 삭제하시겠습니까?")){
            $.ajax({
                url: "http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-save.php",
                type: "post",
                dataType : 'json',
                data: {
                    code : e.value,
                },
            })
            window.location.reload();
        } else {
            return;
        }
    }
    function editsave(e) {
        let trtag = e.parentElement.parentElement;
        let name = trtag.children[1];
        let new_name = name.children[0].value;
        let link = trtag.children[2];
        let new_link = link.children[0].value;
        let link2 = trtag.children[3];
        let new_link2 = link2.children[0].value;
        let newcode = e.value;
        console.log(newcode);

        if(window.confirm("수정하시겠습니까?")){
            $.ajax({
                url: "http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-save.php",
                type: "post",
                dataType : 'json',
                data: {
                    newcode,
                    newname : new_name,
                    newlink : new_link,
                    newlink2 : new_link2
                },
            })
            state = "";
            window.location.reload();
        } else {
            return;
        }

    }
    function reset(e) {
        let trtag = e.parentElement.parentElement;
        let num = trtag.children[0];
        let reset_num = num.innerHTML
        let name = trtag.children[1];
        let reset_name = name.children[0].value;
        let link = trtag.children[2];
        let reset_link = link.children[0].value;
        let link2 = trtag.children[3];
        let reset_link2 = link2.children[0].value;
        let btn = trtag.children[4];
        let code = e.value;

        trtag.innerHTML = `<td>${reset_num}</td>
            <td>${reset_name}</td>
            <td>${reset_link}</td>
            <td>${reset_link2}</td>
            <td id="shoppingmall-box-btn">
                <button class="shoppingmall-box-edit" onclick="edit(this)" value="${reset_name}"><i class="fas fa-edit"></i></button>
                <button class="shoppingmall-box-del" onclick="del(this)" value="${code}"><i class="fas fa-times-circle"></i></button>
            </td>`;
        state = "";
    }
    function back() {
        document.querySelector(".shoppingmall-box").classList.remove('none');
        document.querySelector(".shoppingmall-box2").classList.add('none');
    }
    function mallnamecheck(e) {
        console.log(e.value)
        // console.log(e.parentElement.children[2].classList.remove('none'))
        $.ajax({
            url: "http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-check.php",
            type: "post",
            dataType : 'text',
            data: {
                mallname : e.value,
            },
        }).done((data) => {
            console.log(data);
            if(data === '통과'){
                e.parentElement.children[2].classList.remove('none')
            }else if(data === '중복'){
                e.parentElement.children[3].classList.remove('none')
            }
        })
    }

</script>


