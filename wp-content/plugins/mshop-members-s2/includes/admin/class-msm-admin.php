<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'MSM_Admin' ) ) :

	class MSM_Admin {

		function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			include_once( 'meta-boxes/class-msm-meta-box-agreement.php' );
            add_action( 'admin_enqueue_scripts', array( 'MSM_Admin', 'admin_enqueue_scripts' ) );
		}

		function admin_menu() {
			add_submenu_page( 'edit.php?post_type=mshop_members_form', __( '멤버스 설정', 'mshop-members-s2' ), __( '멤버스 설정', 'mshop-members-s2' ), 'manage_options', 'mshop_members_setting', array( $this, 'mshop_members_setting_page' ) );
			add_submenu_page( 'edit.php?post_type=mshop_members_form', __( '프로필 설정', 'mshop-members-s2' ), __( '프로필 설정', 'mshop-members-s2' ), 'manage_options', 'msm_profile_settings', array( 'MSM_Settings_Profile', 'output' ) );
			add_submenu_page( 'edit.php?post_type=mshop_members_form', __( '소셜로그인 설정', 'mshop-members-s2' ), __( '소셜로그인 설정', 'mshop-members-s2' ), 'manage_options', 'mshop_members_setting_social', array( 'MSM_Settings_Members_Social', 'output' ) );
			add_submenu_page( 'edit.php?post_type=mshop_members_form', __( '회원등급 설정', 'mshop-members-s2' ), __( '회원등급 설정', 'mshop-members-s2' ), 'manage_options', 'mshop_members_role_setting', array( 'MSM_Settings_Members_Role', 'output' ) );
			add_submenu_page( 'edit.php?post_type=mshop_members_form', __( '멤버스 필드', 'mshop-members-s2' ), __( '멤버스 필드', 'mshop-members-s2' ), 'manage_options', 'mshop_members_fields_setting', array( 'MSM_Settings_Fields', 'output' ) );

            $awaiting_count = '';
            if( self::msm_get_awaiting_count() > 0 ) {
                $awaiting_count = sprintf( '<span class="awaiting-mod">3</span>',self::msm_get_awaiting_count() );
            }
            add_submenu_page( 'edit.php?post_type=mshop_members_form', __( '권한요청 목록', 'mshop-members-s2' ) . $awaiting_count, __( '권한요청 목록', 'mshop-members-s2' ) . $awaiting_count, 'manage_options', 'edit.php?post_type=mshop_role_request' );

            add_submenu_page( 'edit.php?post_type=mshop_members_form', __( '매뉴얼', 'mshop-members-s2' ), __( '매뉴얼', 'mshop-members-s2' ), 'manage_options', 'mshop_members_manual','' );
		}

		function mshop_members_setting_page() {
			$setting = new MSM_Settings_Members();
			$setting->output();
		}

        static function admin_enqueue_scripts() {
            wp_enqueue_script( 'msm-admin-menu', MSM()->plugin_url() . '/assets/js/admin/admin-menu.js', array( 'jquery' ), MSM_VERSION );
            wp_localize_script( 'msm-admin-menu', '_msm_admin_menu', array(
                'manual_url' => 'https://manual.codemshop.com/docs/members-s2/'
            ) );
        }

        function msm_get_awaiting_count(){
            global $wpdb;

            $awaiting_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'mshop_role_request' AND post_status = 'mshop-apply'" );

            return $awaiting_count;
        }
	}

	return new MSM_Admin();

endif;
