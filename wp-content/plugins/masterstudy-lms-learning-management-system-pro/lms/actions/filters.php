<?php
// Remove `stm_lms_product` from main archive
if (class_exists('WooCommerce') && is_admin()) add_action('pre_get_posts', 'stm_lms_product_remove_from_archive');
function stm_lms_product_remove_from_archive($query)
{
	$meta_query = $tax_query = array();

	if (empty($_GET['stm_lms_product']) &&
		empty($_GET['post_status']) &&
		(!empty($_GET['post_type']) && $_GET['post_type'] == 'product')
	) {
		$tax_query = array(
			array(
				'taxonomy' => 'product_type',
				'field' => 'slug',
				'terms' => 'stm_lms_product',
				'operator' => 'NOT IN'
			)
		);

		$query->set('tax_query', $tax_query);
	}
}
