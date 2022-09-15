<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$conditional_classes = mfd_get_conditional_class( $element );

$classes = mfd_make_class( array(
	'yes' == mfd_get( $element, 'required' ) ? 'required' : '',
	'yes' == mfd_get( $element, 'inline' ) ? 'inline' : '',
	mfd_get( $element, 'class' ),
	mfd_get( $element, 'width', 'sixteen wide' ),
	'field',
	implode( ' ', $conditional_classes )
) );

$type = mfd_get( $element, 'type', 'text' );

?>
<div class="<?php echo $classes; ?>" style="<?php echo mfd_get_conditional_style( $conditional_classes ); ?>">
	<?php mfd_output_title( $element ); ?>
    <input type="<?php echo $type; ?>"
           name="<?php echo mfd_get( $element, 'name' ); ?>"
		<?php echo 'yes' == mfd_get( $element, 'multiple' ) ? 'multiple' : ''; ?>
		<?php echo 'yes' == mfd_get( $element, 'readonly' ) ? 'readonly' : ''; ?>
           value="<?php echo ! empty( $value ) ? $value : ''; ?>"
           maxlength="<?php echo mfd_get( $element, 'maxlength' ); ?>"
           placeholder="<?php echo mfd_get( $element, 'placeHolder' ); ?>"/>
</div>