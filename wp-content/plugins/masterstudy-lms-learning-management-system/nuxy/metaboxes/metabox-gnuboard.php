

<div class="Inflowbox-selectcontainer">
    <div class="Inflowbox-name">Site selection</div>
    <div class="Inflowbox-select">
        <select name="shopStatus" id="shopStatus-id" onchange="changeState(this.value)">
            <option value="none">'쇼핑몰' 을 선택해주세요 </option>
            <option value="1000">그누보드 샘플 사이트 1</option>
        </select>
    </div>
</div>

<div class="Inflowbox-selectcontainer">
    <div class="Inflowbox-name">Date</div>
    <div class="Inflowbox-select">
        <select name="orderdate" class="orderdate years" onchange="changeState(this.value)">
            <option value="21">'년도' 를 선택해주세요 </option>
            <option value="22">2022년</option>
            <option value="23">2023년</option>
            <option value="24">2024년</option>
        </select>
        <select name="orderdate" class="orderdate month" onchange="changeState(this.value)">
            <option value="13">'월' 을 선택해주세요 </option>
            <?php
            for($i=0; $i<12; $i++){ // value 1 ~ 12
                echo "<option value='".($i+1)."'>".($i+1)."월</option>";
            }
            ?>
        </select>
    </div>
</div>

<div class="Inflowbox-selectcontainer">
    <div class="Inflowbox-name">State</div>
    <div class="Inflowbox-select">
        <select name="orderStatus" id="orderStatus-id" onchange="changeState(this.value)">
            <option value="100">'주문 상태' 를 선택해주세요 </option>
            <option value="101">주문</option>
            <option value="102">배송</option>
            <option value="103">완료</option>
            <option value="104">취소</option>
        </select>
    </div>
</div>

<div class="Inflowbox-container">
    <table style="text-align:center;" id="inflowtable">
        <colgroup>
            <col width="20%">
        </colgroup>
        <thead>
            <tr> 
                <th>사용자</th>
                <th>제품</th>
                <th>제품가격</th>
                <th>수량</th>
                <th>총액</th>
                <th>날짜</th>
                <th>상태</th>
            </tr>
        </thead>
        <tbody  id="name">
        </tbody>
        <tfoot>
            <tr>
                <th scope="row" colspan='4'>총 액</th>
                <td colspan='3' id="tabletotal">원</td>
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

    
    function changeState(e) { //select value 값으로 url 구분하여 불러오기 
        tabletag = document.getElementById("name"); 
        if(e  >= 1000){
            $.ajax({
                url: "http://localhost:8888/inflowbox-db.php",
                type: "post",
                dataType : 'json',
                data: {
                    code : e,
                },
            }).done(function(data) {
                let totaltd= document.querySelector("#tabletotal")
                let total = 0;
                purchase_status = data;
                let datadata = ''
                for(let i=0; i<data.length; i++){
                    total = total + (data[i].qty)*(data[i].price)
                    datadata = datadata 
                    + "<tr><td>"+data[i].user_id+"</td>"
                    + "<td>"+data[i].it_name+"</td>"
                    + "<td>"+data[i].price+"원</td>"
                    + "<td>"+data[i].qty+" 개</td>"
                    + "<td>"+(data[i].qty)*(data[i].price)+" 원</td>"
                    + "<td>"+data[i].status_time+" </td>"
                    + "<td>"+data[i].status+"</td></tr>"
                }
                tabletag.innerHTML = datadata;
                totaltd.innerText = total+"원"
            });
        }else if(e >= 100) { // 주문 상태 선택
            let totaltd= document.querySelector("#tabletotal")
            let total = 0;

            let code;
            if(e > 100){
                if(e === '101'){
                    code = '주문';
                }else if(e === '102'){
                    code = '배송';
                }else if(e === '103'){
                    code = '완료';
                }else if(e === '104'){
                    code = '취소';
                }
                status3 = status2.filter(el => el.status === code)
            }else if(e === '100'){
                status3 = status2;
            }
            
            let datadata = ''
            for(let i=0; i<status3.length; i++){
                total = total + (status3[i].qty)*(status3[i].price)

                datadata = datadata 
                + "<tr><td>"+status3[i].user_id+"</td>"
                + "<td>"+status3[i].it_name+"</td>"
                + "<td>"+status3[i].price+"원</td>"
                + "<td>"+status3[i].qty+" 개</td>"
                + "<td>"+(status3[i].qty)*(status3[i].price)+" 원</td>"
                + "<td>"+status3[i].status_time+" </td>"
                + "<td>"+status3[i].status+"</td></tr>"
            }
                tabletag.innerHTML = datadata;
                totaltd.innerText = total+"원"

        }else if(e < 100){ // 닐짜
            let totaltd= document.querySelector("#tabletotal")

            if(e > 20){ // 년도 선택시
                let total = 0;

                // 월 초기화
                document.querySelector(".month").value = "13"
                document.querySelector("#orderStatus-id").value = "100"

                if(e === '21'){
                    status = purchase_status;
                }else {
                    status = purchase_status.filter(el => 
                    el.status_time.substr(2,2) === e)
                }
                let datadata = ''
                for(let i=0; i<status.length; i++){
                    total = total + (status[i].qty)*(status[i].price);
                    datadata = datadata 
                    + "<tr><td>"+status[i].user_id+"</td>"
                    + "<td>"+status[i].it_name+"</td>"
                    + "<td>"+status[i].price+"원</td>"
                    + "<td>"+status[i].qty+" 개</td>"
                    + "<td>"+(status[i].qty)*(status[i].price)+" 원</td>"
                    + "<td>"+status[i].status_time+" </td>"
                    + "<td>"+status[i].status+"</td></tr>"
                }
                tabletag.innerHTML = datadata;
                totaltd.innerText = total+"원"


            }else if(e < 20){ // 월 선택시 
                document.querySelector("#orderStatus-id").value = "100"
                let total = 0;

                if(e === '13'){
                    status2 = status;
                }else {
                    status2 = status.filter(el => 
                    Number(el.status_time.substr(5,2)) === Number(e))
                }
                let datadata = ''
                for(let i=0; i<status2.length; i++){
                    total = total + (status[i].qty)*(status[i].price);
                    datadata = datadata 
                    + "<tr><td>"+status2[i].user_id+"</td>"
                    + "<td>"+status2[i].it_name+"</td>"
                    + "<td>"+status2[i].price+"원</td>"
                    + "<td>"+status2[i].qty+" 개</td>"
                    + "<td>"+(status2[i].qty)*(status2[i].price)+" 원</td>"
                    + "<td>"+status2[i].status_time+" </td>"
                    + "<td>"+status2[i].status+"</td></tr>"
                }
                tabletag.innerHTML = datadata;
                totaltd.innerText = total+"원"

            }
 
        }else {
            $('#name').html("")
        }

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

    var excelHandler = {
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
    var buf = new ArrayBuffer(s.length); //convert s to arrayBuffer
    var view = new Uint8Array(buf);  //create uint8array as viewer
    for (var i=0; i<s.length; i++) view[i] = s.charCodeAt(i) & 0xFF; //convert to octet
    return buf;    
    }
</script>

