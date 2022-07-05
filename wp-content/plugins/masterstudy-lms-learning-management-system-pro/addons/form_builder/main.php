<?php
new STM_LMS_Form_Builder;

class STM_LMS_Form_Builder
{
    function __construct()
    {
        add_action('admin_menu', array($this, 'admin_menu'), 10000);
        add_action('wp_ajax_stm_lms_save_forms', array($this, 'save_forms'));
        add_action('wp_ajax_stm_lms_upload_form_file', array($this, 'upload_file'));
        add_action('wp_ajax_nopriv_stm_lms_upload_form_file', array($this, 'upload_file'));
        add_filter('stm_lms_user_additional_fields', array($this, 'profile_fields'));
        add_filter('stm_lms_email_manager_emails', array($this, 'email_settings'));

        add_action('show_user_profile', array($this, 'display_fields_in_profile'), 20);
        add_action('edit_user_profile', array($this, 'display_fields_in_profile'), 20);

        add_action('personal_options_update', array($this, 'save_fields_in_profile'));
        add_action('edit_user_profile_update', array($this, 'save_fields_in_profile'));
    }

    function admin_menu()
    {
        add_submenu_page(
            'stm-lms-settings',
            esc_html__('Forms Editor', 'masterstudy-lms-learning-management-system-pro'),
            esc_html__('Forms Editor', 'masterstudy-lms-learning-management-system-pro'),
            'manage_options',
            'form_builder',
            array($this, 'admin_page'),
            80
        );
    }

    function admin_page()
    {
        wp_enqueue_script('vuedraggable', STM_LMS_URL . '/assets/vendors/vuedraggable.umd.min.js', array(), stm_lms_custom_styles_v());
        /*Forms*/
        $fields = self::get_fields();

        foreach ($fields as $field) {
            wp_enqueue_script(
                "stm_lms_forms_{$field['type']}",
                STM_LMS_URL . "/assets/js/form_builder/components/fields/{$field['type']}.js",
                array(),
                stm_lms_custom_styles_v()
            );
        }

        wp_enqueue_style('stm_lms_form_builder', STM_LMS_URL . '/assets/css/parts/form_builder/main.css', array(), stm_lms_custom_styles_v());
        wp_enqueue_script('stm_lms_fields_sidebar', STM_LMS_URL . '/assets/js/form_builder/components/fields_sidebar.js', array(), stm_lms_custom_styles_v());
        wp_enqueue_script('stm_lms_form_builder_form', STM_LMS_URL . '/assets/js/form_builder/components/form.js', array(), stm_lms_custom_styles_v());
        wp_enqueue_script('stm_lms_form_builder_main', STM_LMS_URL . '/assets/js/form_builder/main.js', array(), stm_lms_custom_styles_v());
        wp_localize_script('stm_lms_fields_sidebar', 'stm_lms_form_fields', self::get_fields());
        wp_localize_script('stm_lms_form_builder_main', 'stm_lms_forms', self::get_forms());
        require_once STM_LMS_PRO_PATH . '/addons/form_builder/templates/main.php';
    }

