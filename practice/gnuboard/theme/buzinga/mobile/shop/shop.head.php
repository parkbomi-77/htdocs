<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');

add_javascript('<script src="'.G5_THEME_JS_URL.'/owl.carousel.min.js"></script>', 10);
add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_JS_URL.'/owl.carousel.css">', 0);


add_javascript('<script src="'.G5_THEME_JS_URL.'/jquery.sidr.min.js"></script>', 0);
add_javascript('<script src="'.G5_THEME_JS_URL.'/unslider.min.js"></script>', 10);
?>

<header id="hd">
    <?php if ((!$bo_table || $w == 's' ) && defined('_INDEX_')) { ?><h1><?php echo $config['cf_title'] ?></h1><?php } ?>

    <div id="skip_to_container"><a href="#container">본문 바로가기</a></div>

    <?php if(defined('_INDEX_')) { // index에서만 실행
        include G5_MOBILE_PATH.'/newwin.inc.php'; // 팝업레이어
    } ?>

    <div id="hd_wr">
    	<div id="hd_wr_inner">
	        <div id="logo"><a href="<?php echo G5_SHOP_URL; ?>/"><img src="<?php echo G5_THEME_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?> 메인"></a></div>
	        <div id="scroll_top_gnb">
			  <?php include_once(G5_MSHOP_SKIN_PATH.'/boxcategory.skin.php'); // 상품분류 ?>
			</div>
			
			<div id="hd_btn">
	            <button type="button" id="btn_hdcate"><i class="fa fa-bars" aria-hidden="true"></i><span class="sound_only">분류</span></button>
				<div class="hd_right_btn">
					<?php if ($is_member) {  ?>
					<a href="<?php echo G5_SHOP_URL; ?>/wishlist.php"><i class="far fa-heart"></i><span class="sound_only">위시리스트</span></a>
					<?php } ?>
					<div class="hd_search btn_align">
						<button class="search_toggle tnb_btn"><i class="fas fa-search"></i><span class="sound_only">검색창 열기</span></button>
			            <div class="tnb_con">
			            	<h3>쇼핑몰 검색</h3>
				            <form name="frmsearch1" action="<?php echo G5_SHOP_URL; ?>/search.php" onsubmit="return search_submit(this);">
				            <label for="sch_str" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
				            <input type="text" name="q" value="<?php echo stripslashes(get_text(get_search_string($q))); ?>" id="sch_str" required>
				            <button type="submit" id="sch_submit"><i class="fa fa-search" aria-hidden="true"></i><span class="sound_only">검색</span></button>
				            </form>
			            	<?php echo popular('theme/basic'); ?>
			            	<button type="button" class="btn_close"><i class="fa fa-times"></i><span class="sound_only">쇼핑몰 검색 닫기</span></button>
			            </div>
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
			        </div>
			        <a href="<?php echo G5_SHOP_URL; ?>/cart.php" class="cart"><i class="fas fa-shopping-basket"></i><span class="sound_only">장바구니</span><span class="cart-count"><?php echo get_boxcart_datas_count(); ?></span></a>
	        		<?php if ($is_member) {  ?>
					<div class="btn_align">
						<button class="member_toggle tnb_btn"><i class="fas fa-user"></i><span class="sound_only">나의메뉴</span></button>
						<?php echo outlogin('theme/shop_basic'); // 외부 로그인 ?>
					</div>
					<?php } else {  ?>
					<a href="<?php echo G5_BBS_URL ?>/login.php" class="join_btn">
						<i class="fas fa-user"></i><span class="sound_only">로그인</span>
						<div id="animated-example" class="animated2 bounce2">+1000</div>
					</a>
					<?php } ?>
	        	</div>
	        </div>
        </div>
    </div>
    <script>
    $( document ).ready( function() {
        var jbOffset = $( '#hd_wr' ).offset();
        $( window ).scroll( function() {
            if ( $( document ).scrollTop() > jbOffset.top ) {
                $( '#hd_wr' ).addClass( 'fixed' );
            }
            else {
                $( '#hd_wr' ).removeClass( 'fixed' );
            }
        });
    });

    $(document).ready(function() {
      $('#btn_hdcate, .menu_close').sidr();
    });

	$(".hd_right_btn .btn_close").click(function(e) {
		$(".tnb_con").hide();
	});

	$(".hd_right_btn .tnb_btn:not(:only-child)").click(function(e) {
	    $(this).siblings(".tnb_con").toggle();
	
	    $(".tnb_con").not($(this).siblings()).hide();
	    e.stopPropagation();
	
	    $("#wrapper").on("click", function() {
	        $(".tnb_con").hide();
	    });
	});
   </script>
</header>

<div id="wrapper">
	<div id="container">
	    <?php if ((!$bo_table || $w == 's' ) && !defined('_INDEX_')) { ?><h1 id="container_title"><?php echo $g5['title'] ?></h1><?php } ?>
