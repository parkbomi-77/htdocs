<?php

function stm_lms_settings_profiles_section()
{

    $pages = WPCFTO_Settings::stm_get_post_type_array('page');
    $submenu_general = esc_html__('General', 'masterstudy-lms-learning-management-system');

    $general_fields = array(
		'user_premoderation' => array(
			'type' => 'checkbox',
			'label' => esc_html__('Enable Email Confirmation', 'masterstudy-lms-learning-management-system'),
			'hint' => esc_html__('All new registration will have an E-mail with account verification', 'masterstudy-lms-learning-management-system'),
			'submenu' => $submenu_general
		),
		'register_as_instructor' => array(
			'type' => 'checkbox',
			'label' => esc_html__('Disable Instructor Registration', 'masterstudy-lms-learning-management-system'),
			'hint' => esc_html__('Remove checkbox "Register as instructor" from registration', 'masterstudy-lms-learning-management-system'),
			'submenu' => $submenu_general
		),
		'disable_instructor_premoderation' => array(
			'type' => 'checkbox',
			'label' => esc_html__('Disable Instructor Pre-moderation', 'masterstudy-lms-learning-management-system'),
			'hint' => esc_html__('Set user role "instructor" automatically', 'masterstudy-lms-learning-management-system'),
			'submenu' => $submenu_general
		),
		'instructor_can_add_students' => array(
			'type' => 'checkbox',
			'label' => esc_html__('Add students to own course', 'masterstudy-lms-learning-management-system'),
			'hint' => esc_html__('Instructor will have a tool in an account to add student by email to course', 'masterstudy-lms-learning-management-system'),
			'submenu' => $submenu_general
		),
		'course_premoderation' => array(
			'type' => 'checkbox',
			'label' => esc_html__('Enable Course Pre-moderation', 'masterstudy-lms-learning-management-system'),
			'hint' => esc_html__('Course will have Pending status, until you approve it', 'masterstudy-lms-learning-management-system'),
			'pro' => true,
			'submenu' => $submenu_general
		),
		'instructors_page' => array(
			'type' => 'select',
			'label' => esc_html__('Instructors Archive Page', 'masterstudy-lms-learning-management-system'),
			'options' => $pages,
			'submenu' => $submenu_general
		),
		'cancel_subscription' => array(
			'type' => 'select',
			'label' => esc_html__('Cancel subscription page', 'masterstudy-lms-learning-management-system'),
			'options' => $pages,
			'hint' => esc_html__('If you want to display link to Cancel Subscription page, choose page and add to page content shortcode [pmpro_cancel].', 'masterstudy-lms-learning-management-system'),
			'submenu' => $submenu_general
		),
	);

    return array(
        'name' => esc_html__('Profiles', 'masterstudy-lms-learning-management-system'),
        'label' => esc_html__('Profiles Settings', 'masterstudy-lms-learning-management-system'),
        'icon' => 'fa fa-user-circle',
        'fields' => $general_fields + stm_lms_settings_float_menu_section()
    );
}
