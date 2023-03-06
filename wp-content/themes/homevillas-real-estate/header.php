<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wp_rem_cs
 */
do_action('wp_rem_cs_before_header');
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <?php
        global $wp_rem_cs_var_options, $wp_rem_plugin_options;
        $wp_rem_cs_var_layout = isset($wp_rem_cs_var_options['wp_rem_cs_var_layout']) ? $wp_rem_cs_var_options['wp_rem_cs_var_layout'] : '';
        ?>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="format-detection" content="telephone=no"> <!-- for ie,safari,mobile browsers disable phone number in html tag content not in hyperlink-->
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<meta charset="UTF-8">
        <?php
        $bg_color = get_post_meta(get_the_id(), 'wp_rem_cs_var_page_bg_color', true);
        $style_bgcol = '';
        if (isset($bg_color) && $bg_color != '' && !is_array($bg_color)) {
            $style_bgcol = 'style="background-color:' . $bg_color . '"';
        }
        wp_head();
        ?>
    </head>
    <body <?php body_class(); ?>>
        <?php if (function_exists('wp_body_open')){ wp_body_open(); } ?>
        <div class="wrapper wrapper-<?php echo esc_html($wp_rem_cs_var_layout); ?>" <?php echo wp_rem_cs_allow_special_char($style_bgcol); ?>>
            <!-- Side Menu Start -->
            <div id="overlay"></div>
            <?php
			
            $wp_rem_cs_var_maintenance_page = isset($wp_rem_cs_var_options['wp_rem_cs_var_maintinance_mode_page']) ? $wp_rem_cs_var_options['wp_rem_cs_var_maintinance_mode_page'] : '';
            $wp_rem_cs_var_maintenance_check = isset($wp_rem_cs_var_options['wp_rem_cs_var_maintenance_switch']) ? $wp_rem_cs_var_options['wp_rem_cs_var_maintenance_switch'] : '';
            $wp_rem_cs_var_maintenance_header_switch = isset($wp_rem_cs_var_options['wp_rem_cs_var_maintenance_header_switch']) ? $wp_rem_cs_var_options['wp_rem_cs_var_maintenance_header_switch'] : 'off';
            if (get_the_ID() == $wp_rem_cs_var_maintenance_page && $wp_rem_cs_var_maintenance_check == 'on' && $wp_rem_cs_var_maintenance_header_switch <> 'on') {
                echo '<header id="header"></header>';
            } elseif ('' != get_the_ID() && get_the_ID() == $wp_rem_cs_var_maintenance_page && $wp_rem_cs_var_maintenance_check <> 'on' && $wp_rem_cs_var_maintenance_header_switch <> 'on') {
                echo '<header id="header"></header>';
            } else {
                wp_rem_cs_main_header();
                if (function_exists('wp_rem_cs_var_subheader_style')) {
                    wp_rem_cs_var_subheader_style();
                }
            }
	