<?php
/**
 * @var $field
 * @var $field_name
 * @var $section_name
 *
 */

$field_key = "data['{$section_name}']['fields']['{$field_name}']";

include STM_LMS_PRO_PATH . '/addons/udemy/udemy_importer/components_js/udemy.php';

?>

<label v-html="<?php echo esc_attr($field_key); ?>['label']"></label>

<stm-udemy-search></stm-udemy-search>