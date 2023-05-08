<?php
$theme_info = wp_get_theme();
define('STM_THEME_VERSION', ( WP_DEBUG ) ? time() : $theme_info->get( 'Version' ) );
define('STM_MS_SHORTCODES', '1' );

$inc_path = get_template_directory() . '/inc';

$widgets_path = get_template_directory() . '/inc/widgets';
// Theme setups

// Custom code and theme main setups
require_once($inc_path . '/setup.php');

// Header an Footer actions
require_once($inc_path . '/header.php');
require_once($inc_path . '/footer.php');

// Enqueue scripts and styles for theme
require_once($inc_path . '/scripts_styles.php');

/*Theme configs*/
require_once($inc_path . '/theme-config.php');

// Visual composer custom modules
if (defined('WPB_VC_VERSION')) {
	require_once($inc_path . '/visual_composer.php');
}

require_once($inc_path . '/elementor.php');

// Custom code for any outputs modifying
//require_once($inc_path . '/payment.php');
require_once($inc_path . '/custom.php');

// Custom code for woocommerce modifying
if (class_exists('WooCommerce')) {
	require_once($inc_path . '/woocommerce_setups.php');
}

if(defined('STM_LMS_URL')) {
	require_once($inc_path . '/lms/main.php');
}
function stm_glob_pagenow(){
    global $pagenow;
    return $pagenow;
}
function stm_glob_wpdb(){
    global $wpdb;
    return $wpdb;
}

if(class_exists('BuddyPress')) {
    require_once($inc_path . '/buddypress.php');
}

//Announcement banner
if (is_admin()) {
	require_once($inc_path . '/admin/generate_styles.php');
	require_once($inc_path . '/admin/admin_helpers.php');
	require_once($inc_path . '/admin/product_registration/admin.php');
	require_once($inc_path . '/tgm/tgm-plugin-registration.php');
}

/*
function wooc_extra_register_fields() {?>
	<p class="form-row">
		<label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?></label>
		<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
		<label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?></label>
		<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
	</p>
	<p class="form-row">
		<label for="reg_billing_company"><?php _e( 'Company', 'woocommerce' ); ?></label>
		<input type="text" class="input-text" name="billing_company" id="reg_billing_company" value="<?php if ( ! empty( $_POST['billing_company'] ) ) esc_attr_e( $_POST['billing_company'] ); ?>" />
	</p>
	<p class="form-row">
		<label for="reg_billing_address_1"><?php _e( '도로명 주소', 'woocommerce' ); ?></label>
		<input type="text" class="input-text" name="billing_address_1" id="reg_billing_address_1" value="<?php if ( ! empty( $_POST['billing_address_1'] ) ) esc_attr_e( $_POST['billing_address_1'] ); ?>" />
		<label for="reg_billing_address_2"><?php _e( '상세 주소', 'woocommerce' ); ?></label>
		<input type="text" class="input-text" name="billing_address_2" id="reg_billing_address_2" value="<?php if ( ! empty( $_POST['billing_address_2'] ) ) esc_attr_e( $_POST['billing_address_2'] ); ?>" />
	</p>
	<p class="form-row">
		<label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?></label>
		<input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e( $_POST['billing_phone'] ); ?>" />
	</p>
	<div class="clear"></div>
	<?php
}
add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );*/


add_filter('wp_head', function() {
	// 접속국가 코드에 따른 통화변경 
    global $WOOCS;
	$ipInfo = geoip_detect2_get_info_from_current_ip();
	$countryCode = $ipInfo->country->isoCode;

    switch ($countryCode)
    {
        case 'KR':
            $WOOCS->set_currency('USD');
            // $WOOCS->set_currency('KRW');

            break;

        default:
            $WOOCS->set_currency('USD');
            break;
    }
});

// 결제페이지에서 통화 강제로 바꾸기 
// add_filter('wp_head',function(){    
//     if(is_checkout()){
//         global $WOOCS;
// 		$ipInfo = geoip_detect2_get_info_from_current_ip();
// 		$countryCode = $ipInfo->country->isoCode;
	
