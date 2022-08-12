

<div class="Inflowbox-selectcontainer">
    <div class="Inflowbox-name">Site selection</div>
    <div class="Inflowbox-select">
        <select name="shopStatus" id="shopStatus-id" onchange="changeState(this.value)">
            <option value="none">== 선택 ==</option>
            <option value="1000">그누보드 샘플 사이트 1</option>
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
    </table>

</div>

<!-- 가져오는  -->
<script src="//code.jquery.com/jquery.min.js"></script>
<script>
    function changeState(e) { //select value 값으로 url 구분하여 불러오기 
        if(e !== "none"){
            $.ajax({
                url: "http://localhost:8888/inflowbox-db.php",
                type: "post",
                dataType : 'json',
                data: {
                    code : e,
                },
            }).done(function(data) {
                console.log(data);
                let datadata = ''
                for(let i=0; i<data.length; i++){
                    datadata = datadata 
                    + "<tr><td>"+data[i].user_id+"</td>"
                    + "<td>"+data[i].it_name+"</td>"
                    + "<td>"+data[i].price+"원</td>"
                    + "<td>"+data[i].qty+" 개</td>"
                    + "<td>"+(data[i].qty)*(data[i].price)+" 원</td>"
                    + "<td>"+data[i].status_time+" </td>"
                    + "<td>"+data[i].status+"</td></tr>"

                }
                $('#name').html(datadata)
            });
        }
        $('#name').html("")

    }
</script>

