<?php if (is_singular('events')): ?>
    <?php get_template_part('partials/event', 'form'); ?>
<?php endif; ?>

    <!-- Searchform -->
<?php get_template_part('partials/searchform'); ?>

    <script>
        var cf7_custom_image = '<?php echo esc_url(get_stylesheet_directory_uri())  ?>/assets/img/';
        var daysStr = '<?php esc_html_e('Days', 'masterstudy'); ?>';
        var hoursStr = '<?php esc_html_e('Hours', 'masterstudy'); ?>';
        var minutesStr = '<?php esc_html_e('Minutes', 'masterstudy'); ?>';
        var secondsStr = '<?php esc_html_e('Seconds', 'masterstudy'); ?>';

        // 몸무게 입력받는 input 태그
        let billingWeight = document.getElementById("billing_weight");
  
        // billingWeight.setAttribute("min", "2");
        // billingWeight.setAttribute("max", "30");
        billingWeight.value = 2;

        billingWeight.addEventListener("change", function() {
            let nowValue = billingWeight.value;
            if(nowValue < 2) {
                alert("2kg이상부터 사용이 가능합니다.")
                billingWeight.value = 2
            }else if(nowValue >30) {
                alert("30kg 이하로 사용이 가능합니다. ")
                billingWeight.value = 30;
            }
        })

    </script>

<?php
global $wp_customize;
if (is_stm() && !$wp_customize) {
    get_template_part('partials/frontend_customizer');
}
?>