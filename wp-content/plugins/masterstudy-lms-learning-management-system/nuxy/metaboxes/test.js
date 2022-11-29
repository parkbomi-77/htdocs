
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
            let link2 = trtag.children[3];
            let btn = trtag.children[4];
            let code = btn.children[1].value;

            let daterow = trtag.nextElementSibling.children[0]
            let date = trtag.nextElementSibling.children[0].innerText;
            let startyear = date.substr(0, 4)
            let startmonth = date.substr(5, 2)

            let endyear = date.substr(10, 4)
            let endmonth = date.substr(15)
      
            name.innerHTML = `<input type="text" style="width:98%;" name="name" value="${e.value}">`
            link.innerHTML = `<input type="text" style="width:100%" name="link" value="${link.innerHTML}">`
            link2.innerHTML = `<input type="text" style="width:100%" name="link" value="${link2.innerHTML}">`
            daterow.innerHTML = `<div class="editdate">
                                <input name="startyear" type="number" value="${startyear}" min="2022">
                                <input name="startmonth" type="number" value="${startmonth}" min="01" max="12">
                                ~ 
                                <input name="endyear" type="number" value="${endyear}" min="2022">
                                <input name="endmonth" type="number" value="${endmonth}" min="01" max="12">
                                </div>`
            btn.innerHTML = 
            `<button type="submit" class="shoppingmall-edit-confirm" onclick="editsave(this)" value="${code}"><i class="fas fa-check"></i></button>
            <button type="submit" class="shoppingmall-edit-confirm" onclick="reset(this)" value="${code}"><i class="fas fa-redo"></i></i></button>`
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
        let link2 = trtag.children[3];
        let new_link2 = link2.children[0].value;
        let newcode = e.value;

        let startyear = trtag.nextElementSibling.children[0].children[0].children[0].value;
        let startmonth = trtag.nextElementSibling.children[0].children[0].children[1].value;
        let endyear = trtag.nextElementSibling.children[0].children[0].children[2].value;
        let endmonth = trtag.nextElementSibling.children[0].children[0].children[3].value;

        if(window.confirm("수정하시겠습니까?")){
            if( (startyear > endyear) || ((startyear === endyear) && (Number(startmonth) > Number(endmonth))) ){
                alert("기한을 확인해주세요.")
            }else {
                $.ajax({
                    url: "http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-save.php",
                    type: "post",
                    dataType : 'json',
                    data: {
                        newcode,
                        newname : new_name,
                        newlink : new_link,
                        newlink2 : new_link2,
                        startyear,
                        startmonth,
                        endyear,
                        endmonth,
                    },
                })
                state = "";
                window.location.reload();
            }
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

        let startyear = trtag.nextElementSibling.children[0].children[0].children[0].value;
        let startmonth = trtag.nextElementSibling.children[0].children[0].children[1].value;
        let endyear = trtag.nextElementSibling.children[0].children[0].children[2].value;
        let endmonth = trtag.nextElementSibling.children[0].children[0].children[3].value;

        let daterow = trtag.nextElementSibling.children[0];
        daterow.innerHTML = `${startyear}-${startmonth} ~ ${endyear}-${endmonth}`;

        trtag.innerHTML = `<td rowspan='2'>${reset_num}</td>
            <td rowspan='2'>${reset_name}</td>
            <td>${reset_link}</td>
            <td>${reset_link2}</td>
            <td id="shoppingmall-box-btn" rowspan='2'>
                <button class="shoppingmall-box-edit" onclick="edit(this)" value="${reset_name}"><i class="fas fa-edit"></i></button>
                <button class="shoppingmall-box-del" onclick="del(this)" value="${code}"><i class="fas fa-times-circle"></i></button>
            </td>`;
        state = "";
    }
    function back() {
        // 입력값 빈칸으로 초기화
        let mallname = document.getElementById("mallname")
        let link = document.getElementById("link")
        let link2 = document.getElementById("link2")
        mallname.value = null;
        link.value = null;
        link2.value = null;

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
        let linkvalue2 = document.getElementById('link2').value;
        let blankcheck = document.querySelector(".blankcheck");

        let startyear = document.querySelector(".shoppingmall-box2-date").children[1].children[0].value;
        let startmonth = document.querySelector(".shoppingmall-box2-date").children[1].children[1].value;
        let endyear = document.querySelector(".shoppingmall-box2-date").children[1].children[2].value;
        let endmonth = document.querySelector(".shoppingmall-box2-date").children[1].children[3].value;



        if(!(namevalue && linkvalue && linkvalue2)){ // 빈 칸이 있을 경우
            blankcheck.classList.remove('none')
            return false;
        }else { // 빈칸없이 다 들어왔을 경우 중복검사 
            blankcheck.classList.add('none')
            // 시작년도가 크거나 
            // 년도가 같을때 시작달이 더 크면 안됨
            if( (startyear > endyear) || ((startyear === endyear) && (Number(startmonth) > Number(endmonth))) ){
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
    const label = document.querySelector(".toggleSwitch");
    console.log(label);
    

    function togglechange() {
        console.log('클릭');
        console.log(label.toggleSwitch);
        console.log(typeof(label));
        // window.location.reload();
        //let label = document.querySelector(".toggleSwitch");
        console.log(label);
        
        console.table(label);
        label.remove();
        //let label = document.querySelector(".toggleSwitch");
         // let state = label.classList.contains('active')
        // label.classList.toggle('active');
        // if(state){
        //     document.querySelector(".toggletitle").innerText = "광고 ON list";

        //     shoppingmall_tabletag = document.querySelector('.shoppingmalltable_body');
        //     shoppingmall_tabletag.innerHTML = `<?php // echo $mall_lists ?>`;
        // }else {
        //     document.querySelector(".toggletitle").innerText = "광고 OFF list";
            
        //     shoppingmall_tabletag = document.querySelector('.shoppingmalltable_body');
        //     shoppingmall_tabletag.innerHTML = `<?php  //echo $d_mall_lists ?>`; 
        // }


    }





