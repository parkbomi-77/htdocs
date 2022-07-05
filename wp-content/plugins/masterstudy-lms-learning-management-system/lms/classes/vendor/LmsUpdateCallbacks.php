<?php

namespace stmLms\Classes\Vendor;

abstract class LmsUpdateCallbacks {

	/**
	 * Add uf_new_messages column to Conversations table.
	 * Rename new_messages column to ut_new_messages in Conversations table.
	 */
	public static function lms_chat_columns() {
		global $wpdb;

        $table_name = stm_lms_user_conversation_name($wpdb);

		if ( ! $wpdb->get_var( sprintf( "SHOW COLUMNS FROM `%s` LIKE 'uf_new_messages';", $table_name ) ) ) {
			$wpdb->query( sprintf( "ALTER TABLE `%s` ADD `uf_new_messages` INT NOT NULL, CHANGE `new_messages` `ut_new_messages` INT;", $table_name ) );
		}
	}

	/**
	 * Delete page routes config transient to reset them and autosave new routes
	 */
	public static function lms_page_routes(){
		delete_transient( 'stm_lms_routes_pages_transient' );
		delete_transient( 'stm_lms_routes_pages_config_transient' );

		flush_rewrite_rules( true );
	}

	public static function lms_admin_notification_transient() {
		$data = [ 'show_time' => DAY_IN_SECONDS * 3 + time(), 'step' => 0, 'prev_action' => '' ];
		set_transient( 'stm_masterstudy-lms-learning-management-system_notice_setting', $data );
	}
}