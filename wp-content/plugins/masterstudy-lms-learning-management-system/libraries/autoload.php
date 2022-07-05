<?php
require_once STM_LMS_PATH . "/libraries/paypal/autoload.php";

if ( is_admin() ) {
    require_once STM_LMS_PATH . "/libraries/admin-notification/admin-notification.php";

    $init_data = [
        'plugin_title' => 'MasterStudy LMS Plugin',
        'plugin_name'  => 'masterstudy-lms-learning-management-system',
        'plugin_file'  => STM_LMS_FILE,
        'logo'         => STM_LMS_URL . 'assets/img/ms-logo.png'
    ];
    stm_admin_notification_init( $init_data );
}