<?php
    define( 'SHORTINIT', true );
    require_once( $_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
    global $wpdb;
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM wp_shoppingmall where state =1"));

?>

<div class="Inflowbox-selectcontainer">
    <div class="Inflowbox-name">
        <div>Site</div>
        <div class="Inflowbox-select">
            <select name="shopStatus" id="shopStatus-id">
                <option value="">'쇼핑몰' 을 선택해주세요 </option>
                <?php
                    for($i=0; $i<count($results); $i++){
                        echo "<option value='{$results[$i]->code}'>{$results[$i]->name}</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="Inflowbox-name">
        <div>Date</div>
        <div class="Inflowbox-select">
            <select name="orderdate" class="orderdate years">
                <option value="">'년도' 를 선택해주세요 </option>
                <option value="2022">2022년</option>
                <option value="2023">2023년</option>
                <option value="2024">2024년</option>
            </select>
            <select name="orderdate" class="orderdate month" onchange="monthcheck(this)">
                <option value="">'월' 을 선택해주세요 </option>
                <?php
                for($i=0; $i<12; $i++){ // value 1 ~ 12
                    echo "<option value='".($i+1)."'>".($i+1)."월</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="Inflowbox-name">
        <div>State</div>
        <div class="Inflowbox-select">
            <select name="orderStatus" id="orderStatus-id">
                <option value="">'주문 상태' 를 선택해주세요 </option>
                <option value="주문">주문</option>
                <option value="배송">배송</option>
                <option value="완료">완료</option>
                <option value="취소">취소</option>
                <!-- <option value="105">최종 완료</option> -->
            </select>
        </div>
    </div>
    <div>
        <input type="button" class="Inflowbox-button-reset" value="reset" onclick="reselect(this)">
        <input type="button" class="Inflowbox-button" value="select" onclick="change(this)">
    </div>

    <div class="Inflowbox-count">
        <p>총 <span>0</span>개</p>
    </div>
    <table style="text-align:center;" id="inflowtable">
        <thead>
            <tr> 
                <th></th>
                <th>사용자</th>
                <th>쇼핑몰</th>
                <th>제품</th>
                <th>제품가격</th>
                <th>수량</th>
                <th>총액</th>
                <th>마진율</th>
                <th>마진</th>
                <th>날짜</th>
                <th>상태</th>
                <th></th>
            </tr>
        </thead>
        <tbody  id="name">
            <tr><td colspan='12' style="padding: 30px;">판매현황 결과가 없습니다.</td></tr>
        </tbody>
        <tfoot class="tfoottag">
            <tr>
                <th scope="row" colspan='6'>총 액 / 마 진</th>
                <td colspan='6' id="tabletotal">원</td>
            </tr>
        </tfoot>
    </table>
    <div class="download-btn" onclick="exportExcel()">download</div>
</div>

<!-- 가져오는  -->
<script src="//code.jquery.com/jquery.min.js"></script>
<!-- Sheet JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.14.3/xlsx.full.min.js"></script>
<!--FileSaver savaAs 이용 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.min.js"></script>
<script>
    let purchase_status = ''; // 쇼핑몰 선택 
    let status = ''; // 날짜 년도 선택
    let status2 = ''; // 날짜 월 선택
    let status3 = ''; // 상태
    let tabletag ='';
    let month = document.querySelector(".month")
    let countspan = '';

    function monthcheck(e) {
        console.log(e.previousElementSibling.value)
        if(!e.previousElementSibling.value) {
            alert('"년도"를 먼저 선택해주세요.')
            e.value = "";
        }
    }

    function change(e) {
        let siteCode = e.parentElement.parentElement.getElementsByTagName('select')[0].value;
        let year = e.parentElement.parentElement.getElementsByTagName('select')[1].value;
        let month = e.parentElement.parentElement.getElementsByTagName('select')[2].value;
        let orderState = e.parentElement.parentElement.getElementsByTagName('select')[3].value;

        tabletag = document.getElementById("name"); 
        countspan = document.querySelector(".Inflowbox-count").getElementsByTagName('span');

        // 년도없이 월만 들어왔을 경우 예외처리
        if(month && !year) {
            alert('"년도"를 선택해주세요')
            return;
        }

        if(month < 10 && month > 0) {
            month = '0'+month;
        }

        $.ajax({
            url: "http://localhost:8888/inflowbox-db.php",
            type: "post",
            dataType : 'json',
            data: {
                siteCode,
                year,
                month,
                orderState,
            },
        }).done(function(data){
            let totaltd= document.querySelector("#tabletotal")
            let total = 0;
            let margin = 0;

            let datadata = ''
            for(let i=0; i<data.length; i++){
                let onetotal = (data[i].qty)*(data[i].price);
                margin = margin + (onetotal*data[i].margin)/100;
                total = total + (data[i].qty)*(data[i].price)
                datadata = datadata 
                + "<tr> <td></td><td>"+data[i].user_id+"</td>"
                + "<td>"+data[i].name+"</td>"
                + "<td>"+data[i].it_name+"</td>"
                + "<td>"+(data[i].price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+" 원</td>"
                + "<td>"+data[i].qty+"</td>"
                + "<td>"+onetotal.toLocaleString('ko-KR')+" 원</td>"
                + "<td>"+data[i].margin+" %</td>"
                + "<td>"+((onetotal*data[i].margin)/100).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')+" 원</td>"
                + "<td>"+data[i].status_time+" </td>"
                + "<td>"+data[i].status+"</td><td></td> </tr>"
            }
            tabletag.innerHTML = datadata;
            totaltd.innerText = total.toLocaleString('ko-KR')+" 원 / "+margin.toLocaleString('ko-KR')+" 원"
            countspan[0].innerText = data.length;
        })

    }
    function reselect(e) {
        console.log(e)
        e.parentElement.parentElement.getElementsByTagName('select')[0].value = "";
        e.parentElement.parentElement.getElementsByTagName('select')[1].value = "";
        e.parentElement.parentElement.getElementsByTagName('select')[2].value = "";
        e.parentElement.parentElement.getElementsByTagName('select')[3].value = "";
        
        let totaltd= document.querySelector("#tabletotal")
        tabletag = document.getElementById("name"); 
        countspan = document.querySelector(".Inflowbox-count").getElementsByTagName('span');

        // 테이블
        tabletag.innerHTML = "<tr><td colspan='12' style='padding: 30px;'>판매현황 결과가 없습니다.</td></tr>";
        totaltd.innerText = "원"
        countspan[0].innerText = 0;

        
    }
    function exportExcel(){ 
        // step 1. workbook 생성
        var wb = XLSX.utils.book_new();

        // step 2. 시트 만들기 
        var newWorksheet = excelHandler.getWorksheet();

        // step 3. workbook에 새로만든 워크시트에 이름을 주고 붙인다.  
        XLSX.utils.book_append_sheet(wb, newWorksheet, excelHandler.getSheetName());

        // step 4. 엑셀 파일 만들기 
        var wbout = XLSX.write(wb, {bookType:'xlsx',  type: 'binary'});

        // step 5. 엑셀 파일 내보내기 
        saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), excelHandler.getExcelFileName());
    }
    let excelHandler = {
        getExcelFileName : function(){
            return 'table-test.xlsx';	//파일명
        },
        getSheetName : function(){
            return 'Table Test Sheet';	//시트명
        },
        getExcelData : function(){
            return document.getElementById('inflowtable'); 	//TABLE id
        },
        getWorksheet : function(){
            return XLSX.utils.table_to_sheet(this.getExcelData());
        }
    }

    function s2ab(s) { 
    let buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
    let view = new Uint8Array(buf);  //create uint8array as viewer
    for (let i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
    return buf;    
    }
</script>

