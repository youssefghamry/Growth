<?php

/**
 * The template for displaying single property
 *
 */
get_header();
global $post, $wp_rem_plugin_options, $wp_rem_theme_options, $wp_rem_post_property_types, $wp_rem_plugin_options;
$post_id = $post->ID;

$wp_rem_single_view = wp_rem_property_detail_page_view($post_id);
if( $wp_rem_single_view == '' ){
	$wp_rem_single_view = 'detail_view1';
}

wp_enqueue_script('wp-rem-property-detail-scripts');
wp_enqueue_script('html2canvas');
wp_enqueue_script('fitvids');
wp_enqueue_script('property-detail-print');

$iconmoon_css = '';
$icons_groups = get_option('cs_icons_groups');
if ( ! empty($icons_groups) ) {
	foreach ( $icons_groups as $icon_key => $icon_obj ) {
		if ( isset($icon_obj['status']) && $icon_obj['status'] == 'on' ) {
			$iconmoon_css = '<link href="'. $icon_obj['url'] .'/style.css" rel="stylesheet" type="text/css">'."\n";
		}
	}
}
$wp_rem_property_strings = array(
	'property_id' => $post_id,
	'bootstrap_css' => plugins_url().'/wp-realestate-manager/assets/frontend/css/bootstrap.css',
	'print_css' => plugins_url().'/wp-realestate-manager/assets/frontend/css/print.css',
	'iconmoon_css' => $iconmoon_css,
	'ajax_url' => admin_url('admin-ajax.php'),
);
wp_localize_script('property-detail-print', 'wp_rem_print_str', $wp_rem_property_strings);

do_action('wp_rem_notes_frontend_modal_popup');
do_action('wp_rem_property_compare_sidebar');
if ( $wp_rem_single_view == 'detail_view1' ) {
	wp_rem_get_template_part('property', 'view1', 'single-property');
} elseif ( $wp_rem_single_view == 'detail_view2' ) {
	wp_rem_get_template_part('property', 'view2', 'single-property');
} elseif ( $wp_rem_single_view == 'detail_view3' || $wp_rem_single_view == 'detail_view4' ) {
	wp_rem_get_template_part('property', 'view3', 'single-property');
} elseif ( $wp_rem_single_view == 'detail_view5' ) {
	wp_rem_get_template_part('property', 'view5', 'single-property');
}

get_footer();
