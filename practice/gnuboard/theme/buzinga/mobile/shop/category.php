<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function get_mshop_category($ca_id, $len)
{
    global $g5;

    $sql = " select ca_id, ca_name from {$g5['g5_shop_category_table']}
                where ca_use = '1' ";
    if($ca_id)
        $sql .= " and ca_id like '$ca_id%' ";
    $sql .= " and length(ca_id) = '$len' order by ca_order, ca_id ";

    return $sql;
}

$mshop_categories = get_shop_category_array(true);

// 일반회원에게 보여질 카테고리 
$membercate[0] = $mshop_categories['10'];
if($member['mb_level'] === "2") {
	$mshop_categories = '';
	$mshop_categories = $membercate;
}


?>

<div id="menu">
	<button type="button" class="menu_close"><span class="sound_only">카테고리닫기</span></button>
	<div id="category" class="menu">
		<div class="menu_wr">
	        <form name="frmsearch1" action="<?php echo G5_SHOP_URL; ?>/search.php" onsubmit="return search_submit(this);">
	        <aside id="hd_sch">
	            <div class="sch_inner">
	                <h2>상품 검색</h2>
	                <label for="sch_str" class="sound_only">상품명<strong class="sound_only"> 필수</strong></label>
	                <input type="text" name="q" value="<?php echo stripslashes(get_text(get_search_string($q))); ?>" id="sch_str" required class="frm_input" placeholder="검색어를 입력해주세요">
	                <button type="submit" value="검색" class="sch_submit"><i class="fa fa-search" aria-hidden="true"></i></button>
	            </div>
	        </aside>
	        </form>
	        <script>
	        function search_submit(f) {
	            if (f.q.value.length < 2) {
	                alert("검색어는 두글자 이상 입력하십시오.");
	                f.q.select();
	                f.q.focus();
	                return false;
	            }
	
	            return true;
	        }
	        </script>
	
	        <ul class="cate_tab">
	            <li><a href="#cate_01" class="selected">카테고리</a></li>
	            <li><a href="#cate_02">마이페이지</a></li>
	            <li><a href="#cate_03">오늘본상품</a></li>
	        </ul>
	        
	        <ul class="content">
	            <li id="cate_01" class="con">
	            <?php

	            $i = 0;
	            foreach($mshop_categories as $cate1){ 
	                if( empty($cate1) ) continue;

	                $mshop_ca_row1 = $cate1['text'];
	                if($i == 0)
	                    echo '<ul class="cate">'.PHP_EOL;
	            ?>
	                <li>
	                    <a href="<?php echo $mshop_ca_row1['url']; ?>"><?php echo get_text($mshop_ca_row1['ca_name']); ?></a>
	                    <?php
	                    if( count($cate1) > 1 )
	                        echo '<button class="sub_ct_toggle ct_op">'.get_text($mshop_ca_row1['ca_name']).' 하위분류 열기</button>'.PHP_EOL;
	
	                    $j=0;
	                    foreach($cate1 as $key=>$cate2){
	                        if( empty($cate2) || $key === 'text' ) continue;
	                        
	                        $mshop_ca_row2 = $cate2['text'];
	                        if($j == 0)
	                            echo '<ul class="sub_cate sub_cate1">'.PHP_EOL;
	                    ?>
	                        <li>
	                            <a href="<?php echo $mshop_ca_row2['url']; ?>"><?php echo get_text($mshop_ca_row2['ca_name']); ?></a>
	                            <?php
	                            $mshop_ca_res3 = sql_query(get_mshop_category($mshop_ca_row2['ca_id'], 6));
	                            if( count($cate2) > 1 )
	                                echo '<button type="button" class="sub_ct_toggle ct_op">'.get_text($mshop_ca_row2['ca_name']).' 하위분류 열기</button>'.PHP_EOL;
	                            
	                            $k = 0;
	                            foreach($cate2 as $cate3_key=>$cate3){
	                                if( empty($cate2) || $cate3_key === 'text' ) continue;
	                                
	                                $mshop_ca_row3 = $cate3['text'];
	                                if($k == 0)
	                                    echo '<ul class="sub_cate sub_cate2">'.PHP_EOL;
	                            ?>
	                                <li>
	                                    <a href="<?php echo $mshop_ca_row3['url']; ?>"><?php echo get_text($mshop_ca_row3['ca_name']); ?></a>
	                                    <?php
	                                    $mshop_ca_res4 = sql_query(get_mshop_category($mshop_ca_row3['ca_id'], 8));
	                                    if(sql_num_rows($mshop_ca_res4))
	                                        echo '<button type="button" class="sub_ct_toggle ct_op">'.get_text($mshop_ca_row3['ca_name']).' 하위분류 열기</button>'.PHP_EOL;
	
	                                    for($m=0; $mshop_ca_row4=sql_fetch_array($mshop_ca_res4); $m++) {
	                                        if($m == 0)
	                                            echo '<ul class="sub_cate sub_cate3">'.PHP_EOL;
	                                    ?>
	                                        <li>
	                                            <a href="<?php echo $mshop_ca_href.$mshop_ca_row4['ca_id']; ?>"><?php echo get_text($mshop_ca_row4['ca_name']); ?></a>
	                                            <?php
	                                            $mshop_ca_res5 = sql_query(get_mshop_category($mshop_ca_row4['ca_id'], 10));
	                                            if(sql_num_rows($mshop_ca_res5))
	                                                echo '<button type="button" class="sub_ct_toggle ct_op">'.get_text($mshop_ca_row4['ca_name']).' 하위분류 열기</button>'.PHP_EOL;
	
	                                            for($n=0; $mshop_ca_row5=sql_fetch_array($mshop_ca_res5); $n++) {
	                                                if($n == 0)
	                                                    echo '<ul class="sub_cate sub_cate4">'.PHP_EOL;
	                                            ?>
	                                                <li>
	                                                    <a href="<?php echo $mshop_ca_href.$mshop_ca_row5['ca_id']; ?>"><?php echo get_text($mshop_ca_row5['ca_name']); ?></a>
	                                                </li>
	                                            <?php
	                                            }
	
	                                            if($n > 0)
	                                                echo '</ul>'.PHP_EOL;
	                                            ?>
	                                        </li>
	                                    <?php
	                                    }
	
	                                    if($m > 0)
	                                        echo '</ul>'.PHP_EOL;
	                                    ?>
	                                </li>
	                            <?php
	                            $k++;
	                            }
	
	                            if($k > 0)
	                                echo '</ul>'.PHP_EOL;
	                            ?>
	                        </li>
	                    <?php
	                    $j++;
	                    }
	
	                    if($j > 0)
	                        echo '</ul>'.PHP_EOL;
	                    ?>
	                </li>
	            <?php
	            $i++;
	            }   // end for
	
	            if($i > 0)
	                echo '</ul>'.PHP_EOL;
	            else
	                echo '<p>등록된 분류가 없습니다.</p>'.PHP_EOL;
	            ?>
	        </li>
	            <li id="cate_02" class="con">
	                <ul id="hd_tnb" class="cate">
	                    <li class="bd"><a href="<?php echo G5_SHOP_URL; ?>/mypage.php">마이페이지</a></li>
	                    <li class="bd"><a href="<?php echo G5_SHOP_URL; ?>/orderinquiry.php">주문내역</a></li>
	                    <!-- <li class="bd"><a href="<?php echo G5_SHOP_URL; ?>/couponzone.php">쿠폰존</a></li> -->
	                    <li class="bd"><a href="<?php echo G5_BBS_URL; ?>/faq.php">FAQ</a></li>
	                    <li><a href="<?php echo G5_BBS_URL; ?>/qalist.php">1:1문의</a></li>
	                    <li><a href="<?php echo G5_SHOP_URL; ?>/personalpay.php">개인결제</a></li>
	                    <li><a href="<?php echo shop_type_url(5); ?>">세일상품</a></li>
	                </ul> 
	            </li>
	            <li id="cate_03" class="con"><?php include(G5_MSHOP_SKIN_PATH.'/boxtodayview.skin.php'); // 오늘 본 상품 ?></li>
	        </ul>
	    </div>
	</div>
</div>
<script>
jQuery(function ($){

    $("button.sub_ct_toggle").on("click", function() {
        var $this = $(this);
        $sub_ul = $(this).closest("li").children("ul.sub_cate");

        if($sub_ul.size() > 0) {
            var txt = $this.text();

            if($sub_ul.is(":visible")) {
                txt = txt.replace(/닫기$/, "열기");
                $this
                    .removeClass("ct_cl")
                    .text(txt);
            } else {
                txt = txt.replace(/열기$/, "닫기");
                $this
                    .addClass("ct_cl")
                    .text(txt);
            }

            $sub_ul.toggle();
        }
    });
    
	$(".content li.con").hide();
    $(".content li.con:first").show();   
    $(".cate_tab li a").click(function(){
        $(".cate_tab li a").removeClass("selected");
        $(this).addClass("selected");
        $(".content li.con").hide();
        //$($(this).attr("href")).show();
        $($(this).attr("href")).fadeIn();
    });
     
});

</script>

