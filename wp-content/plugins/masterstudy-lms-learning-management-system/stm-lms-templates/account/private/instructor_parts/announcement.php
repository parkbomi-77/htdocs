<?php if (!defined('ABSPATH')) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $lms_current_user
 */

stm_lms_register_style('user_info_top');
stm_lms_register_style('edit_account');
stm_lms_register_style('instructor/account');

?>

<div class="container">

    <div class="stm-lms-wrapper create_announcement">

        <?php do_action('stm_lms_admin_after_wrapper_start', $lms_current_user); ?>


        <h1><?php esc_html_e('Create announcement', 'masterstudy-lms-learning-management-system'); ?></h1>

        <div class="multiseparator"></div>

        <?php STM_LMS_Templates::show_lms_template('account/private/instructor_parts/create_announcement', array('current_user' => $lms_current_user)); ?>

    </div>

</div>
