<?php 
	add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
	function theme_enqueue_styles() {

		wp_enqueue_style( 'theme-style', get_stylesheet_uri(), null, STM_THEME_VERSION, 'all' );

		
	}
	function codem_prevent_auto_login_after_register( $user_id ) {
		if($_FILES['inputfield#0']){ //일반회원을 제외한 수의사, 수의대생만 자동로그인 방지
			wp_logout();
		}
	}
add_action( 'msm_user_registered', 'codem_prevent_auto_login_after_register' );