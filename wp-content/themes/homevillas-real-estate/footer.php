<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Wp_rem_cs
 * @since Wp_rem_cs 1.0
 */
global $wp_rem_plugin_options;
$wp_rem_cs_var_options = WP_REM_CS_VAR_GLOBALS()->theme_options();
$wp_rem_cs_var_footer_widget = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_widget']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_widget'] : '';
$wp_rem_cs_var_footer_logo = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_logo']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_logo'] : '';
$wp_rem_cs_var_copy_write_section = isset($wp_rem_cs_var_options['wp_rem_cs_var_copy_write_section']) ? $wp_rem_cs_var_options['wp_rem_cs_var_copy_write_section'] : '';
$wp_rem_cs_var_copy_write_text_switch = isset($wp_rem_cs_var_options['wp_rem_cs_var_copy_write_text_switch']) ? $wp_rem_cs_var_options['wp_rem_cs_var_copy_write_text_switch'] : '';
$wp_rem_cs_var_copy_right = isset($wp_rem_cs_var_options['wp_rem_cs_var_copy_right']) ? $wp_rem_cs_var_options['wp_rem_cs_var_copy_right'] : '';
$wp_rem_cs_var_footer_contact_no = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_contact_no']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_contact_no'] : '';
$wp_rem_cs_var_footer_menu = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_menu']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_menu'] : '';
$wp_rem_cs_var_back_to_top = isset($wp_rem_cs_var_options['wp_rem_cs_var_back_to_top']) ? $wp_rem_cs_var_options['wp_rem_cs_var_back_to_top'] : '';
$wp_rem_cs_var_custom_footer_background = isset($wp_rem_cs_var_options['wp_rem_cs_var_custom_footer_background']) ? $wp_rem_cs_var_options['wp_rem_cs_var_custom_footer_background'] : '';
$wp_rem_cs_var_google_store = isset($wp_rem_cs_var_options['wp_rem_cs_var_google_store']) ? $wp_rem_cs_var_options['wp_rem_cs_var_google_store'] : '';
$wp_rem_cs_var_google_store_url = isset($wp_rem_cs_var_options['wp_rem_cs_var_google_store_url']) ? $wp_rem_cs_var_options['wp_rem_cs_var_google_store_url'] : '';
$wp_rem_cs_var_footer_app_store = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_app_store']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_app_store'] : '';
$wp_rem_cs_var_footer_app_store_url = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_app_store_url']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_app_store_url'] : '';
$wp_rem_cs_var_footer_style = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_style']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_style'] : '';
$wp_rem_cs_var_footerbg_color = isset($wp_rem_cs_var_options['wp_rem_cs_var_footerbg_color']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footerbg_color'] : '';
$wp_rem_cs_var_footer_menu = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_menu']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_menu'] : '';
$wp_rem_cs_var_footer_border_color = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_border_color']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_border_color'] : '';
$wp_rem_wpml_switch = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_lang_switch']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_lang_switch'] : '';
$wp_rem_cs_var_footer_social_icons = isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_social_icons']) ? $wp_rem_cs_var_options['wp_rem_cs_var_footer_social_icons'] : '';
$the_global_vars = array( 'wp_rem_cs_var_frame_options', 'wp_rem_cs_var_static_text' );
$wp_rem_cs_var_options_vars = WP_REM_CS_VAR_GLOBALS()->globalizing($the_global_vars);
extract($wp_rem_cs_var_options_vars);
$wp_rem_cs_var_maintenance_footer_switch = isset($wp_rem_cs_var_options['wp_rem_cs_var_maintenance_footer_switch']) ? $wp_rem_cs_var_options['wp_rem_cs_var_maintenance_footer_switch'] : '';
$wp_rem_cs_var_maintinance_mode_page = isset($wp_rem_cs_var_options['wp_rem_cs_var_maintinance_mode_page']) ? $wp_rem_cs_var_options['wp_rem_cs_var_maintinance_mode_page'] : '';
$wp_rem_currency_switch = isset($wp_rem_plugin_options['wp_rem_currency_switch']) ? $wp_rem_plugin_options['wp_rem_currency_switch'] : '';


