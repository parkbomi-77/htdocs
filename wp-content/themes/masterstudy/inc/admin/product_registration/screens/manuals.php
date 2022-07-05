<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$manuals = [
	[
		'title' => 'Education LMS WordPress Theme for Online Courses',
		'video_id' => 'wGtDvLkVvaQ',
		'tags' => [
			'guides',
			'overview',
			'features',
		],
	],
	[
		'title' => 'MasterStudy PRO - LMS plugin for WordPress',
		'video_id' => 'p3_zQbWTlEE',
		'tags' => [
			'features',
			'overview',
		],
	],
	[
		'title' => 'WordPress Theme for Online Courses – MasterStudy',
		'video_id' => '8WQf7LnS4Sk',
		'tags' => [
			'overview',
			'features',
		],
	],
	[
		'title' => 'Education WordPress Theme | Masterstudy - Installation',
		'video_id' => 'a8zb5KTAw48',
		'tags' => [
			'installation',
			'guides',
		],
	],
	[
		'title' => 'How to create a membership Pricing Plan in LMS WordPress theme',
		'video_id' => 'xen5oWdO9CE',
		'tags' => [
			'addons',
			'features',
			'setup',
		],
	],
	[
		'title' => 'How to use Statistics and Payouts',
		'video_id' => 'MUIE0gbs8QY',
		'tags' => [
			'addons',
			'features',
			'setup',
		],
	],
	[
		'title' => 'How to Create an Online Course, Lesson and Quiz in MasterStudy',
		'video_id' => 'enASM22U3JY',
		'tags' => [
			'setup',
			'features',
		],
	],
	[
		'title' => 'Points Reward System Feature in MasterStudy',
		'video_id' => 'HCQHA9IrVWw',
		'tags' => [
			'addons',
			'features',
			'setup',
		],
	],
	[
		'title' => 'Assignments Add-On | Masterstudy LMS',
		'video_id' => 'XCAXxaBnz54',
		'tags' => [
			'setup',
			'features',
			'addons',
		],
	],
	[
		'title' => 'Course Bundles Add-On | Masterstudy LMS',
		'video_id' => 'KgIUHEGOMOI',
		'tags' => [
			'addons',
			'features',
			'setup',
		],
	],
	[
		'title' => 'How to Create Offline Courses in Masterstudy',
		'video_id' => 'FgAVhdBuT90',
		'tags' => [
			'guides',
			'features',
		],
	],
	[
		'title' => 'How to Speed up Your WordPress Site',
		'video_id' => 'WH7ghByG2vw',
		'tags' => [
			'guides',
		],
	],
	[
		'title' => 'How to create a Membership Pricing Plan in MasterStudy LMS',
		'video_id' => 'kxp_gjwHH-k',
		'tags' => [
			'features',
			'setup',
		],
	],
	[
		'title' => 'MasterStudy - WPML Settings | StylemixThemes',
		'video_id' => 'frw7rdgBe2w',
		'tags' => [
			'guides',
			'setup',
		],
	],
	[
		'title' => 'SCORM Add-on MasterStudy LMS | StylemixThemes',
		'video_id' => '1CvuxLAjFW0',
		'tags' => [
			'addons',
			'features',
			'setup',
		],
	],
];

/**
 * Collect Tags
 */
$tags = array_unique( call_user_func_array( 'array_merge', array_column( $manuals, 'tags' ) ) );

?>
<div class="wrap about-wrap stm-admin-wrap stm-admin-manuals-screen">
	<?php stm_get_admin_tabs('manuals'); ?>

	<div class="stm-video-filters">
		<a href="#" class="stm-videos-filter" data-key="all"><?php esc_html_e('All', 'masterstudy'); ?></a>
		<?php foreach ( $tags as $tag ) { ?>
			<a href="#" class="stm-videos-filter" data-key="<?php echo esc_attr( $tag ); ?>"><?php echo wp_kses( $tag, [] ); ?></a>
		<?php } ?>
	</div>

	<div class="stm-video-manuals">
		<?php foreach ( $manuals as $manual ) { ?>
			<div class="video-manual" data-tags="all <?php echo esc_attr( implode( ' ', $manual['tags'] ) ); ?>">
				<iframe src="https://www.youtube.com/embed/<?php echo esc_attr( $manual['video_id'] ); ?>?wmode=opaque&theme=dark&showinfo=0&rel=0&controls=0"
				        height="300" frameborder="0" allowfullscreen></iframe>
				<div class="title-box">
					<h3><?php echo wp_kses( $manual['title'], [] ); ?></h3>
					<div class="tags">
						<?php foreach ( $manual['tags'] as $tag ) { ?>
							<a href="#" class="stm-videos-filter" data-key="<?php echo esc_attr( $tag ); ?>">#<?php echo wp_kses( $tag, [] ); ?></a>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>

<script type="text/javascript">
    "use strict";

    (function ($) {
        /**
         * Videos Filter
         */
        $('.stm-videos-filter').on('click', function (e) {
            e.preventDefault();
            let tag = $(this).data('key');

            $('.stm-video-filters > a').removeClass('active');
            $(`.stm-video-filters > a[data-key="${tag}"]`).addClass('active');
            $('.stm-video-manuals > .video-manual').each(function() {
                $(this).toggle(
                    $(this).filter('[data-tags*="' + tag + '"]').length > 0
                );
            });
        });

    })(jQuery);
</script>