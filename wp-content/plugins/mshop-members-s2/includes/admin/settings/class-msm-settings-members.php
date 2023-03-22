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
}

if ( ! class_exists( 'MSM_Settings_Members' ) ) :

	class MSM_Settings_Members {

		function update_settings() {
			require_once MSM()->plugin_path() . '/includes/admin/setting-manager/mshop-setting-helper.php';

			$_REQUEST = array_merge( $_REQUEST, json_decode( stripslashes( $_REQUEST['values'] ), true ) );

			MSM_Setting_Helper::update_settings( $this->get_setting_fields() );

			if ( 'yes' == $_REQUEST['mshop_members_personal_info_noti'] ) {
				MSM_Personal_Info::maybe_register_scheduled_action();
			} else {
				MSM_Personal_Info::maybe_deregister_scheduled_action();
			}

			wp_send_json_success();
		}


		public function get_role_field() {
			require_once( ABSPATH . 'wp-admin/includes/user.php' );

			$roles_setting = array();

			foreach ( get_editable_roles() as $slug => $role ) {
				$roles_setting[ "msm_security_" . $slug ] = $role['name'];
			}

			$roles_setting['msm_security_guest'] = __( 'Guest', 'mshop-members-s2' );

			return $roles_setting;
		}

		public function get_setting_fields() {
			return array(
				'type'     => 'Tab',
				'id'       => 'mshop-members-setting-tab',
				'elements' => apply_filters( 'msm_setting_fields', array(
					$this->get_setting_main_tab(),
					$this->get_setting_email_authentication(),
					$this->get_setting_phone_certification(),
					$this->get_setting_terms_and_condition(),
					$this->get_setting_members_rule(),
					$this->get_access_stibee(),
					$this->get_access_mailchimp(),
					$this->get_setting_cookie(),
					$this->get_settings_access_control(),
					$this->get_setting_tools(),
				) )
			);
		}

		public function get_setting_main_tab() {
			$subscription_settings = array();

			if ( class_exists( 'WC_Subscription' ) ) {
				$subscription_settings = array(
					array(
						'id'        => 'msm_prevent_unsubscribe_when_have_active_subscription',
						'title'     => '회원탈퇴 불가',
						'className' => '',
						'type'      => 'Toggle',
						'default'   => 'no',
						'desc'      => '활성화된 정기결제권을 보유하고 있는 경우, 회원탈퇴를 할 수 없습니다.'
					),
					array(
						'id'        => 'msm_prevent_unsubscribe_message',
						'showIf'    => array( 'msm_prevent_unsubscribe_when_have_active_subscription' => 'yes' ),
						'title'     => '회원탈퇴 불가 안내메시지',
						'className' => '',
						'type'      => 'TextArea',
						'default'   => __( '<h4>고객님은 진행중인 정기결제권을 보유하고 있습니다.<br>회원탈퇴를 하시려면, 정기결제권을 모두 취소해주셔야합니다.</h4>', 'mshop-members-s2' )
					),
				);
			}

			if ( class_exists( 'MSSMS_Manager' ) ) {
				$notification_settings = array(
					array(
						"id"        => "mshop_members_sleep_notification_sms_template",
						"title"     => __( "휴면예고 문자 템플릿", 'mshop-members-s2' ),
						'showIf'    => array( array( 'mshop_members_use_sleep' => 'yes' ), array( 'mshop_members_sleep_notification_method' => 'sms' ) ),
						"className" => "center aligned fluid",
						"type"      => "TextArea",
						"default"   => __( "[{쇼핑몰명}] 안녕하세요? {고객명} 회원님.

저희 {쇼핑몰명} 쇼핑몰에 장기간 미접속으로 인해, 휴면회원으로 전환이 될 예정임을 안내해 드립니다.

휴면회원으로 전환이 되었어도, 다시 접속을 하시면, 휴면회원 처리에서 제외되오니 이점 참고하여 주세요.

* 휴면회원으로 전환된 이후 {휴면회원삭제대기일} 일 이후에는 회원 정보가 자동으로 삭제처리 됩니다.

그동안 저희 {쇼핑몰명}을 이용 해 주셔서 감사합니다." ),
						"rows"      => 8,
					),
					array(
						"id"          => "mshop_members_sleep_notification_alimtalk_template",
						'showIf'      => array( array( 'mshop_members_use_sleep' => 'yes' ), array( 'mshop_members_sleep_notification_method' => 'alimtalk' ) ),
						"title"       => __( "휴면예고 알림톡 템플릿", "mshop-members-s2" ),
						"placeholder" => __( "휴면예고 템플릿을 선택해주세요.", "mshop-members-s2" ),
						"className"   => "",
						"type"        => "Select",
						'options'     => MSSMS_Manager::get_alimtalk_templates()
					),
				);
			} else {
				$notification_settings = array(
					array(
						"id"       => "mshop_members_sleep_notification_sms_template",
						"title"    => __( "휴면예고 문자 템플릿", 'mshop-members-s2' ),
						'showIf'   => array( array( 'mshop_members_use_sleep' => 'yes' ), array( 'mshop_members_sleep_notification_method' => 'sms' ) ),
						'type'     => 'Label',
						'readonly' => 'yes',
						'default'  => '',
						'desc2'    => __( '<div class="desc2">휴면예고 문자알림 기능을 이용하시려면 "<a target="_blank" href="https://www.codemshop.com/shop/sms_out/">엠샵 문자 알림톡 자동발송 플러그인</a>"이 설치되어 있어야 합니다.</div>', 'mshop-members-s2' ),
					),
					array(
						"id"       => "mshop_members_sleep_notification_alimtalk_template",
						'showIf'   => array( array( 'mshop_members_use_sleep' => 'yes' ), array( 'mshop_members_sleep_notification_method' => 'alimtalk' ) ),
						"title"    => __( "휴면예고 알림톡 템플릿", "mshop-members-s2" ),
						'type'     => 'Label',
						'readonly' => 'yes',
						'default'  => '',
						'desc2'    => __( '<div class="desc2">휴면예고 알림톡 기능을 이용하시려면 "<a target="_blank" href="https://www.codemshop.com/shop/sms_out/">엠샵 문자 알림톡 자동발송 플러그인</a>"이 설치되어 있어야 합니다.</div>', 'mshop-members-s2' ),
					),
				);
			}

			return array(
				'type'     => 'Page',
				'title'    => '기본 설정',
				'class'    => 'active',
				'elements' => array(
					array(
						'type'     => 'Section',
						'title'    => '엠샵 멤버스',
						'elements' => array(
							array(
								'id'        => 'mshop_members_enabled',
								'title'     => '활성화',
								'className' => '',
								'type'      => 'Toggle',
								'default'   => 'no',
								'desc'      => '엠샵 멤버스 회원가입 기능을 사용합니다.'
							)
						)
					),
					array(
						'type'     => 'Section',
						'title'    => '기본설정',
						'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
						'elements' => array(
							array(
								'id'      => 'msm_user_can_edit_fields',
								'title'   => __( '회원 정보 수정', 'mshop-members-s2' ),
								'desc'    => __( '<div class="desc2">회원은 회원가입 시 입력한 정보를 내계정 페이지에서 수정할 수 있습니다.</div>', 'mshop-members-s2' ),
								'default' => 'no',
								'type'    => 'Toggle'
							),
							array(
								'id'      => 'mshop_members_using_footer_script',
								'title'   => __( '스크립트 Footer 사용', 'mshop-members-s2' ),
								'desc'    => __( '<div class="desc2">스크립트를 Footer 영역에서 읽도록 설정 할 수 있습니다. 타 플러그인과의 스크립트 충돌로 기능이 정상 동작되지 않는 경우에만 활성화를 해 주세요.</div>', 'mshop-members-s2' ),
								'default' => 'no',
								'type'    => 'Toggle'
							),
							array(
								'id'      => 'msm_display_customer_info',
								'title'   => __( '고객정보 표시', 'mshop-members-s2' ),
								'desc'    => __( '<div class="desc2">로그인 후 로그인 링크 위치에 고객정보를 표시합니다.</div>', 'mshop-members-s2' ),
								'default' => 'no',
								'type'    => 'Toggle'
							),
							array(
								'id'        => 'msm_customer_info_string',
								'showIf'    => array( 'msm_display_customer_info' => 'yes' ),
								'title'     => __( '고객정보 문구', 'mshop-members-s2' ),
								'className' => 'fluid',
								'default'   => __( '{고객명}님 반갑습니다' ),
								'type'      => 'Text'
							),
						)
					),
					array(
						'type'     => 'Section',
						'title'    => '개인정보 설정',
						'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
						'elements' => array(
							array(
								'id'      => 'mshop_members_personal_info_noti',
								'title'   => __( '개인정보 이용 메일 활성화', 'mshop-members-s2' ),
								'desc'    => __( '<div class="desc2">개인정보 이용내역은 연1회 발송되는 법적의무 사항입니다.<br>발송되는 메일에 대한 설정은 <a style="color:red;" target="_blank" href="/wp-admin/admin.php?page=wc-settings&tab=email&section=msm_email_personal_information">개인정보 이용내역 안내 메일 설정</a>에서 진행 해주세요.</div>', 'mshop-members-s2' ),
								'default' => 'no',
								'type'    => 'Toggle'
							),
						)
					),
					array(
						'type'     => 'Section',
						'title'    => '로그인 제한',
						'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
						'elements' => array(
							array(
								'id'       => 'mshop_members_restrict_login',
								'title'    => __( '로그인 제한', 'mshop-members-s2' ),
								'desc'     => __( '<div class="desc2">지정된 등급의 사용자는 로그인할 수 없습니다.</div>', 'mshop-members-s2' ),
								"type"     => "Select",
								'default'  => '',
								'multiple' => true,
								'options'  => apply_filters( 'msm_get_roles', array() ),
							),
							array(
								'id'        => 'mshop_members_restrict_login_message',
								'title'     => __( '로그인 제한 메시지', 'mshop-members-s2' ),
								'className' => 'fluid',
								"type"      => "Text",
								'default'   => __( '등록되지 않은 이메일이거나 비밀번호가 잘못되었습니다.', 'mshop-members-s2' )
							),
							array(
								'id'          => 'mshop_members_restrict_login_redirect_url',
								'title'       => __( '회원가입시 이동할 URL', 'mshop-members-s2' ),
								'className'   => 'fluid',
								"type"        => "Text",
								'placeholder' => __( '회원가입 시 기본사용자 등급이 로그인 제한 사용자인 경우, 지정된 URL로 이동됩니다.', 'mshop-members-s2' )
							),
						)
					),
					array(
						'type'     => 'Section',
						'title'    => '회원탈퇴',
						'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
						'elements' => array_filter( array_merge( array(
								array(
									'id'        => 'mshop_members_use_unsubscribe',
									'title'     => '활성화',
									'className' => '',
									'type'      => 'Toggle',
									'default'   => 'no',
									'desc'      => '회원탈퇴 기능을 사용합니다.'
								),
								array(
									'id'        => 'mshop_members_unsubscribe_button_text',
									'showIf'    => array( 'mshop_members_use_unsubscribe' => 'yes' ),
									'title'     => '버튼 문구',
									'className' => '',
									'type'      => 'Text',
									'default'   => '회원탈퇴'
								),
								array(
									"id"          => "mshop_members_unsubscribe_after_process",
									'showIf'      => array( 'mshop_members_use_unsubscribe' => 'yes' ),
									"title"       => "탈퇴 시 처리",
									"placeholder" => "규칙 종류를 지정하세요.",
									"type"        => "Select",
									'default'     => 'none',
									'options'     => array(
										'none'   => '탈퇴 시 정보 유지',
										'delete' => '탈퇴 시 정보 삭제'
									),
								),
								array(
									'id'          => 'mshop_members_unsubscribe_auto_delete_wait_day',
									'showIf'      => array(
										array( 'mshop_members_use_unsubscribe' => 'yes' ),
										array( 'mshop_members_unsubscribe_after_process' => 'none' )
									),
									'title'       => '탈퇴 회원 자동 삭제 대기일',
									'className'   => '',
									'type'        => 'LabeledInput',
									"label"       => '일 이후',
									'default'     => '0',
									'placeholder' => '0',
									"tooltip"     => array(
										"title" => array(
											"content" => "탈퇴 시 회원 정보가 유지되는 일 수를 입력시 탈퇴 회원은 해당 날짜가 초과되는 시점에 정보가 자동으로 삭제 됩니다. 사용하지 않는 경우 입력칸을 비워주세요."
										)
									)
								)
							), $subscription_settings )
						)
					),
					array(
						'type'     => 'Section',
						'title'    => '휴면회원',
						'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
						'elements' => array_merge( array(
							array(
								'id'        => 'mshop_members_use_sleep',
								'title'     => '활성화',
								'className' => '',
								'type'      => 'Toggle',
								'default'   => 'no',
								'desc'      => '휴면회원 기능을 사용합니다.'
							),
							array(
								'id'        => 'mshop_members_sleep_warning_day',
								'showIf'    => array( 'mshop_members_use_sleep' => 'yes' ),
								'title'     => '휴면 예고일',
								'className' => '',
								'type'      => 'LabeledInput',
								"label"     => '일 이전',
								'default'   => '30',
								"tooltip"   => array(
									"title" => array(
										"content" => "휴면 처리일로부터 지정된 일수 이전에 휴면 예고 메일이 발송됩니다. <br>입력 값이 없거나 숫자 0을 입력하는 경우 휴면예고 이메일은 발송되지 않습니다."
									)
								)
							),
							array(
								'id'        => 'mshop_members_sleep_wait_day',
								'showIf'    => array( 'mshop_members_use_sleep' => 'yes' ),
								'title'     => '휴면 처리일',
								'className' => '',
								'type'      => 'LabeledInput',
								"label"     => '일 이후',
								'default'   => '365',
								"tooltip"   => array(
									"title" => array(
										"content" => "휴면 회원으로 전환되는 일 수 입력시, 휴면 회원 대상자는 마지막 로그인 한 후, 해당 일 수가 초과된 경우 자동으로 휴면 회원으로 전환됩니다."
									)
								)
							),
							array(
								'id'        => 'mshop_members_sleep_auto_delete_wait_day',
								'showIf'    => array( 'mshop_members_use_sleep' => 'yes' ),
								'title'     => '휴면처리 후 삭제 대기일',
								'className' => '',
								'type'      => 'LabeledInput',
								"label"     => '일 이후',
								'default'   => '365',
								"tooltip"   => array(
									"title" => array(
										"content" => "휴면회원으로 전환된 이후, 휴면회원이 삭제 대기일을 초과한 경우 자동으로 회원 정보를 삭제합니다. <br>입력 값이 없거나, 숫자 0을 입력하는 경우, 휴면 처리 전환 후 바로 회원 정보를 삭제합니다."
									)
								)
							),
							array(
								"id"        => "mshop_members_sleep_notification_method",
								'showIf'    => array( 'mshop_members_use_sleep' => 'yes' ),
								"title"     => "휴면 예고 발송 수단",
								"className" => "",
								"type"      => "Select",
								"multiple"  => "true",
								'default'   => 'email',
								'options'   => array(
									'email'    => '이메일',
									'sms'      => '문자 (LMS)',
									'alimtalk' => '알림톡'
								),
							),
							array(
								'id'        => 'mshop_members_sleep_warning_email_title',
								'showIf'    => array( array( 'mshop_members_use_sleep' => 'yes' ), array( 'mshop_members_sleep_notification_method' => 'email' ) ),
								'title'     => '휴면예고 이메일 제목',
								'className' => 'fluid',
								'type'      => 'Text',
								'default'   => '휴면 전환 예고입니다.'
							),
							array(
								'id'        => 'mshop_members_sleep_warning_email',
								'showIf'    => array( array( 'mshop_members_use_sleep' => 'yes' ), array( 'mshop_members_sleep_notification_method' => 'email' ) ),
								'title'     => '휴면예고 이메일 내용',
								'className' => '',
								'type'      => 'TextArea',
								'default'   => '안녕하세요? {고객명} 회원님.

저희 {쇼핑몰명} 쇼핑몰에 장기간 미접속으로 인해, 휴면회원으로 전환이 될 예정임을 안내해 드립니다.

휴면회원으로 전환이 되었어도, 다시 접속을 하시면, 휴면회원 처리에서 제외되오니 이점 참고하여 주세요.

* 휴면회원으로 전환된 이후 {휴면회원삭제대기일} 일 이후에는 회원 정보가 자동으로 삭제처리 됩니다.

그동안 저희 {쇼핑몰명}을 이용 해 주셔서 감사합니다.',
								"rows"      => 8,
							)
						), $notification_settings )
					),
				)
			);
		}

		public function get_setting_terms_and_condition() {
			return array(
				'type'     => 'Page',
				'title'    => '이용약관',
				'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
				'elements' => array(
					array(
						'type'     => 'Section',
						'title'    => '이용약관 동의',
						'elements' => array(
							array(
								"id"        => "mshop_members_use_terms_and_conditions",
								"title"     => "사용",
								"className" => "",
								"type"      => "Toggle",
								"default"   => "no",
								"desc"      => "이용 약관 기능을 사용합니다."
							),
						)
					),
					array(
						'type'     => 'Section',
						'title'    => '회원 이용약관 동의',
						'showIf'   => array( 'mshop_members_use_terms_and_conditions' => 'yes' ),
						'elements' => array(
							array(
								"id"        => "mshop_members_require_tac_for_customer",
								"title"     => "사용",
								"className" => "",
								"type"      => "Toggle",
								"default"   => "no",
								"desc"      => "신규 회원가입 시 이용 약관을 사용합니다."
							),
							array(
								"id"          => "mshop_members_tac_form_for_customer",
								'showIf'      => array( 'mshop_members_require_tac_for_customer' => 'yes' ),
								"title"       => "회원 이용약관",
								"placeholder" => "회원용 이용약관을 선택하세요.",
								"className"   => "",
								"type"        => "Select",
								'options'     => msm_get_members_forms( 'terms_and_conditions' )
							)
						)
					),
					array(
						'type'     => 'Section',
						'title'    => '비회원 이용약관 동의',
						'showIf'   => array( 'mshop_members_use_terms_and_conditions' => 'yes' ),
						'elements' => array(
							array(
								"id"        => "mshop_members_require_tac_for_guest",
								"title"     => "비회원",
								"className" => "",
								"type"      => "Toggle",
								"default"   => "no",
								"desc"      => "비회원의 상품 구매 시 이용 약관을 사용합니다."
							),
							array(
								"id"          => "mshop_members_tac_form_for_guest",
								'showIf'      => array( 'mshop_members_require_tac_for_guest' => 'yes' ),
								"title"       => "이용약관",
								"placeholder" => "비회원용 이용약관을 선택하세요.",
								"className"   => "",
								"type"        => "Select",
								'options'     => msm_get_members_forms( array( 'login', 'register', 'terms_and_conditions' ) )
							)
						)
					)
				)
			);
		}

		public function get_setting_tools() {
			return array(
				'type'     => 'Page',
				'title'    => '도구',
				'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
				'elements' => array(
					array(
						'type'     => 'Section',
						'title'    => '멤버스 도구',
						'elements' => array(
							array(
								'id'             => 'msm_install_page',
								'title'          => '기본 페이지 생성',
								'label'          => '실행',
								'iconClass'      => 'icon settings',
								'className'      => '',
								'type'           => 'Button',
								'default'        => '',
								'actionType'     => 'ajax',
								'confirmMessage' => __( '[주의] 엠샵 멤버스 기본 페이지를 생성하시겠습니까? 
기본 페이지 생성 시, 기존에 수정된 엠샵 멤버스 페이지는 모두 삭제 됩니다.', 'mshop-members-s2' ),
								'ajaxurl'        => admin_url( 'admin-ajax.php' ),
								'action'         => MSM()->slug() . '-install_pages',
								"desc"           => "엠샵 멤버스 기본 페이지를 생성합니다."
							),
							array(
								'id'             => 'msm_install_form',
								'title'          => '기본 템플릿 생성',
								'label'          => '실행',
								'iconClass'      => 'icon settings',
								'className'      => '',
								'type'           => 'Button',
								'default'        => '',
								'actionType'     => 'ajax',
								'confirmMessage' => __( '[주의] 엠샵 멤버스 기본 템플릿을 생성하시겠습니까? 
기본 템플릿 생성 시, 기존에 수정된 엠샵 멤버스 템플릿은 모두 삭제됩니다.', 'mshop-members-s2' ),
								'ajaxurl'        => admin_url( 'admin-ajax.php' ),
								'action'         => MSM()->slug() . '-install_forms',
								"desc"           => "엠샵 멤버스 기본 템플릿을 생성합니다."
							),
							array(
								'id'             => 'msm_install_agreement',
								'title'          => '이용약관 생성',
								'label'          => '실행',
								'iconClass'      => 'icon settings',
								'className'      => '',
								'type'           => 'Button',
								'default'        => '',
								'actionType'     => 'ajax',
								'confirmMessage' => __( '[주의] 엠샵 멤버스 이용약관을 생성하시겠습니까? 
이용 약관 생성 시, 기존에 수정된 이용약관은 모두 삭제됩니다.', 'mshop-members-s2' ),
								'ajaxurl'        => admin_url( 'admin-ajax.php' ),
								'action'         => MSM()->slug() . '-install_agreements',
								"desc"           => "엠샵 멤버스 이용약관을 생성합니다."
							),
							array(
								'id'         => 'msm_import_forms2',
								'title'      => '폼 불러오기 (Import)',
								'label'      => '실행',
								'iconClass'  => 'icon settings',
								'className'  => '',
								'type'       => 'Upload',
								'default'    => '',
								'actionType' => 'ajax',
								'ajaxurl'    => admin_url( 'admin-ajax.php' ),
								'action'     => MSM()->slug() . '-import_forms2'
							)
						)
					)
				)
			);
		}

		public function get_setting_members_rule() {
			return array(
				'type'     => 'Page',
				'title'    => '멤버스 정책',
				'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
				'class'    => '',
				'elements' => array(
					array(
						'type'     => 'Section',
						'title'    => '멤버스 정책 설정',
						'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
						'elements' => array(
							array(
								"id"        => "mshop_members_use_role_application_rule",
								"title"     => "활성화",
								"className" => "",
								"type"      => "Toggle",
								"default"   => "no",
								"desc"      => "멤버스 정책 관리 기능을 사용합니다."
							)
						)
					),
					array(
						"id"           => "mshop_members_role_application_rules",
						"type"         => "SortableList",
						"title"        => "멤버스 정책 목록",
						"listItemType" => "MShopMembersRule",
						"repeater"     => true,
						'showIf'       => array( 'mshop_members_use_role_application_rule' => 'yes' ),
						"template"     => array(
							'rule_type'      => 'role',
							'rule_enabled'   => 'no',
							'mms_conditions' => array(),
						),
						"default"      => array(),
						"elements"     => array(
							'left' => array(
								'type'              => 'Section',
								"hideSectionHeader" => true,
								'elements'          => array(
									array(
										"id"        => "rule_type",
										"title"     => "규칙종류",
										"showIf"    => array( 'hidden' => 'hidden' ),
										"className" => "fluid",
										"type"      => "Select",
										'default'   => 'role',
										'options'   => array(
											'role'     => '사용자 역할',
											'usermeta' => '사용자 정보'
										),
									),
									array(
										"id"        => "rule_title",
										"title"     => "규칙이름",
										"className" => "fluid",
										"type"      => "Text",
									),
									array(
										'id'        => 'rule_enabled',
										'title'     => '활성화',
										'className' => '',
										'type'      => 'Toggle',
										'default'   => 'no',
//										'desc'      => '정책 사용'
									),
								)
							),
							'role' => array(
								'type'              => 'Section',
								"hideSectionHeader" => true,
								'elements'          => array(
									array(
										"id"          => "role",
										"title"       => "표시대상",
										"placeholder" => "규칙을 적용할 회원등급을 선택하세요.",
										"className"   => "fluid",
										"type"        => "Select",
										'default'     => '',
										'multiple'    => true,
										'options'     => apply_filters( 'msm_get_roles', array() ),
									),
									array(
										"id"        => "mms_conditions",
										"title"     => "추가 조건",
										"className" => "",
										"editable"  => 'true',
										"type"      => "SortableTable",
										"template"  => array(
											'condition' => '',
											'value'     => '',
											'operator'  => '',
										),
										"elements"  => array(
											array(
												"id"        => "condition",
												"title"     => __( "사용자 조건", 'mshop-members-s2' ),
												"className" => " eight wide column fluid",
												"type"      => "Select",
												'default'   => 'role',
												'options'   => apply_filters( 'msm_rule_conditions', array(
													'' => '조건을 선택하세요'
												) )
											),
											array(
												"id"        => "value",
												"className" => " six wide column fluid",
												"title"     => __( "값", 'mshop-members-s2' ),
												"type"      => "Select",
												'default'   => 'yes',
												'options'   => apply_filters( 'msm_rule_condition_values', array(
													''    => '선택하세요',
													'yes' => 'YES',
													'no'  => 'NO'
												) ),
											),
											array(
												"id"        => "operator",
												"className" => " two wide column fluid",
												"type"      => "Select",
												'default'   => 'role',
												'options'   => array(
													''    => '',
													'and' => 'AND',
													'or'  => 'OR'
												),
											),
										)
									),
									array(
										"id"          => "description",
										"title"       => "안내 메시지",
										"placeholder" => "정책에 대한 안내 메시지를 입력하세요.
HTML 태그를 이용하여 작성도 가능합니다.",
										"className"   => "fluid",
										"type"        => "TextArea"
									),
									array(
										"id"        => "button_text",
										"title"     => "버튼 문구",
										"className" => "fluid",
										"type"      => "Text",
									),
									array(
										"id"          => "page",
										"title"       => "이동 페이지",
										"placeholder" => "이동할 페이지를 선택하세요.",
										"className"   => "fluid search",
										"type"        => "SearchSelect",
										'default'     => '',
										'multiple'    => false,
										'search'      => true,
										'action'      => 'action=' . MSM()->slug() . '-search_page&keyword=',

									)
								)
							)
						)
					)
				)
			);
		}

		public function get_setting_phone_certification() {
			if ( class_exists( 'MSSMS_Manager' ) ) {
				ob_start();
				include( 'html/sms-message-guide.php' );
				$guide = ob_get_clean();

				ob_start();
				include( 'html/temporary-password-message-guide.php' );
				$temporary_password_guide = ob_get_clean();

				$pages = get_pages();
				$pages = array_combine( array_column( $pages, 'ID' ), array_column( $pages, 'post_title' ) );

				return array(
					'type'     => 'Page',
					'title'    => '휴대폰 인증',
					'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
					'elements' => array(
						array(
							'type'     => 'Section',
							'title'    => '휴대폰 인증',
							'elements' => array(
								array(
									"id"        => "mssms_use_phone_certification",
									"title"     => "사용",
									"className" => "",
									"type"      => "Toggle",
									"default"   => "no",
									"desc"      => __( "문자 또는 알림톡을 이용한 휴대폰 인증 기능을 사용합니다.", "mshop-members-s2" )
								),
							)
						),
						array(
							'type'     => 'Section',
							'title'    => '휴대폰 인증 설정',
							'showIf'   => array( 'mssms_use_phone_certification' => 'yes' ),
							'elements' => array(
								array(
									"id"        => "mssms_phone_certification_method",
									"title"     => "인증 수단",
									"className" => "",
									"type"      => "Select",
									'default'   => 'alimtalk',
									'options'   => array(
										'sms'      => '문자 (LMS)',
										'alimtalk' => '알림톡'
									),
								),
								array(
									"id"        => "mssms_phone_certification_restrict_duplicate",
									"title"     => "중복 가입 제한",
									"className" => "",
									"type"      => "Toggle",
									"default"   => "no",
									"desc"      => __( "이미 가입된 사용자의 휴대폰 번호인 경우 인증을 제한합니다.", "mshop-members-s2" )
								),
								array(
									"id"        => "mssms_phone_certification_sms_template",
									"title"     => __( "인증문자 템플릿", 'mshop-members-s2' ),
									'showIf'    => array( 'mssms_phone_certification_method' => 'sms' ),
									"className" => "center aligned fluid",
									"type"      => "TextArea",
									"default"   => __( "[{쇼핑몰명}] 고객님이 요청하신 인증번호는 [{인증번호}] 입니다. (타인 노출 금지)", "mshop-members-s2" ),
									"rows"      => 3,
									"desc2"     => $guide
								),
								array(
									"id"          => "mssms_phone_certification_alimtalk_template",
									'showIf'      => array( 'mssms_phone_certification_method' => 'alimtalk' ),
									"title"       => "알림톡 템플릿",
									"placeholder" => "휴대폰인증을 위한 템플릿을 선택해주세요.",
									"className"   => "",
									"type"        => "Select",
									'options'     => MSSMS_Manager::get_alimtalk_templates()
								)
							)
						),
						array(
							'type'     => 'Section',
							'title'    => '비회원 인증 설정',
							'showIf'   => array( 'mssms_use_phone_certification' => 'yes' ),
							'elements' => array(
								array(
									"id"        => "mssms_use_phone_certification_for_guest",
									"title"     => "비회원 결제시 휴대폰 인증",
									"className" => "",
									"type"      => "Toggle",
									"default"   => "no",
									"desc"      => __( "<div class='desc2'>비회원 결제 시 약관동의 화면에서 휴대폰 인증을 진행합니다. 인증된 휴대폰 번호는 체크아웃 화면의 휴대폰 필드에 자동으로 설정됩니다.</div>", "mshop-members-s2" )
								)
							)
						),
						array(
							'type'     => 'Section',
							'title'    => '사용 제한 설정',
							'showIf'   => array( 'mssms_use_phone_certification' => 'yes' ),
							'elements' => array(
								array(
									"id"        => "mssms_phone_certification_required",
									"title"     => "필수인증",
									"className" => "",
									"type"      => "Toggle",
									"default"   => "no",
									"desc"      => __( "<div class='desc2'>휴대폰 미인증 사용자는 쇼핑몰 이용이 제한됩니다. 이미 가입된 회원도 반드시 휴대폰 인증을 진행해야 합니다.</div>", "mshop-members-s2" )
								),
								array(
									"id"      => "msm_phone_certification_social_except",
									"showIf"  => array( 'mssms_phone_certification_required' => 'yes' ),
									"title"   => "소셜 로그인 예외 처리",
									"type"    => "Toggle",
									"default" => "no",
									"desc"    => __( "<div class='desc2'>소셜 로그인 사용자는 휴대폰 인증을 하지 않습니다.</div>", "mshop-members-s2" )
								),
								array(
									"id"        => "mssms_phone_certification_only_checkout",
									"showIf"    => array( 'mssms_phone_certification_required' => 'yes' ),
									"title"     => "결제 시 인증",
									"className" => "",
									"type"      => "Toggle",
									"default"   => "no",
									"desc"      => __( "<div class='desc2'>고객이 결제를 진행할때 휴대폰 인증 여부를 체크합니다. 결제 페이지를 제외한 다른 페이지는 이용이 가능합니다.</div>", "mshop-members-s2" )
								),
								array(
									"id"          => "mssms_phone_certification_page_id",
									'showIf'      => array( 'mssms_phone_certification_required' => 'yes' ),
									"title"       => "휴대폰 인증 페이지",
									"placeholder" => "휴대폰 인증 페이지를 선택하세요.",
									"className"   => "",
									"type"        => "Select",
									'options'     => $pages
								)
							)
						),
						array(
							'type'     => 'Section',
							'title'    => '임시 비밀번호 발급 설정',
							'showIf'   => array( 'mssms_use_phone_certification' => 'yes' ),
							'elements' => array(
								array(
									"id"        => "msm_use_issue_temporary_password",
									"title"     => "임시 비밀번호 발급 기능",
									"className" => "",
									"type"      => "Toggle",
									"default"   => "no",
									"desc"      => __( "<div class='desc2'>고객은 임시 비밀번호를 휴대폰으로 받을 수 있습니다.</div>", "mshop-members-s2" )
								),
								array(
									"id"        => "msm_issue_temporary_password_method",
									"title"     => "발송 수단",
									'showIf'    => array( 'msm_use_issue_temporary_password' => 'yes' ),
									"className" => "",
									"type"      => "Select",
									'default'   => 'alimtalk',
									'options'   => array(
										'sms'      => '문자 (LMS)',
										'alimtalk' => '알림톡'
									),
								),
								array(
									"id"        => "msm_issue_temporary_password_sms_template",
									"title"     => __( "인증문자 템플릿", 'mshop-members-s2' ),
									'showIf'    => array( array( 'msm_use_issue_temporary_password' => 'yes' ), array( 'msm_issue_temporary_password_method' => 'sms' ) ),
									"className" => "center aligned fluid",
									"type"      => "TextArea",
									"default"   => __( "[{쇼핑몰명}] 고객님의 임시 비밀번호는 [{임시비밀번호}] 입니다.", "mshop-members-s2" ),
									"rows"      => 3,
									"desc2"     => $temporary_password_guide
								),
								array(
									"id"          => "msm_issue_temporary_password_alimtalk_template",
									'showIf'      => array( array( 'msm_use_issue_temporary_password' => 'yes' ), array( 'msm_issue_temporary_password_method' => 'alimtalk' ) ),
									"title"       => "알림톡 템플릿",
									"placeholder" => "임시 비밀번호 발급을 위한 템플릿을 선택해주세요.",
									"className"   => "",
									"type"        => "Select",
									'options'     => MSSMS_Manager::get_alimtalk_templates()
								)
							)
						)
					)
				);
			} else {
				return array(
					'type'     => 'Page',
					'title'    => '휴대폰 인증',
					'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
					'elements' => array(
						array(
							'type'     => 'Section',
							'title'    => '휴대폰 인증 기능 사용 안내',
							'elements' => array(
								array(
									'id'       => 'mssms_requirement_guide',
									'type'     => 'Label',
									'readonly' => 'yes',
									'default'  => '',
									'desc2'    => __( '<div class="desc2">휴대폰 인증 기능을 이용하시려면 "<a target="_blank" href="https://www.codemshop.com/shop/sms_out/">엠샵 문자 알림톡 자동발송 플러그인</a>"이 설치되어 있어야 합니다.</div>', 'mshop-members-s2' ),
								)
							)
						),
					)
				);
			}
		}

		function get_setting_email_authentication() {
			if ( class_exists( 'WooCommerce' ) ) {
				return array(
					'type'     => 'Page',
					'title'    => '이메일 인증',
					'class'    => '',
					'elements' => array(
						array(
							'type'     => 'Section',
							'title'    => '인증 설정',
							'elements' => array(
								array(
									"id"      => "msm_required",
									"title"   => "필수 인증",
									"type"    => "Toggle",
									"default" => "no",
									"desc"    => "이메일 미인증 사용자는 이용이 제한됩니다."
								),
								array(
									"title"     => "접근 허용 경로 설정",
									"id"        => "msm_exception_list",
									"showIf"    => array( 'msm_required' => 'yes' ),
									"className" => "",
									"sortable"  => 'true',
									"editable"  => 'true',
									"repeater"  => 'true',
									"type"      => "SortableTable",
									"elements"  => array(
										array(
											"className" => "one wide column fluid",
											'id'        => "enabled",
											"title"     => __( "활성화", 'mshop-members-s2' ),
											'default'   => '',
											"type"      => "Toggle",
										),
										array(
											"className"   => "thirteen wide column fluid",
											"id"          => "url",
											"title"       => __( "허용 경로", 'mshop-members-s2' ),
											"type"        => "Text",
											"placeholder" => __( "허용 경로를 입력하세요.", 'mshop-members-s2' ),
										)
									)
								),
								array(
									"id"      => "msm_social_except",
									"showIf"  => array( 'msm_required' => 'yes' ),
									"title"   => "소셜 로그인 예외 처리",
									"type"    => "Toggle",
									"default" => "no",
									"desc"    => "활성화 시, 소셜 로그인 사용자는 이메일 인증을 이용하지 않습니다."
								),
							)
						),
						array(
							'type'     => 'Section',
							"showIf"   => array( 'msm_required' => 'yes' ),
							'title'    => '이메일 인증시 회원등급 변경',
							'elements' => array(
								array(
									"id"      => "msm_change_role",
									"title"   => "사용",
									"type"    => "Toggle",
									"default" => "no",
									"desc"    => "이메일 인증 된 사용자의 회원 등급을 변경합니다."
								),
								array(
									"id"          => "msm_target_role",
									"showIf"      => array( "msm_change_role" => "yes" ),
									"title"       => "회원등급",
									"placeholder" => "회원등급을 선택하세요.",
									"className"   => "",
									"type"        => "Select",
									'default'     => '',
									'options'     => apply_filters( 'msm_get_roles', array() ),
								)
							)
						),
						array(
							'type'     => 'Section',
							"showIf"   => array( 'msm_required' => 'yes' ),
							'title'    => '템플릿 색상',
							'elements' => array(
								array(
									'id'          => 'msm_theme_color',
									'title'       => '테마 선택',
									'className'   => '',
									'type'        => 'Select',
									'default'     => "red",
									'placeholder' => "테마 선택",
									"options"     => array(
										"black"  => "검정",
										"blue"   => "파랑",
										"green"  => "초록",
										"orange" => "주황",
										"yellow" => "노랑",
										"red"    => "빨강"
									)
								)
							)
						),
						array(
							'type'     => 'Section',
							"showIf"   => array( 'msm_required' => 'yes' ),
							'title'    => '인증 완료 설정',
							'elements' => array(
								array(
									'id'          => "msm_finish_url",
									"className"   => "four wide column fluid",
									"title"       => "인증완료 후 이동할 URL",
									"type"        => "Text",
									'placeholder' => "인증완료 후 이동시킬 URL을 입력하세요.",
								),
								array(
									'id'          => "msm_finish_text",
									"className"   => "four wide column fluid",
									"title"       => "인증완료 후 표시할 버튼문구",
									"type"        => "Text",
									'placeholder' => "인증완료 후 표시할 버튼문구를 입력하세요.",
								)
							)
						)
					)
				);
			} else {
				return array(
					'type'     => 'Page',
					'title'    => '이메일 인증',
					'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
					'elements' => array(
						array(
							'type'     => 'Section',
							'title'    => '이메일 인증 기능 사용 안내',
							'elements' => array(
								array(
									'id'       => 'msm_email_requirement_guide',
									'type'     => 'Label',
									'readonly' => 'yes',
									'default'  => '',
									'desc2'    => __( '<div class="desc2">이메일 인증 기능을 이용하시려면 "우커머스(Woocommere) 플러그인"이 설치되어 있어야 합니다.</div>', 'mshop-members-s2' ),
								)
							)
						),
					)
				);
			}
		}

		function get_setting_cookie() {
			return array(
				'type'     => 'Page',
				'title'    => __( '쿠키 사용 동의 설정', 'mshop-members-s2' ),
				'elements' => array(
					array(
						'type'     => 'Section',
						'title'    => __( '기본 설정', 'mshop-members-s2' ),
						'elements' => array(
							array(
								'id'        => 'msmp_use_cookie_agreement',
								'title'     => __( '활성화', 'mshop-members-s2' ),
								'className' => '',
								'type'      => 'Toggle',
								'default'   => 'no',
								'desc'      => __( '쿠키 사용동의 기능 기능을 사용합니다.', 'mshop-members-s2' )
							),
							array(
								'id'        => 'msmp_cookie_agreement_message',
								'showIf'    => array( 'msmp_use_cookie_agreement' => 'yes' ),
								'title'     => '쿠키 사용동의 안내문구',
								'className' => 'fluid',
								'type'      => 'TextArea',
								'rows'      => 10,
								'default'   => __( '{사이트명}에 오신 것을 환영합니다! 웹사이트를 원활하게 표시하기 위해 쿠키를 사용합니다. {사이트명}을 계속 이용하려면 쿠키 사용에 동의해야 합니다.', 'mshop-members-s2' )
							),
						)
					)
				)
			);
		}

		function get_settings_access_control() {
			return array(
				'type'     => 'Page',
				'title'    => __( '보안 설정', 'mshop-members-s2' ),
				'elements' => array(
					array(
						'type'     => 'Section',
						'title'    => __( '기본 설정', 'mshop-members-s2' ),
						'elements' => array(
							array(
								'id'        => "msm_use_access_control",
								"className" => "one wide column",
								"title"     => __( "접근제어 기능 활성화", 'mshop-members-s2' ),
								"type"      => "Toggle",
								'default'   => 'no',
							),
							array(
								'id'        => "msm_disable_wc_login",
								"className" => "one wide column",
								'showIf'    => array( 'msm_use_access_control' => 'yes' ),
								"title"     => __( "우커머스 로그인 비활성화", 'mshop-members-s2' ),
								"type"      => "Toggle",
								'default'   => 'yes',
								'desc'      => __( '스팸회원 가입 차단을 위해 우커머스의 기본 폼 핸들러를 비활성화합니다.' )
							),
							array(
								'id'          => 'msm_security_author_display',
								'title'       => __( '댓글 작성자 숨김', 'mshop-members-s2' ),
								'className'   => '',
								'type'        => 'Select',
								'default'     => "no",
								'placeHolder' => __( "필드선택", 'mshop-members-s2' ),
								"options"     => array(
									"no"    => __( "사용안함", 'mshop-members-s2' ),
									"left"  => __( "왼쪽 숨김", 'mshop-members-s2' ),
									"right" => __( "오른쪽 숨김", 'mshop-members-s2' ),
									"email" => __( "이메일 숨김", 'mshop-members-s2' )
								)
							),
							array(
								'id'        => 'msm_security_redirect_url',
								'showIf'    => array( 'msm_use_access_control' => 'yes' ),
								'title'     => __( '이동할 페이지 URL 주소', 'mshop-members-s2' ),
								'className' => 'fluid',
								'type'      => 'Text',
								'default'   => home_url(),
							),
						)
					),
					array(
						'type'     => 'Section',
						'showIf'   => array( 'msm_use_access_control' => 'yes' ),
						'title'    => __( '사용자 등급별 URL 접근차단', 'mshop-members-s2' ),
						'elements' => array(
							array(
								"id"        => "msm_security_block_list",
								"default"   => MSM_Access_Control::get_default_block_fields(),
								"className" => "",
								"sortable"  => 'true',
								"editable"  => 'true',
								"repeater"  => 'true',
								"type"      => "SortableTable",
								"elements"  => array(

									array(
										"className" => "seven wide column fluid",
										'id'        => "path",
										"title"     => __( "경로 이름", 'mshop-members-s2' ),
										'default'   => '',
										"type"      => "Text",
									),
									array(
										"className"   => "seven wide column fluid",
										"id"          => "block_list",
										"title"       => __( "등급", 'mshop-members-s2' ),
										"type"        => "Select",
										"placeHolder" => __( "등급을 선택하세요.", 'mshop-members-s2' ),
										"multiple"    => true,
										"options"     => $this->get_role_field()
									)
								)
							)
						)
					),
					array(
						'type'     => 'Section',
						'showIf'   => array( 'msm_use_access_control' => 'yes' ),
						'title'    => __( 'URL 접근차단 예외규칙', 'mshop-members-s2' ),
						'elements' => array(
							array(
								"id"        => "msm_security_exception_list",
								"className" => "",
								"sortable"  => 'true',
								"editable"  => 'true',
								"repeater"  => 'true',
								"type"      => "SortableTable",
								"default"   => MSM_Access_Control::get_default_exception_fields(),
								"elements"  => array(
									array(
										'id'        => "path",
										"className" => "six wide column fluid",
										"title"     => __( "경로 이름", 'mshop-members-s2' ),
										'default'   => '',
										"type"      => "Text",
									),
									array(
										'id'        => "is_param",
										"className" => "one wide column",
										"title"     => __( "파라미터", 'mshop-members-s2' ),
										"type"      => "Toggle",
										'default'   => '',
									),
									array(
										'id'        => "is_path",
										"className" => "one wide column",
										"title"     => __( "경로", 'mshop-members-s2' ),
										"type"      => "Toggle",
										'default'   => '',
									),
									array(
										'id'        => "value",
										"className" => "six wide column fluid",
										"title"     => __( "값", 'mshop-members-s2' ),
										'default'   => '',
										"type"      => "Text",
									)
								)
							)
						)
					)
				)
			);
		}

		public function get_access_stibee() {
			return array(
				'type'     => 'Page',
				'title'    => __( "스티비 연동", 'mshop-members-s2' ),
				'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
				'elements' => array(
					array(
						'type'     => 'Section',
						'title'    => __( "스티비 연동", 'mshop-members-s2' ),
						'elements' => array(
							array(
								'id'        => 'mshop_members_access_stibee',
								'title'     => __( "활성화", 'mshop-members-s2' ),
								'className' => '',
								'type'      => 'Toggle',
								'default'   => 'no',
								'desc'      => '스티비 연동 기능을 사용합니다.'
							),
							array(
								'id'          => 'mshop_members_stibee_api',
								'showIf'      => array( 'mshop_members_access_stibee' => 'yes' ),
								'title'       => __( "스티비 API", 'mshop-members-s2' ),
								'className'   => 'fluid',
								'type'        => 'Text',
								"placeholder" => __( "스티비 API 키 값을 입력 해주세요. 스티비 -> 계정 및 결제 -> API키 에서 확인하실 수 있습니다.", 'mshop-members-s2' ),
								'default'     => '',
								'desc2'       => __( '<div class="desc2">스티비 API 키를 입력합니다.</div>', 'mshop-members-s2' ),
							),
							array(
								'id'          => 'mshop_members_stibee_lists',
								'showIf'      => array( 'mshop_members_access_stibee' => 'yes' ),
								'title'       => __( "스티비 주소록", 'mshop-members-s2' ),
								'className'   => 'fluid',
								'type'        => 'Text',
								"placeholder" => __( "스티비 주소록의 아이디를 입력 해주세요. 스티비 -> 주소록 -> 목록 페이지의 URL 에서 확인하실 수 있습니다.", 'mshop-members-s2' ),
								'default'     => '',
								'desc2'       => __( '<div class="desc2">스티비 주소록의 아이디를 입력합니다.(예 : https://www.stibee.com/lists/12345/subscribers/S/all -> 12345)</div>', 'mshop-members-s2' ),
							),
							array(
								'id'          => 'mshop_members_stibee_groupid',
								'showIf'      => array( 'mshop_members_access_stibee' => 'yes' ),
								'title'       => __( "스티비 그룹 아이디 (선택사항)", 'mshop-members-s2' ),
								'className'   => 'fluid',
								'type'        => 'Text',
								"placeholder" => __( "스티비 주소록의 그룹 아이디를 입력 해주세요. 스티비 -> 주소록 -> 목록 -> 그룹 페이지의 URL 에서 확인하실 수 있습니다.", 'mshop-members-s2' ),
								'default'     => '',
								'desc2'       => __( '<div class="desc2">스티비 그룹의 아이디를 입력합니다.(예 : https://www.stibee.com/lists/12345/subscribers/S/00012 -> 00012)</div>', 'mshop-members-s2' ),
							),
						)
					)
				)
			);
		}

		public function get_access_mailchimp() {
			return array(
				'type'     => 'Page',
				'title'    => __( "메일침프 연동", 'mshop-members-s2' ),
				'showIf'   => array( 'mshop_members_enabled' => 'yes' ),
				'elements' => array(
					array(
						'type'     => 'Section',
						'title'    => __( "메일침프 연동", 'mshop-members-s2' ),
						'elements' => array(
							array(
								'id'        => 'mshop_members_access_mailchimp',
								'title'     => __( "활성화", 'mshop-members-s2' ),
								'className' => '',
								'type'      => 'Toggle',
								'default'   => 'no',
								'desc'      => __( '메일침프 연동 기능을 사용합니다.', 'mshop-members-s2' ),
							),
							array(
								'id'          => 'mshop_members_mailchimp_api',
								'showIf'      => array( 'mshop_members_access_mailchimp' => 'yes' ),
								'title'       => __( "메일침프 API", 'mshop-members-s2' ),
								'className'   => 'fluid',
								'type'        => 'Text',
								"placeholder" => __( "메일침프 API 키 값을 입력 해주세요.(위치는 매뉴얼을 참고해주세요.)", 'mshop-members-s2' ),
								'default'     => '',
								'desc2'       => __( '<div class="desc2">메일침프 API 키를 입력합니다. |<a target=\'_blank\' href=\'' . "https://manual.codemshop.com/docs/members-s2/stibee/mailchimp/#msm-field-1" . '\'> API 발급 매뉴얼</a></div>', 'mshop-members-s2' ),
							),
							array(
								'id'          => 'mshop_members_mailchimp_prefix',
								'showIf'      => array( 'mshop_members_access_mailchimp' => 'yes' ),
								'title'       => __( "메일침프 프리픽스", 'mshop-members-s2' ),
								'className'   => 'fluid',
								'type'        => 'Text',
								"placeholder" => __( "메일침프 서버 프리픽스를 입력해주세요.", 'mshop-members-s2' ),
								'default'     => '',
								'desc2'       => __( '<div class="desc2">메일침프 서버 프리픽스를 입력합니다.(예 : https://us14.admin.mailchimp.com/ -> us14)</div>', 'mshop-members-s2' ),
							),
							array(
								'id'          => 'mshop_members_mailchimp_list_id',
								'showIf'      => array( 'mshop_members_access_mailchimp' => 'yes' ),
								'title'       => __( "메일침프 리스트 ID", 'mshop-members-s2' ),
								'className'   => 'fluid',
								'type'        => 'Text',
								"placeholder" => __( "메일침프 Audience 아이디를 입력해주세요.(위치는 매뉴얼을 참고해주세요.)", 'mshop-members-s2' ),
								'default'     => '',
								'desc2'       => __( '<div class="desc2">메일침프 리스트 ID를 입력합니다. |<a target=\'_blank\' href=\'' . "https://manual.codemshop.com/docs/members-s2/stibee/mailchimp/#msm-field-2" . '\'> 리스트 ID 매뉴얼</a></div>', 'mshop-members-s2' ),
							),
						)
					)
				)
			);
		}

		static function get_wp_social_login_install_guide() {
			return array(
				'type'     => 'Page',
				'title'    => '소셜로그인',
				'elements' => array(
					array(
						'type'           => 'Section',
						'title'          => '필수 플러그인',
						'hideSaveButton' => true,
						'elements'       => array(
							array(
								'id'       => 'wp_social_login_guide',
								'type'     => 'Label',
								'readonly' => 'yes',
								'default'  => '<p>WordPress Social Login 플러그인 설치가 필요합니다.</p><a target="_blank" href="https://wordpress.org/plugins/wordpress-social-login/">WordPress Social Login 플러그인 설치하기</a>'
							)
						)
					)
				)
			);
		}

		function enqueue_scripts() {
			wp_enqueue_style( 'mshop-setting-manager', MSM()->plugin_url() . '/includes/admin/setting-manager/css/setting-manager.min.css', array(), MSM_VERSION );
			wp_enqueue_script( 'mshop-setting-manager', MSM()->plugin_url() . '/includes/admin/setting-manager/js/setting-manager.min.js', array( 'jquery', 'jquery-ui-core', 'underscore' ), MSM_VERSION );
		}
		public function output() {
			require_once MSM()->plugin_path() . '/includes/admin/setting-manager/mshop-setting-helper.php';

			$settings = $this->get_setting_fields();

			$this->enqueue_scripts();

			$license_info = json_decode( get_option( 'msl_license_' . MSM()->slug(), json_encode( array(
				'slug'   => MSM()->slug(),
				'domain' => preg_replace( '#^https?://#', '', home_url() )
			) ) ), true );

			$license_info = apply_filters( 'mshop_get_license', $license_info, MSM()->slug() );
			wp_localize_script( 'mshop-setting-manager', 'mshop_setting_manager', array(
				'element'     => 'mshop-setting-wrapper',
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'action'      => MSM()->slug() . '-update_msm_settings',
				'settings'    => $settings,
				'slug'        => MSM()->slug(),
				'domain'      => preg_replace( '#^https?://#', '', site_url() ),
				'licenseInfo' => json_encode( $license_info )
			) );

			if ( MSM_Personal_Info::enabled() ) {
				$next_schedule = MSM_Personal_Info::get_next_schedule();

				if ( ! empty( $next_schedule ) ) {
					?>
                    <div class="notice notice-info">
                        <p style="padding: 10px;">
                        <p><?php echo sprintf( __( '개인정보 이용 안내 메일 발송 예약작업이 등록되어 있습니다. 다음 실행 예정 시간은 %s 입니다.', 'mshop-members-s2' ), $next_schedule ); ?></p>
                        <a href="/wp-admin/admin.php?page=wc-status&tab=action-scheduler&status=pending&s=msm_personal_information_notification&action=-1&pahed=1&action2=-1"
                           target="_blank" class="button" style="margin-left: 10px;">예약작업 확인하기</a>
                    </div>
					<?php
				} else {
					?>
                    <div class="notice notice-error">
                        <p style="padding: 10px;"><?php echo __( '개인정보 이용 안내 메일 발송 기능이 활성화되어 있지만, 발송 처리를 위한 예약작업이 등록되지 않았습니다. 개인정보 설정을 확인하신 후, 저장 버튼을 클릭해주세요.', 'mshop-members-s2' ); ?></p>
                    </div>
					<?php
				}
			}

			?>
            <script>
                jQuery( document ).ready( function () {
                    jQuery( this ).trigger( 'mshop-setting-manager', ['mshop-setting-wrapper', '100', <?php echo json_encode( MSM_Setting_Helper::get_settings( $settings ) ); ?>, <?php echo json_encode( $license_info ); ?>, null] );
                } );
            </script>

            <div id="mshop-setting-wrapper"></div>
			<?php
		}
	}

endif;

return new MSM_Settings_Members();