    public static function get_forms()
    {
        $default = array(
            array(
                'slug' => 'become_instructor',
                'name' => esc_html__('Become Instructor Form', 'masterstudy-lms-learning-management-system-pro'),
                'fields' => array()
            ),
            array(
                'slug' => 'enterprise_form',
                'name' => esc_html__('Enterprise Form', 'masterstudy-lms-learning-management-system-pro'),
                'fields' => array()
            ),
            array(
                'slug' => 'profile_form',
                'name' => esc_html__('Profile Form', 'masterstudy-lms-learning-management-system-pro'),
                'fields' => array()
            )
        );

        $forms = get_option('stm_lms_form_builder_forms', $default);
        $default_profile_fields = array(
            'first_name' => array(
                'register' => false,
                'required' => false,
            ),
            'last_name' => array(
                'register' => false,
                'required' => false,
            ),
            'position' => array(
                'register' => false,
                'required' => false,
            ),
            'description' => array(
                'register' => false,
                'required' => false,
            ),
        );

        $default_profile_fields = get_option('stm_lms_default_profile_form', $default_profile_fields);

        $profile_form = array(
            'first_name' => array(
                'label' => esc_html__('First Name', 'masterstudy-lms-learning-management-system-pro'),
                'placeholder' => esc_html__('Enter your name', 'masterstudy-lms-learning-management-system-pro'),
                'register' => !empty($default_profile_fields['first_name']['register']) ? $default_profile_fields['first_name']['register'] : false,
                'required' => !empty($default_profile_fields['first_name']['required']) ? $default_profile_fields['first_name']['required'] : false,
                'value' => ''
            ),
            'last_name' => array(
                'label' => esc_html__('Last Name', 'masterstudy-lms-learning-management-system-pro'),
                'placeholder' => esc_html__('Enter your last name', 'masterstudy-lms-learning-management-system-pro'),
                'register' => !empty($default_profile_fields['last_name']['register']) ? $default_profile_fields['last_name']['register'] : false,
                'required' => !empty($default_profile_fields['last_name']['required']) ? $default_profile_fields['last_name']['required'] : false,
                'value' => ''
            ),
            'position' => array(
                'label' => esc_html__('Position', 'masterstudy-lms-learning-management-system-pro'),
                'placeholder' => esc_html__('Enter your position', 'masterstudy-lms-learning-management-system-pro'),
                'register' => !empty($default_profile_fields['position']['register']) ? $default_profile_fields['position']['register'] : false,
                'required' => !empty($default_profile_fields['position']['required']) ? $default_profile_fields['position']['required'] : false,
                'value' => ''
            ),
            'description' => array(
                'label' => esc_html__('Bio', 'masterstudy-lms-learning-management-system-pro'),
                'placeholder' => esc_html__('Enter your BIO', 'masterstudy-lms-learning-management-system-pro'),
                'register' => !empty($default_profile_fields['description']['register']) ? $default_profile_fields['description']['register'] : false,
                'required' => !empty($default_profile_fields['description']['required']) ? $default_profile_fields['description']['required'] : false,
                'value' => ''
            ),
        );

        $required_fields = array(
            'become_instructor' => array(),
            'enterprise_form' => array(),
            'profile_form' => $profile_form
        );
        $user_forms = array(
            'forms' => $forms,
            'currentForm' => 0,
            'required_fields' => $required_fields
        );

        return $user_forms;
    }

    public static function get_become_instructor_form_fields() {
        $forms = get_option('stm_lms_form_builder_forms', array());
        $r = array();
        if(!empty($forms) && is_array($forms)){
            foreach ($forms as $form){
                if($form['slug'] === 'become_instructor'){
                    $r = $form['fields'];
                }
            }
        }
        return $r;
    }

    public static function get_enterprise_form_fields() {
        $forms = get_option('stm_lms_form_builder_forms', array());
        $r = array();
        if(!empty($forms) && is_array($forms)){
            foreach ($forms as $form){
                if($form['slug'] === 'enterprise_form'){
                    $r = $form['fields'];
                }
            }
        }
        return $r;
    }

    public static function get_fields()
    {
        $fields = array(
            array(
                'type' => 'text',
                'field_name' => esc_html__('Single Line Text', 'masterstudy-lms-learning-management-system-pro'),
            ),
            array(
                'type' => 'email',
                'field_name' => esc_html__('Email', 'masterstudy-lms-learning-management-system-pro'),
            ),
            array(
                'type' => 'select',
                'field_name' => esc_html__('Drop Down', 'masterstudy-lms-learning-management-system-pro'),
            ),
            array(
                'type' => 'radio',
                'field_name' => esc_html__('Radio Buttons', 'masterstudy-lms-learning-management-system-pro'),
            ),
            array(
                'type' => 'checkbox',
                'field_name' => esc_html__('Checkboxes', 'masterstudy-lms-learning-management-system-pro'),
            ),
            array(
                'type' => 'tel',
                'field_name' => esc_html__('Phone', 'masterstudy-lms-learning-management-system-pro'),
            ),
            array(
                'type' => 'file',
                'field_name' => esc_html__('File Attachment', 'masterstudy-lms-learning-management-system-pro'),
            ),
            array(
                'type' => 'textarea',
                'field_name' => esc_html__('Text Area', 'masterstudy-lms-learning-management-system-pro'),
            ),
        );
        return apply_filters('stm_lms_form_builder_available_fields', $fields);
    }

    function save_forms()
    {

        check_ajax_referer('stm_lms_save_forms', 'nonce');

        $data = json_decode(file_get_contents('php://input'), true);
        if (!empty($data['forms']) && !empty($data['requiredFields'])) {
            $forms = $data['forms'];
            $profile_form = !empty($data['requiredFields']['profile_form']) ? $data['requiredFields']['profile_form'] : array();
            if(!empty($profile_form)){
                update_option('stm_lms_default_profile_form', $profile_form);
            }
            array_walk_recursive($forms, "sanitize_text_field");
            update_option('stm_lms_form_builder_forms', $forms);
            wp_send_json('ok');
        }
    }

