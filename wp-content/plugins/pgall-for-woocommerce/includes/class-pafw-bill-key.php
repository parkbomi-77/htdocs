<?php



if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PAFW_Bill_Key' ) ) {
	class PAFW_Bill_Key {
		protected static $payment_gateways = null;

		public static function get_order( $order, $order_id ) {
			if ( false !== strpos( $order_id, 'PAFW-BILL' ) ) {
				$customer_id = str_replace( 'PAFW-BILL-', '', $order_id );

				if ( ! is_numeric( $customer_id ) ) {
					$customer_id = get_transient( '_pafw_' . $order_id );
				}

				$order = get_userdata( $customer_id );
			}

			return $order;
		}
		public static function get_payment_gateways() {
			if ( is_null( self::$payment_gateways ) ) {
				self::$payment_gateways = array ();
				foreach ( WC()->payment_gateways()->payment_gateways() as $payment_gateway ) {
					if ( 'yes' == $payment_gateway->enabled && $payment_gateway->supports( 'subscriptions' ) && 'user' == pafw_get( $payment_gateway->settings, 'management_batch_key', 'subscription' ) ) {
						self::$payment_gateways[] = $payment_gateway;
					}
				}
			}

			return self::$payment_gateways;
		}

		public static function add_account_menu_items( $items ) {

			if ( ! empty( self::get_payment_gateways() ) ) {
				$items = array_merge(
					$items,
					array ( 'pafw-card' => __( '결제수단 관리', 'pgall-for-woocommerce' ) )
				);
			}

			return $items;
		}

		public static function card_info() {
			$payment_gateways = self::get_payment_gateways();

			if ( ! empty( $payment_gateways ) ) {
				wc_get_template( 'pafw/payment_methods.php', array ( 'payment_gateways' => $payment_gateways ), '', PAFW()->template_path() );
			}
		}
	}

}