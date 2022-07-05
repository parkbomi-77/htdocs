<div class="stm_lms_splash_wizard__content_tab"
     v-if="active_step === 'profiles'">

    <h4>
        <?php esc_html_e('Profiles', 'masterstudy-lms-learning-management-system'); ?>
    </h4>

    <hr v-if="isMarketPlace()"/>

    <div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_switch"
         v-if="isMarketPlace()"
         v-bind:class="{'inactive' : !wizard.disable_instructor_premoderation}">

        <?php STM_LMS_Templates::show_lms_template('wizard/views/field_data', array(
            'title' => esc_html__('Instructor Pre-moderation', 'masterstudy-lms-learning-management-system'),
        )); ?>

        <div class="stm_lms_splash_wizard__field_input">

            <?php STM_LMS_Templates::show_lms_template('wizard/fields/switcher', array(
                'model' => 'wizard.disable_instructor_premoderation',
                'desc' => esc_html__('When enabled, you need to moderate all the instructors and change the user role manually.', 'masterstudy-lms-learning-management-system'),
            )); ?>

        </div>

    </div>

    <hr/>

    <div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_switch"
         v-bind:class="{'inactive' : !wizard.register_as_instructor}">

        <?php STM_LMS_Templates::show_lms_template('wizard/views/field_data', array(
            'title' => esc_html__('Instructor Registration', 'masterstudy-lms-learning-management-system'),
        )); ?>

        <div class="stm_lms_splash_wizard__field_input">

            <?php STM_LMS_Templates::show_lms_template('wizard/fields/switcher', array(
                'model' => 'wizard.register_as_instructor',
                'desc' => esc_html__('By disabling instructor registration you are removing the checkbox "Register as an instructor" from the registration form.', 'masterstudy-lms-learning-management-system'),
            )); ?>

        </div>

    </div>

    <hr/>

    <div class="stm_lms_splash_wizard__field stm_lms_splash_wizard__field_image_radio profile-style">

        <?php STM_LMS_Templates::show_lms_template('wizard/views/field_data', array(
            'title' => esc_html__('Profile style', 'masterstudy-lms-learning-management-system'),
        )); ?>

        <div class="stm_lms_splash_wizard__field_input">

            <?php STM_LMS_Templates::show_lms_template('wizard/fields/radio_image', array(
                'model' => 'wizard.profile_style',
                'value' => 'default',
                'image' => 'assets/img/wizard/users/default@2x.png?v=1',
                'label' => esc_html__('Default', 'masterstudy-lms-learning-management-system'),
            )); ?>

            <?php STM_LMS_Templates::show_lms_template('wizard/fields/radio_image', array(
                'model' => 'wizard.profile_style',
                'value' => 'classic',
                'image' => 'assets/img/wizard/users/classic@2x.png?v=1',
                'label' => esc_html__('Classic', 'masterstudy-lms-learning-management-system'),
            )); ?>


        </div>

    </div>

</div>