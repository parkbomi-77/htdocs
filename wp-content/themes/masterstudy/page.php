<style>
    .wish_table_head{
        background-color: rgb(177, 182, 180);
    }
    .wish_table_body{
        text-align: center;
    }
    .wish_table_trash{
        border: 0;
        background-color: white;
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
                    "
                    <form name='wishlist' method='POST'>
                        <tr>
                            <input type='hidden' name='user_id' value='".$results[$i]->user_id ."'>
                            <input type='hidden' name='item_id' value='".$results[$i]->item_id ."'>
                            <td>".($i+1)."</td>
                            <td class='wish_table_trash_item'>".$results[$i]->product_name."</td>
                            <td><button class='wish_table_trash' type='submit' onclick='trash(this)' formaction='../page-delete.php'><i class='fa-solid fa-trash'></i></button></td>
                            <td><button class='wish_table_trash' type='submit' formaction='../page-shop.php'><i class='fa-solid fa-shop'></i></button></td>
                        </tr>
                    </form>
                    ";
                }

                echo "<table>
                        <colgroup>
                        <col style='width:50px'>
                        <col style='width:65%'>
                        <col style='width:80px'>
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
                            <th scope='col';>
                            삭제
                            </th>
                            <th scope='col';>
                            이동
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


<script>
    function trash (e) {
        // 테이블 바디에서 해당 행 삭제
        let playboxList = document.querySelector('.wish_table_body');
        playboxList.removeChild(e.parentNode);
    }


</script>







