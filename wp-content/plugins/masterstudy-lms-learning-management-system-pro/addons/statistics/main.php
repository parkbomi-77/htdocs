<?php
new Stm_Lms_Statistics();

class Stm_Lms_Statistics
{

    function __construct()
    {
        if (class_exists("\stmLms\Classes\Models\StmStatistics")) {
            $statistics = new \stmLms\Classes\Models\StmStatistics();
            $statistics->admin_menu();
            $user = new \stmLms\Classes\Models\StmUser(get_current_user_id());
            if ($user) {
                $user_role = $user->getRole();
                if ($user_role && $user_role['id'] == "stm_lms_instructor")
                    add_filter('stm_lms_float_menu_items', function ($menus, $current_user, $lms_template_current, $object_id) {
                        $menus[] = array(
                            'order' => 70,
                            'current_user' => $current_user,
                            'lms_template_current' => $lms_template_current,
                            'lms_template' => 'stm-lms-payout-statistic',
                            'menu_title' => esc_html__('Payout', 'masterstudy-lms-learning-management-system-pro'),
                            'menu_icon' => 'fa-chart-pie',
                            'menu_url' => $this::url(),
                        );

                        return $menus;
                    }, 10, 4);
            }
        }
    }

    /**
     * @return mixed
     */
    public function url()
    {
        $pages_config = STM_LMS_Page_Router::pages_config();

        return STM_LMS_User::login_page_url() . $pages_config['user_url']['sub_pages']['payout_statistic']['url'];
    }
}
