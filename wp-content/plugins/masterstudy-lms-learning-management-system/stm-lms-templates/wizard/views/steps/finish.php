<div class="wizard-finish" v-if="active_step === 'finish'">
    <div class="wizard-finish-bg">
        <?php STM_LMS_Helpers::print_svg('/assets/img/wizard/finish.svg'); ?>
    </div>
    <div class="wizard-finish-content">
        <h4><?php esc_html_e('Congratulations', 'masterstudy-lms-learning-management-system'); ?></h4>
        <p>
            You’ve passed all the way through! MasterStudy LMS setup is successfully finished. Explore our <a href="https://docs.stylemixthemes.com/masterstudy-theme-documentation/" target="_blank">Documentation</a> to learn more about the features.
        </p>
        <p>
            Try our premium tools, check out the <a href="https://stylemixthemes.com/wordpress-lms-plugin/" target="_blank">LMS Addons</a>, and create an ideal learning website. Or you can start creating courses right away!
        </p>

        <a href="<?php echo esc_url(admin_url('post-new.php?post_type=stm-courses')) ?>" class="btn btn-blue" style="margin-right: 12px;">
            <span>
                <?php esc_html_e('Add new course', 'masterstudy-lms-learning-management-system'); ?>
            </span>
        </a>

        <a href="<?php echo esc_url(admin_url('admin.php?page=stm-lms-settings#section_2')) ?>" class="btn btn-default">
            <span>
                <?php esc_html_e('Import demo courses', 'masterstudy-lms-learning-management-system'); ?>
            </span>
        </a>
    </div>
</div>