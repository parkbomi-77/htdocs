<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php $post_id = get_the_ID(); ?>

<div class="stm-lms-course__sidebar">

	<?php STM_LMS_Templates::show_lms_template('global/wish-list', array('course_id' => $post_id)); ?>

    <?php STM_LMS_Templates::show_lms_template('global/expired_course', array('course_id' => $post_id)); ?>

	<?php STM_LMS_Templates::show_lms_template('global/buy-button', array('course_id' => $post_id)); ?>

	<?php STM_LMS_Templates::show_lms_template('global/completed_info', array('course_id' => $post_id)); ?>

	<?php STM_LMS_Templates::show_lms_template('course/parts/info', array('course_id' => $post_id)); ?>

	<?php STM_LMS_Templates::show_lms_template('course/parts/dynamic_sidebar', array('course_id' => $post_id)); ?>

</div>