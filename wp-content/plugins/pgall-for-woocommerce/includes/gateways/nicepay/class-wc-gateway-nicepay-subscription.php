<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Payment_Gateway' ) ) {

	if ( ! class_exists( 'WC_Gateway_Nicepay_Subscription' ) ) {

		class WC_Gateway_Nicepay_Subscription extends WC_Gateway_Nicepay {
			public function __construct() {
				$this->id = 'nicepay_subscription';

				parent::__construct();

				if ( empty( $this->settings['title'] ) ) {
					$this->title       = __( '나이스페이 정기결제', 'pgall-for-woocommerce' );
					$this->description = __( '나이스페이 정기결제를 진행합니다.', 'pgall-for-woocommerce' );
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

				add_filter( 'pafw_subscription_register_complete_params_' . $this->id, array( $this, 'add_subscription_register_complete_params' ), 10, 2 );
				add_filter( 'pafw_bill_key_params_' . $this->id, array( $this, 'add_bill_key_request_params' ), 10, 2 );
			}

			function issue_bill_key_mode() {
				return 'api';
			}

			function adjust_settings() {
				$this->settings['merchant_id']    = $this->settings['subscription_merchant_id'];
				$this->settings['merchant_key']   = $this->settings['subscription_merchant_key'];
				$this->settings['cancel_pw']      = $this->settings['subscription_cancel_pw'];
				$this->settings['operation_mode'] = $this->settings['operation_mode_subscription'];
				$this->settings['test_user_id']   = $this->settings['test_user_id_subscription'];
			}
			public function add_subscription_register_complete_params( $params, $order ) {
				$payment_info = array();

				parse_str( $_POST['params'], $payment_info ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				$payment_info = apply_filters( 'pafw_subscription_payment_info', $payment_info, $this );

				$params[ $this->get_master_id() ] = array(
					'order_id'     => 'PAFW-BILL-' . strtoupper( bin2hex( openssl_random_pseudo_bytes( 6 ) ) ),
					'card_no'      => pafw_get( $payment_info, 'pafw_' . $this->get_master_id() . '_card_no' ),
					'expiry_year'  => pafw_get( $payment_info, 'pafw_' . $this->get_master_id() . '_expiry_year' ),
					'expiry_month' => pafw_get( $payment_info, 'pafw_' . $this->get_master_id() . '_expiry_month' ),
					'cert_no'      => pafw_get( $payment_info, 'pafw_' . $this->get_master_id() . '_cert_no' ),
					'password'     => pafw_get( $payment_info, 'pafw_' . $this->get_master_id() . '_card_pw' ),
					'card_type'    => pafw_get( $payment_info, 'pafw_' . $this->get_master_id() . '_card_type' )
				);

				return $params;
			}
			public function add_bill_key_request_params( $params, $order ) {
				if ( 'process_order_pay' == pafw_get( $_REQUEST, 'payment_action' ) && ! empty( pafw_get( $_REQUEST, 'data' ) ) ) {
					$post_params = array();
					parse_str( pafw_get( $_REQUEST, 'data' ), $post_params );
				} else {
					$post_params = wc_clean( $_REQUEST );
				}

				$post_params = apply_filters( 'pafw_subscription_payment_info', $post_params, $this );

				$params[ $this->get_master_id() ] = array(
					'card_quota'   => pafw_get( $post_params, 'pafw_' . $this->master_id . '_card_quota', '00' ),
					'card_no'      => pafw_get( $post_params, 'pafw_' . $this->get_master_id() . '_card_no' ),
					'expiry_year'  => pafw_get( $post_params, 'pafw_' . $this->get_master_id() . '_expiry_year' ),
					'expiry_month' => pafw_get( $post_params, 'pafw_' . $this->get_master_id() . '_expiry_month' ),
					'cert_no'      => pafw_get( $post_params, 'pafw_' . $this->get_master_id() . '_cert_no' ),
					'password'     => pafw_get( $post_params, 'pafw_' . $this->get_master_id() . '_card_pw' ),
					'card_type'    => pafw_get( $post_params, 'pafw_' . $this->get_master_id() . '_card_type' )
				);

				return $params;
			}

			public function payment_fields() {
				if ( $this->is_available() ) {
					ob_start();
					wc_get_template( 'pafw/nicepay/form-payment-fields.php', array( 'gateway' => $this ), '', PAFW()->template_path() );
					ob_end_flush();
				}
			}
			public function maybe_clear_bill_key( $response, $order, $gateway, $user_id ) {
				$this->before_change_payment_method_for_subscription( $order, pafw_is_issue_bill_key_request( $this ) );
			}
			function process_payment( $order_id ) {
				$order = wc_get_order( $order_id );

				do_action( 'pafw_process_payment', $order );

				try {
					$bill_key = $this->get_bill_key( $order );

					if ( pafw_is_subscription( $order ) ) {
						add_action( 'pafw_before_update_bill_key', array( $this, 'maybe_clear_bill_key' ), 10, 4 );
						if ( pafw_is_issue_bill_key_request( $this ) ) {
							PAFW_Gateway::issue_bill_key( $order, $this );
						}

						$this->after_change_payment_method_for_subscription( $order );

						return array(
							'result'       => 'success',
							'redirect_url' => $order->get_view_order_url()
						);
					} else {
						if ( pafw_is_issue_bill_key_request( $this ) || empty( $bill_key ) ) {
							PAFW_Gateway::issue_bill_key( $order, $this );
						}

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

			public function subscription_payment_info() {
				$bill_key = get_user_meta( get_current_user_id(), $this->get_subscription_meta_key( 'bill_key' ), true );

				ob_start();

				wc_get_template( 'pafw/nicepay/card-info.php', array( 'payment_gateway' => $this, 'bill_key' => $bill_key ), '', PAFW()->template_path() );

				return ob_get_clean();
			}
			function register_payment_method() {
				try {
					$user = get_currentuserinfo();

					PAFW_Gateway::register_complete( $user, $this );

					wp_send_json_success();
				} catch ( Exception $e ) {
					wp_send_json_error( $e->getMessage() );
				}
			}
		}

	}
}
