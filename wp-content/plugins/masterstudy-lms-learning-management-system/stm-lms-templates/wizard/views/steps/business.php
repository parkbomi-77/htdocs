<div class="stm_lms_splash_wizard__content_tab" v-if="active_step === 'business'">
    <h4>
        <?php esc_html_e('Choose Business Type', 'masterstudy-lms-learning-management-system'); ?>
    </h4>

    <div class="stm_lms_splash_wizard__business_type">

        <div class="stm_lms_splash_wizard__business_type_one"
             v-bind:class="{'active': business_type === 'individual'}"
             @click="business_type = 'individual'">
            <div class="stm_lms_splash_wizard__business_type_one__image">
                <img src="<?php echo esc_url(STM_LMS_URL . '/assets/img/wizard/individual.svg') ?>"/>
            </div>
            <div class="stm_lms_splash_wizard__business_type_label">
                <label class="stm_lms_wizard__radio">
                    <?php esc_html_e('Individual', 'masterstudy-lms-learning-management-system'); ?>
                    <input type="radio"
                           value="individual"
                           name="business_type"
                           v-model="business_type"/>
                    <div></div>
                </label>
            </div>
            <div class="stm_lms_splash_wizard__business_type_description">
                <?php esc_html_e('Create personalized learning programs and promote yourself as a private instructor.',
                    'masterstudy-lms-learning-management-system'); ?>
            </div>
        </div>

        <div class="stm_lms_splash_wizard__business_type_one"
             v-bind:class="{'active': business_type === 'marketplace'}"
             @click="business_type = 'marketplace'">
            <div class="stm_lms_splash_wizard__business_type_one__image">
                <img src="<?php echo esc_url(STM_LMS_URL . '/assets/img/wizard/marketplace.svg') ?>"/>
            </div>
            <div class="stm_lms_splash_wizard__business_type_label">
                <label class="stm_lms_wizard__radio">
                    <?php esc_html_e('Marketplace', 'masterstudy-lms-learning-management-system'); ?>
                    <input type="radio"
                           value="marketplace"
                           name="business_type"
                           v-model="business_type"/>
                    <div></div>
                </label>
            </div>
            <div class="stm_lms_splash_wizard__business_type_description">
                <?php esc_html_e('Establish a big educational platform and connect teachers and learners. ',
                    'masterstudy-lms-learning-management-system'); ?>
            </div>
        </div>

    </div>

    <?php STM_LMS_Templates::show_lms_template('wizard/views/skip'); ?>

</div>