<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<div class="featured-box align-left msmu">
	<div class="box-content">
		<h3 style="font-size: 24px; line-height: 24px; font-weight: normal; text-align: center; margin: 0 0 20px 0; margin-top: 0; margin-bottom: 20px; color: #333333;"><?php _e( "회원가입을 해 주셔서 감사합니다.", 'mshop-members-s2' ); ?></h3>
		<p style="text-align: center; font-size: 14px; line-height: 20px; color: #797979; margin: 0 0 30px 0; margin-top: 0; margin-bottom: 30px;"><?php _e( "이메일 인증을 위해 클릭을 해 주세요.<br>이메일 인증 후 원할한 사이트 이용이 가능합니다.", 'mshop-members-s2' ); ?></p>
		<p style="text-align: center;">
			<a class="link" href="<?php echo esc_url( add_query_arg( array( 'key' => $auth_key, 'login' => rawurlencode( $user_login ) ), get_permalink( get_page_by_path( 'email-authentication') ) ) ); ?>" style="display: inline-block; padding: 14px 34px; background-color: #42839f; color: #ffffff; font-weight: bold; font-size: 14px; line-height: 14px; border: none; border-radius: 0; margin: 0; text-decoration: none !important;">
				<?php _e( '이메일 인증', 'mshop-members-s2' ); ?></a>
		</p>
	</div>
</div>

<?php do_action( 'woocommerce_email_footer', $email ); ?>
