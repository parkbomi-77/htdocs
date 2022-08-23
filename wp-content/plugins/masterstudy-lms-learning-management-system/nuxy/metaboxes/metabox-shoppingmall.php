<?php
    global $wpdb;
    $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_shoppingmall"));

    if($results){ // 벳스쿨쪽으로 광고의뢰한 쇼핑몰이 있을 경우 
        $mall_lists = "";
        for($i=0; $i<count($results); $i++){
            $mall_list = '<tr><td>'.($i+1).'</td>
            <td>'.$results[$i]->name.'</td></tr>';
            $mall_lists = $mall_lists.$mall_list;
        }
    }else { // 벳스쿨쪽으로 광고의뢰한 쇼핑몰이 없을 경우 
        $mall_lists="<tr>
                        <td></td>
                        <td>현재 광고를 의뢰한 쇼핑몰이 없습니다.</td>
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
                <col width="20%">
                <col width="80%">
            </colgroup>
            <thead>
                <tr> 
                    <th>no.</th>
                    <th>shoppiing mall list</th>
                </tr>
            </thead>
            <tbody  id="name">
                    <?php echo $mall_lists ?>
            </tbody>
        </table>
        <div class="edit-btn" onclick="add()">add</div>
    </div>

    <!-- 수정할때 -->
    <div class="shoppingmall-box2 none">
        <form action="http://localhost:8888/wp-content/plugins/masterstudy-lms-learning-management-system/nuxy/metaboxes/metabox-shoppingmall-save.php" method="post">
            <div class="shoppingmall-box2-header">
                <div class="shoppingmall-box2-num">no.</div>
                <div class="shoppingmall-box2-name">shoppiing mall list</div>
            </div>
            <div class="shoppingmall-box2-body">
                <?php
                for($i=0; $i<10; $i++){
                    echo '<div class="shoppingmall-box2-row">
                            <div class="shoppingmall-box2-num">'.($i+1).'</div>
                            <div class="shoppingmall-box2-name"><input type="text" name="name[]"></div>
                        </div>';
                }
                ?>
                <!-- <div>
                    <div class="shoppingmall-box2-num">0</div>
                    <div class="shoppingmall-box2-name"><input type="text"></div>
                </div> -->
                <input type="hidden" value="">
            </div>
            
            
            <div class="shoppingmall-box2-add" onclick="inadd()">add</div>
            <div class="edit-btn" ><input type="submit" id="" value="save"></div>
        </form>
    </div>
    
</div>


<script src="//code.jquery.com/jquery.min.js"></script>
<script>
    function add(){
        // let tbody = document.querySelector("#name")
        document.querySelector(".shoppingmall-box").classList.add('none');
        document.querySelector(".shoppingmall-box2").classList.remove('none');
    }
    function inadd(){
        let parent = document.querySelector(".shoppingmall-box2-body");
        let child = parent.childElementCount + 1;
        let addChild = ''
        for(let i=child; i<child+10; i++){
            addChild = addChild + `<div>
                <div class="shoppingmall-box2-num">${i}</div>
                <div class="shoppingmall-box2-name"><input type="text"></div>
            </div>`
        }
        // document.parent.appendChild(addChild);
        $(parent).append(addChild);
    }

</script>

