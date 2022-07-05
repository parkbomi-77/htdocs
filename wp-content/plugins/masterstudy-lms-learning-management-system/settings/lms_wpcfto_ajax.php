<?php

new STM_LMS_WPCFTO_AJAX();

class STM_LMS_WPCFTO_AJAX
{
    public function __construct()
    {
        add_action('wp_ajax_stm_curriculum_create_item', array($this, 'stm_curriculum_create_item'));

        add_action('wp_ajax_stm_curriculum_get_item', array($this, 'stm_curriculum_get_item'));

        add_action('wp_ajax_stm_save_questions', array($this, 'stm_save_questions'));

        add_action('wp_ajax_stm_save_title', array($this, 'stm_save_title'));

        add_filter('stm_wpcfto_autocomplete_review_user', array($this, 'users_search'), 10, 2);
    }

    function stm_curriculum_create_item()
    {

        check_ajax_referer('stm_curriculum_create_item', 'nonce');

        $r = array();
        $available_post_types = array('stm-lessons', 'stm-quizzes', 'stm-questions', 'stm-assignments');

        if (!empty($_GET['post_type'])) $post_type = sanitize_text_field($_GET['post_type']);
        if (!empty($_GET['title'])) $title = sanitize_text_field($_GET['title']);
        $is_front = (bool)(!empty($_GET['is_front'])) ? sanitize_text_field($_GET['is_front']) : false;

        /*Check if data passed*/
        if (empty($post_type) and empty($title)) return;

        /*Check if available post type*/
        if (!in_array($post_type, $available_post_types)) return;

        $item = array(
            'post_type' => $post_type,
            'post_title' => html_entity_decode($title),
            'post_status' => 'publish',
        );

        $r['id'] = wp_insert_post($item);

        do_action('stm_lms_item_added', array("id" => $r['id'], 'front' => $is_front));

        $r['title'] = html_entity_decode(get_the_title($r['id']));
        $r['post_type'] = $post_type;
        $r['edit_link'] = html_entity_decode(get_edit_post_link($r['id']));

        $r = apply_filters('stm_lms_wpcfto_create_question', $r, array($post_type));

        do_action('stm_lms_item_question_added', array("id" => $r['id'], 'front' => $is_front));

        wp_send_json($r);

    }

    function stm_curriculum_get_item()
    {

        check_ajax_referer('stm_curriculum_get_item', 'nonce');

        $post_id = intval($_GET['id']);
        $r = array();

        $r['meta'] = STM_LMS_Helpers::simplify_meta_array(get_post_meta($post_id));
        if (!empty($r['meta']['lesson_video_poster'])) {
            $image = wp_get_attachment_image_src($r['meta']['lesson_video_poster'], 'img-870-440');
            if (!empty($image[0])) $r['meta']['lesson_video_poster_url'] = $image[0];
        }
        if (!empty($r['meta']['lesson_video'])) {
            $video = wp_get_attachment_url($r['meta']['lesson_video']);

            if (!empty($video)) $r['meta']['uploaded_lesson_video'] = $video;
        }
        if (!empty($r['meta']['lesson_files_pack'])) {
            $r['meta']['lesson_files_pack'] = json_decode($r['meta']['lesson_files_pack']);
        }
        $r['content'] = get_post_field('post_content', $post_id);

        wp_send_json($r);
    }

    function stm_save_questions()
    {

        check_ajax_referer('stm_save_questions', 'nonce');

        $r = array();
        $request_body = file_get_contents('php://input');

        do_action('stm_lms_before_save_questions');

        if (!empty($request_body)) {

            $fields = STM_LMS_WPCFTO_HELPERS::get_question_fields();

            $data = json_decode($request_body, true);

            foreach ($data as $question) {

                if (empty($question['id'])) continue;
                $post_id = $question['id'];

                foreach ($fields as $field_key => $field) {
                    if (isset($question[$field_key])) {
                        foreach ($question[$field_key] as $index => $value) {
                            $question[$field_key][$index]['text'] = sanitize_text_field(wp_slash($value['text']));
                        }

                        $r[$field_key] = update_post_meta($post_id, $field_key, $question[$field_key]);
                    }
                }
            }
        }

        wp_send_json($r);
    }

    function stm_save_title()
    {

        check_ajax_referer('stm_save_title', 'nonce');

        if (empty($_GET['id']) and !empty($_GET['title'])) return false;

        $post = array(
            'ID' => intval($_GET['id']),
            'post_title' => sanitize_text_field($_GET['title']),
        );

        wp_update_post($post);

        wp_send_json($post);
    }

    function users_search($r, $args)
    {

        $s_args = array();

        if (!empty($_GET['s'])) {
            $s = sanitize_text_field($_GET['s']);
            $s_args['search'] = "*{$s}*";
            $s_args['search_columns'] = array(
                'user_login',
                'user_nicename',
            );
        }

        if(!empty($_GET['ids'])) {
            $s_args['include'] = explode(',', sanitize_text_field($_GET['ids']));
        }

        $users = new WP_User_Query($s_args);
        $users = $users->get_results();

        $data = array();

        foreach ($users as $user) {
            $data[] = array(
                'id' => $user->ID,
                'title' => $user->data->user_nicename,
                'post_type' => 'user'
            );
        }


        return $data;

    }
}