<?php

/**
 * Claims / Flags Property Module
 */
// Direct access not allowed.
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Wp_Rem_Claims')) {

    class Wp_Rem_Claims {

	/**
	 * Start construct Functions
	 */
	public function __construct() {
	    // Define constants
	    define('WP_REM_CLAIMS_PLUGIN_URL', WP_PLUGIN_URL . '/wp-realestate-manager/modules/wp-rem-claims');
	    define('WP_REM_CLAIMS_CORE_DIR', WP_PLUGIN_DIR . '/wp-realestate-manager/modules/wp-rem-claims');

	    add_filter('wp_rem_plugin_text_strings', array($this, 'wp_rem_claims_strings_callback'), 1);

	    add_filter('manage_wp_rem_claims_posts_columns', array($this, 'wp_rem_claims_cpt_columns'));
	    add_action('manage_wp_rem_claims_posts_custom_column', array($this, 'custom_wp_rem_claims_column'), 10, 2);
	    add_filter('manage_edit-wp_rem_claims_sortable_columns', array($this, 'wp_rem_sortable_wp_rem_claims_column'));
	    add_action('pre_get_posts', array($this, 'wp_rem_type_orderby'));

	    add_filter('get_sample_permalink_html', array($this, 'wp_rem_hide_permalinks'));


	    if (is_admin()) {
		add_filter('post_row_actions', array($this, 'remove_quick_edit'), 10, 2);
		add_action('restrict_manage_posts', array($this, 'wp_rem_admin_claims_flags_type_filters'), 11);
		add_filter('parse_query', array(&$this, 'wp_rem_admin_claims_filter'), 11, 1);
	    }

	    $this->includes();

	    // Initialize Addon
	    add_action('init', array($this, 'init'));
	}

	public function wp_rem_claims_strings_callback($wp_rem_static_text = array()) {
	    global $wp_rem_static_text;
	    $wp_rem_static_text['wp_rem_claims_name'] = esc_html__('Claims/Flags Options ', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claims_sure_message'] = esc_html__('Are you sure to do this?', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claims_desc'] = esc_html__('Post type for claim and flag property', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_id'] = esc_html__('Claim ID', 'wp-rem');
	    $wp_rem_static_text['wp_rem_flag_id'] = esc_html__('Flag ID', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_reference_id'] = esc_html__('reference id of claim', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_on'] = esc_html__('Claim On', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_on_desc'] = esc_html__('Claim from which property', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_user_name'] = esc_html__('User Name', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_user_name_desc'] = esc_html__('Claimer Name', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_user_email'] = esc_html__('User Email', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_user_email_desc'] = esc_html__('Claimer Email', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_reason'] = esc_html__('Reason', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_reason_hint'] = esc_html__('Claim Reason', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_Type'] = esc_html__('Type', 'wp-rem');
	    $wp_rem_static_text['wp_rem_select_claim_type'] = esc_html__('Select Type', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_Type_hint'] = esc_html__('Claim Type', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_action'] = esc_html__('Actions', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_action_hint'] = esc_html__('Claim Actions', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_properties'] = esc_html__('Claim Properties', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_flag_properties'] = esc_html__('Flag Properties', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_property'] = esc_html__('Claim Property', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_flag_property'] = esc_html__('Flag Property', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim'] = esc_html__('Claim', 'wp-rem');
	    $wp_rem_static_text['wp_rem_flag'] = esc_html__('Flag', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_status_pending'] = esc_html__('Pending', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_status_resolved'] = esc_html__('Resolved', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_status_delete'] = esc_html__('Delete', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_user_name_error'] = esc_html__('Name is Required', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_email_error'] = esc_html__('Email is required', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_reason_error'] = esc_html__('Reason is required', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_list_success'] = esc_html__('Posted successfully', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_flag_send'] = esc_html__('Send', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_type'] = esc_html__('Type', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_are_you_sure'] = esc_html__('Are you sure to do this?', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_flags'] = esc_html__('Claims / Flags', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_flags_desc'] = esc_html__('Post type for claim and flag property', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_name'] = esc_html__('Name', 'wp-rem');
	    $wp_rem_static_text['wp_rem_claim_email'] = esc_html__('Email Address', 'wp-rem');

	    return $wp_rem_static_text;
	}

	public function wp_rem_claims_cpt_columns($columns) {

	    $new_columns = array(
		'type' => wp_rem_plugin_text_srt('wp_rem_claim_type'),
	    );
	    return array_merge($columns, $new_columns);
	}

	function wp_rem_type_orderby($query) {
	    if (!is_admin())
		return;


	    $orderby = $query->get('orderby');


	    if ('type' == $orderby) {

		$query->set('meta_key', 'wp_rem_claim_type');
		$query->set('orderby', 'meta_value');
	    }
	}

	public function custom_wp_rem_claims_column($column) {
	    switch ($column) {

		case 'type' :
		    $post_id = get_the_id();
		    $post_type = get_post_meta($post_id, 'wp_rem_claim_type', true);
		    echo $post_type;

		    break;
	    }
	}

	public function wp_rem_sortable_wp_rem_claims_column($columns) {
	    $columns['type'] = 'type';
	    return $columns;
	}

	/**
	 * Initialize application, load text domain, enqueue scripts and bind hooks
	 */
	public function init() {
	    // Enqueue JS

	    wp_enqueue_script('wp_rem-claims-script', esc_url(WP_REM_CLAIMS_PLUGIN_URL . '/assets/js/functions.js'), '', '', true);
	    wp_localize_script('wp_rem-claims-script', 'wp_rem_claims', array(
		'admin_url' => esc_url(admin_url('admin-ajax.php')),
		'confirm_msg' => wp_rem_plugin_text_srt('wp_rem_claim_are_you_sure')
	    ));
	    $args = array(
		'label' => wp_rem_plugin_text_srt('wp_rem_claim_flags'),
		'description' => wp_rem_plugin_text_srt('wp_rem_claim_flags_desc'),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => 'edit.php?post_type=properties',
		'menu_position' => true,
		'supports' => array('title'),
		'exclude_from_search' => true,
	    );
	    register_post_type('wp_rem_claims', $args);
	}

	public function includes() {
	    require_once 'frontend/class-claim-property.php';
	    require_once 'frontend/class-flag-property.php';
	    if (is_admin()) {
		require_once 'backend/meta-box/class-claims-meta.php';
	    }
	}

	public function wp_rem_hide_permalinks($out) {
	    global $post;
	    if ($post->post_type == 'wp_rem_claims')
		$out = '';
	    return $out;
	}

	public function remove_quick_edit($actions) {


	    global $post;

	    if ($post->post_type == 'wp_rem_claims') {
		unset($actions['inline hide-if-no-js']);
		unset($actions['view']);
	    }
	    return $actions;
	}

	/*
	 * add on strings
	 */

	public function wp_rem_admin_claims_flags_type_filters() {
	    global $wp_rem_form_fields, $post_type;

	    //only add filter to post type you want
	    if ($post_type == 'wp_rem_claims') {

		$selected_type = isset($_GET['type']) ? $_GET['type'] : '';
		$type = array('' => wp_rem_plugin_text_srt('wp_rem_select_claim_type'), 'flag' => wp_rem_plugin_text_srt('wp_rem_claim_flag_properties'), 'claim' => wp_rem_plugin_text_srt('wp_rem_claim_properties'));
		$wp_rem_opt_array = array(
		    'std' => $selected_type,
		    'id' => 'type',
		    'cust_id' => 'type',
		    'cust_name' => 'type',
		    'extra_atr' => '',
		    'classes' => '',
		    'options' => $type,
		    'return' => false,
		);
		$wp_rem_form_fields->wp_rem_form_select_render($wp_rem_opt_array);
	    }
	}

	function wp_rem_admin_claims_filter($query) {
	    global $pagenow;
	    $custom_filter_arr = array();
	    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp_rem_claims' && isset($_GET['type']) && $_GET['type'] != '') {

		$custom_filter_arr[] = array(
		    'key' => 'wp_rem_claim_type',
		    'value' => $_GET['type'],
		    'compare' => '=',
		);
	    }
	    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'wp_rem_claims' && !empty($custom_filter_arr)) {
		$query->set('meta_query', $custom_filter_arr);
	    }
	}

    }

    global $wp_rem_claims;
    $wp_rem_claims = new Wp_Rem_Claims();
}