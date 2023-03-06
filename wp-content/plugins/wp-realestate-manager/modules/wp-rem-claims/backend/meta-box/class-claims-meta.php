<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Claims_Meta_Boxes' ) ) {

	class Claims_Meta_Boxes {

		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'claims_meta_box_callback' ) );
		}

		public function claims_meta_box_callback() {

			add_meta_box( 'claims_meta_box', wp_rem_plugin_text_srt( 'wp_rem_claims_name' ), array( $this, 'claims_meta_box_callback_func' ), 'wp_rem_claims', 'normal', 'high' );
		}

		public function claims_meta_box_callback_func() {
			global $post, $wp_rem_html_fields, $wp_rem_form_fields;

			$post_id = get_the_id();
			
			$wp_rem_claimer_on     = get_post_meta( $post_id, 'wp_rem_claimer_on', true );
			$wp_rem_claimer_name   = get_post_meta( $post_id, 'wp_rem_claimer_name', true );
			$wp_rem_claimer_email  = get_post_meta( $post_id, 'wp_rem_claimer_email', true );
			$wp_rem_claimer_reason = get_post_meta( $post_id, 'wp_rem_claimer_reason', true );
			$wp_rem_claim_type     = get_post_meta( $post_id, 'wp_rem_claim_type', true );
			$wp_rem_claim_action   = get_post_meta( $post_id, 'wp_rem_claim_action', true );
			
			if( $wp_rem_claim_type == 'claim' ){
				$claim_id_label = wp_rem_plugin_text_srt( 'wp_rem_claim_id' );
			}else{
				$claim_id_label = wp_rem_plugin_text_srt( 'wp_rem_flag_id' );
			}
			
			$wp_rem_opt_array = array(
				'name' => esc_html($claim_id_label),
				'desc' => '',
				'hint_text' => '',
				'echo' => true,
				'field_params' => array(
					'std' => $post_id,
					'id' => 'wp_rem_claimecase_id',
					'cust_name' => 'wp_rem_claimecase_id',
					'return' => true,
				),
			);
			$wp_rem_html_fields->wp_rem_text_field( $wp_rem_opt_array );
			
			$wp_rem_opt_array = array(
				'name' => wp_rem_plugin_text_srt( 'wp_rem_claim_on' ),
				'desc' => '',
				'hint_text' => wp_rem_plugin_text_srt( 'wp_rem_claim_on_desc' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_rem_claimer_on,
					'id' => 'wp_rem_claim_on',
					'cust_name' => 'wp_rem_claimer_on',
					'return' => true,
				),
			);
			$wp_rem_html_fields->wp_rem_text_field( $wp_rem_opt_array );
			
			$wp_rem_opt_array = array(
				'name' => wp_rem_plugin_text_srt( 'wp_rem_claim_user_name' ),
				'desc' => '',
				'hint_text' => wp_rem_plugin_text_srt( 'wp_rem_claim_user_name_desc' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_rem_claimer_name,
					'id' => 'wp_rem_claimer_name',
					'cust_name' => 'wp_rem_claimer_name',
					'return' => true,
				),
			);

			$wp_rem_html_fields->wp_rem_text_field( $wp_rem_opt_array );
			
			$wp_rem_opt_array = array(
				'name' => wp_rem_plugin_text_srt( 'wp_rem_claim_user_email' ),
				'desc' => '',
				'hint_text' => wp_rem_plugin_text_srt( 'wp_rem_claim_user_email_desc' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_rem_claimer_email,
					'id' => 'wp_rem_claimer_email',
					'cust_name' => 'wp_rem_claimer_email',
					'return' => true,
				),
			);

			$wp_rem_html_fields->wp_rem_text_field( $wp_rem_opt_array );
			
			$wp_rem_opt_array = array(
				'name' => wp_rem_plugin_text_srt( 'wp_rem_claim_reason' ),
				'desc' => '',
				'hint_text' => wp_rem_plugin_text_srt( 'wp_rem_claim_reason_hint' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_rem_claimer_reason,
					'id' => 'wp_rem_claimer_reason',
					'cust_name' => 'wp_rem_claimer_reason',
					'return' => true,
				),
			);

			$wp_rem_html_fields->wp_rem_textarea_field( $wp_rem_opt_array );
			
			$type = array( 'flag' => wp_rem_plugin_text_srt( 'wp_rem_claim_flag_property' ), 'claim' => wp_rem_plugin_text_srt( 'wp_rem_claim_property' ) );
			$wp_rem_opt_array = array(
				'name' => wp_rem_plugin_text_srt( 'wp_rem_claim_Type' ),
				'desc' => '',
				'hint_text' => wp_rem_plugin_text_srt( 'wp_rem_claim_Type_hint' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_rem_claim_type,
					'id' => 'wp_rem_claim_type',
					'cust_name' => 'wp_rem_claim_type',
					'return' => true,
					'options' => $type,
				),
			);
			$wp_rem_html_fields->wp_rem_select_field( $wp_rem_opt_array );
			
			$actions = array( 'pending' => wp_rem_plugin_text_srt( 'wp_rem_claim_status_pending' ), 'resolved' => wp_rem_plugin_text_srt( 'wp_rem_claim_status_resolved' ), );
			$wp_rem_opt_array = array(
				'name' => wp_rem_plugin_text_srt( 'wp_rem_claim_action' ),
				'desc' => '',
				'hint_text' => wp_rem_plugin_text_srt( 'wp_rem_claim_action_hint' ),
				'echo' => true,
				'field_params' => array(
					'std' => $wp_rem_claim_action,
					'id' => 'wp_rem_claim_action',
					'cust_name' => 'wp_rem_claim_action',
					'return' => true,
					'options' => $actions,
				),
			);
			$wp_rem_html_fields->wp_rem_select_field( $wp_rem_opt_array );
		}

	}

	global $Claims_Meta_Boxes;
	$Claims_Meta_Boxes = new Claims_Meta_Boxes();
}