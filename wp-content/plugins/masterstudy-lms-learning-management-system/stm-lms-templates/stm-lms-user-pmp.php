<?php if (!defined('ABSPATH')) exit; //Exit if accessed directly ?>

<?php
do_action('stm_lms_template_main');

get_header();
?>

<?php stm_lms_register_style('pmpro_membership');

global $pmpro_pages;

$pmpro_pages = array(
    'account' => get_option('pmpro_account_page_id'),
    'billing' => get_option('pmpro_billing_page_id'),
    'cancel' => get_option('pmpro_cancel_page_id'),
    'checkout' => get_option('pmpro_checkout_page_id'),
    'confirmation' => get_option('pmpro_confirmation_page_id'),
    'invoice' => get_option('pmpro_invoice_page_id'),
    'levels' => get_option('pmpro_levels_page_id')
);
?>

    <div class="container">

		<?php do_action('stm_lms_admin_after_wrapper_start', STM_LMS_User::get_current_user()); ?>

        <div class="stm-lms-user-memberships">
            <?php echo do_shortcode('[pmpro_account]'); ?>
        </div>
    </div>

<?php get_footer();
