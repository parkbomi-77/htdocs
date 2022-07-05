<?php
function stm_lms_settings_float_menu_section()
{

	$submenu_float_menu = esc_html__('Floating menu', 'masterstudy-lms-learning-management-system');

	return array(
		'float_menu' => array(
			'type' => 'checkbox',
			'label' => esc_html__('Enable floating menu', 'masterstudy-lms-learning-management-system'),
			'value' => false,
			'submenu' => $submenu_float_menu,
		),
		'float_menu_guest' => array(
			'type' => 'checkbox',
			'label' => esc_html__('Show floating menu for guest users', 'masterstudy-lms-learning-management-system'),
			'value' => true,
			'dependency' => array(
				'key' => 'float_menu',
				'value' => 'not_empty'
			),
			'submenu' => $submenu_float_menu,
		),
		'float_menu_position' => array(
			'type' => 'select',
			'label' => esc_html__('Floating menu position', 'masterstudy-lms-learning-management-system'),
			'options' => array(
				'left' => esc_html__('Left', 'masterstudy-lms-learning-management-system'),
				'right' => esc_html__('Right', 'masterstudy-lms-learning-management-system'),
			),
			'value' => 'left',
			'dependency' => array(
				'key' => 'float_menu',
				'value' => 'not_empty'
			),
			'submenu' => $submenu_float_menu,
		),
		/*GROUP STARTED*/
		'float_background_color' => array(
			'group' => 'started',
			'type' => 'color',
			'label' => esc_html__('Background color', 'masterstudy-lms-learning-management-system'),
			'columns' => '33',
			'group_title' => esc_html__('Float menu Colors', 'masterstudy-lms-learning-management-system'),
			'dependency' => array(
				'key' => 'float_menu',
				'value' => 'not_empty'
			),
			'submenu' => $submenu_float_menu,
		),
		'float_text_color' => array(
			'group' => 'ended',
			'type' => 'color',
			'label' => esc_html__('Text color', 'masterstudy-lms-learning-management-system'),
			'columns' => '33',
			'dependency' => array(
				'key' => 'float_menu',
				'value' => 'not_empty'
			),
			'submenu' => $submenu_float_menu,
		),
		/*GROUP ENDED*/
	);
}
