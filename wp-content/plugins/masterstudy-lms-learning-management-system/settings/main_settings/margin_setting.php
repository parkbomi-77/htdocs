
<?php

function stm_lms_margin_setting()
{
	return array(
		'name' => esc_html__('margin_setting', 'masterstudy-lms-learning-management-system'),
		'label' => esc_html__('margin_setting', 'masterstudy-lms-learning-management-system'),
		'icon' => 'fas fa-percentage',
		'fields' => array(
			'type' => 'margin_setting',
		)
	);
}

