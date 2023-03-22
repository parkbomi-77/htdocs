<?php
/**
 * Woocommerce-direct-checkout Plugin
 *
 * @package  WooCommerce Direct Checkout
 * @since    1.0.0
 */

namespace QuadLayers\WCDC\Controller;

/**
 * Controller Premium
 *
 * @class Premium
 * @version 1.0.0
 */
class Premium {

	/**
	 * The single instance of the class.
	 *
	 * @var WCDC
	 */
	protected static $instance;

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'qlwcdc_sections_header', array( __CLASS__, 'add_header' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
	}

	/**
	 * Instance
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add header
	 */
	public static function add_header() {
		?>
			<li><a href="<?php echo esc_url( admin_url( 'admin.php?page=' . QLWCDC_PREFIX ) ); ?>"><?php echo esc_html__( 'Premium', 'woocommerce-direct-checkout' ); ?></a></li> |
		<?php
	}

	/**
	 * Add page
	 * ADMIN
	 */
	public static function add_page() {
		include_once QLWCDC_PLUGIN_DIR . 'lib/view/backend/pages/premium.php';
	}

	/**
	 * Add menu
	 * ADMIN
	 */
	public static function add_menu() {
		add_menu_page( QLWCDC_PLUGIN_NAME, QLWCDC_PLUGIN_NAME, 'manage_woocommerce', QLWCDC_PREFIX, array( __CLASS__, 'add_page' ) );
		add_submenu_page( QLWCDC_PREFIX, esc_html__( 'Premium', 'woocommerce-direct-checkout' ), esc_html__( 'Premium', 'woocommerce-direct-checkout' ), 'manage_woocommerce', QLWCDC_PREFIX, array( __CLASS__, 'add_page' ) );
	}

}
