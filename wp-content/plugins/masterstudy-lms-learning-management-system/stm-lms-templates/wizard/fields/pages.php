<?php
/**
 * @var $pages
 * @var $btn_title
 */

if (empty($btn_title)) $btn_title = esc_html__('Generate Pages', 'masterstudy-lms-learning-management-system');
?>

<div class="pages">

    <?php foreach ($pages as $page_key => $page): ?>
        <div class="page" v-bind:class="{'created' : system_pages.<?php echo esc_attr($page_key); ?>}">
            <?php echo esc_html($page); ?>
        </div>
    <?php endforeach; ?>

</div>

<a href="#" class="btn" @click="generatePages({
        <?php foreach ($pages as $page_key => $page): ?>
            <?php echo esc_attr($page_key); ?>: '<?php echo esc_html($page); ?>',
        <?php endforeach; ?>
    })">

    <i class="fa fa-arrow-right" v-if="!loading_system_pages"></i>

    <i class="fa fa-circle-notch" v-else></i>

    <span v-if="!loading_system_pages">
        <?php echo esc_html($btn_title); ?>
    </span>

    <span v-else>
        <?php esc_html_e('Generating',
            'masterstudy-lms-learning-management-system'); ?>
    </span>
</a>