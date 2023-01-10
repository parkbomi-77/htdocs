<style>
    .wish_table_head{
        background-color: rgb(177, 182, 180);
        line-height: 26px;
    }
    .wish_table_body{
        text-align: center;
    }
    .wish_table_body tr{
        line-height: 28px;
    }
    .wish_table_item span{
        color: #838282;
        font-size: medium;
    }
    .wish_table_trash, .wish_table_link{
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
                $sql = "SELECT t.*, m.name
                        FROM wp_wish_list as t
                        join wp_product_list as l
                        on t.item_id = l.ID
                        join wp_shoppingmall as m
                        on l.mall_code = m.code
                        where t.user_id = {$current_user->ID}
                        and l.adv_state = 1";
                $results = $wpdb->get_results($wpdb->prepare($sql));
                $num = count($results);



                $tr = "";
                for($i = 0; $i<$num; $i++){
                    $course_name = $wpdb->get_var($wpdb->prepare("SELECT post_title FROM wp_posts where ID =".$results[$i]->course_id));
                    $lessons_name = $wpdb->get_var($wpdb->prepare("SELECT post_title FROM wp_posts where ID =".$results[$i]->lessons_id));

                    $tr = $tr.
                    "
                    <form name='wishlist' method='POST' onsubmit='return true'>
                        <tr>
                            <input type='hidden' name='user_id' value='".$results[$i]->user_id ."'>
                            <input type='hidden' name='item_id' value='".$results[$i]->item_id ."'>
                            <td>".($i+1)."</td>
                            <td class='wish_table_item'>".$results[$i]->name."</td>
                            <td class='wish_table_item'>{$results[$i]->product_name} <br> <span>{$course_name} - {$lessons_name}</span></td>
                            <td><button class='wish_table_trash' type='submit' formaction='../page-delete.php'><i class='fa-solid fa-trash'></i></button></td>
                            <td><button class='wish_table_link' type='submit' formaction='../page-shop.php'><i class='fa-solid fa-shop'></i></button></td>
                        </tr>
                    </form>
                    ";
                }

                echo "<table>
                        <colgroup>
                        <col style='width:60px'>
                        <col style='width:20%'>
                        <col style='width:45%'>
                        <col style='width:60px'>
                        <col style='width:60px'>
                        </colgroup>
                        <thead class='wish_table_head'>
                        <tr>
                            <th scope='col'>
                                no.
                            </th>
                            <th scope='col'>
                                Shpping mall name
                            </th>
                            <th scope='col'>
                                Product name
                            </th>
                            <th scope='col';>
                                delete
                            </th>
                            <th scope='col';>
                                link
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
    let trashbutton = document.getElementsByClassName('wish_table_trash');
    for(let i=0; i<trashbutton.length; i++){
        trashbutton[i].addEventListener('click',function(event){
            if(confirm('삭제하시겠습니까?')){
                return;
            }
            event.preventDefault();
        })
    }

</script>







