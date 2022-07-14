<style>
    .wish_table_head{
        background-color: rgb(177, 182, 180);
    }
    .wish_table_body{
        text-align: center;
    }
</style>


<?php
$style = (defined('STM_POST_TYPE')) ? 'post_type_exist' : 'text_block';

get_header(); ?>

<?php get_template_part('partials/title_box'); ?>

    <div class="container">
        
        <?php global $wpdb, $post;
            if($post->ID === 10656){
                // 일단 유저 id 3으로 하드코딩 
                $results = $wpdb->get_results($wpdb->prepare("SELECT * from wp_wish_list where user_id = 3"));
                $num = count($results);

                $tr = "";
                for($i = 0; $i<$num; $i++){
                    $tr = $tr.
                    "<tr>
                        <td>".$results[$i]->ID."</td>
                        <td>".$results[$i]->item_id."</td>
                        <td>".$results[$i]->quantity."</td>
                        <td>".$results[$i]->price."</td>
                        <td class='wish_table_trash'><i class='fa-solid fa-trash'></i></td>
                    </tr>";
                }

                echo "
            <table>
                <colgroup>
                  <col style='width:50px'>
                  <col style='width:65%'>
                  <col style='width:80px'>
                  <col style='width:60px'>
                  <col style='width:80px'>
                </colgroup>
                <thead class='wish_table_head'>
                  <tr>
                    <th scope='col'>
                        no.
                    </th>
                    <th scope='col'>
                        제품명
                    </th>
                    <th scope='col'>
                        수량
                    </th>
                    <th scope='col'>
                      가격
                    </th>
                    <th scope='col';>
                      삭제버튼
                    </th>
                  </tr>
                </thead>
                <tbody class='wish_table_body'>".$tr.
                  "</tbody>
              </table>";
            }

        ?>
        <?php if (have_posts()) : ?>
            <div class="<?php echo esc_attr($style); ?> clearfix">
                <?php while (have_posts()) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php
        wp_link_pages(array(
            'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'masterstudy') . '</span>',
            'after' => '</div>',
            'link_before' => '<span>',
            'link_after' => '</span>',
            'pagelink' => '<span class="screen-reader-text">' . __('Page', 'masterstudy') . ' </span>%',
            'separator' => '<span class="screen-reader-text">, </span>',
        ));
        ?>

        <div class="clearfix">
            <?php
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
            ?>
        </div>

    </div>

<?php get_footer(); ?>