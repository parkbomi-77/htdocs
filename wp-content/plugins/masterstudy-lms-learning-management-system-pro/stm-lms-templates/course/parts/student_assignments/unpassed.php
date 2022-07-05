<?php if (!defined('ABSPATH')) {
	exit;
} //Exit if accessed directly ?>

<?php
/**
 * @var $post_id
 * @var $item_id
 * @var $unpassed
 */

$editor_id = "stm_lms_assignment__{$unpassed['id']}";

$q = new WP_Query([
	'posts_per_page' => 1,
	'post_type' => 'stm-assignments',
	'post__in' => [$item_id],
]);

$actual_link = STM_LMS_Assignments::get_current_url();

$attachments = STM_LMS_Assignments::uploaded_attachments($unpassed['id']);

$attempts = STM_LMS_Assignments::get_attempts($item_id);

$can_attempt = $attempts['can_attempt'];

$retake_html = [
	'',
	'</a>',
];

stm_lms_register_script(
	'assignment_edit',
	[],
	false,
	"stm_lms_editor_id = '{$editor_id}'; 
    stm_lms_draft_id = {$unpassed['id']}; 
    stm_lms_assignment_translations = {'delete' : '" . esc_html__('Delete File?',
		'masterstudy-lms-learning-management-system-pro') . "'}
    stm_lms_assignment_files = " . json_encode($attachments) . ""
);

if ($q->have_posts()): ?>
	<div class="stm-lms-course__assignment stm-lms-course__assignment-pending">
        <?php while ($q->have_posts()): $q->the_post(); ?>

			<?php STM_LMS_Templates::show_lms_template('course/parts/assignment_parts/task',
				['item_id' => $item_id, 'content' => get_the_content()]); ?>

			<div class="assignment_status not_passed heading_font">
                <div class="inner">
                    <i class="fa fa-times"></i>
                    <span>

                        <?php esc_html_e('You failed assignment.',
							'masterstudy-lms-learning-management-system-pro'); ?>

						<?php if (isset($attempts['total']) and isset($attempts['attempts'])): ?>
							<?php printf(esc_html__("%s/%s attempts.",
								'masterstudy-lms-learning-management-system-pro'),
								$attempts['attempts'],
								$attempts['total']
							) ?>
						<?php endif; ?>

						<?php if ($can_attempt): ?>
							<a href="<?php echo esc_url(add_query_arg([
								'start_assignment' => $item_id, 'course_id' => $post_id,
							], $actual_link)); ?>">
								<?php esc_html_e('Retake',
									'masterstudy-lms-learning-management-system-pro'); ?>
							</a>
						<?php endif; ?>

                    </span>
                </div>
            </div>

            <?php
			if (!empty($unpassed['meta']) and !empty($unpassed['meta']['editor_comment'])
				and !empty($unpassed['meta']['editor_comment'][0])) {
				STM_LMS_Templates::show_lms_template(
					'course/parts/assignment_parts/comment',
					[
						'comment' => $unpassed['meta']['editor_comment'][0],
						'editor_id' => $unpassed['editor_id'],
					]
				);
			} ?>



			<div class="stm_lms_assignment__edit">

                <div class="assignment_approved_content">
                    <?php echo wp_kses_post($unpassed['content']); ?>
                </div>

				<?php STM_LMS_Templates::show_lms_template('course/parts/assignment_parts/file_loader',
					['readonly' => true]); ?>

            </div>

		<?php endwhile; ?>
    </div>

<?php endif;