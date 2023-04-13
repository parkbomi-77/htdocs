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

add_filter('woocommerce_save_account_details_required_fields', 'woocommerce_save_account_details_required_fields', 10, 1);
function woocommerce_save_account_details_required_fields($fields = array()) {
unset($fields['account_last_name']);
return $fields;
}

add_filter( 'msm_is_phone_certification_page', function ( $flag, $request_url, $redirect_url ) {
	if ( str_starts_with( $request_url, '/ko_kr/휴대폰 인증' ) ) {
		$flag = true;
	}

	return $flag;
}, 10, 3 );