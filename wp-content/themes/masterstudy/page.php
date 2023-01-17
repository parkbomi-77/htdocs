<style>
    .filterbox{
        display: flex;
        justify-content: space-between;
        margin-bottom: 2px;
        margin-top: 50px;
    }
    .filter_choice {
        display: flex;
    }
    .filter_btn{
        margin-right:4px
    }
    .filter_choice button{
        height: 45px;
        padding: 0 15px;
        border: none;
        border-radius: 4px;
        background-color: #ebe8e8;
        color: #7a7a7a;
    }
    .filter_selector{
        display: flex;
    }
    .filter_selector div{
        display: inline-block;
        margin-right: 4px;
    }
    .counter {
        line-height: 70px;
        margin-right: 12px;
        height: 45px;
        font-size: initial;
        color: #666666;
    }
    .wish_table_head{
        background-color: rgb(177, 182, 180);
        line-height: 30px;
    }
    .wish_table_head tr th{
        color: #eeeeee;
    }
    .wish_table_body{
        text-align: center;
    }
    .wish_table_body tr{
        line-height: 28px;
        color: #4b4b4c;
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

<?php 
global $wpdb, $post;

$mallsql = "SELECT m.code, m.name FROM wp_shoppingmall as m where state = 1;";
$mall_results = $wpdb->get_results($wpdb->prepare($mallsql));
$mall_option = '';
for($i=0; $i<count($mall_results); $i++) {
    $mall_option = $mall_option."<option value=".$mall_results[$i]->code.">".$mall_results[$i]->name."</option>";
}

$categorysql = "SELECT * FROM wp_product_category";
$cate_results = $wpdb->get_results($wpdb->prepare($categorysql));
$cate_option = '';
for($i=0; $i<count($cate_results); $i++) {
    $cate_option = $cate_option."<option value=".$cate_results[$i]->ca_code.">".$cate_results[$i]->category."</option>";
}

?>
    <div class="container">

        <?php global $wpdb, $post;
            if($post->ID === 10656){
                $sql = "SELECT t.*, m.code, m.name, c.*
                FROM wp_wish_list as t
                join wp_product_list as l
                on t.item_id = l.ID
                join wp_shoppingmall as m
                on l.mall_code = m.code
                join wp_product_category as c
                on l.ca_code = c.ca_code
                where t.user_id = {$current_user->ID}
                and l.adv_state = 1";
        
                $results = $wpdb->get_results($wpdb->prepare($sql));
                $num = count($results);

                echo '<div class="filterbox">
                    <div class="filter_choice">
                        <div class="filter_btn">
                            <button onclick="allbtn()">전체목록</button>
                        </div>
                        <div class="filter_selector">
                            <div>
                                <select name="mallname" id="m_name">
                                    <option value="0">쇼핑몰</option>
                                    '.$mall_option.'
                                </select>
                            </div>
                            <div>
                                <select name="categoryname" id="c_name">
                                    <option value="">카테고리</option>
                                    '.$cate_option.'
                                </select>
                            </div>
                            <button type="button" onclick="filterbtn()"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
        
                    <div class="counter">
                        <p><span id="count">'.$num.'</span>건</p>
                    </div>
                </div>';

                function table_list($results) {
                    global $wpdb, $post;

                    $tr = "";
                    for($i = 0; $i<count($results); $i++){
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
                                <td class='wish_table_item'>".$results[$i]->category."</td>
                                <td class='wish_table_item'>{$results[$i]->product_name} <br> <span>( {$course_name} - {$lessons_name} )</span></td>
                                <td><button class='wish_table_trash' type='submit' formaction='../page-delete.php'><i class='fa-solid fa-trash'></i></button></td>
                                <td><button class='wish_table_link' type='submit' formaction='../page-shop.php'><i class='fa-solid fa-shop'></i></button></td>
                            </tr>
                        </form>
                        ";
                    }
                    return $tr;
                }

                echo "<table>
                        <colgroup>
                        <col style='width:60px'>
                        <col style='width:20%'>
                        <col style='width:10%'>
                        <col style='width:35%'>
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
                                Category
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
                        <tbody class='wish_table_body'>".table_list($results).
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

    let tbody = document.querySelector(".wish_table_body")
    let count = document.querySelector("#count")

    function allbtn() {
        tbody.innerHTML = `<?php echo table_list($results) ?>`
        // console.log(tbody.lastElementChild.querySelector("td").innerText);
        count.innerText = tbody.lastElementChild.querySelector("td").innerText;
    }
    function filterbtn() {
        let m_code = document.querySelector("#m_name").value;
        let c_code = document.querySelector("#c_name").value;

        jQuery.ajax({
            type: "POST",
            url: "/wp-content/themes/masterstudy/cartfilter.php",
            data: { 
                m_code,
                c_code,
                user : <?php echo $current_user->ID;?>
            },
            dataType: "text",
            success: function(data) {
                // console.log(data);
                tbody.innerHTML = data;
                count.innerText = tbody.lastElementChild.querySelector("td").innerText;
            }
        });
    }

</script>