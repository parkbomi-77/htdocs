<?php

new STM_LMS_User_Menu;

class STM_LMS_User_Menu
{
    public function __construct()
    {
        add_action('wp_footer', array($this, 'float_menu'));
        add_action('stm_lms_user_float_menu_before', array($this,'stm_lms_user_float_menu_styles'));
	
    }

    function float_menu()
    {
        STM_LMS_Templates::show_lms_template('account/float_menu/float_menu');
    }

    static function float_menu_enabled()
    {

        $float_menu = STM_LMS_Options::get_option('float_menu', false);
        $float_menu_guest = STM_LMS_Options::get_option('float_menu_guest', true);

        if (!is_user_logged_in() && $float_menu) return $float_menu_guest;

        return $float_menu;
    }
    
    function stm_lms_user_float_menu_styles () {
		$float_background_color = esc_attr(STM_LMS_Options::get_option( 'float_background_color', 'rgba(255, 255, 255, 1)' ));
		$float_text_color       = esc_attr(STM_LMS_Options::get_option( 'float_text_color', 'rgba(39, 48, 68, 1)' ));
		$is_background_color_default = ! empty( STM_LMS_Options::get_option( 'float_background_color' ) );
		$is_text_color_default = ! empty( STM_LMS_Options::get_option( 'float_text_color' ) );
		
		if ( $is_background_color_default ) { ?>
			<style>
				@media(max-width:768px) {
                    body .stm_lms_user_float_menu:not(.__collapsed) .stm_lms_user_float_menu__toggle {
                        background-color: <?php echo $float_background_color?> !important;
					}
				}
                .stm_lms_button .stm_lms_user_float_menu__scrolled .stm_lms_user_float_menu__scrolled_label {
                    background-color: <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,1)?>;
				}
				
                .stm_lms_button .stm_lms_user_float_menu .float_menu_item_active {
                    background-color: <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,.2)?>;
                }

                .stm_lms_button .stm_lms_user_float_menu .float_menu_item:hover:before, .stm_lms_user_float_menu .float_menu_item_active:before {
                    background-color: <?php echo $float_text_color?> !important;
                }

                .stm_lms_user_float_menu .stm-lms-logout-button {
                    background-color: <?php echo $float_background_color?> !important;
                }

                .stm_lms_user_float_menu .stm_lms_user_float_menu__empty {
                    background-color: <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,.2)?>;
                }
                .stm_lms_user_float_menu .stm_lms_user_float_menu__tabs a.active {
					color: <?php echo $float_text_color ?> !important;
                    background-color: <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,.2)?>;
				}
				.stm_lms_user_float_menu .stm_lms_user_float_menu__tabs a {
                    background-color: <?php echo $float_background_color?>;
					color: <?php echo $float_text_color ?> !important;
				}
				.stm_lms_user_float_menu .stm_lms_user_float_menu__tabs  {
                    border-bottom: 3px solid <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,.2) ?> !important;
				}

                body .stm_lms_user_float_menu {
                    background-color: <?php echo $float_background_color; ?>;
                }

                body .stm_lms_user_float_menu .float_menu_item:hover {
                    background-color: <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,.2)?>;
                }

                body .stm_lms_user_float_menu__user {
                    border-top: rgb(<?php echo $float_background_color; ?>, .1);
                    border-bottom: rgba(<?php echo $float_background_color; ?>, '0.1');
                }
			</style>
			<?php
		}
		
		if ( $is_text_color_default ) { ?>
			<style>
                .stm_lms_button .stm_lms_user_float_menu__scrolled .stm_lms_user_float_menu__scrolled_label i {
                    color: <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_background_color,.5)?> !important;
				}
                .stm_lms_user_float_menu .float_menu_item_active .stm_lms_user_float_menu__user_settings {
                    color: <?php echo $float_text_color?> !important;
				}
                .stm_lms_user_float_menu .stm-lms-logout-button {
                    color: <?php echo $float_text_color?> !important;
                    border-top: 1px solid <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,.2)?> !important;
                }

                .stm_lms_user_float_menu .stm_lms_user_float_menu__user_settings {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_user_float_menu .stm_lms_user_float_menu__empty {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_user_float_menu .stm_lms_user_float_menu__login_head h4 {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_user_float_menu .stm_lms_user_float_menu__login #stm-lms-login .stm_lms_login_wrapper .stm_lms_login_wrapper__actions .lostpassword {
                    color: <?php echo $float_text_color?> !important;
                }

                .stm_lms_user_float_menu__login .stm_lms_user_float_menu__login_head a {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_user_float_menu__login .stm_lms_user_float_menu__login_head a:hover {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_button .stm_lms_user_float_menu .float_menu_item__divider {
                    border-top: 1px solid <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,.15)?> !important;
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_button .stm-lms-logout-button:hover i {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_user_float_menu .stm_lms_user_float_menu__user {
                    border-top: 1px solid <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,.15)?> !important;
                    border-bottom: 1px solid <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,.15)?> !important;
                }

                .stm_lms_user_float_menu__toggle svg:hover path {
                    fill: <?php echo STM_LMS_Helpers::stm_rgba_change_alpha_dynamically($float_text_color,1)?> !important;
                }

                .stm_lms_user_float_menu__toggle svg path {
                    fill: <?php echo $float_text_color?> !important;
                }

                .stm_lms_button .stm_lms_user_float_menu .float_menu_item:hover .stm_lms_user_float_menu__user_settings, .stm_lms_user_float_menu .float_menu_item_active .stm_lms_user_float_menu__user_settings {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_button .stm_lms_user_float_menu .float_menu_item:hover .float_menu_item__icon, .stm_lms_user_float_menu .float_menu_item_active .float_menu_item__icon {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_user_float_menu .stm_lms_user_float_menu__user_info span, .stm_lms_user_float_menu .stm_lms_user_float_menu__user_info h3 {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_button .stm_lms_user_float_menu .float_menu_item__inline .float_menu_item__icon {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_button .stm_lms_user_float_menu.__collapsed .stm_lms_user_float_menu__toggle:hover {
                    color: <?php echo $float_text_color?>;
                }

                .stm_lms_button .stm_lms_user_float_menu.__collapsed .stm_lms_user_float_menu__toggle:hover svg path {
                    fill: <?php echo $float_text_color?>;
                }


                body .stm_lms_user_float_menu .float_menu_item:hover .float_menu_item__title {
                    color: <?php echo $float_text_color; ?>;
                }

                .stm_lms_user_float_menu .float_menu_item__inline .float_menu_item__title {
                    color: <?php echo $float_text_color; ?>;
                }
			</style>
		<?php }
	}
	
	
}