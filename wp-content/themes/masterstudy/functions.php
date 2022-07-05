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