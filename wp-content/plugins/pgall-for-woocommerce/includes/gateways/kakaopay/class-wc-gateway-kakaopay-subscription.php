<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	if ( ! class_exists( 'WC_Gateway_KakaoPay_Subscription' ) ) {

		class WC_Gateway_KakaoPay_Subscription extends WC_Gateway_KakaoPay {

			public function __construct() {

				$this->id = 'kakaopay_subscription';

				parent::__construct();

				if ( empty( $this->settings['title'] ) ) {
					$this->title       = __( '카카오페이 정기결제', 'pgall-for-woocommerce' );
					$this->description = __( '카카오페이 정기결제로 결제합니다.', 'pgall-for-woocommerce' );
				} else {
					$this->title       = $this->settings['title'];
					$this->description = $this->settings['description'];
				}

				$this->countries = array( 'KR' );
				$this->supports  = array(
					'products',
					'subscriptions',
					'multiple_subscriptions',
					'subscription_cancellation',
					'subscription_suspension',
					'subscription_reactivation',
					'subscription_amount_changes',
					'subscription_date_changes',
					'subscription_payment_method_change_customer',
					'pafw',
					'refunds',
					'pafw_additional_charge',
					'pafw_cancel_bill_key'
				);

				add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'woocommerce_scheduled_subscription_payment' ), 10, 2 );
				add_action( 'woocommerce_subscription_status_cancelled', array( $this, 'cancel_subscription' ) );
				add_action( 'woocommerce_subscription_cancelled_' . $this->id, array( $this, 'cancel_subscription' ) );

				add_action( 'woocommerce_subscriptions_pre_update_payment_method', array( $this, 'maybe_remove_subscription_cancelled_callback' ), 10, 3 );
				add_action( 'woocommerce_subscription_payment_method_updated', array( $this, 'maybe_reattach_subscription_cancelled_callback' ), 10, 3 );

				add_filter( 'pafw_register_order_params_' . $this->id, array( $this, 'add_register_order_request_params' ), 10, 2 );

				add_action( 'pafw_subscription_register_complete_response_' . $this->id, array( $this, 'process_subscription_register_complete_response' ), 10, 2 );
				add_action( 'pafw_subscription_payment_response_' . $this->id, array( $this, 'process_subscription_payment_response' ), 10, 2 );

				add_action( 'pafw_' . $this->id . '_register', array( $this, 'wc_api_request_register' ) );
			}

			function adjust_settings() {
				$this->settings['cid']            = $this->settings['cid_subscription'];
				$this->settings['operation_mode'] = $this->settings['operation_mode_subscription'];
				$this->settings['test_user_id']   = $this->settings['test_user_id_subscription'];
			}

			public function payment_fields() {
				if ( $this->is_available() ) {
					ob_start();
					wc_get_template( 'pafw/kakaopay/form-payment-fields.php', array( 'gateway' => $this ), '', PAFW()->template_path() );
					ob_end_flush();
				}
			}

			public function get_subscription_meta_key( $meta_key ) {
				if ( 'bill_key' == $meta_key ) {
					return '_pafw_subscription_batch_key';
				}

				return '_pafw_kakaopay_' . $meta_key;
			}
			public function add_register_order_request_params( $params, $order ) {
				$params['kakaopay'] = array(
					'is_subscription'  => pafw_is_subscription( $order ) ? 'yes' : 'no'
				);

				return $params;
			}
			function process_payment( $order_id ) {
				$order = wc_get_order( $order_id );

				do_action( 'pafw_process_payment', $order );

				try {
					$bill_key = $this->get_bill_key( $order );

					if ( pafw_is_subscription( $order ) ) {
						if ( pafw_is_issue_bill_key_request( $this ) ) {
							return parent::process_payment( $order_id );
						} else {
							$this->before_change_payment_method_for_subscription( $order, false );
							$this->after_change_payment_method_for_subscription( $order );

							return array(
								'result'       => 'success',
								'redirect_url' => $order->get_view_order_url()
							);
						}
					} else if ( pafw_is_issue_bill_key_request( $this ) || empty( $bill_key ) ) {
						return parent::process_payment( $order_id );
					} else {
						if ( $order->get_total() > 0 ) {
							PAFW_Gateway::request_subscription_payment( $order, $this );
						} else {
							$order->payment_complete();
						}

						return array(
							'result'       => 'success',
							'redirect_url' => $order->get_checkout_order_received_url()
						);
					}
				} catch ( Exception $e ) {
					$message = sprintf( "[결제오류] %s [%s]", $e->getMessage(), $e->getCode() );

					$order->add_order_note( $message );

					do_action( 'pafw_payment_fail', $order, $e->getCode(), $e->getMessage() );

					throw $e;
				}
			}
			public function process_subscription_payment_response( $order, $response ) {
				if ( ! defined( 'PAFW_ADDITIONAL_CHARGE' ) ) {
					$order->update_meta_data( '_pafw_subscription_batch_key', $response['bill_key'] );
					$order->save_meta_data();
				}
			}
			function process_subscription_register_complete_response( $user, $response ) {
				update_user_meta( $user->ID, $this->get_subscription_meta_key( 'payment_method_type' ), $response['payment_method_type'] );
			}
			function wc_api_request_register() {
				try {
					$user = null;

					if ( empty( $_GET['transaction_id'] ) || empty( $_GET['auth_token'] ) || empty( $_GET['user_id'] ) ) {
						throw new Exception( __( '잘못된 요청입니다.', 'pgall-for-woocommerce' ), '9000' );
					}

					$user_id = str_replace( 'PAFW-BILL-', '', wc_clean( $_GET['user_id'] ) );

					$user = get_userdata( $user_id );

					PAFW_Gateway::register_complete( $user, $this );

					PAFW_Gateway::redirect( $user, $this );
				} catch ( Exception $e ) {
					PAFW_Gateway::redirect( $user, $this, $e->getMessage(), false );
				}
			}
			function register_payment_method() {
				try {
					$user = get_currentuserinfo();

					$response = PAFW_Gateway::get_register_form( $user, $this );

					wp_send_json_success( array_merge( array(
						'result' => 'success'
					), $response ) );

				} catch ( Exception $e ) {
					wc_add_notice( $e->getMessage(), 'error' );
				}
			}
		}
	}

}
