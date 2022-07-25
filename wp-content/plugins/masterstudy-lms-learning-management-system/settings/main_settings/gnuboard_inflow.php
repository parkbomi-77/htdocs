<?php

function stm_lms_gnuboard()
{
	return array(
		'name' => esc_html__('Inflowbox', 'masterstudy-lms-learning-management-system'),
		'label' => esc_html__('Product Inflowbox', 'masterstudy-lms-learning-management-system'),
		'icon' => 'fas fa-location-arrow',
		'fields' => array(
			'type' => 'gnuboard_inflow',
		)
	);
}