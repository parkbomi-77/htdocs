<?php

new STM_LMS_Compatibility();

class STM_LMS_Compatibility {

	private static $pro = '3.5.0';

	public function __construct() {

		add_action('admin_notices', array($this, 'init'));

	}

	function check_version() {

		$has_pro = defined('STM_LMS_PRO_FILE');

		if(!$has_pro) return false;

		$plugin_data = get_plugin_data( STM_LMS_PRO_FILE );
		$version = $plugin_data['Version'];

		return version_compare(self::$pro, $version) > 0;

	}

	function init() {

		if ($this->check_version()) : ?>

			<div class="notice notice-lms notice-lms-go-to-pages notice-lms-compatibility">

				<div class="notice-lms-go-to-pages-icon">
					<i class="fa fa-exclamation"></i>
				</div>

				<div class="notice-lms-go-to-pages-content">

					<p>
						<strong>
							<?php esc_html_e('Please update MasterStudy LMS Pro!', 'masterstudy-lms-learning-management-system'); ?>
						</strong>
					</p>

					<p>
						<?php esc_html_e('The current version of MasterStudy LMS is not compatible with old versions of the MasterStudy LMS Pro plugin, some functionality may not work correctly or may stop working completely.', 'masterstudy-lms-learning-management-system'); ?>
					</p>

				</div>

			</div>

		<?php endif;

	}

}