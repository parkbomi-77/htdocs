<div class="stm_lms_splash_wizard__header">

    <div class="stm_lms_splash_wizard__header_logo">

        <img srcset="<?php echo esc_url(STM_LMS_URL . 'assets/img/wizard/logo@2x.png'); ?> 2x"
             src="<?php echo esc_url(STM_LMS_URL . 'assets/img/wizard/logo.png'); ?>"/>

    </div>

    <div class="stm_lms_splash_wizard__reset" @click="resetSettings()">
        <?php STM_LMS_Helpers::print_svg('/assets/img/wizard/reset.svg'); ?>
        <?php esc_html_e('Reset Settings', 'masterstudy-lms-learning-management-system'); ?>
    </div>

    <div class="stm_lms_splash_wizard__header_nav">

        <a href="#"
           class="btn btn-prev"
           v-bind:class="{'disabled' : active_step === 'business'}"
           @click="prevStep()">
            <i class="lnricons-chevron-left"></i>
            <span>
                <?php esc_html_e('Previous', 'masterstudy-lms-learning-management-system'); ?>
            </span>
        </a>

        <a href="#"
           class="btn btn-next"
           v-bind:class="{'disabled' : active_step === 'finish'}"
           @click="nextStep()" >
            <span>
                <?php esc_html_e('Next step', 'masterstudy-lms-learning-management-system'); ?>
            </span>
            <i class="lnricons-chevron-right"></i>
        </a>

    </div>

</div>