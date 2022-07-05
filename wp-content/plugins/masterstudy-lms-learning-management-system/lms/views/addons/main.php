<div class="stm-lms-addons-header">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'MasterStudy PRO Addons', 'masterstudy-lms-learning-management-system' ); ?></h1>
	<?php if ( ! STM_LMS_Helpers::is_pro() ) : ?>
		<div class="stm-lms-get-pro">
			<span>
				<?php esc_html_e( 'Improve your LMS with', 'masterstudy-lms-learning-management-system' ); ?>
				<b><?php esc_html_e( 'MasterStudy Pro', 'masterstudy-lms-learning-management-system' ); ?></b>
			</span>
			<a href="https://stylemixthemes.com/wordpress-lms-plugin/?utm_source=wpadmin&utm_medium=addons&utm_campaign=masterstudy-plugin&licenses=1&billing_cycle=annual" target="_blank">
				<?php esc_html_e( 'Get now', 'masterstudy-lms-learning-management-system' ); ?>
			</a>
		</div>
	<?php endif; ?>
</div>

<div class="stm-lms-addons">
    <?php foreach ( $addons as $key => $addon ) :
        $addon_enabled = ! empty( $enabled_addons[ $key ] ); ?>
        <div class="stm-lms-addon <?php if ( $addon_enabled ) echo 'active'; ?>">
            <div class="addon-image">
                <img src="<?php echo esc_url($addon['url']); ?>"/>
            </div>
            <div class="addon-install">
                <div class="addon-title">
                    <h4 class="addon-name"><?php echo wp_kses($addon['name'], []); ?></h4>
                    <?php if ( ! empty( $addon['documentation'] ) ) : ?>
                        <div>
                            <a href="https://docs.stylemixthemes.com/masterstudy-lms/lms-pro-addons/<?php esc_attr_e($addon['documentation']); ?>" target="_blank">
                                <?php esc_html_e( 'How it works', 'masterstudy-lms-learning-management-system' ); ?>
                            </a>
                            <i class="stmlms-question"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="addon-description"><?php echo wp_kses($addon['description'], []); ?></div>
                <?php if ( STM_LMS_Helpers::is_pro() ) : ?>
                    <div class="wpcfto-admin-checkbox section_2-enable_courses_filter">
                        <label class="toggle-addon" data-key="<?php esc_attr_e($key); ?>">
                            <div class="wpcfto-admin-checkbox-wrapper is_toggle <?php if ( $addon_enabled ) echo 'active'; ?>">
                                <div class="wpcfto-checkbox-switcher"></div>
                                <input type="checkbox" name="enable_courses_filter" id="section_2-enable_courses_filter">
                            </div>
                        </label>
                    </div>
                    <?php if ( ! empty( $addon['settings'] ) ) : ?>
                        <a href="<?php echo esc_url($addon['settings']); ?>" class="addon-settings <?php if ( $addon_enabled ) echo 'active'; ?>" target="_blank">
                            <i class="fa fa-cog"></i>
                            <?php esc_html_e( 'Settings', 'masterstudy-lms-learning-management-system' ); ?>
                        </a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo esc_url($addon['pro_url']); ?>" class="btn stm-pro-btn" target="_blank">Buy now</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>