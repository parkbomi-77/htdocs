<?php

function stm_lms_registration()
{
	return array(
		'name' => esc_html__('Registration', 'masterstudy-lms-learning-management-system'),
		'label' => esc_html__('Product Registration', 'masterstudy-lms-learning-management-system'),
		'icon' => 'fas fa-store',
		'fields' => array(
			'type' => 'registration',
		)
	);
}