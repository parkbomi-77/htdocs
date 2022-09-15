<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class MFD_Birthday_Field extends MFD_Field {
	public function output ( $element, $post, $form ) {
		$value = mfd_get_post_value( $element['name'], $post, $form );

		msm_get_template( 'form-field/birthday.php', array (
			'element' => $element,
			'value'   => $value
		) );
	}

}