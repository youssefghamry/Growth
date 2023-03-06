<?php

/**
 * Translate Plugin Options
 */
if ( ! class_exists('wp_rem_translate_options') ) {

    class wp_rem_translate_options {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_filter('wp_rem_translate_options_admin', array( $this, 'wp_rem_translate_options_admin_callback' ), 0, 1);
            add_filter('wp_rem_translate_options', array( $this, 'wp_rem_translate_options_callback' ), 0, 1);
        }

        public function wp_rem_translate_options_admin_callback($wp_rem_plugin_options = array()) {
            if ( function_exists('icl_register_string') ) {
                $lang_code = ICL_LANGUAGE_CODE;

                $review_flag_opts = isset($wp_rem_plugin_options['review_flag_opts']) ? $wp_rem_plugin_options['review_flag_opts'] : '';
                if ( isset($review_flag_opts) && ! empty($review_flag_opts) ) {
                    $review_flag_opts_trans = array();
                    foreach ( $review_flag_opts as $review_flag_opt ) {
                        do_action('wpml_register_single_string', 'WP REM Settings', 'Property Reviews Flag - ' . $review_flag_opt, $review_flag_opt);
                    }
                }

                $fixed_price_opt = isset($wp_rem_plugin_options['fixed_price_opt']) ? $wp_rem_plugin_options['fixed_price_opt'] : '';
                if ( isset($fixed_price_opt) && ! empty($fixed_price_opt) ) {
                    $fixed_price_op_trans = array();
                    foreach ( $fixed_price_opt as $key => $fixed_price_op ) {
                        do_action('wpml_register_single_string', 'WP REM Settings', 'Fixed Price - ' . $fixed_price_op, $fixed_price_op);
                    }
                }

                $term_policy_description = isset($wp_rem_plugin_options['wp_rem_term_policy_description']) ? $wp_rem_plugin_options['wp_rem_term_policy_description'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Terms & Condition - Description', $term_policy_description);

                $member_title_options = isset($wp_rem_plugin_options['member_title']) ? $wp_rem_plugin_options['member_title'] : '';
                if ( isset($member_title_options) && ! empty($member_title_options) ) {
                    $member_title_options_trans = array();
                    foreach ( $member_title_options as $member_title_option ) {
                        do_action('wpml_register_single_string', 'WP REM Settings', 'Member Type - ' . $member_title_option, $member_title_option);
                    }
                }

                $wp_rem_yelp_places_cats = isset($wp_rem_plugin_options['wp_rem_yelp_places_cats']) ? $wp_rem_plugin_options['wp_rem_yelp_places_cats'] : '';
                if ( isset($member_title_options) && ! empty($member_title_options) ) {
                    $wp_rem_yelp_places_cats_trans = array();
                    if ( ! empty($wp_rem_yelp_places_cats) ) {
                        foreach ( $wp_rem_yelp_places_cats as $wp_rem_yelp_places_cat ) {
                            do_action('wpml_register_single_string', 'WP REM Settings', 'Yelp Places - ' . $wp_rem_yelp_places_cat, $wp_rem_yelp_places_cat);
                        }
                    }
                }

                $dashboard_announce_title = isset($wp_rem_plugin_options['wp_rem_dashboard_announce_title']) ? $wp_rem_plugin_options['wp_rem_dashboard_announce_title'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Announcement Heading', $dashboard_announce_title);


                $dashboard_announce_desc = isset($wp_rem_plugin_options['wp_rem_dashboard_announce_description']) ? $wp_rem_plugin_options['wp_rem_dashboard_announce_description'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Announcement Description', $dashboard_announce_desc);


                $wp_rem_mortgage_static_text_block = isset($wp_rem_plugin_options['wp_rem_mortgage_static_text_block']) ? $wp_rem_plugin_options['wp_rem_mortgage_static_text_block'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Mortgage Calculator - Description', $wp_rem_mortgage_static_text_block);


                $wp_rem_property_static_envior_text = isset($wp_rem_plugin_options['wp_rem_property_static_envior_text']) ? $wp_rem_plugin_options['wp_rem_property_static_envior_text'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Instructions - Title', $wp_rem_property_static_envior_text);


                $wp_rem_property_static_text_block = isset($wp_rem_plugin_options['wp_rem_property_static_text_block']) ? $wp_rem_plugin_options['wp_rem_property_static_text_block'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Instructions - Description', $wp_rem_property_static_text_block);


                $wp_rem_map_markers_data = isset($wp_rem_plugin_options['wp_rem_map_markers_data']) ? $wp_rem_plugin_options['wp_rem_map_markers_data'] : array();
                if ( isset($wp_rem_map_markers_data['label']) ) {
                    foreach ( $wp_rem_map_markers_data['label'] as $key => $row ) {
                        $title = isset($wp_rem_map_markers_data['label'][$key]) ? $wp_rem_map_markers_data['label'][$key] : '';
                        do_action('wpml_register_single_string', 'WP REM Settings', 'Nearby Places - ' . $title, $title);
                    }
                }
            }
        }

        public function wp_rem_translate_options_callback($wp_rem_plugin_options = array()) {
            global $wp_rem_plugin_options;

            $wp_rem_plugin_options_translate = get_option('wp_rem_plugin_options');

            //$wp_rem_plugin_options = apply_filters('wp_rem_translate_options', $wp_rem_plugin_options );

            if ( function_exists('icl_register_string') ) {
                $lang_code = ICL_LANGUAGE_CODE;
                if ( isset($_GET['wpml_lang']) && $_GET['wpml_lang'] != '' ) {
                    $lang_code = $_GET['wpml_lang'];
                }

                $review_flag_opts = isset($wp_rem_plugin_options_translate['review_flag_opts']) ? $wp_rem_plugin_options_translate['review_flag_opts'] : '';
                if ( isset($review_flag_opts) && ! empty($review_flag_opts) ) {
                    $review_flag_opts_trans = array();
                    foreach ( $review_flag_opts as $review_flag_opt ) {
                        do_action('wpml_register_single_string', 'WP REM Settings', 'Property Reviews Flag - ' . $review_flag_opt, $review_flag_opt);
                        $review_flag_opts_trans[] = apply_filters('wpml_translate_single_string', $review_flag_opt, 'WP REM Settings', 'Property Reviews Flag - ' . $review_flag_opt, $lang_code);
                    }
                    $wp_rem_plugin_options['review_flag_opts'] = $review_flag_opts_trans;
                }

                $fixed_price_opt = isset($wp_rem_plugin_options_translate['fixed_price_opt']) ? $wp_rem_plugin_options_translate['fixed_price_opt'] : '';
                if ( isset($fixed_price_opt) && ! empty($fixed_price_opt) ) {

                    $fixed_price_op_trans = array();
                    foreach ( $fixed_price_opt as $key => $fixed_price_op ) {
                        do_action('wpml_register_single_string', 'WP REM Settings', 'Fixed Price - ' . $fixed_price_op, $fixed_price_op);
                        $fixed_price_op_trans[$key] = apply_filters('wpml_translate_single_string', $fixed_price_op, 'WP REM Settings', 'Fixed Price - ' . $fixed_price_op, $lang_code);
                    }
                    $wp_rem_plugin_options['fixed_price_opt'] = $fixed_price_op_trans;
                }

                $term_policy_description = isset($wp_rem_plugin_options_translate['wp_rem_term_policy_description']) ? $wp_rem_plugin_options_translate['wp_rem_term_policy_description'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Terms & Condition - Description', $term_policy_description);
                $wp_rem_plugin_options['wp_rem_term_policy_description'] = apply_filters('wpml_translate_single_string', $term_policy_description, 'WP REM Settings', 'Terms & Condition - Description', $lang_code);


                $member_title_options = isset($wp_rem_plugin_options_translate['member_title']) ? $wp_rem_plugin_options_translate['member_title'] : '';
                if ( isset($member_title_options) && ! empty($member_title_options) ) {
                    $member_title_options_trans = array();
                    foreach ( $member_title_options as $member_title_option ) {
                        do_action('wpml_register_single_string', 'WP REM Settings', 'Member Type - ' . $member_title_option, $member_title_option);
                        $member_title_options_trans[] = apply_filters('wpml_translate_single_string', $member_title_option, 'WP REM Settings', 'Member Type - ' . $member_title_option, $lang_code);
                    }
                    $wp_rem_plugin_options['member_title'] = $member_title_options_trans;
                }

                $wp_rem_yelp_places_cats = isset($wp_rem_plugin_options_translate['wp_rem_yelp_places_cats']) ? $wp_rem_plugin_options_translate['wp_rem_yelp_places_cats'] : '';
                if ( isset($wp_rem_yelp_places_cats) && ! empty($wp_rem_yelp_places_cats) ) {
                    $wp_rem_yelp_places_cats_trans = array();
                    foreach ( $wp_rem_yelp_places_cats as $wp_rem_yelp_places_cat ) {
                        do_action('wpml_register_single_string', 'WP REM Settings', 'Yelp Places - ' . $wp_rem_yelp_places_cat, $wp_rem_yelp_places_cat);
                        $wp_rem_yelp_places_cats_trans[] = apply_filters('wpml_translate_single_string', $wp_rem_yelp_places_cat, 'WP REM Settings', 'Yelp Places - ' . $wp_rem_yelp_places_cat, $lang_code);
                    }
                    $wp_rem_plugin_options['wp_rem_yelp_places_cats'] = $wp_rem_yelp_places_cats_trans;
                }

                $dashboard_announce_title = isset($wp_rem_plugin_options_translate['wp_rem_dashboard_announce_title']) ? $wp_rem_plugin_options_translate['wp_rem_dashboard_announce_title'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Announcement Heading', $dashboard_announce_title);
                $wp_rem_plugin_options['wp_rem_dashboard_announce_title'] = apply_filters('wpml_translate_single_string', $dashboard_announce_title, 'WP REM Settings', 'Announcement Heading', $lang_code);

                $dashboard_announce_desc = isset($wp_rem_plugin_options_translate['wp_rem_dashboard_announce_description']) ? $wp_rem_plugin_options_translate['wp_rem_dashboard_announce_description'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Announcement Description', $dashboard_announce_desc);
                $wp_rem_plugin_options['wp_rem_dashboard_announce_description'] = apply_filters('wpml_translate_single_string', $dashboard_announce_desc, 'WP REM Settings', 'Announcement Description', $lang_code);

                $wp_rem_mortgage_static_text_block = isset($wp_rem_plugin_options_translate['wp_rem_mortgage_static_text_block']) ? $wp_rem_plugin_options_translate['wp_rem_mortgage_static_text_block'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Mortgage Calculator - Description', $wp_rem_mortgage_static_text_block);
                $wp_rem_plugin_options['wp_rem_mortgage_static_text_block'] = apply_filters('wpml_translate_single_string', $wp_rem_mortgage_static_text_block, 'WP REM Settings', 'Mortgage Calculator - Description', $lang_code);


                $wp_rem_property_static_envior_text = isset($wp_rem_plugin_options_translate['wp_rem_property_static_envior_text']) ? $wp_rem_plugin_options_translate['wp_rem_property_static_envior_text'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Instructions - Title', $wp_rem_property_static_envior_text);
                $wp_rem_plugin_options['wp_rem_property_static_envior_text'] = apply_filters('wpml_translate_single_string', $wp_rem_property_static_envior_text, 'WP REM Settings', 'Instructions - Title', $lang_code);

                $wp_rem_property_static_text_block = isset($wp_rem_plugin_options_translate['wp_rem_property_static_text_block']) ? $wp_rem_plugin_options_translate['wp_rem_property_static_text_block'] : '';
                do_action('wpml_register_single_string', 'WP REM Settings', 'Instructions - Description', $wp_rem_property_static_text_block);
                $wp_rem_plugin_options['wp_rem_property_static_text_block'] = apply_filters('wpml_translate_single_string', $wp_rem_property_static_text_block, 'WP REM Settings', 'Instructions - Description', $lang_code);

                $wp_rem_map_markers_data = isset($wp_rem_plugin_options_translate['wp_rem_map_markers_data']) ? $wp_rem_plugin_options_translate['wp_rem_map_markers_data'] : array();
                if ( isset($wp_rem_map_markers_data['label']) && ! empty(isset($wp_rem_map_markers_data['label'])) ) {
                    foreach ( $wp_rem_map_markers_data['label'] as $key => $row ) {
                        $title = isset($wp_rem_map_markers_data['label'][$key]) ? $wp_rem_map_markers_data['label'][$key] : '';
                        do_action('wpml_register_single_string', 'WP REM Settings', 'Nearby Places - ' . $title, $title);
                        $wp_rem_map_markers_data['label'][$key] = apply_filters('wpml_translate_single_string', $title, 'WP REM Settings', 'Nearby Places - ' . $title, $lang_code);
                    }
                    $wp_rem_plugin_options['wp_rem_map_markers_data'] = $wp_rem_map_markers_data;
                }
            }
            return $wp_rem_plugin_options;
        }

    }

    global $wp_rem_translate_options;
    $wp_rem_translate_options = new wp_rem_translate_options();
}