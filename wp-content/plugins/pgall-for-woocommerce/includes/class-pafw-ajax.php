<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
class PAFW_Ajax {
	static $slug;
	public static function init() {
		self::$slug = PAFW()->slug();
		self::add_ajax_events();
	}
	public static function add_ajax_events() {

		$ajax_events = array(
			'pafw_ajax_action'         => true,
			'pafw_simple_payment'      => true,
			'request_exchange_return'  => true,
			'launch_payment'           => true,
			'change_next_payment_date' => false,
			'survey_cancel_reason'     => true,
		);

		if ( is_admin() ) {
			$ajax_events = array_merge( $ajax_events, array(
				'update_pafw_settings'                        => false,
				'update_pafw_review_settings'                 => false,
				'update_pafw_payment_method_control_settings' => false,
				'update_pafw_order_status_control_settings'   => false,
				'update_inicis_settings'                      => false,
				'update_nicepay_settings'                     => false,
				'update_kcp_settings'                         => false,
				'update_lguplus_settings'                     => false,
				'update_payco_settings'                       => false,
				'update_kakaopay_settings'                    => false,
				'update_kicc_settings'                        => false,
				'update_npay_settings'                        => false,
				'update_settlebank_settings'                  => false,
				'update_settlevbank_settings'                 => false,
				'update_settlepg_settings'                    => false,
				'pafw_sales_action'                           => false,
				'pafw_payment_statistics_action'              => false,
				'agree_to_tac'                                => false,
				'target_search'                               => false,
				'cancel_subscription'                         => false,
				'pafw_cash_receipt'                           => false,
				'pafw_cancel_receipt'                         => false,
				'pafw_view_receipt'                           => false,
				'pafw_update_receipt_info'                    => false,
				'get_cash_receipts'                           => false,
				'pafw_search_user'                            => false,
			) );
		}

		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_' . self::$slug . '-' . $ajax_event, array( __CLASS__, $ajax_event ) );

			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_' . self::$slug . '-' . $ajax_event, array( __CLASS__, $ajax_event ) );
			}
		}
		add_action( 'wp_ajax_woocommerce_delete_refund', array( __CLASS__, 'delete_exchange_return' ), 1 );
	}

	public static function update_pafw_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		PAFW_Admin_Settings::update_settings();
	}

	public static function update_pafw_review_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		PAFW_Admin_Review_Settings::update_settings();
	}

	public static function update_pafw_payment_method_control_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		PAFW_Admin_Payment_Method_Control_Settings::update_settings();
	}

	public static function update_pafw_order_status_control_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		PAFW_Admin_Order_Status_Control_Settings::update_settings();
	}

	public static function update_inicis_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_Inicis::update_settings();
	}

	public static function update_nicepay_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_Nicepay::update_settings();
	}

	public static function update_kcp_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_Kcp::update_settings();
	}

	public static function update_lguplus_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_LGUPlus::update_settings();
	}

	public static function update_payco_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_Payco::update_settings();
	}

	public static function update_kakaopay_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_KakaoPay::update_settings();
	}

	public static function update_kicc_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_KICC::update_settings();
	}

	public static function update_npay_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_NPay::update_settings();
	}

	public static function update_settlebank_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_Settlebank::update_settings();
	}

	public static function update_settlevbank_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_Settlevbank::update_settings();
	}

	public static function update_settlepg_settings() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		WC_Gateway_PAFW_SettlePG::update_settings();
	}
	public static function pafw_ajax_action() {

		try {
			if ( isset( $_REQUEST['payment_method'] ) && isset( $_REQUEST['payment_action'] ) ) {
				$payment_method = wc_clean( $_REQUEST['payment_method'] );
				$payment_action = wc_clean( $_REQUEST['payment_action'] );

				$payment_gateway = pafw_get_payment_gateway( $payment_method );

				if ( $payment_gateway && is_callable( array( $payment_gateway, $payment_action ) ) ) {
					$payment_gateway->$payment_action();
				}
			}

			wp_send_json_error( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}

	}
	public static function pafw_sales_action() {
		try {
			if ( isset( $_REQUEST['command'] ) ) {
				$command = wc_clean( $_REQUEST['command'] );

				if ( is_callable( array( 'PAFW_Admin_Sales', $command ) ) ) {
					PAFW_Admin_Sales::$command();
				}
			}

			wp_send_json_error( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}

	}
	public static function pafw_payment_statistics_action() {
		try {
			if ( isset( $_REQUEST['command'] ) ) {
				$command = wc_clean( $_REQUEST['command'] );

				if ( is_callable( array( 'PAFW_Admin_Payment_Statistics', $command ) ) ) {
					PAFW_Admin_Payment_Statistics::$command();
				}
			}

			wp_send_json_error( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}

	}
	public static function request_exchange_return() {
		try {
			check_ajax_referer( 'request_exchange_return' );

			$exchange_return_order = PAFW_Exchange_Return_Manager::create_exchange_return( $_REQUEST );

			if ( is_wp_error( $exchange_return_order ) ) {
				throw new Exception( $exchange_return_order->get_error_messages() );
			}
			WC()->mailer();
			do_action( 'pafw-' . wc_clean( $_REQUEST['type'] ) . '-request-notification', $exchange_return_order->get_id(), $exchange_return_order );

			$message = sprintf( __( '%s ????????? ?????????????????????.', 'pgall-for-woocommerce' ), 'exchange' == wc_clean( $_REQUEST['type'] ) ? __( '??????', 'pgall-for-woocommerce' ) : __( '??????', 'pgall-for-woocommerce' ) );

			$parent_order = wc_get_order( absint( wp_unslash( $_REQUEST['order_id'] ) ) );
			$parent_order->update_status( wc_clean( $_REQUEST['type'] ) . '-request', $message );

			$redirect_url = pafw_get( $_REQUEST, 'redirect_url', wc_get_account_endpoint_url( 'orders' ) );

			wp_send_json_success( array( 'message' => $message, 'redirect_url' => $redirect_url ) );
		} catch ( Exception $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	public static function delete_exchange_return() {
		check_ajax_referer( 'order-item', 'security' );

		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			die( - 1 );
		}

		$exchange_return_ids = array_map( 'absint', is_array( $_POST['refund_id'] ) ? $_POST['refund_id'] : array( $_POST['refund_id'] ) );
		foreach ( $exchange_return_ids as $exchange_return_id ) {
			if ( $exchange_return_id && 'shop_order_pafw_ex' === get_post_type( $exchange_return_id ) ) {
				$order_id = wp_get_post_parent_id( $exchange_return_id );
				wc_delete_shop_order_transients( $order_id );
				wp_delete_post( $exchange_return_id );
				do_action( 'pafw_exchange_return_deleted', $exchange_return_id, $order_id );
			}
		}
	}

	public static function agree_to_tac() {
		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			die( - 1 );
		}

		update_option( PAFW()->slug() . '-agree-to-tac', 'yes', false );

		wp_send_json_success( array( 'reload' => true ) );
	}
	static function make_taxonomy_tree( $taxonomy, $args, $depth = 0, $parent = 0, $paths = array() ) {
		$results = array();

		$args['parent'] = $parent;
		$terms          = get_terms( $taxonomy, $args );

		foreach ( $terms as $term ) {
			$current_paths = array_merge( $paths, array( $term->name ) );
			$results[]     = array(
				"name"  => '<span class="tree-indicator-desc">' . implode( '-', $current_paths ) . '</span><span class="tree-indicator" style="margin-left: ' . ( $depth * 8 ) . 'px;">' . $term->name . '</span>',
				"value" => $term->term_id
			);

			$results = array_merge( $results, self::make_taxonomy_tree( $taxonomy, $args, $depth + 1, $term->term_id, $current_paths ) );
		}

		return $results;
	}
	static function target_search_category( $depth = 0, $parent = 0 ) {
		$args = array();

		if ( ! empty( $_REQUEST['args'] ) ) {
			$args['name__like'] = sanitize_title_for_query( $_REQUEST['args'] );
		}

		$results = self::make_taxonomy_tree( 'product_cat', $args );

		$respose = array(
			'success' => true,
			'results' => $results
		);

		echo json_encode( $respose );
		die();
	}
	static function target_search_attributes() {
		$results = array();

		foreach ( wc_get_attribute_taxonomies() as $attribute_taxonomy ) {
			$terms = get_terms( wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name ) );
			foreach ( $terms as $term ) {
				$label     = $attribute_taxonomy->attribute_label . ' - ' . $term->name;
				$results[] = array(
					"name"  => '<span class="tree-indicator-desc">' . $label . '</span><span class="tree-indicator">' . $label . '</span>',
					"value" => $term->term_id
				);
			}
		}

		$response = array(
			'success' => true,
			'results' => $results
		);

		echo json_encode( $response );
		die();
	}
	static function target_search_product_posts_title_like( $where, &$wp_query ) {
		global $wpdb;
		if ( $posts_title = $wp_query->get( 'posts_title' ) ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE "%' . $posts_title . '%"';
		}

		return $where;
	}
	static function target_search_product() {
		$keyword = ! empty( $_REQUEST['args'] ) ? sanitize_title_for_query( $_REQUEST['args'] ) : '';

		add_filter( 'posts_where', array( __CLASS__, 'target_search_product_posts_title_like' ), 10, 2 );

		$args = array(
			'post_type'      => 'product',
			'posts_title'    => $keyword,
			'post_status'    => 'publish',
			'posts_per_page' => - 1
		);

		$query = new WP_Query( $args );

		remove_filter( 'posts_where', array( __CLASS__, 'target_search_product_posts_title_like' ) );

		$results = array();

		foreach ( $query->posts as $post ) {
			$results[] = array(
				"name"  => $post->post_title,
				"value" => $post->ID
			);
		}
		$respose = array(
			'success' => true,
			'results' => $results
		);

		echo json_encode( $respose );

		die();
	}

	public static function target_search() {
		if ( ! empty( $_REQUEST['type'] ) ) {
			$type = wc_clean( $_REQUEST['type'] );

			switch ( $type ) {
				case 'product' :
				case 'product-category' :
					self::target_search_product();
					break;
				case 'category' :
					self::target_search_category();
					break;
				case 'attributes' :
					self::target_search_attributes();
					break;
				default:
					die();
			}
		}
	}

	public static function launch_payment() {
		PAFW_Shortcodes::launch_payment();
	}
	public static function pafw_simple_payment() {

		try {
			if ( isset( $_REQUEST['payment_method'] ) ) {
				$order = PAFW_Simple_Pay::get_order_for_simple_payment( wc_clean( $_REQUEST['_pafw_uid'] ) );

				$payment_gateway = pafw_get_payment_gateway( wc_clean( $_REQUEST['payment_method'] ) );

				if ( $payment_gateway ) {
					$result = $payment_gateway->process_payment( $order->get_id() );

					if ( $result && 'success' == $result['result'] ) {
						die( json_encode( $result ) );
					} else {
						$result = array(
							'result'   => 'fail',
							'messages' => wc_print_notices( true )
						);

						die( json_encode( $result ) );
					}
				}
			}

			throw new Exception( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
		} catch ( Exception $e ) {
			ob_start();

			wc_get_template( "notices/error.php", array(
				'messages' => array( $e->getMessage() ),
				'notices'  => array( array( 'notice' => $e->getMessage() ) ),
			) );

			$notices = ob_get_clean();

			die( json_encode( array(
				'result'   => 'fail',
				'messages' => $notices
			) ) );
		}
	}

	public static function change_next_payment_date() {
		try {
			if ( 'yes' != get_option( 'pafw-subscription-allow-change-date', 'no' ) ) {
				throw new Exception( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
			}

			if ( ! is_user_logged_in() || empty( $_POST['subscription_id'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'pgall-for-woocommerce' ) ) {
				throw new Exception( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
			}

			$subscription = wcs_get_subscription( absint( wp_unslash( $_POST['subscription_id'] ) ) );

			if ( ! is_a( $subscription, 'WC_Subscription' ) || 'active' != $subscription->get_status() || ! $subscription->can_date_be_updated( 'next_payment' ) || get_current_user_id() != $subscription->get_customer_id() ) {
				throw new Exception( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
			}

			$renewal_time = pafw_get_renewal_time( '12:00:00' );

			$next_payment_date = strtotime( wc_clean( $_POST['next_payment_date'] ) . ' ' . $renewal_time ) - get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS;

			if ( $next_payment_date < time() ) {
				throw new Exception( __( '?????? ???????????? ?????? ???????????? ???????????? ??? ????????????.', 'pgall-for-woocommerce' ) );
			}

			$subscription->update_dates( array( 'next_payment' => date( 'Y-m-d H:i:s', $next_payment_date ) ) );

			wp_send_json_success();
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
	public static function cancel_subscription() {
		try {
			if ( ! is_user_logged_in() || empty( $_POST['subscription_id'] ) || empty( $_POST['cancel_reason'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'pgall-for-woocommerce' ) ) {
				throw new Exception( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
			}

			$subscription = wcs_get_subscription( absint( wp_unslash( $_POST['subscription_id'] ) ) );

			if ( ! is_a( $subscription, 'WC_Subscription' ) || 'active' != $subscription->get_status() || get_current_user_id() != $subscription->get_customer_id() ) {
				throw new Exception( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
			}

			do_action( 'pafw_before_cancel_subscription', $subscription, $_POST['cancel_reason'] );

			$subscription->add_order_note( sprintf( __( '????????? ????????? ?????????????????????.<br>[????????????] %s', 'pgall-for-woocommerce' ), wc_clean( $_REQUEST['cancel_reason'] ) ) );

			$subscription->update_meta_data( '_pafw_cancel_reason', wc_clean( $_REQUEST['cancel_reason'] ) );

			WCS_User_Change_Status_Handler::change_users_subscription( $subscription, 'cancelled' );

			do_action( 'pafw_after_cancel_subscription', $subscription, $_POST['cancel_reason'] );

			wp_send_json_success();
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
	public static function survey_cancel_reason() {
		try {
			if ( empty( $_POST['cancel_reason'] ) || empty( $_POST['redirect_url'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'pgall-for-woocommerce' ) ) {
				throw new Exception( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
			}

			if ( ! is_user_logged_in() && 'no' == get_option( 'pafw-gw-support-cancel-guest-order', 'no' ) ) {
				throw new Exception( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
			}

			$url    = parse_url( wc_clean( $_REQUEST['redirect_url'] ) );
			$params = array();
			parse_str( $url['query'], $params );

			$order = wc_get_order( $params['order_id'] );

			if ( ! is_a( $order, 'WC_Order' ) || $order->get_order_key() != $params['order_key'] ) {
				throw new Exception( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
			}

			if ( ( is_user_logged_in() && $order->get_customer_id() == get_current_user_id() ) || ( ! is_user_logged_in() && 'yes' == get_option( 'pafw-gw-support-cancel-guest-order', 'no' ) ) ) {
				$order->add_order_note( sprintf( __( '????????? ????????? ?????????????????????.<br>[????????????] %s', 'pgall-for-woocommerce' ), wc_clean( $_REQUEST['cancel_reason'] ) ) );

				$order->update_meta_data( '_pafw_cancel_reason', wc_clean( $_REQUEST['cancel_reason'] ) );

				wp_send_json_success();
			} else {
				throw new Exception( __( '????????? ???????????????.', 'pgall-for-woocommerce' ) );
			}
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
	public static function pafw_cash_receipt() {
		if ( ! current_user_can( 'manage_woocommerce' ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'pgall-for-woocommerce' ) ) {
			die();
		}

		try {
			$gateway = PAFW_Cash_Receipt::get_gateway();

			if ( is_null( $gateway ) ) {
				throw new Exception( __( '??????????????? ????????? ???????????? ?????????????????? ????????????.', 'pgall-for-woocommerce' ) );
			}

			$gateway->issue_cash_receipt( $_POST['order_id'] );

			wp_send_json_success( __( '?????????????????? ?????????????????????.', 'pgall-for-woocommerce' ) );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
	public static function pafw_cancel_receipt() {
		if ( ! current_user_can( 'manage_woocommerce' ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'pgall-for-woocommerce' ) ) {
			die();
		}

		try {
			$gateway = PAFW_Cash_Receipt::get_gateway();

			if ( is_null( $gateway ) ) {
				throw new Exception( __( '??????????????? ????????? ???????????? ?????????????????? ????????????.', 'pgall-for-woocommerce' ) );
			}

			$gateway->cancel_cash_receipt( $_POST['order_id'] );

			wp_send_json_success( __( '????????? ?????????????????? ?????????????????????.', 'pgall-for-woocommerce' ) );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
	public static function pafw_search_user() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		global $wpdb;

		$results = array();

		$keyword = isset( $_REQUEST['args'] ) ? esc_attr( $_REQUEST['args'] ) : '';

		$sql = "SELECT user.ID
				FROM {$wpdb->users} user
				WHERE
				    user.user_login like '%{$keyword}%'
				    OR user.user_nicename like '%{$keyword}%'
				    OR user.display_name like '%{$keyword}%'
				    OR user.user_email like '%{$keyword}%'
				LIMIT 20";


		$user_ids = $wpdb->get_col( $sql );

		foreach ( $user_ids as $user_id ) {
			$user      = get_user_by( 'id', $user_id );
			$results[] = array(
				"value" => $user->ID,
				"name"  => $user->data->display_name . ' ( #' . $user->ID . ' - ' . $user->data->user_email . ', ' . $user->billing_last_name . $user->billing_first_name . ')'
			);
		}

		echo json_encode( array(
			'success' => true,
			'results' => $results
		) );

		die();
	}
	public static function pafw_update_receipt_info() {
		if ( ! current_user_can( 'manage_woocommerce' ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'pgall-for-woocommerce' ) || empty( $_POST['order_id'] ) ) {
			die();
		}

		try {
			$order = wc_get_order( $_POST['order_id'] );

			if ( $order ) {

				if ( 'yes' != $order->get_meta_data( '_pafw_bacs_receipt' ) ) {
					$order->update_meta_data( '_pafw_bacs_receipt', 'yes' );

					PAFW_Cash_Receipt::insert_receipt_request( $order );
				}

				$order->update_meta_data( '_pafw_bacs_receipt_usage', $_POST['receipt_usage'] );
				$order->update_meta_data( '_pafw_bacs_receipt_issue_type', $_POST['receipt_issue_type'] );
				$order->update_meta_data( '_pafw_bacs_receipt_reg_number', $_POST[ 'reg_number_' . $_POST['receipt_usage'] ] );
				$order->save_meta_data();
			}

			wp_send_json_success( __( '??????????????? ??????????????? ???????????? ???????????????.', 'pgall-for-woocommerce' ) );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
	public static function get_cash_receipts() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die();
		}

		try {

			$receipt_requests = PAFW_Cash_Receipt::get_receipt_requests( $_POST );

			foreach ( $receipt_requests['results'] as &$request ) {
				$order = wc_get_order( $request['order_id'] );

				if ( $order ) {
					$request['order'] = sprintf( "<a href='%s' target=_blank>#%d</a>", get_edit_post_link( $request['order_id'] ), $request['order_id'] );
					if ( ! empty( $request['customer_id'] ) ) {
						$request['customer'] = sprintf( "<a href='%s' target=_blank>%s (#%d)</a>", get_edit_user_link( $request['customer_id'] ), $order->get_billing_last_name() . $order->get_billing_first_name(), $request['customer_id'] );
					} else {
						$request['customer'] = __( '?????????', 'pgall-for-woocommerce' );
					}

					$request['date'] = date( 'Y-m-d', strtotime( $request['date'] ) );

					$request['status_label'] = PAFW_Cash_Receipt::get_status_name( $request['status'] );
					$request['total_price']  = wc_price( $order->get_meta( '_pafw_bacs_receipt_total_price' ) );
					$request['usage']        = sprintf( "%s<br>%s", PAFW_Cash_Receipt::get_usage_label( $order->get_meta( '_pafw_bacs_receipt_usage' ) ), $order->get_meta( '_pafw_bacs_receipt_reg_number' ) );
				} else {
					$request['order'] = sprintf( "#%d", $request['order_id'] );
					if ( ! empty( $request['customer_id'] ) ) {
						$user = new WC_Customer( $request['customer_id'] );

						$request['customer'] = sprintf( "<a href='%s' target=_blank>%s (#%d)</a>", get_edit_user_link( $request['customer_id'] ), $user->get_billing_last_name() . $user->get_billing_first_name(), $request['customer_id'] );
					} else {
						$request['customer'] = __( '?????????', 'pgall-for-woocommerce' );
					}

					$request['date'] = date( 'Y-m-d', strtotime( $request['date'] ) );

					$request['status_label'] = PAFW_Cash_Receipt::get_status_name( $request['status'] );
					$request['total_price']  = '-';
					$request['usage']        = '-';
				}
			}

			wp_send_json_success( $receipt_requests );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
	public static function pafw_view_receipt() {
		if ( ! current_user_can( 'manage_woocommerce' ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'pgall-for-woocommerce' ) ) {
			die();
		}

		try {
			$order = wc_get_order( $_POST['order_id'] );

			$payment_gateway = pafw_get_payment_gateway_from_order( $order );

			ob_start();
			wc_get_template( 'pafw/cash-receipt.php', array( 'order' => $order, 'payment_gateway' => $payment_gateway ), '', PAFW()->template_path() );
			$receipt = ob_get_clean();
			wp_send_json_success( $receipt );
		} catch ( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
}

PAFW_Ajax::init();