    function upload_file()
    {
        check_ajax_referer('stm_lms_upload_form_file', 'nonce');
        $extensions = 'png;jpg;jpeg;mp4;pdf';
        if(!empty($_POST['extensions'])){
            $extensions = sanitize_text_field($_POST['extensions']);
            $extensions = preg_replace('/\s+/','', $extensions);
            $extensions = str_replace('.', '', $extensions);
            $extensions = str_replace(',', ';', $extensions);
        }
        $is_valid_image = Validation::is_valid($_FILES, array(
            'file' => 'required_file|extension,' . $extensions
        ));

        if ($is_valid_image !== true) {
            wp_send_json(array(
                'error' => true,
                'message' => $is_valid_image[0]
            ));
        }


        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');


        $attachment_id = media_handle_upload('file', 0);

        if (is_wp_error($attachment_id)) {
            wp_send_json(array(
                'error' => true,
                'message' => $attachment_id->get_error_message()
            ));
        }

        $attachment = wp_get_attachment_url($attachment_id);

        wp_send_json(array(
            'files' => $_FILES,
            'id' => $attachment_id,
            'url' => $attachment,
            'error' => 'false',
        ));

    }

    function profile_fields($additional_fields)
    {
        $forms = get_option('stm_lms_form_builder_forms', array());
        $profile_form = array();
        if (!empty($forms) && is_array($forms)) {
            foreach ($forms as $form) {
                if ($form['slug'] === 'profile_form') {
                    $profile_form = $form['fields'];
                }
            }
        }
        $custom_fields = array();
        foreach ($profile_form as $field) {
            $custom_fields[$field['id']] = array(
                'label' => !empty($field['label']) ? $field['label'] : $field['field_name'],
                'required' => (!empty($field['required']) && $field['required'] !== 'false') ? true : false
            );
        }

        if(!empty($custom_fields)) {
            $additional_fields = array_merge($additional_fields, $custom_fields);
        }

        return $additional_fields;
    }

    public static function profile_default_fields_for_register()
    {
        $default_profile_form = get_option('stm_lms_default_profile_form');
        $fields = array();
        if(!empty($default_profile_form)){
            foreach ($default_profile_form as $index => $field){
                if($field['register'] && $field['register'] !== 'false'){
                    $fields[$index] = $field;
                }
            }
        }
        return $fields;
    }

    public static function register_form_fields($only_register = true)
    {
        $forms = get_option('stm_lms_form_builder_forms', array());
        $profile_form = array();
        $become_instructor = array();
        $register_form = array();
        foreach ($forms as $form) {
            if ($form['slug'] === 'profile_form') {
                $profile_form = $form['fields'];
            }
            elseif ($form['slug'] === 'become_instructor') {
                $become_instructor = $form['fields'];
            }
        }
        foreach ($profile_form as $field) {

            if($only_register){
                if(!empty($field['register'])){
                    $register_form[] = $field;
                }
            }
            else {
                $register_form[] = $field;
            }
        }

        return array(
            'register' => $register_form,
            'become_instructor' => $become_instructor,
        );
    }

    function email_settings($data)
    {
        $enterprise_form = self::get_enterprise_form_fields();
        $enterprise_fields = array();
        if(!empty($enterprise_form)){
            foreach ($enterprise_form as $field){
                if(!empty($field['slug'])){
                    $enterprise_fields[$field['slug']] = !empty($field['label']) ? $field['label'] : $field['slug'];
                }
            }
        }
        if(!empty($enterprise_fields)){
            $data['stm_lms_enterprise']['vars'] = $enterprise_fields;
        }

        $become_instructor_form = self::get_become_instructor_form_fields();
        $become_instructor_fields = array();

        if(!empty($become_instructor_form)){
            foreach ($become_instructor_form as $field){
                if(!empty($field['slug'])){
                    $become_instructor_fields[$field['slug']] = !empty($field['label']) ? $field['label'] : $field['slug'];
                }
            }
        }
        if(!empty($become_instructor_fields)){
            $become_instructor_fields['user_login'] = esc_html__('User login', 'masterstudy-lms-learning-management-system-pro');
            $become_instructor_fields['user_id'] = esc_html__('User ID', 'masterstudy-lms-learning-management-system-pro');
            $data['stm_lms_become_instructor_email']['vars'] = $become_instructor_fields;
        }

        return $data;
    }

    function display_fields_in_profile($user)
    {
        require_once(STM_LMS_PRO_PATH . '/addons/form_builder/templates/admin_profile_fields.php');
    }

    function save_fields_in_profile($user_id)
    {
        $fields = STM_LMS_Form_Builder::register_form_fields(false);
        if (!empty($fields) && !empty($fields['register']) && !empty($fields['become_instructor'])) {
            $fields = array_merge($fields['register'], $fields['become_instructor']);
            foreach ($fields as $field){
                if(!empty($_POST[$field['id']])){
                    update_user_meta($user_id, $field['id'], sanitize_text_field($_POST[$field['id']]));
                }
            }
        }
    }
}