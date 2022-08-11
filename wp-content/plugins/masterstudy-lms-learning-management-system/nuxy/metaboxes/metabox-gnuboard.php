
<div class="Inflowbox-selectcontainer">
    <div class="Inflowbox-name">Site selection</div>
    <div class="Inflowbox-select">
        <select name="shopStatus" id="shopStatus-id" onchange="changeState(this.value)">
            <option value="none">== 선택 ==</option>
            <option value="0">그누보드 샘플 사이트 1</option>
        </select>
    </div>
</div>
<div class="Inflowbox-container">
    <div>임시 데이터 가져오기 확인</div>
    <div id="name"></div>

</div>

<!-- 가져오는  -->
<script src="//code.jquery.com/jquery.min.js"></script>
<script>
    // 더미데이터
    let site = ["http://localhost:8888/practice/gnuboard/vetschoolapi.php"]
    function changeState(e) { //select value 값으로 url 구분하여 불러오기 
        if(e !== "none"){
            $.ajax({
                url: site[e],
                type: "get",
            }).done(function(data) {
                console.log(data);
                let datadata = ''
                for(let i=0; i<data.length; i++){
                    datadata = datadata 
                    + "<p>"+data[i].userID+"</p>"
                    + "<p>"+data[i].price+"원</p>"
                    + "<p>"+data[i].product_name+"</p>"
                    + "<p>"+data[i].qty+" 개</p>"
                    + "<p>"+data[i].date+"</p>"

                }
                $('#name').html(datadata)
            });
        }
    }
</script>