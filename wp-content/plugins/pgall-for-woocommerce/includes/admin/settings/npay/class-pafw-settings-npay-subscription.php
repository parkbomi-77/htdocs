<?php

//소스에 URL로 직접 접근 방지
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PAFW_Settings_NPay_Subscription' ) ) {
	class PAFW_Settings_NPay_Subscription extends PAFW_Settings_NPay {
		function get_setting_fields() {
			return array (
				array (
					'type'     => 'Section',
					'title'    => '네이버페이 정기/반복결제 설정',
					'elements' => array (
						array (
							'id'        => 'npay_subscription_title',
							'title'     => '결제수단 이름',
							'className' => 'fluid',
							'type'      => 'Text',
							'default'   => '네이버페이 정기/반복결제',
							'tooltip'   => array (
								'title' => array (
									'content' => __( '결제 페이지에서 구매자들이 결제 진행 시 선택하는 결제수단명 입니다.', 'pgall-for-woocommerce' )
								)
							)
						),
						array (
							'id'        => 'npay_subscription_description',
							'title'     => '결제수단 설명',
							'className' => 'fluid',
							'type'      => 'TextArea',
							'default'   => '<ul>
	<li>주문 변경 시 카드사 혜택 및 할부 적용 여부는 해당 카드사 정책에 따라 변경될 수 있습니다.</li>
	<li>네이버페이는 네이버ID로 별도 앱 설치 없이 신용카드 또는 은행계좌 정보를 등록하여 네이버페이 비밀번호로 결제할 수 있는 간편결제 서비스입니다.</li>
	<li>결제 가능한 신용카드: 신한, 삼성, 현대, BC, 국민, 하나, 롯데, NH농협, 씨티, 카카오뱅크</li>
	<li>결제 가능한 은행: NH농협, 국민, 신한, 우리, 기업, SC제일, 부산, 경남, 수협, 우체국, 미래에셋대우, 광주, 대구, 전북, 새마을금고, 제주은행, 신협, 하나은행, 케이뱅크, 카카오뱅크, 삼성증권, KDB산업은행, 씨티은행, SBI은행, 유안타증권</li>
</ul>',
							'tooltip'   => array (
								'title' => array (
									'content' => __( '결제 페이지에서 구매자들이 결제 진행 시 제공되는 결제수단 상세설명 입니다.', 'pgall-for-woocommerce' )
								)
							)
						)
					)
				),
			);
		}
	}
}
