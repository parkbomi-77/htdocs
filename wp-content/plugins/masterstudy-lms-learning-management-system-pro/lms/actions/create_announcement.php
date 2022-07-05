<?php

add_filter('stm_lms_float_menu_items', function ($menus, $current_user, $lms_template_current, $object_id) {

    if (STM_LMS_Instructor::is_instructor()) {
        $menus[] = array(
            'order' => 20,
            'current_user' => $current_user,
            'lms_template_current' => $lms_template_current,
            'lms_template' => 'stm-lms-instructor-announcement',
            'menu_title' => esc_html__('Announcement', 'masterstudy-lms-learning-management-system-pro'),
            'menu_icon' => 'fa-bullhorn',
            'menu_url' => STM_LMS_User::my_announcements_url(),
        );
    }

    return $menus;
    
}, 10, 4);

add_action('wp_ajax_stm_lms_create_announcement', 'stm_lms_create_announcement_pro');

function stm_lms_create_announcement_pro()
{

    check_ajax_referer('stm_lms_create_announcement', 'nonce');

    $current_user = STM_LMS_User::get_current_user();
    $user_id = $current_user['id'];

    $r = array(
        'status' => 'success',
        'message' => esc_html__('Announcement has been sent to course students.', 'masterstudy-lms-learning-management-system-pro')
    );

    if (empty($_GET['post_id']) or empty($_GET['mail'])) {
        $r['status'] = 'error';
        $r['message'] = esc_html__('Please fill all fields', 'masterstudy-lms-learning-management-system-pro');
        wp_send_json($r);
    }

    $post_id = intval($_GET['post_id']);
    $mail = sanitize_text_field($_GET['mail']);

    /*get post author*/
    $post_author_id = get_post_field('post_author', $post_id);

    if ($post_author_id == $user_id) {

        do_action('stm_lms_announcement_ready_to_send', $post_id, $user_id, $mail);

        $users = stm_lms_get_course_users($post_id, array('user_id'));
        foreach ($users as $user) {
            $user_id = $user['user_id'];
            $user_info = get_userdata($user_id);
            STM_LMS_Helpers::send_email(
                $user_info->user_email,
                esc_html__('Announcement from Instructor', 'masterstudy-lms-learning-management-system-pro'),
                $mail,
                'stm_lms_announcement_from_instructor',
                compact('mail')
            );
        }

        wp_send_json($r);
    }


}