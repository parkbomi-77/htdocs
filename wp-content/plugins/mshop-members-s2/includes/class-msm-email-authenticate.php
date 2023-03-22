<?php

/*
=====================================================================================
                ﻿엠샵 멤버스 / Copyright 2015 by CodeM(c)
=====================================================================================

  [ 우커머스 버전 지원 안내 ]

   워드프레스 버전 : WordPress 4.3

   우커머스 버전 : WooCommerce 2.4


  [ 코드엠 플러그인 라이센스 규정 ]

   (주)코드엠에서 개발된 워드프레스  플러그인을 사용하시는 분들에게는 다음 사항에 대한 동의가 있는 것으로 간주합니다.

   1. 코드엠에서 개발한 워드프레스 우커머스용 엠샵 멤버스 플러그인의 저작권은 (주)코드엠에게 있습니다.
   
   2. 플러그인은 사용권을 구매하는 것이며, 프로그램 저작권에 대한 구매가 아닙니다.

   3. 플러그인을 구입하여 다수의 사이트에 복사하여 사용할 수 없으며, 1개의 라이센스는 1개의 사이트에만 사용할 수 있습니다. 
      이를 위반 시 지적 재산권에 대한 손해 배상 의무를 갖습니다.

   4. 플러그인은 구입 후 1년간 업데이트를 지원합니다.

   5. 플러그인은 워드프레스, 테마, 플러그인과의 호환성에 대한 책임이 없습니다.

   6. 플러그인 설치 후 버전에 관련한 운용 및 관리의 책임은 사이트 당사자에게 있습니다.

   7. 다운로드한 플러그인은 환불되지 않습니다.

=====================================================================================
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'MSM_Email_Authenticate' ) ) :

	class MSM_Email_Authenticate {
		public static function woocommerce_email_classes( $emails ) {
			$emails['MSM_Email_Authentication'] = include( 'emails/class-msm-email-authentication.php' );

			return $emails;
		}

		public static function get_user_role( $user ) {
			$user_roles = $user->roles;

			return array_shift( $user_roles );
		}

		public static function process_authentication( $login, $key ) {
			$user = get_user_by( 'login', $login );

			if ( $user instanceof WP_User ) {
				$valid_auth_key = get_user_meta( $user->ID, 'msm_email_auth_key', true );

				if ( $valid_auth_key == $key ) {
					update_user_meta( $user->ID, 'msm_email_certified', 'yes' );
					delete_user_meta( $user->ID, 'msm_email_auth_key' );

					if ( 'yes' == get_option( 'msm_change_role', 'no' ) ) {
						$user->set_role( get_option( 'msm_target_role' ) );
					}

					return true;
				}
			}

			return false;
		}
		public static function generate_authentication_key( $user ) {
			$key = wp_generate_password( 20, false );

			if ( empty( $wp_hasher ) ) {
				require_once ABSPATH . 'wp-includes/class-phpass.php';
				$wp_hasher = new PasswordHash( 8, true );
			}

			$hashed = $wp_hasher->HashPassword( $key );

			update_user_meta( $user->ID, 'msm_email_auth_key', $hashed );
		}
		public static function send_authentication_email( $user ) {
			if ( $user instanceof WP_User && is_email( $user->user_email ) ) {

				$social_provider = get_user_meta( $user->ID, 'wsl_current_provider', true );

				if ( 'yes' == get_option( 'msm_required', 'no' ) && ( 'yes' != get_option( 'msm_social_except', 'no' ) || empty( $social_provider ) ) ) {
					self::generate_authentication_key( $user );

					WC_Emails::instance();

					do_action( 'msm_send_authentication_email_notification', $user );
				}
			}
		}
		public static function user_register( $user_id ) {
			if ( class_exists( 'WooCommerce' ) ) {
				self::send_authentication_email( get_user_by( 'id', $user_id ) );
			}
		}
		protected static function get_certification_number() {
			return rand( 100000, 999999 );
		}
		public static function get_certification_hash( $certification_number, $salt ) {
			return md5( $certification_number . $salt );
		}
		public static function send_verification_email( $field_name, $field_value, $field_label, $form_slug ) {
			$user = get_user_by( 'email', $field_value );

			if ( $user ) {
				throw new Exception( __( '이미 사용중인 이메일입니다.', 'mshop-members-s2' ), 8100 );
			}

			delete_transient( 'msm_email_verification_' . $field_value );
			delete_transient( 'msm_email_verification_salt_' . $field_value );
			delete_transient( 'msm_email_verification_retry_' . $field_value );

			do_action( 'msm_before_send_verification_email', $field_name, $field_value, $field_label, $form_slug );

			$certification_number = self::get_certification_number();

			$blog_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

			ob_start();
			msm_get_template( 'emails/email-verification.php', array( 'blog_name' => $blog_name, 'certification_number' => $certification_number ), array(), MSM()->plugin_path() . '/templates/' );
			$content = ob_get_clean();

			wp_mail( $field_value, sprintf( __( "[%s] 이메일 인증번호를 알려드립니다.", "mshop-members-s2" ), $blog_name ), $content, array( 'Content-Type: text/html; charset=UTF-8' ) );

			$salt = bin2hex( random_bytes( 10 ) );
			$hash = self::get_certification_hash( $certification_number, $salt );

			$expiration = apply_filters( 'msm_email_verification_expiration', 10 * MINUTE_IN_SECONDS );

			set_transient( 'msm_email_verification_' . $field_value, $hash, $expiration );
			set_transient( 'msm_email_verification_salt_' . $field_value, $salt, $expiration );
			set_transient( 'msm_email_verification_retry_' . $field_value, 0, $expiration );

			do_action( 'msm_after_send_verification_email', $field_name, $field_value, $field_label, $form_slug, $hash );

			return $hash;
		}
		public static function validate_certification_number( $field_name, $field_value, $certificate_hash, $certification_number, $form_slug ) {
			$saved_hash  = get_transient( 'msm_email_verification_' . $field_value );
			$saved_salt  = get_transient( 'msm_email_verification_salt_' . $field_value );
			$retry_count = intval( get_transient( 'msm_email_verification_retry_' . $field_value ) );
			$expiration  = apply_filters( 'msm_email_verification_expiration', 10 * MINUTE_IN_SECONDS );

			if ( $retry_count >= 5 ) {
				throw new Exception( __( '인증 가능 횟수가 초과되었습니다.', 'mshop-members-s2' ), '2001' );
			} else {
				set_transient( 'msm_email_verification_retry_' . $field_value, $retry_count + 1, $expiration );
			}

			if ( empty( $saved_hash ) || empty( $saved_salt ) ) {
				throw new Exception( __( '인증 정보가 존재하지 않습니다.', 'mshop-members-s2' ), '2002' );
			}

			if ( $saved_hash != $certificate_hash ) {
				throw new Exception( __( '인증 정보가 올바르지 않습니다.', 'mshop-members-s2' ), '2003' );
			}

			if ( $saved_hash != MSM_Phone_Certification::get_certification_hash( $certification_number, $saved_salt ) ) {
				throw new Exception( sprintf( __( '올바르지 않은 인증번호입니다. 남은 시도횟수 : %d', 'mshop-members-s2' ), 5 - ( $retry_count + 1 ) ), '2004' );
			}

			return true;
		}
	}

endif;