$footer_class = '';
if ( $wp_rem_cs_var_footer_style == 'modern' ) {
    $footer_class = ' class="modern"';
}
if ( $wp_rem_cs_var_footer_style == 'classic' ) {
    $footer_class = ' class="classic"';
}
if ( $wp_rem_cs_var_footer_style == 'advance' ) {
    $footer_class = ' class="advance"';
}

$footer_top_color = '';
$footer_top_class = '';
if ( isset($wp_rem_cs_var_footer_border_color) && ! empty($wp_rem_cs_var_footer_border_color) ) {
    $footer_top_color = ' style="border-top:1px solid ' . $wp_rem_cs_var_footer_border_color . '"';
    $footer_top_class = ' has-border';
}
if ( 'on' <> $wp_rem_cs_var_maintenance_footer_switch && get_the_id() == $wp_rem_cs_var_maintinance_mode_page ) {
    echo '<div class="cs-nofooter"></div>';
} else {
    $footer_bg_image = '';
    if ( isset($wp_rem_cs_var_custom_footer_background) && $wp_rem_cs_var_custom_footer_background != '' ) {
        $footer_bg_image = 'style="background:url(' . esc_url($wp_rem_cs_var_custom_footer_background) . ') no-repeat left / cover !important;"';
    }
    ?>
    <footer id="footer"<?php echo wp_rem_cs_allow_special_char($footer_class); ?> <?php echo wp_rem_cs_allow_special_char($footer_bg_image); ?>>
        <?php
        echo wp_rem_clients();
        $footer_sidebar_list = array();
        $wp_rem_cs_footer_sidebar_width = '';
        if ( isset($wp_rem_cs_var_options) and isset($wp_rem_cs_var_options['wp_rem_cs_var_footer_sidebar']) ) {
            if ( is_array($wp_rem_cs_var_options['wp_rem_cs_var_footer_sidebar']) and count($wp_rem_cs_var_options['wp_rem_cs_var_footer_sidebar']) > 0 ) {
                $wp_rem_cs_footer_sidebar = array( 'wp_rem_cs_var_footer_sidebar' => $wp_rem_cs_var_options['wp_rem_cs_var_footer_sidebar'] );
            } else {
                $wp_rem_cs_footer_sidebar = array( 'wp_rem_cs_var_footer_sidebar' => array() );
            }
        } else {
            
            $wp_rem_cs_footer_sidebar = isset($wp_rem_cs_var_footer_sidebar) ? $wp_rem_cs_var_footer_sidebar : array();
        }  
        $cssidebar = false;
        $i = 0;
        if ( isset($wp_rem_cs_footer_sidebar['wp_rem_cs_var_footer_sidebar']) && is_array($wp_rem_cs_footer_sidebar['wp_rem_cs_var_footer_sidebar']) ) {
            foreach ( $wp_rem_cs_footer_sidebar['wp_rem_cs_var_footer_sidebar'] as $footer_sidebar_var => $footer_sidebar_val ) {
                $footer_sidebar_list[$footer_sidebar_var] = $footer_sidebar_val;
                $wp_rem_cs_footer_sidebar_width = substr($wp_rem_cs_var_options['wp_rem_cs_var_footer_width'][$i], 0, 2);
                $footer_sidebar_id = sanitize_title($footer_sidebar_val);
                if ( is_active_sidebar($footer_sidebar_id) ) {
                    $cssidebar = true;
                }
                $i ++;
            }
        }
        if ( 'on' === $wp_rem_cs_var_footer_widget && $cssidebar ) {
            ?>
            <div class="footer-widget">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <?php
                                if ( true === $cssidebar ) {
                                    $i = 0;
                                    if ( isset($wp_rem_cs_footer_sidebar['wp_rem_cs_var_footer_sidebar']) ) {
                                        if ( is_array($wp_rem_cs_footer_sidebar['wp_rem_cs_var_footer_sidebar']) ) {
                                            foreach ( $wp_rem_cs_footer_sidebar['wp_rem_cs_var_footer_sidebar'] as $footer_sidebar_var => $footer_sidebar_val ) {
                                                $footer_sidebar_list[$footer_sidebar_var] = $footer_sidebar_val;
                                                $wp_rem_cs_footer_sidebar_width = intval(substr($wp_rem_cs_var_options['wp_rem_cs_var_footer_width'][$i], 0, 2));
                                                $footer_sidebar_id = sanitize_title($footer_sidebar_val);
                                                $footer_side = '';
                                                if ( 2 === $wp_rem_cs_footer_sidebar_width ) {
                                                    $footer_side = 'col-lg-2 col-md-2 col-sm-6 col-xs-12';
                                                } elseif ( 3 === $wp_rem_cs_footer_sidebar_width ) {
                                                    $footer_side = 'col-lg-3 col-md-3 col-sm-6 col-xs-12';
                                                } elseif ( 4 === $wp_rem_cs_footer_sidebar_width ) {
                                                    $footer_side = 'col-lg-4 col-md-4 col-sm-6 col-xs-12';
                                                } elseif ( 6 === $wp_rem_cs_footer_sidebar_width ) {
                                                    $footer_side = 'col-lg-6 col-md-6 col-sm-12 col-xs-12';
                                                } elseif ( 8 === $wp_rem_cs_footer_sidebar_width ) {
                                                    $footer_side = 'col-lg-8 col-md-8 col-sm-12 col-xs-12';
                                                } elseif ( 9 === $wp_rem_cs_footer_sidebar_width ) {
                                                    $footer_side = 'col-lg-9 col-md-9 col-sm-12 col-xs-12';
                                                } elseif ( 10 === $wp_rem_cs_footer_sidebar_width ) {
                                                    $footer_side = 'col-lg-10 col-md-10 col-sm-12 col-xs-12';
                                                } else {
                                                    $footer_side = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
                                                }
                                                if ( is_active_sidebar(wp_rem_cs_get_sidebar_id($footer_sidebar_id)) ) {
                                                    echo '<div class="' . esc_attr($footer_side) . '">';
                                                    dynamic_sidebar($footer_sidebar_id);
                                                    echo '</div>';
                                                }
                                                $i ++;
                                            }
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /.cs-footer-widgets -->
            <?php // }    ?>
        <?php } ?>
        <?php if ( 'on' === $wp_rem_cs_var_back_to_top && $wp_rem_cs_var_footer_style != 'classic' && $wp_rem_cs_var_footer_style != 'advance' ) { ?>
            <div class="btn-top">
                <a class="back-to-top" href="javascript:void(0)"><i class="icon-keyboard_arrow_up bounce"></i></a>
            </div>
        <?php } ?>
        <?php if ( 'on' === $wp_rem_cs_var_copy_write_section ) { ?>
            <?php
            if ( $wp_rem_cs_var_footer_style == 'modern' ) {
                ?>
                <div class="copyright-sec<?php echo wp_rem_cs_allow_special_char($footer_top_class); ?>">
                    <div class="container"<?php echo wp_rem_cs_allow_special_char($footer_top_color); ?>>
                        <div class="row">
                            <?php if ( isset($wp_rem_cs_var_copy_write_text_switch) && $wp_rem_cs_var_copy_write_text_switch == 'on' ) { ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="copy-right">
                                        <p>
                                            <?php
                                            if ( ! empty($wp_rem_cs_var_copy_right) ) {
                                                $wp_rem_cs_allowed_tags = array(
                                                    'a' => array( 'href' => array(), 'class' => array() ),
                                                    'b' => array(),
                                                    'i' => array( 'class' => array() ),
                                                );
                                                echo wp_kses(wp_specialchars_decode($wp_rem_cs_var_copy_right), $wp_rem_cs_allowed_tags);
                                            } else {
                                                echo ('&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved. <a href="#">chimpstudio</a>');
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="footer-social-media">
                                    <?php
                                    if ( function_exists('wp_rem_cs_social_network') ) {
                                        echo wp_rem_cs_social_network(1, '', '', '', false);
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } elseif ( $wp_rem_cs_var_footer_style == 'classic' ) {
                ?>
                <div class="copyright-sec<?php echo wp_rem_cs_allow_special_char($footer_top_class); ?>">
                    <div class="container"<?php echo wp_rem_cs_allow_special_char($footer_top_color); ?>>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 left-side">
                                <div class="copy-right">
                                    <?php if ( isset($wp_rem_cs_var_footer_logo) && ! empty($wp_rem_cs_var_footer_logo) ) { ?>
                                        <div class="footer-logo">
                                            <a href="<?php echo esc_url(home_url('/')) ?>">
                                                <img src="<?php echo esc_url($wp_rem_cs_var_footer_logo) ?>" alt="<?php esc_html(bloginfo('name')) ?>">
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <p>
                                        <?php
                                        if ( ! empty($wp_rem_cs_var_copy_right) ) {
                                            $wp_rem_cs_allowed_tags = array(
                                                'a' => array( 'href' => array(), 'class' => array() ),
                                                'b' => array(),
                                                'i' => array( 'class' => array() ),
                                            );
                                            echo wp_kses(wp_specialchars_decode($wp_rem_cs_var_copy_right), $wp_rem_cs_allowed_tags);
                                        } else {
                                            echo ('&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved. <a href="#">chimpstudio</a>');
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 right-side">
                                <?php if ( has_nav_menu('footer_menu') ) { ?>
                                    <div class="footer-nav">
                                        <?php
                                        wp_nav_menu(array(
                                            'container' => ' ',
                                            'theme_location' => 'footer_menu',
                                            'depth' => 1,
                                        ));
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php if ( 'on' === $wp_rem_cs_var_back_to_top ) { ?>
                                    <div class="btn-top">
                                        <a class="back-to-top" href="javascript:void(0)"><i class="icon-keyboard_arrow_up bounce"></i></a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div> <!-- /.cs-copyright -->
                <?php
            } elseif ( $wp_rem_cs_var_footer_style == 'advance' ) {
                ?>
                <div class="copyright-sec<?php echo wp_rem_cs_allow_special_char($footer_top_class); ?>">
                    <div class="container"<?php echo wp_rem_cs_allow_special_char($footer_top_color); ?>>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 left-side">
                                <div class="copy-right">
                                    <?php if ( isset($wp_rem_cs_var_footer_logo) && ! empty($wp_rem_cs_var_footer_logo) ) { ?>
                                        <div class="footer-logo">
                                            <a href="<?php echo esc_url(home_url('/')) ?>">
                                                <img src="<?php echo esc_url($wp_rem_cs_var_footer_logo) ?>" alt="<?php esc_html(bloginfo('name')) ?>">
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <div class="footer-nav-area">
                                        <?php if ( has_nav_menu('footer_menu') && $wp_rem_cs_var_footer_menu == 'on' ) { ?>
                                            <div class="footer-nav">
                                                <?php
                                                wp_nav_menu(array(
                                                    'container' => ' ',
                                                    'theme_location' => 'footer_menu',
                                                    'depth' => 1,
                                                ));
                                                ?>
                                            </div>
                                        <?php } ?>
                                        <p><?php
                                            if ( ! empty($wp_rem_cs_var_copy_right) ) {
                                                $wp_rem_cs_allowed_tags = array(
                                                    'a' => array( 'href' => array(), 'class' => array() ),
                                                    'b' => array(),
                                                    'i' => array( 'class' => array() ),
                                                );
                                                echo wp_kses(wp_specialchars_decode($wp_rem_cs_var_copy_right), $wp_rem_cs_allowed_tags);
                                            } else {
                                                echo ('&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved. <a href="#">chimpstudio</a>');
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php if ( (isset($wp_rem_cs_var_footer_social_icons) && 'on' === $wp_rem_cs_var_footer_social_icons) || (isset($wp_rem_cs_var_back_to_top) && 'on' === $wp_rem_cs_var_back_to_top) ) { ?>     
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 right-side">
                                    <?php if ( isset($wp_rem_cs_var_footer_social_icons) && 'on' === $wp_rem_cs_var_footer_social_icons ) { ?>
                                        <div class="footer-social-media">
                                            <?php
                                            if ( function_exists('wp_rem_cs_social_network') ) {
                                                echo wp_rem_cs_social_network(1, '', '', '', false);
                                            }
                                            ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ( 'on' === $wp_rem_cs_var_back_to_top ) { ?>
                                        <div class="btn-top">
                                            <a class="back-to-top" href="javascript:void(0)"><i class="icon-keyboard_arrow_up bounce"></i></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?> 
                        </div>
                    </div>
                </div> <!-- /.cs-copyright -->
                <?php
            } else {
                ?>
                <div class="copyright-sec<?php echo wp_rem_cs_allow_special_char($footer_top_class); ?>">
                    <div class="container"<?php echo wp_rem_cs_allow_special_char($footer_top_color); ?>>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <?php if ( has_nav_menu('footer_menu') ) { ?>
                                            <div class="footer-nav">
                                                <?php
                                                wp_nav_menu(array(
                                                    'container' => ' ',
                                                    'theme_location' => 'footer_menu',
                                                    'depth' => 1,
                                                ));
                                                ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="footer-social-media">
                                            <?php
                                            if ( function_exists('wp_rem_cs_social_network') ) {
                                                echo wp_rem_cs_social_network(1, '', '', '', false);
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="copy-right">
                                    <p>
                                        <?php
                                        if ( ! empty($wp_rem_cs_var_copy_right) ) {
                                            $wp_rem_cs_allowed_tags = array(
                                                'a' => array( 'href' => array(), 'class' => array() ),
                                                'b' => array(),
                                                'i' => array( 'class' => array() ),
                                            );
                                            echo wp_kses(wp_specialchars_decode($wp_rem_cs_var_copy_right), $wp_rem_cs_allowed_tags);
                                        } else {
                                            echo ('&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved. <a href="#">chimpstudio</a>');
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <?php if ( $wp_rem_cs_var_footer_app_store != '' || $wp_rem_cs_var_google_store != '' ) { ?>
                                    <div class="widget widget-app">
                                        <a href="<?php echo esc_url($wp_rem_cs_var_footer_app_store_url); ?>"><img src="<?php echo esc_url($wp_rem_cs_var_footer_app_store); ?>" alt="ref"></a>
                                        <a href="<?php echo esc_url($wp_rem_cs_var_google_store_url); ?>"><img src="<?php echo esc_url($wp_rem_cs_var_google_store); ?>" alt="ref"></a>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="footer-currency">
                                    <?php 
				    if(isset($wp_rem_currency_switch)&&$wp_rem_currency_switch == 'on'){
				    do_action('wp_rem_before_app_in_footer'); // hook for currency and language in footer   
				    }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /.cs-copyright -->
                <?php
            }
            ?>    
        <?php } ?>
    </footer> <!-- /#footer -->
<?php } ?> 
</div> <!-- /.wrapper -->
<?php wp_footer(); ?>
<?php
$wp_rem_plugin_options = get_option('wp_rem_plugin_options');
$wp_rem_cookies_list_page = isset($wp_rem_plugin_options['wp_rem_cookies_list_page']) ? $wp_rem_plugin_options['wp_rem_cookies_list_page'] : '';
$wp_rem_term_policy_switch = isset($wp_rem_plugin_options['wp_rem_term_policy_switch']) ? $wp_rem_plugin_options['wp_rem_term_policy_switch'] : '';
if (isset($wp_rem_term_policy_switch) && $wp_rem_term_policy_switch == 'on') {
    ?>
    <div class="alert alert-dismissible text-center cookiealert" role="alert">
        <div class="cookiealert-container">
            <?php echo esc_html__('We use cookies to provide you with the best possible user experience. By continuing to use our site, you agree to their use.', 'homevillas-real-estate'); ?> <a style="color:#fff;" href="<?php echo esc_url_raw(get_page_link($wp_rem_cookies_list_page)); ?>" target="_blank"><?php echo esc_html__('Learn more', 'homevillas-real-estate'); ?></a>

            <button type="button" class="btn btn-primary btn-sm acceptcookies" aria-label="Close">
                <?php echo esc_html__('I agree', 'homevillas-real-estate'); ?>
            </button>
<button type="button" class="close" data-dismiss="alert">
    <span aria-hidden="true"><i class="icon-cross2"></i></span><span class="sr-only">Close</span>
</button>
        </div>
    </div>
<?php } ?>

</body>
</html>