<?php

/**
 * Received Property Flag Status Email Template
 *
 * @since 1.0
 * @package	Homevillas
 */
if (!class_exists('Wp_rem_property_flag_status_email_template')) {

    class Wp_rem_property_flag_status_email_template {

	public $email_template_type;
	public $email_default_template;
	public $email_template_variables;
	public $template_type;
	public $email_template_index;
	public $form_fields;
	public $is_email_sent;
	public static $is_email_sent1;
	public $template_group;

	public function __construct() {

	    $this->email_template_type = 'Property Flag Resolved';

	    $this->email_default_template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0"/></head><body style="margin: 0; padding: 0;"><div style="background-color: #eeeeef; padding: 50px 0;"><table style="max-width: 640px;" border="0" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="padding: 40px 30px 30px 30px;" align="center" bgcolor="#33333e"><h1 style="color: #fff;">Property Flag Resolved</h1></td></tr><tr><td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tr><td width="260" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="padding-bottom: 8px;">Hi, [FLAG_USER_NAME]</td></tr><tr><td style="padding-bottom: 8px;">Your flag on property ( <a href="[PROPERTY_LINK]">[PROPERTY_TITLE]</a> ). has been resolved</td></tr><tr><td style="padding-bottom: 8px;">Property Name: [PROPERTY_TITLE]</td></tr><tr><td style="padding-bottom: 8px;">Email: [FLAG_USER_EMAIL]</td></tr><tr><td style="padding-bottom: 8px;">Flag Status: [FLAG_REASON]</td></tr></tbody></table></td></tr></table></td></tr><tr><td style="background-color: #ffffff; padding: 30px 30px 30px 30px;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="font-family: Arial, sans-serif; font-size: 14px;">&reg; [SITE_NAME], 2017</td></tr></tbody></table></td></tr></tbody></table></div></body></html>';

	    $this->email_template_variables = array(
		array(
		    'tag' => 'FLAG_USER_NAME',
		    'display_text' => 'Claim User Name',
		    'value_callback' => array($this, 'get_claim_user_name'),
		),
		array(
		    'tag' => 'FLAG_USER_EMAIL',
		    'display_text' => 'Claim User Email',
		    'value_callback' => array($this, 'get_claim_user_email'),
		),
		array(
		    'tag' => 'FLAG_REASON',
		    'display_text' => 'Claim Reason',
		    'value_callback' => array($this, 'get_claim_user_reason'),
		),
		array(
		    'tag' => 'SITE_NAME',
		    'display_text' => 'Blog Name',
		    'value_callback' => array($this, 'get_blog_name'),
		),
		array(
		    'tag' => 'ADMIN_EMAIL',
		    'display_text' => 'ADMIN Email',
		    'value_callback' => array($this, 'get_admin_email'),
		),
		array(
		    'tag' => 'PROPERTY_USER_NAME',
		    'display_text' => 'Property User Name',
		    'value_callback' => array($this, 'get_property_user_name'),
		),
		array(
		    'tag' => 'PROPERTY_USER_EMAIL',
		    'display_text' => 'Property User Email',
		    'value_callback' => array($this, 'get_property_user_email'),
		),
		array(
		    'tag' => 'PROPERTY_TITLE',
		    'display_text' => 'Property Title',
		    'value_callback' => array($this, 'get_property_title'),
		),
		array(
		    'tag' => 'PROPERTY_LINK',
		    'display_text' => 'Property Link',
		    'value_callback' => array($this, 'get_property_link'),
		),
	    );
	    $this->template_group = 'Claims';
	    $this->email_template_index = 'flag-status-template';
	    add_action('init', array($this, 'add_email_template'), 13);
	    add_filter('wp_rem_email_template_settings', array($this, 'template_settings_callback'), 12, 1);
	    add_action('wp_rem_flag_status_email', array($this, 'wp_rem_flag_status_email_callback'), 10, 2);
	}

	public function wp_rem_flag_status_email_callback($user_data, $post_id) {
	    global $post_id;
	    $template = $this->get_template();
	    // checking email notification is enable/disable
	    if (isset($template['email_notification']) && $template['email_notification'] == 1) {
		// getting template fields
		$subject = (isset($template['subject']) && $template['subject'] != '' ) ? $template['subject'] : wp_rem_plugin_text_srt('wp_rem_received_property_flag_status');
		$from = (isset($template['from']) && $template['from'] != '') ? $template['from'] : esc_attr($this->get_claim_user_name()) . ' <' . $this->get_claim_user_email() . '>';
		$recipients = (isset($template['recipients']) && $template['recipients'] != '') ? $template['recipients'] : $this->get_claim_user_email();
		$email_type = (isset($template['email_type']) && $template['email_type'] != '') ? $template['email_type'] : 'html';

		$args = array(
		    'to' => $recipients,
		    'subject' => $subject,
		    'message' => $template['email_template'],
		    'email_type' => $email_type,
		    'class_obj' => $this,
		);
		do_action('wp_rem_send_mail', $args);
		Wp_rem_property_flag_status_email_template::$is_email_sent1 = $this->is_email_sent;
	    }
	}

	public function add_email_template() {
	    $email_templates = array();

	    $email_templates[$this->template_group] = array();
	    $email_templates[$this->template_group][$this->email_template_index] = array(
		'title' => $this->email_template_type,
		'template' => $this->email_default_template,
		'email_template_type' => $this->email_template_type,
		'is_recipients_enabled' => TRUE,
		'description' => wp_rem_plugin_text_srt('wp_rem_property_claim_email_resolved'),
		'jh_email_type' => 'html',
	    );
	    do_action('wp_rem_load_email_templates', $email_templates);
	}

	public function template_settings_callback($email_template_options) {

	    $email_template_options["types"][] = $this->email_template_type;

	    $email_template_options["templates"][$this->email_template_type] = $this->email_default_template;

	    $email_template_options["variables"][$this->email_template_type] = $this->email_template_variables;

	    return $email_template_options;
	}

	public function get_template() {
	    return wp_rem::get_template($this->email_template_index, $this->email_template_variables, $this->email_default_template);
	}

	function get_claim_user_name() {
	    global $post_id;
	    $wp_rem_property_claim_username = get_post_meta($post_id, 'wp_rem_claimer_name', true);
	    $user_name = $wp_rem_property_claim_username;
	    return $wp_rem_property_claim_username;
	}

	function get_claim_user_email() {
	    global $post_id;
	    $wp_rem_property_claim_email = get_post_meta($post_id, 'wp_rem_claimer_email', true);
	    return $wp_rem_property_claim_email;
	}

	function get_claim_user_reason() {
	    global $post_id, $_POST;
	    $wp_rem_property_new_claim_status = (isset($_POST['wp_rem_claim_action']) ? $_POST['wp_rem_claim_action'] : '');
	    return ucfirst($wp_rem_property_new_claim_status);
	}

	function get_blog_name() {
	    return get_option('blogname');
	}

	function get_admin_email() {
	    return get_option('admin_email');
	}

	function get_property_user_name() {
	    $wp_rem_property_id = isset($this->form_fields['wp_rem_property_id']) ? $this->form_fields['wp_rem_property_id'] : '';
	    $property_member = get_post_meta($wp_rem_property_id, 'wp_rem_property_member', true);
	    $property_member_name = esc_html(get_the_title($property_member));
	    return $property_member_name;
	}

	function get_property_user_email() {
	    $wp_rem_property_id = isset($this->form_fields['wp_rem_property_id']) ? $this->form_fields['wp_rem_property_id'] : '';
	    $property_member = get_post_meta($wp_rem_property_id, 'wp_rem_property_member', true);
	    $property_member_email = get_post_meta($property_member, 'wp_rem_email_address', true);
	    return $property_member_email;
	}

	function get_property_title() {
	    $property_id = isset($this->form_fields['wp_rem_property_id']) ? $this->form_fields['wp_rem_property_id'] : '';
	    return esc_html(get_the_title($property_id));
	}

	function get_property_link() {
	    $property_id = isset($this->form_fields['wp_rem_property_id']) ? $this->form_fields['wp_rem_property_id'] : '';
	    return esc_url(get_permalink($property_id));
	}

    }

    new Wp_rem_property_flag_status_email_template();
}