// 		switch ($countryCode)
// 		{
// 			case 'KR':
// 				$WOOCS->set_currency('KRW');
// 				break;
	
// 			default:
// 				$WOOCS->set_currency('USD');
// 				break;
// 		}
//     }
// });

add_filter( 'woocommerce_available_payment_gateways', 'disable_payment_method_by_country' );

function disable_payment_method_by_country( $gateways ) {
	$ipInfo = geoip_detect2_get_info_from_current_ip();
	$customer_country = $ipInfo->country->isoCode;

    // 한국일 경우 페이팔 결제 안뜨도록 
    if ( $customer_country == 'KR' ) {
        unset( $gateways['ppcp-gateway'] ); 
    } else { // 해외일 경우 토스 안뜨도록 
		foreach ( $gateways as $gateway_id => $gateway ) {
			if ( $gateway_id !== 'ppcp-gateway' ) {
				unset( $gateways[ $gateway_id ] );
			}
		}
	}
    return $gateways;
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'myplugin/v1', '/login', array(
	   'methods' => 'POST',
	   'callback' => 'myplugin_login'
	) );
 } );
 function myplugin_login(WP_REST_Request $request ) {
    $creds = $request->get_params();
    $user = wp_signon( $creds, false );

	// 유저 아이디, 회원등급, UUID
	$user_login = $user->data->user_login;
	$user_uuid = $user->data->uuid;
	$user_class_arr = $user->roles;
	
	// 닉네임, 이메일 추출
	$user_display_name = $user->data->display_name;
	$user_email = $user->data->user_email;
	
	$user_id = $user->data->ID;
	// 휴대폰 번호 얻기
	$user_phone_number = get_user_meta( $user_id, 'billing_phone', true );

	// 우편번호, 주소, 상세주소
	$user_postcode = get_user_meta( $user_id, 'billing_postcode', true );
	$user_address = get_user_meta( $user_id, 'billing_address_1', true );
	$user_address2 = get_user_meta( $user_id, 'billing_address_2', true );

	// 수의사인지
	$vet_role_check = in_array('vet_role', $user_class_arr);
	// 수의대생인지 
	$student_role_check = in_array('student_role', $user_class_arr);
	// 대기회원
	$wait_check = in_array('wait', $user_class_arr);
	// 일반회원, 관리자
	$general_check = in_array('general_members', $user_class_arr);
	$administrator_check = in_array('administrator', $user_class_arr);

	$user_class = "customer";
	if($vet_role_check) {
		$user_class = "vet_role";
	}else if($student_role_check) {
		$user_class = "student_role";
	}else if($wait_check) {
		$user_class = "wait";
	}else if($general_check || $administrator_check) {
		$user_class = "general_members";
	}
	
    if ( is_wp_error( $user ) ) {
        $error_data = $user->get_error_data();
        if ( $error_data && isset( $error_data['type'] ) ) {
            $error_type = $error_data['type'];
            return new WP_Error( $error_type, $user->get_error_message( $error_type ), array( 'status' => 401 ) );
        }
        return new WP_Error( 'rest_login_failed', __( '로그인에 실패했습니다.' ), array( 'status' => 401 ) );
    }
    wp_set_current_user( $user->ID );
    wp_set_auth_cookie( $user->ID );
    do_action( 'wp_login', $user->user_login, $user );
    return new WP_REST_Response( array( 
		'user_login' => $user_login ,
		'user_class' => $user_class, 
		'user_uuid' => $user_uuid, 
		'user_display_name' => $user_display_name,
		'user_email' => $user_email,
		'user_phone_number' => $user_phone_number,
		'user_postcode' => $user_postcode,
		'user_address' => $user_address,
		'user_address2' => $user_address2
	), 200 );
}


// Add an out of stock overlay to product images when all variations are unavailable
//상품 재고가 떨어지면 상품 이미지에 Out of Stock 오버레이 추가

add_action( 'woocommerce_before_shop_loop_item_title', function() {
	global $product;
	if ( !$product->is_in_stock() ) {
	echo '<span class="sold-out-overlay">Sold Out</span>';
	}
});