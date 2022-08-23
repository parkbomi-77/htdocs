<?php

function stm_lms_shopping_mall()
{
	return array(
		'name' => esc_html__('shopping_mall', 'masterstudy-lms-learning-management-system'),
		'label' => esc_html__('shopping_mall', 'masterstudy-lms-learning-management-system'),
		'icon' => 'fas fa-archway',
		'fields' => array(
			'type' => 'shopping_mall',
		)
	);
}