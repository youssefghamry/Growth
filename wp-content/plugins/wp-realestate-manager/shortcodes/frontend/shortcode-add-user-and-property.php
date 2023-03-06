<?php
/**
 * Homevillas Register User and Add Property Shortcode Frontend
 *
 */
if (!class_exists('Wp_rem_Member_Register_User_And_Property')) {

    class Wp_rem_Member_Register_User_And_Property
    {

        /**
         * Start construct Functions.
         */
        public function __construct()
        {
            add_shortcode('wp_rem_register_user_and_add_property', array($this, 'wp_rem_register_user_and_add_property_shortcode'));

            add_action('wp_rem_property_custom_fields_cf', array($this, 'property_custom_fields'), 10);

            add_action('wp_rem_assign_free_package_to_member', array($this, 'assign_free_package_to_member'), 10, 1);

            add_action('wp_ajax_user_and_property_meta_save', array($this, 'user_and_property_meta_save_callback'));
            add_action('wp_ajax_nopriv_user_and_property_meta_save', array($this, 'user_and_property_meta_save_callback'));

            add_action('wp_ajax_wp_rem_register_user_and_property_load_cf', array($this, 'wp_rem_register_user_and_property_load_cf_callback'));
            add_action('wp_ajax_nopriv_wp_rem_register_user_and_property_load_cf', array($this, 'wp_rem_register_user_and_property_load_cf_callback'));

            add_action('wp_ajax_wp_rem_property_load_cf_cats', array($this, 'custom_fields_features'));
            add_action('wp_ajax_nopriv_wp_rem_property_load_cf_cats', array($this, 'custom_fields_features'));

            add_action('wp_ajax_wp_rem_payment_gateways_package_selected', array($this, 'wp_rem_payment_gateways_package_selected_callback'));
            add_action('wp_ajax_nopriv_wp_rem_payment_gateways_package_selected', array($this, 'wp_rem_payment_gateways_package_selected_callback'));

            add_action('wp_ajax_wp_rem_create_property_login', array($this, 'create_property_login_action'));
            add_action('wp_ajax_nopriv_wp_rem_create_property_login', array($this, 'create_property_login_action'));

            add_action('wp_ajax_wp_rem_property_price_calculating', array($this, 'property_price_calculating'));
            add_action('wp_ajax_nopriv_wp_rem_property_price_calculating', array($this, 'property_price_calculating'));

            add_action('wp_ajax_wp_rem_show_property_pkg_info', array($this, 'show_property_pkg_info'));
            add_action('wp_ajax_nopriv_wp_rem_show_property_pkg_info', array($this, 'show_property_pkg_info'));

            add_action('wp_ajax_wp_rem_show_type_packgs', array($this, 'property_packages'));
            add_action('wp_ajax_nopriv_wp_rem_show_type_packgs', array($this, 'property_packages'));

            add_action('wp_ajax_wp_rem_show_pkg_activation_msg', array($this, 'property_show_pkg_activation_msg'));
            add_action('wp_ajax_nopriv_wp_rem_show_pkg_activation_msg', array($this, 'property_show_pkg_activation_msg'));

            add_filter('wp_rem_user_login_redirect_url', array($this, 'user_login_redirect_url'));
            add_filter('social_login_redirect_to', array($this, 'user_login_redirect_url'), 10, 1);
        }

        /**
         * Save wp_rem property
         * @return
         */
        public function user_and_property_meta_save_callback()
        {
            global $current_user, $property_add_counter;
            $get_property_id = wp_rem_get_input('get_property_id', 0);

            if ($get_property_id == '' || $get_property_id == 0) {
                wp_rem_verify_term_condition_form_field('term_policy');
            }
            $response = array('status' => false, 'msg' => wp_rem_plugin_text_srt('wp_rem_property_error_occured'));
            if (is_user_logged_in() && $this->is_form_submit()) {

                $is_updating = false;
                if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                    $property_id = $get_property_id;
                    $is_updating = true;
                    $member_id = get_post_meta($property_id, 'wp_rem_property_member', true);
                    $wp_rem_property_title = wp_rem_get_input('wp_rem_property_title', '', 'STRING');
                    $wp_rem_property_content = isset($_POST['wp_rem_property_desc']) ? $_POST['wp_rem_property_desc'] : '';
                    $property_post = array(
                        'ID' => $property_id,
                        'post_title' => $wp_rem_property_title,
                        'post_content' => $wp_rem_property_content,
                    );
                    wp_update_post($property_post);
                } else {
                    $company_id = wp_rem_company_id_form_user_id($current_user->ID);
                    $member_id = $company_id;
                    $property_id = $this->property_insert($member_id);
                    $property_post = array(
                        'ID' => $property_id,
                        'post_date' => current_time('Y/m/d H:i:s', 1),
                        'post_date_gmt' => current_time('Y/m/d H:i:s', 1),
                    );
                    wp_update_post($property_post);
                }

                $publish_user_id = $current_user->ID;

                if ($property_id != '') {

                    if (!$is_updating) {
                        // saving Property posted date
                        update_post_meta($property_id, 'wp_rem_property_posted', strtotime(current_time('Y/m/d H:i:s', 1)));
                    }

                    // Saving Property Member
                    update_post_meta($property_id, 'wp_rem_property_member', $member_id);
                    update_post_meta($property_id, 'wp_rem_property_username', $publish_user_id);

                    // updating company id
                    $company_id = get_user_meta($member_id, 'wp_rem_company', true);
                    update_post_meta($property_id, 'wp_rem_property_company', $company_id);

                    $property_short_summary = isset($_POST['wp_rem_property_summary']) ? $_POST['wp_rem_property_summary'] : '';
                    update_post_meta($property_id, 'wp_rem_property_summary', $property_short_summary);

                    do_action('wp_rem_update_property_fields', $property_id, $_POST);

                    $property_featured_image_id = '';
                    $wp_rem_property_featured_image_id = isset($_POST['wp_rem_property_featured_image_id']) ? $_POST['wp_rem_property_featured_image_id'] : '';
                    $property_featured_image = isset($_FILES['wp_rem_property_featured_image']) ? $_FILES['wp_rem_property_featured_image'] : '';
                    if ($wp_rem_property_featured_image_id != '') {
                        $property_featured_image_id = $wp_rem_property_featured_image_id;
                    } else if ($property_featured_image != '' && !is_numeric($property_featured_image) && !empty($property_featured_image)) {
                        $gallery_media_upload = $this->property_gallery_upload('wp_rem_property_featured_image', $property_featured_image);
                        $property_featured_image_id = isset($gallery_media_upload[0]) ? $gallery_media_upload[0] : '';
                    }
                    if ($property_featured_image_id != '' && is_numeric($property_featured_image_id)) {
                        set_post_thumbnail($property_id, $property_featured_image_id);
                        $img_url = wp_get_attachment_url($property_featured_image_id);
                        update_post_meta($property_id, 'wp_rem_cover_image', $img_url);
                    } else {
                        delete_post_thumbnail($property_id);
                        update_post_meta($property_id, 'wp_rem_cover_image', '');
                    }

                    // member status
                    update_post_meta($property_id, 'property_member_status', 'active');

                    // Saving video cover image
                    $property_featured_image_id = '';
                    $wp_rem_property_featured_image_id = isset($_POST['wp_rem_property_image_id']) ? $_POST['wp_rem_property_image_id'] : '';
                    $property_featured_image = isset($_FILES['wp_rem_property_image']) ? $_FILES['wp_rem_property_image'] : '';
                    if ($wp_rem_property_featured_image_id != '') {
                        $property_featured_image_id = $wp_rem_property_featured_image_id;
                    } else if ($property_featured_image != '' && !is_numeric($property_featured_image) && !empty($property_featured_image)) {
                        $gallery_media_upload = $this->property_gallery_upload('wp_rem_property_image', $property_featured_image);
                        $property_featured_image_id = isset($gallery_media_upload[0]) ? $gallery_media_upload[0] : '';
                    }
                    if ($property_featured_image_id != '' && is_numeric($property_featured_image_id)) {
                        update_post_meta($property_id, 'wp_rem_property_image', $property_featured_image_id);
                    } else {
                        delete_post_thumbnail($property_id);
                        update_post_meta($property_id, 'wp_rem_property_image', '');
                    }

                    // ------- Saving Property Gallery --------//
                    $property_gal_array = array();
                    if (isset($_FILES['wp_rem_property_gallery_images']) && !empty($_FILES['wp_rem_property_gallery_images'])) {
                        $gallery_media_upload = $this->property_gallery_upload('wp_rem_property_gallery_images');
                        if (is_array($gallery_media_upload)) {
                            $property_gal_array = array_merge($property_gal_array, $gallery_media_upload);
                        }
                    }
                    $wp_rem_property_gallery_items = wp_rem_get_input('wp_rem_property_gallery_item', '', 'ARRAY');
                    if (is_array($wp_rem_property_gallery_items) && sizeof($wp_rem_property_gallery_items) > 0) {
                        $property_gal_array = array_merge($property_gal_array, $wp_rem_property_gallery_items);
                    }
                    update_post_meta($property_id, 'wp_rem_detail_page_gallery_ids', $property_gal_array);
                    //
                    // saving floor plans

                    $property_floor_array = array();
                    if (isset($_FILES['wp_rem_property_floor_images']) && !empty($_FILES['wp_rem_property_floor_images'])) {
                        $floor_media_upload = $this->property_gallery_upload('wp_rem_property_floor_images');
                        if (is_array($floor_media_upload)) {
                            $property_floor_array = array_merge($property_floor_array, $floor_media_upload);
                        }
                        
                    }
                    $wp_rem_property_floor_plan_title = wp_rem_get_input('wp_rem_property_floor_plan_title', '', 'ARRAY');
                    $wp_rem_property_floor_plan_image = wp_rem_get_input('wp_rem_property_floor_plan_image', '', 'ARRAY');
                    if (is_array($wp_rem_property_floor_plan_image) && sizeof($wp_rem_property_floor_plan_image) > 0) {
                        $property_floor_array = array_merge($property_floor_array, $wp_rem_property_floor_plan_image);
                    }
                    $wp_rem_property_floor_plan_desc = wp_rem_get_input('wp_rem_property_floor_plan_desc', '', 'ARRAY');
                    if (is_array($property_floor_array) && sizeof($property_floor_array) > 0) {
                        $floor_plans_array = array();
                        foreach ($property_floor_array as $key => $floor_plan) {
                            
                            $floor_plans_array[] = array(
                                'floor_plan_title' => isset($wp_rem_property_floor_plan_title[$key]) ? $wp_rem_property_floor_plan_title[$key] : '',
                                'floor_plan_description' => isset($wp_rem_property_floor_plan_desc[$key]) ? $wp_rem_property_floor_plan_desc[$key] : '',
                                'floor_plan_image' => $floor_plan,
                            );
                        }
                        
                        update_post_meta($property_id, 'wp_rem_floor_plans', $floor_plans_array);
                    }

                    // if package is free then updat the status to active
                    if (isset($_REQUEST['wp_rem_property_package']) && $_REQUEST['wp_rem_property_package'] != '') {
                        $value = get_post_meta($_REQUEST['wp_rem_property_package'], 'wp_rem_package_type', true);
                        if ($value == 'free') {
                            update_post_meta($property_id, 'wp_rem_property_status', 'active');
                        }
                    }

                    // saving attachments.
                    $property_attach_array = array();
                    if (isset($_FILES['wp_rem_property_attachment_images']) && !empty($_FILES['wp_rem_property_attachment_images'])) {
                        $attach_media_upload = $this->property_attach_file_upload('wp_rem_property_attachment_images');
                        if (is_array($attach_media_upload)) {
                            $property_attach_array = array_merge($property_attach_array, $attach_media_upload);
                        }
                    }
                    $wp_rem_property_attachment_title = wp_rem_get_input('wp_rem_property_attachment_title', '', 'ARRAY');
                    $wp_rem_property_attachment_file = wp_rem_get_input('wp_rem_property_attachment_file', '', 'ARRAY');
                    if (is_array($wp_rem_property_attachment_file) && sizeof($wp_rem_property_attachment_file) > 0) {
                        $property_attach_array = array_merge($property_attach_array, $wp_rem_property_attachment_file);
                    }
                    if (is_array($property_attach_array) && sizeof($property_attach_array) > 0) {
                        $attachments_array = array();
                        foreach ($property_attach_array as $key => $attachment) {
                            $attachments_array[] = array(
                                'attachment_title' => isset($wp_rem_property_attachment_title[$key]) ? $wp_rem_property_attachment_title[$key] : '',
                                'attachment_file' => $attachment,
                            );
                        }
                        update_post_meta($property_id, 'wp_rem_attachments', $attachments_array);
                    }
                    // end saving attachments.
                    // saving apartment by
                    $wp_rem_property_apartment_plot = wp_rem_get_input('wp_rem_property_apartment_plot', '', 'ARRAY');
                    $wp_rem_property_apartment_beds = wp_rem_get_input('wp_rem_property_apartment_beds', '', 'ARRAY');
                    $wp_rem_property_apartment_price_from = wp_rem_get_input('wp_rem_property_apartment_price_from', '', 'ARRAY');
                    $wp_rem_property_apartment_floor = wp_rem_get_input('wp_rem_property_apartment_floor', '', 'ARRAY');
                    $wp_rem_property_apartment_address = wp_rem_get_input('wp_rem_property_apartment_address', '', 'ARRAY');
                    $wp_rem_property_apartment_availability = wp_rem_get_input('wp_rem_property_apartment_availability', '', 'ARRAY');
                    $wp_rem_property_apartment_link = wp_rem_get_input('wp_rem_property_apartment_link', '', 'ARRAY');

                    if (is_array($wp_rem_property_apartment_plot) && sizeof($wp_rem_property_apartment_plot) > 0) {
                        $apartment_array = array();
                        foreach ($wp_rem_property_apartment_plot as $key => $apartment) {
                            if (count($apartment) > 0) {
                                $apartment_array[] = array(
                                    'apartment_plot' => $apartment,
                                    'apartment_beds' => isset($wp_rem_property_apartment_beds[$key]) ? $wp_rem_property_apartment_beds[$key] : '',
                                    'apartment_price_from' => isset($wp_rem_property_apartment_price_from[$key]) ? $wp_rem_property_apartment_price_from[$key] : '',
                                    'apartment_floor' => isset($wp_rem_property_apartment_floor[$key]) ? $wp_rem_property_apartment_floor[$key] : '',
                                    'apartment_address' => isset($wp_rem_property_apartment_address[$key]) ? $wp_rem_property_apartment_address[$key] : '',
                                    'apartment_availability' => isset($wp_rem_property_apartment_availability[$key]) ? $wp_rem_property_apartment_availability[$key] : '',
                                    'apartment_link' => isset($wp_rem_property_apartment_link[$key]) ? $wp_rem_property_apartment_link[$key] : '',
                                );
                            }
                        }
                        update_post_meta($property_id, 'wp_rem_apartment', $apartment_array);
                    }
                    // updating company id
                    $company_id = get_user_meta($member_id, 'wp_rem_company', true);
                    update_post_meta($property_id, 'wp_rem_property_company', $company_id);

                    // saving Property Type
                    $wp_rem_property_type = isset($_REQUEST['wp_rem_property_type']) ? $_REQUEST['wp_rem_property_type'] : '';
                    update_post_meta($property_id, 'wp_rem_property_type', $wp_rem_property_type);

                    // saving Property video
                    $wp_rem_property_video = wp_rem_get_input('wp_rem_property_video', '', 'RAW');
                    update_post_meta($property_id, 'wp_rem_property_video', $wp_rem_property_video);

                    // saving open house

                    $wp_rem_open_house_date = wp_rem_get_input('wp_rem_open_house_date', '', 'STRING');
                    $wp_rem_open_house_time_from = wp_rem_get_input('wp_rem_open_house_time_from', '', 'STRING');
                    $wp_rem_open_house_time_to = wp_rem_get_input('wp_rem_open_house_time_to', '', 'STRING');
                    update_post_meta($property_id, 'wp_rem_open_house_date', ($wp_rem_open_house_date));
                    update_post_meta($property_id, 'wp_rem_open_house_time_from', ($wp_rem_open_house_time_from));
                    update_post_meta($property_id, 'wp_rem_open_house_time_to', ($wp_rem_open_house_time_to));


                    // saving Property virtual tour
                    $wp_rem_property_virtual_tour = wp_rem_get_input('wp_rem_property_virtual_tour', '', 'STRING');
                    update_post_meta($property_id, 'wp_rem_property_virtual_tour', $wp_rem_property_virtual_tour);
                    do_action('wp_rem_photos_epc_tab_save', $property_id, $_POST);

                    // saving Custom Fields
                    // all dynamic fields
                    $wp_rem_cus_fields = wp_rem_get_input('wp_rem_cus_field', '', 'ARRAY');
                    if (is_array($wp_rem_cus_fields) && sizeof($wp_rem_cus_fields) > 0) {
                        foreach ($wp_rem_cus_fields as $c_key => $c_val) {
                            update_post_meta($property_id, $c_key, $c_val);
                        }
                    }

                    // price save

                    $property_type_post = get_posts(array('fields' => 'ids', 'posts_per_page' => '1', 'post_type' => 'property-type', 'name' => "$wp_rem_property_type", 'post_status' => 'publish', 'suppress_filters' => '0'));
                    $property_type_id = isset($property_type_post[0]) && $property_type_post[0] != '' ? $property_type_post[0] : 0;
                    $wp_rem_property_type_price = get_post_meta($property_type_id, 'wp_rem_property_type_price', true);
                    $wp_rem_property_type_price = isset($wp_rem_property_type_price) && $wp_rem_property_type_price != '' ? $wp_rem_property_type_price : 'off';
                    $html = '';
                    if ($wp_rem_property_type_price == 'on') {
                        $wp_rem_property_price_options = wp_rem_get_input('wp_rem_property_price_options', 'STRING');
                        $wp_rem_property_price = wp_rem_get_input('wp_rem_property_price', 'STRING');
                        //$price_type = wp_rem_get_input('wp_rem_price_type', 'STRING');
                        $price_type = isset($_REQUEST['wp_rem_price_type']) ? $_REQUEST['wp_rem_price_type'] : '';
                        update_post_meta($property_id, 'wp_rem_price_type', $price_type);

                        do_action('homevillas_save_variant_prices', $property_id, $price_type, $wp_rem_property_price);

                        update_post_meta($property_id, 'wp_rem_property_price_options', $wp_rem_property_price_options);
                        update_post_meta($property_id, 'wp_rem_property_price', $wp_rem_property_price);
                    }

                    $wp_rem_phone_number_property_frontend = isset($_REQUEST['wp_rem_phone_number_property_frontend']) ? $_REQUEST['wp_rem_phone_number_property_frontend'] : '';
                    if ($wp_rem_phone_number_property_frontend != '') {
                        update_post_meta($property_id, 'wp_rem_phone_number_property', $wp_rem_phone_number_property_frontend);
                    }


                    // end price save

                    $property_cats_formate = 'single';

                    $wp_rem_property_category_array = wp_rem_get_input('wp_rem_property_category', '', 'ARRAY');
                    if (!empty($wp_rem_property_category_array) && is_array($wp_rem_property_category_array)) {
                        foreach ($wp_rem_property_category_array as $cate_slug => $cat_val) {

                            if ($cat_val) {
                                $term = get_term_by('slug', $cat_val, 'property-category');

                                if (isset($term->term_id)) {
                                    $cat_ids = array();
                                    $cat_ids[] = $term->term_id;
                                    $cat_slugs = $term->slug;
                                    wp_set_post_terms($property_id, $cat_ids, 'property-category', FALSE);
                                }
                            }
                        }

                        update_post_meta($property_id, 'wp_rem_property_category', $wp_rem_property_category_array);
                    }

                    // adding property tags
                    $new_pkg_check = wp_rem_get_input('wp_rem_property_new_package_used', '');
                    if ($new_pkg_check == 'on') {
                        $get_package_id = wp_rem_get_input('wp_rem_property_package', '');
                    } else {
                        $active_package_key = wp_rem_get_input('wp_rem_property_active_package', '');
                        $active_package_key = explode('pt_', $active_package_key);
                        $get_package_id = isset($active_package_key[0]) ? $active_package_key[0] : '';
                    }

                    if ($get_package_id == '') {
                        $get_package_id = get_post_meta($property_id, 'wp_rem_property_package', true);
                    }

                    $trans_id = $this->property_trans_id($property_id);

                    if ($trans_id > 0 && $this->wp_rem_is_pkg_subscribed($get_package_id, $trans_id)) {
                        $tags_limit = get_post_meta($trans_id, 'wp_rem_transaction_property_tags_num', true);
                    } else {
                        $wp_rem_pckg_data = get_post_meta($get_package_id, 'wp_rem_package_data', true);
                        $tags_limit = isset($wp_rem_pckg_data['number_of_tags']['value']) ? $wp_rem_pckg_data['number_of_tags']['value'] : '';
                    }

                    $wp_rem_property_tags = wp_rem_get_input('property_tags', '', 'ARRAY');
                    if (!empty($wp_rem_property_tags) && is_array($wp_rem_property_tags)) {
                        if ($tags_limit && $tags_limit > 0) {
                            $wp_rem_property_tags = array_slice($wp_rem_property_tags, 0, $tags_limit, true);
                        }
                        wp_set_post_terms($property_id, $wp_rem_property_tags, 'property-tag', FALSE);
                        update_post_meta($property_id, 'wp_rem_property_tags', $wp_rem_property_tags);
                    }

                    // saving property features
                    $wp_rem_property_features = wp_rem_get_input('wp_rem_property_feature', '', 'ARRAY');
                    update_post_meta($property_id, 'wp_rem_property_feature_list', $wp_rem_property_features);

                    // saving location fields
                    $wp_rem_property_country = wp_rem_get_input('wp_rem_post_loc_country_property', '', 'STRING');
                    $wp_rem_property_state = wp_rem_get_input('wp_rem_post_loc_state_property', '', 'STRING');
                    $wp_rem_property_city = wp_rem_get_input('wp_rem_post_loc_city_property', '', 'STRING');
                    $wp_rem_property_town = wp_rem_get_input('wp_rem_post_loc_town_property', '', 'STRING');
                    $wp_rem_property_loc_addr = wp_rem_get_input('wp_rem_post_loc_address_property', '', 'STRING');
                    $wp_rem_property_loc_lat = wp_rem_get_input('wp_rem_post_loc_latitude_property', '', 'STRING');
                    $wp_rem_property_loc_long = wp_rem_get_input('wp_rem_post_loc_longitude_property', '', 'STRING');
                    $wp_rem_property_loc_zoom = wp_rem_get_input('wp_rem_post_loc_zoom_property', '', 'STRING');
                    $wp_rem_property_loc_radius = wp_rem_get_input('wp_rem_loc_radius_property', '', 'STRING');
                    $wp_rem_add_new_loc = wp_rem_get_input('wp_rem_add_new_loc_property', '', 'STRING');
                    $wp_rem_loc_bounds_rest = wp_rem_get_input('wp_rem_loc_bounds_rest_property', '', 'STRING');

                    update_post_meta($property_id, 'wp_rem_post_loc_country_property', $wp_rem_property_country);
                    update_post_meta($property_id, 'wp_rem_post_loc_state_property', $wp_rem_property_state);
                    update_post_meta($property_id, 'wp_rem_post_loc_city_property', $wp_rem_property_city);
                    update_post_meta($property_id, 'wp_rem_post_loc_town_property', $wp_rem_property_town);
                    update_post_meta($property_id, 'wp_rem_post_loc_address_property', $wp_rem_property_loc_addr);
                    update_post_meta($property_id, 'wp_rem_post_loc_latitude_property', $wp_rem_property_loc_lat);
                    update_post_meta($property_id, 'wp_rem_post_loc_longitude_property', $wp_rem_property_loc_long);
                    update_post_meta($property_id, 'wp_rem_post_loc_zoom_property', $wp_rem_property_loc_zoom);
                    update_post_meta($property_id, 'wp_rem_loc_radius_property', $wp_rem_property_loc_radius);
                    update_post_meta($property_id, 'wp_rem_add_new_loc_property', $wp_rem_add_new_loc);
                    update_post_meta($property_id, 'wp_rem_loc_bounds_rest_property', $wp_rem_loc_bounds_rest);

                    $wp_rem_data = array();
                    $wp_rem_data['wp_rem_post_loc_country_property'] = $wp_rem_property_country;
                    $wp_rem_data['wp_rem_post_loc_state_property'] = $wp_rem_property_state;
                    $wp_rem_data['wp_rem_post_loc_city_property'] = $wp_rem_property_city;
                    $wp_rem_data['wp_rem_post_loc_town_property'] = $wp_rem_property_town;
                    update_post_meta($property_id, 'wp_rem_array_data', $wp_rem_data);

                    // saving opening hours
                    $wp_rem_opening_hours = wp_rem_get_input('wp_rem_opening_hour', '', 'ARRAY');
                    update_post_meta($property_id, 'wp_rem_opening_hours', $wp_rem_opening_hours);

                    // saving book off days
                    $wp_rem_off_days = wp_rem_get_input('wp_rem_property_off_days', '', 'ARRAY');
                    update_post_meta($property_id, 'wp_rem_calendar', $wp_rem_off_days);

                    $response1 = $this->property_save_assignments($property_id, $member_id);

                    if ($response1['status'] == true) {
                        $response['status'] = true;
                        $response['msg'] = 'User account and property successfully registered.';
                    }
                }
            }
            echo json_encode($response);
            wp_die();
        }

        public function create_property_login_action()
        {

            $property_type = isset($_POST['login_property_type']) && $_POST['login_property_type'] != 'undefined' ? $_POST['login_property_type'] : '';
            $property_categ = isset($_POST['login_property_categ']) && $_POST['login_property_categ'] != 'undefined' ? $_POST['login_property_categ'] : '';
            $property_subcateg = isset($_POST['login_property_sub_categ']) && $_POST['login_property_sub_categ'] != 'undefined' ? $_POST['login_property_sub_categ'] : '';
            $property_pckge = isset($_POST['login_property_pkge']) && $_POST['login_property_pkge'] != 'undefined' ? $_POST['login_property_pkge'] : '';

            $final_value = array(
                'create_property' => 'yes',
                'type' => $property_type,
                'category' => $property_categ,
                'sub_category' => $property_subcateg,
                'package' => $property_pckge,
            );
            $final_value = json_encode($final_value);
            if (isset($_COOKIE['wp_rem_was_create_property'])) {
                unset($_COOKIE['wp_rem_was_create_property']);
                setcookie('wp_rem_was_create_property', null, -1, '/');
                setcookie('wp_rem_was_create_property', $final_value, time() + 1800, '/');
            } else {
                setcookie('wp_rem_was_create_property', $final_value, time() + 1800, '/');
            }
            die;
        }

        public function user_login_redirect_url($redierct_url = '')
        {
            if (isset($_COOKIE['wp_rem_was_create_property']) && !empty($_COOKIE['wp_rem_was_create_property'])) {
                global $wp_rem_plugin_options;
                $wp_rem_create_property_page = isset($wp_rem_plugin_options['wp_rem_create_property_page']) ? $wp_rem_plugin_options['wp_rem_create_property_page'] : '';
                $redierct_url = esc_url(get_permalink($wp_rem_create_property_page));
            }
            return $redierct_url;
        }

        public function wp_rem_register_user_and_add_property_shortcode($atts, $content = "")
        {

            $html = $this->render_shortcode_ui(array('wp_rem_add_property_seperator_style' => '', 'wp_rem_add_property_element_title_color' => '', 'wp_rem_add_property_element_subtitle_color' => '', 'property_title' => '', 'wp_rem_element_logo' => '', 'property_subtitle' => '', 'property_title_alignment' => '',), $atts);

            return $html;
        }

        public function render_shortcode_ui($defaults, $atts)
        {
            global $wp_rem_plugin_options, $property_add_counter, $post, $wp_rem_form_fields_frontend;
            extract(shortcode_atts($defaults, $atts));


            $element_title_color = '';
            if (isset($wp_rem_add_property_element_title_color) && $wp_rem_add_property_element_title_color != '') {
                $element_title_color = ' style="color:' . $wp_rem_add_property_element_title_color . ' ! important"';
            }
            $element_subtitle_color = '';
            if (isset($wp_rem_add_property_element_subtitle_color) && $wp_rem_add_property_element_subtitle_color != '') {
                $element_subtitle_color = ' style="color:' . $wp_rem_add_property_element_subtitle_color . ' ! important"';
            }

            $page_id = $post->ID;

            ob_start();
            $page_element_size = isset($atts['wp_rem_register_user_and_add_property_element_size']) ? $atts['wp_rem_register_user_and_add_property_element_size'] : 100;
            if (function_exists('wp_rem_cs_var_page_builder_element_sizes')) {
                echo '<div class="' . wp_rem_cs_var_page_builder_element_sizes($page_element_size, $atts) . ' ">';
            }

            $property_add_counter = rand(10000000, 99999999);

            $wp_rem_id = wp_rem_get_input('property_id', 0);

            wp_enqueue_script('wp-rem-property-user-add');
            //editor
            wp_enqueue_style('jquery-te');
            wp_enqueue_script('jquery-te');
            wp_enqueue_script('jquery-ui');

            //iconpicker
            wp_enqueue_style('fonticonpicker');
            wp_enqueue_script('fonticonpicker');

            wp_enqueue_style('datetimepicker');
            wp_enqueue_script('datetimepicker');

            $get_property_id = wp_rem_get_input('property_id', 0);
            $is_updating = false;
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
            }

            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '') {
                $server_link = $_SERVER['HTTP_REFERER'];
                $create_property_link = get_permalink($page_id);

                $back_to_title = wp_rem_plugin_text_srt('wp_rem_create_property_back');
                $server_link_id = url_to_postid($server_link);
                if ($server_link_id != '') {
                    $server_link_title = wp_trim_words(get_the_title($server_link_id), 4, '...');
                    $back_to_title = sprintf(wp_rem_plugin_text_srt('wp_rem_create_property_back_to'), $server_link_title);
                }

                if ($server_link != $create_property_link) {
                    echo '<div class="back-page-url"><a href="' . $server_link . '">' . $back_to_title . '</a></div>';
                }
            }
            ?>
            <div class="user-account-holder loader-holder">
                <?php
                if ($wp_rem_element_logo != '') {
                    $wp_rem_element_logo = wp_get_attachment_url($wp_rem_element_logo);
                    echo '<div class="property-add-elem-logo"><figure><a href="' . home_url('/') . '"><img src="' . $wp_rem_element_logo . '" alt=""></a></figure></div>';
                }
                ?>
                <div class="element-title align-center ">
                    <?php
                    if ($is_updating === true) {
                        ?>
                        <h2<?php echo wp_rem_allow_special_char($element_title_color); ?>><?php echo wp_rem_plugin_text_srt('wp_rem_property_update_property') ?></h2>
                        <?php
                    } else {
                        ?>
                        <h2<?php echo wp_rem_allow_special_char($element_title_color); ?>><?php echo wp_rem_plugin_text_srt('wp_rem_property_add_your_property') ?></h2>
                        <?php
                    }
                    if ($property_subtitle != '') {
                        ?>
                        <p<?php echo wp_rem_allow_special_char($element_subtitle_color); ?>><?php echo esc_html($property_subtitle) ?></p>
                        <?php
                    }

                    if (isset($wp_rem_add_property_seperator_style) && !empty($wp_rem_add_property_seperator_style)) {
                        $wp_rem_add_properties_seperator_html = '';
                        if ($wp_rem_add_property_seperator_style == 'classic') {
                            $wp_rem_add_properties_seperator_html .= '<div class="classic-separator ' . $property_title_alignment . '"><span></span></div>';
                        }
                        if ($wp_rem_add_property_seperator_style == 'zigzag') {
                            $wp_rem_add_properties_seperator_html .= '<div class="separator-zigzag ' . $property_title_alignment . '">
                                            <figure><img src="' . trailingslashit(wp_rem::plugin_url()) . 'assets/images/zigzag-img1.png" alt=""/></figure>
                                        </div>';
                        }
                        echo force_balance_tags($wp_rem_add_properties_seperator_html);
                    }
                    ?>
                </div>
                <div id="wp-rem-dev-posting-main-<?php echo absint($property_add_counter); ?>"
                     class="user-holder create-property-holder"
                     data-ajax-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>"
                     data-plugin-url="<?php echo esc_url(wp_rem::plugin_url()); ?>">
                    <?php
                    ob_start();
                    // Check if it is form save request.
                    $output = ob_get_clean();

                    $wp_rem_free_properties_switch = isset($wp_rem_plugin_options['wp_rem_free_properties_switch']) ? $wp_rem_plugin_options['wp_rem_free_properties_switch'] : 'off';

                    $activation_process = (isset($_GET['tab']) && isset($_GET['tab']) == 'activation') ? ' processing' : '';
                    $active_class = (isset($_GET['tab']) && isset($_GET['tab']) == 'activation') ? ' class="active' . $activation_process . '"' : '';
                    $active_processing_class = (isset($_GET['tab']) && isset($_GET['tab']) == 'activation') ? ' class="active"' : '';
                    $processing = (isset($_GET['tab']) && isset($_GET['tab']) != 'activation') ? ' processing' : '';

                    $activeation_tab_active = (isset($_GET['tab']) && isset($_GET['tab']) == 'activation') ? 'true' : 'false';

                    if ($activeation_tab_active != 'true') {
                        ?>
                        <ul class="property-settings-nav progressbar-nav"
                            data-property="<?php echo absint($wp_rem_id) ?>"
                            data-mcounter="<?php echo absint($property_add_counter) ?>">
                            <li class="active <?php echo $processing; ?>" data-act="property-information"><a
                                        href="javascript:void(0);" class="cond-property-settings1"
                                        data-act="property-information"><?php echo wp_rem_plugin_text_srt('wp_rem_property_property_type'); ?></a>
                            </li>
                            <li<?php echo(isset($_COOKIE['wp_rem_was_create_property']) && $_COOKIE['wp_rem_was_create_property'] != '' && is_user_logged_in() ? ' class="active processing"' : $active_processing_class); ?>
                                    data-act="property-detail-info"><a href="javascript:void(0);"
                                                                       class="cond-property-settings1"
                                                                       data-act="property-detail-info"><?php echo wp_rem_plugin_text_srt('wp_rem_property_property_detail'); ?></a>
                            </li>
                            <li<?php echo $active_processing_class; ?> data-act="advance-options"><a
                                        href="javascript:void(0);" class="cond-property-settings1"
                                        data-act="advance-options"><?php echo wp_rem_plugin_text_srt('wp_rem_property_dec_and_price'); ?></a>
                            </li>
                            <li<?php echo $active_processing_class; ?> data-act="loc-address"><a
                                        href="javascript:void(0);" class="cond-property-settings1"
                                        data-act="loc-address"><?php echo wp_rem_plugin_text_srt('wp_rem_property_location'); ?></a>
                            </li>
                            <li<?php echo $active_processing_class; ?> data-act="property-photos"><a
                                        href="javascript:void(0);" class="cond-property-settings1"
                                        data-act="property-photos"><?php echo wp_rem_plugin_text_srt('wp_rem_property_photos_and_epc'); ?></a>
                            </li>
                            <?php if ($wp_rem_free_properties_switch != 'on' && $is_updating === false) { ?>
                                <li<?php echo $active_processing_class; ?> data-act="package"><a
                                            href="javascript:void(0);" class="cond-property-settings1"
                                            data-act="package"><?php echo wp_rem_plugin_text_srt('wp_rem_property_review'); ?></a>
                                </li>
                                <?php
                            } else {
                                ?>
                                <li<?php echo $active_processing_class; ?> data-act="package"><a
                                            href="javascript:void(0);" class="cond-property-settings1"
                                            data-act="package"><?php echo wp_rem_plugin_text_srt('wp_rem_property_update'); ?></a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>

                        <?php
                    }
                    $property_tab = isset($_GET['property_tab']) ? $_GET['property_tab'] : '';
                    ?>
                    <div id="property-sets-holder"
                         data-doing="<?php echo($is_updating === true ? 'updating' : 'creating') ?>">

                        <form id="wp-rem-dev-property-form-<?php echo absint($property_add_counter); ?>"
                              name="wp-rem-dev-property-form" class="form-fields-set wp-rem-dev-property-form"
                              data-id="<?php echo absint($property_add_counter); ?>" method="post"
                              enctype="multipart/form-data">
                            <?php
                            if ($activeation_tab_active != 'true') {
                                $this->property_show_set_settings(1);
                                $this->property_show_detail_settings(1);
                                $this->property_show_advance_options(1);
                                $this->property_show_loc_address(1);
                                $this->property_show_property_photos(1);

                                if ($wp_rem_free_properties_switch != 'on') :
                                    $this->property_show_set_membership(1);
                                endif;
                            }
                            $wp_rem_opt_array = array(
                                'std' => 'user_and_property_meta_save',
                                'cust_id' => 'action',
                                'cust_name' => 'action',
                                'cust_type' => 'hidden',
                                'classes' => '',
                            );
                            $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                            ?>
                        </form>
                        <div class="payment-redirecting-process">
                            <div class="payment-process-form-container"></div>
                        </div>
                        <?php
                        if ($wp_rem_free_properties_switch != 'on') {

                        }

                        if (isset($_COOKIE['wp_rem_was_create_property']) && is_user_logged_in()) {
                            unset($_COOKIE['wp_rem_was_create_property']);
                            setcookie('wp_rem_was_create_property', null, -1, '/');
                        }

                        if ($activeation_tab_active == 'true') {
                            $this->property_show_activation_tab();
                        }
                        ?>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            add_event_listners({
                                'package_required_error': '<?php echo wp_rem_plugin_text_srt('wp_rem_property_select_package'); ?>',
                                'processing_request': '<?php echo wp_rem_plugin_text_srt('wp_rem_property_processing'); ?>',
                                'is_property_posting_free': '<?php echo($wp_rem_free_properties_switch); ?>',
                            }, $);
                        });
                    </script>
                </div>
            </div>
            <?php
            if (function_exists('wp_rem_cs_var_page_builder_element_sizes')) {
                echo '</div>';
            }
            return force_balance_tags(ob_get_clean());
        }

        public function property_show_pkg_activation_msg()
        {
            ob_start();
            $this->property_show_activation_tab();
            $html = ob_get_clean();

            echo json_encode(array('html' => $html));
            die;
        }

        public function property_show_set_settings($die_ret = '')
        {
            global $property_add_counter, $wp_rem_plugin_options, $wp_rem_form_fields_frontend;

            $property_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $property_add_counter;

            $get_property_id = wp_rem_get_input('property_id', 0);
            $is_updating = false;
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
            }

            ob_start();

            $tab_display = 'block';
            if (isset($_COOKIE['wp_rem_was_create_property']) && $_COOKIE['wp_rem_was_create_property'] != '' && is_user_logged_in()) {
                $tab_display = 'none';
            }

            $this->property_add_tag_before('property-information-tab-container', $tab_display);
            ?>
            <li>
                <?php
                $this->select_property_type();
                ?>
            </li>
            <li>
                <ul class="membership-info-main">
                    <?php
                    do_action('wp_rem_property_add_info', '');
                    ?>
                </ul>
                <ul id="property-membership-info-main" class="membership-info-main">
                    <?php
                    $this->property_packages();
                    ?>
                </ul>
            </li>

            <li>
                <?php
                $check_box = '';
                $get_property_id = wp_rem_get_input('property_id', 0);

                $check_box = wp_rem::get_terms_and_conditions_field('', 'terms-' . $property_add_counter);

                $back_dash_btn = '';
                $update_dash_btn = '';
                if ($is_updating === true) {
                    $wp_rem_dashboard_page = isset($wp_rem_plugin_options['wp_rem_member_dashboard']) ? $wp_rem_plugin_options['wp_rem_member_dashboard'] : '';
                    $wp_rem_dashboard_link = $wp_rem_dashboard_page != '' ? wp_rem_wpml_lang_page_permalink($wp_rem_dashboard_page, 'page') : '';
                    $user_properties_list = add_query_arg(array('dashboard' => 'properties'), $wp_rem_dashboard_link);
                    $back_dash_btn = '<div class="property-back-dashboard"><a href="' . $user_properties_list . '">' . wp_rem_plugin_text_srt('wp_rem_property_back_dashboard') . '</a></div>';
                    $update_dash_btn = '<div class="property-update-dashboard">';
                    $wp_rem_opt_array = array(
                        'std' => wp_rem_plugin_text_srt('wp_rem_property_update'),
                        'cust_id' => '',
                        'cust_name' => 'do_updating_btn',
                        'cust_type' => 'submit',
                        'classes' => 'do_updating_btn',
                        'return' => true,
                    );
                    $update_dash_btn .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                    $update_dash_btn .= '</div>';
                }
                $html = '
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="btns-section">
							<div class="field-holder">
								<div class="payment-holder">
									' . $check_box . '
									<div class="dashboard-left-btns">
										' . $back_dash_btn . '
										' . $update_dash_btn . '
										<div class="next-btn-field">';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_next'),
                    'cust_id' => (is_user_logged_in() ? 'btn-next-property-information' : 'btn-next-user-login'),
                    'cust_name' => 'next-btn',
                    'cust_type' => 'button',
                    'classes' => 'next-btn',
                    'extra_atr' => 'data-id="' . $property_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>
									</div>
								</div>
							</div> 
						</div> 
					</div>
				</div>';
                echo force_balance_tags($html);

                $this->after_property();
                ?>
            </li>
            <?php
            $this->property_add_tag_after();

            $html = ob_get_clean();

            if ($die_ret == 1) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array('html' => $html));
                die;
            }
        }

        public function property_show_detail_settings($die_ret = '')
        {
            global $property_add_counter, $wp_rem_plugin_options, $wp_rem_form_fields_frontend;

            $selected_type = '';
            $get_property_id = wp_rem_get_input('property_id', 0);
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
                $selected_type = get_post_meta($get_property_id, 'wp_rem_property_type', true);
            } else {
                $is_updating = false;
                $types_args = array('posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
                $cust_query = get_posts($types_args);
                $selected_type = isset($cust_query[0]->post_name) ? $cust_query[0]->post_name : '';
            }

            if (isset($_COOKIE['wp_rem_was_create_property']) && is_user_logged_in() && $is_updating === false) {
                $pre_cookie_val = stripslashes($_COOKIE['wp_rem_was_create_property']);
                $pre_cookie_val = json_decode($pre_cookie_val, true);
                $selected_type = isset($pre_cookie_val['type']) ? $pre_cookie_val['type'] : '';
            }

            $property_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $property_add_counter;

            $types_args = array('posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
            $cust_query = get_posts($types_args);
            $property_type_id = isset($cust_query[0]->ID) ? $cust_query[0]->ID : '';

            $member_add_property_obj = new wp_rem_member_property_actions();

            $back_dash_btn = '';
            $update_dash_btn = '';
            if ($is_updating === true) {
                $wp_rem_dashboard_page = isset($wp_rem_plugin_options['wp_rem_member_dashboard']) ? $wp_rem_plugin_options['wp_rem_member_dashboard'] : '';
                $wp_rem_dashboard_link = $wp_rem_dashboard_page != '' ? wp_rem_wpml_lang_page_permalink($wp_rem_dashboard_page, 'page') : '';
                $user_properties_list = add_query_arg(array('dashboard' => 'properties'), $wp_rem_dashboard_link);
                $back_dash_btn = '<div class="property-back-dashboard"><a href="' . $user_properties_list . '">' . wp_rem_plugin_text_srt('wp_rem_property_back_dashboard') . '</a></div>';
                $update_dash_btn = '<div class="property-update-dashboard">';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_update'),
                    'cust_id' => '',
                    'cust_name' => 'do_updating_btn',
                    'cust_type' => 'submit',
                    'classes' => 'do_updating_btn',
                    'return' => true,
                );
                $update_dash_btn .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $update_dash_btn .= '</div>';
            }


            // get selected property-type-id
            $property_type_post = get_page_by_path($selected_type, OBJECT, 'property-type');
            $type_id = $property_type_post->ID;
            $type_id = isset($type_id) && !empty($type_id) ? $type_id : $property_type_id;


            ob_start();

            $tab_display = 'none';
            if (isset($_COOKIE['wp_rem_was_create_property']) && $_COOKIE['wp_rem_was_create_property'] != '' && is_user_logged_in()) {
                $tab_display = 'block';
            }

            $this->property_add_tag_before('property-detail-info-tab-container', $tab_display);
            ?>

            <li>
                <div class="wp-rem-dev-appended-cats"><?php echo($this->property_categories($selected_type, $get_property_id)) ?></div>
            </li>

            <li>
                <?php
                $html = '';
                $html .= '
				<div id="wp-rem-dev-cf-con">';
                ob_start();
                do_action('wp_rem_property_custom_fields_cf');
                $html .= ob_get_clean();
                $html .= $this->property_opening_house($property_type_id, $get_property_id, 'create');
                $html .= '
				</div>';
                echo force_balance_tags($html);
                ?>
            </li>
            <div class="wp-rem-append-features-check-list">
                <?php
                echo($this->property_features_list($property_type_id, $get_property_id));
                ?>
            </div>
            <li>
                <?php
                $html = '
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="btns-section">
							<div class="field-holder">
								<div class="payment-holder">
									<div class="back-btn-field">
										<i class="icon-keyboard_arrow_left"></i>';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_back'),
                    'cust_id' => 'btn-back-property-detail',
                    'cust_name' => 'btn-back-property-detail',
                    'cust_type' => 'button',
                    'classes' => 'back-btn',
                    'extra_atr' => 'data-id="' . $property_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>
									<div class="dashboard-left-btns">
										' . $back_dash_btn . '
										' . $update_dash_btn . '
										<div class="next-btn-field">';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_next'),
                    'cust_id' => 'btn-next-property-detail',
                    'cust_name' => 'btn-next-property-detail',
                    'cust_type' => 'button',
                    'classes' => 'next-btn',
                    'extra_atr' => 'data-id="' . $property_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
                echo force_balance_tags($html);

                $this->after_property();
                ?>
            </li>
            <?php
            $this->property_add_tag_after();

            $html = ob_get_clean();

            if ($die_ret == 1) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array('html' => $html));
                die;
            }
        }

        /**
         * Property Tags
         * @return markup
         */
        public function property_tags($type_slug = '', $property_id = '')
        {
            global $property_add_counter;

            $html = '';

            // enqueue required script
            wp_enqueue_script('jquery-ui');
            wp_enqueue_script('wp-rem-tags-it');
            $select_property_type = wp_rem_get_input('select_type', '');
            if ($select_property_type != '') {
                $property_type_post = get_page_by_path($select_property_type, OBJECT, 'property-type');
                $type_id = $property_type_post->ID;
            } else {
                $property_type_post = get_page_by_path($type_slug, OBJECT, 'property-type');
                $type_id = $property_type_post->ID;
            }

            $wp_rem_tags_element = get_post_meta($type_id, 'wp_rem_tags_element', true);

            if ($wp_rem_tags_element == 'on') {

                $wp_rem_property_type_tags = get_post_meta($type_id, 'wp_rem_property_type_tags', true);
                $property_tags_list = '';

                $wp_rem_property_tags = get_post_meta($property_id, 'wp_rem_property_tags', true);
                if (is_array($wp_rem_property_tags) && !empty($wp_rem_property_tags)) {
                    $property_tags_list = '';
                    foreach ($wp_rem_property_tags as $wp_rem_property_tag) {
                        $property_tags_list .= '<li>' . $wp_rem_property_tag . '</li>';
                    }
                }

                $html .= '<div class="row">';
                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $html .= '<div class="dashboard-element-title">';
                $html .= '<strong>' . wp_rem_plugin_text_srt('wp_rem_add_property_key_tags') . '</strong>';
                $html .= '</div>';
                $html .= '
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery(\'#property-tags\').tagit({
							allowSpaces: true,
							fieldName : \'property_tags[]\'
						});
					});
				</script>';
                $html .= '<ul id="property-tags">';
                $html .= $property_tags_list;
                $html .= '</ul>';
                if (is_array($wp_rem_property_type_tags) && !empty($wp_rem_property_type_tags)) {
                    $html .= '<div class="dashboard-element-title suggested-tags-head">';
                    $html .= '<strong>' . wp_rem_plugin_text_srt('wp_rem_add_property_suggested_tags') . '</strong>';
                    $html .= '</div>';
                    $html .= '<ul class="tag-cloud-container" id="tag-cloud">';
                    foreach ($wp_rem_property_type_tags as $wp_rem_property_type_tag) {
                        $term = get_term_by('slug', $wp_rem_property_type_tag, 'property-tag');
                        if (is_object($term)) {
                            $html .= '<li class="tag-cloud" onclick="jQuery(\'#property-tags\').tagit(\'createTag\', \'' . $term->name . '\');return false;">' . $term->name . '</li>';
                        }
                    }
                    $html .= '</ul>';
                }
                $html .= '</div>';
                $html .= '</div>';
            }

            return apply_filters('wp_rem_front_property_add_tags', $html, $type_id, $property_id);
            // usage :: add_filter('wp_rem_front_property_add_tags', 'my_callback_function', 10, 3);
        }

        public function property_show_advance_options($die_ret = '')
        {
            global $property_add_counter, $wp_rem_plugin_options, $wp_rem_form_fields_frontend;

            $property_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $property_add_counter;

            $get_property_id = '';

            $selected_type = '';
            $get_property_id = wp_rem_get_input('property_id', 0);
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
                $selected_type = get_post_meta($get_property_id, 'wp_rem_property_type', true);
            } else {
                $is_updating = false;
                $types_args = array('posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
                $cust_query = get_posts($types_args);
                $selected_type = isset($cust_query[0]->post_name) ? $cust_query[0]->post_name : '';
            }

            if (isset($_COOKIE['wp_rem_was_create_property']) && is_user_logged_in() && $is_updating === false) {
                $pre_cookie_val = stripslashes($_COOKIE['wp_rem_was_create_property']);
                $pre_cookie_val = json_decode($pre_cookie_val, true);
                $selected_type = isset($pre_cookie_val['type']) ? $pre_cookie_val['type'] : '';
            }

            $types_args = array('posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
            $cust_query = get_posts($types_args);
            $property_type_id = isset($cust_query[0]->ID) ? $cust_query[0]->ID : '';

            $member_add_property_obj = new wp_rem_member_property_actions();

            $back_dash_btn = '';
            $update_dash_btn = '';
            if ($is_updating === true) {
                $wp_rem_dashboard_page = isset($wp_rem_plugin_options['wp_rem_member_dashboard']) ? $wp_rem_plugin_options['wp_rem_member_dashboard'] : '';
                $wp_rem_dashboard_link = $wp_rem_dashboard_page != '' ? wp_rem_wpml_lang_page_permalink($wp_rem_dashboard_page, 'page') : '';
                $user_properties_list = add_query_arg(array('dashboard' => 'properties'), $wp_rem_dashboard_link);
                $back_dash_btn = '<div class="property-back-dashboard"><a href="' . $user_properties_list . '">' . wp_rem_plugin_text_srt('wp_rem_property_back_dashboard') . '</a></div>';
                $update_dash_btn = '<div class="property-update-dashboard">';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_update'),
                    'cust_id' => '',
                    'cust_name' => 'do_updating_btn',
                    'cust_type' => 'submit',
                    'classes' => 'do_updating_btn',
                    'return' => true,
                );
                $update_dash_btn .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $update_dash_btn .= '</div>';
            }

            ob_start();

            $this->property_add_tag_before('advance-options-tab-container');

            $this->title_description();
            echo '<li class="wp-rem-dev-appended-price">' . $this->property_price($selected_type, $get_property_id) . '</li>'
            // echo ($member_add_property_obj->property_apartment($property_type_id, $get_property_id));
            ?>

            <li>
                <?php
                $html = '
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="btns-section">
							<div class="field-holder">
								<div class="payment-holder">
									<div class="back-btn-field">
										<i class="icon-keyboard_arrow_left"></i>';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_back'),
                    'cust_id' => 'btn-back-advance-options',
                    'cust_name' => 'btn-back-advance-options',
                    'cust_type' => 'button',
                    'classes' => 'back-btn',
                    'extra_atr' => 'data-id="' . $property_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>
									<div class="dashboard-left-btns">
										' . $back_dash_btn . '
										' . $update_dash_btn . '
										<div class="next-btn-field">';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_next'),
                    'cust_id' => 'btn-next-advance-options',
                    'cust_name' => 'btn-next-advance-options',
                    'cust_type' => 'button',
                    'classes' => 'next-btn',
                    'extra_atr' => 'data-id="' . $property_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>
									</div>
								</div>
							</div> 
						</div> 
					</div>
				</div>';
                echo force_balance_tags($html);
                ?>
                <?php $this->after_property(); ?>
            </li>
            <?php
            $this->property_add_tag_after();

            $html = ob_get_clean();

            if ($die_ret == 1) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array('html' => $html));
                die;
            }
        }

        public function property_show_loc_address($die_ret = '')
        {
            global $property_add_counter, $wp_rem_plugin_options, $wp_rem_form_fields_frontend;

            $property_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $property_add_counter;

            $get_property_id = wp_rem_get_input('property_id', 0);
            $is_updating = false;
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
            }

            $types_args = array('posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
            $cust_query = get_posts($types_args);
            $property_type_id = isset($cust_query[0]->ID) ? $cust_query[0]->ID : '';

            $member_add_property_obj = new wp_rem_member_property_actions();

            $back_dash_btn = '';
            $update_dash_btn = '';
            if ($is_updating === true) {
                $wp_rem_dashboard_page = isset($wp_rem_plugin_options['wp_rem_member_dashboard']) ? $wp_rem_plugin_options['wp_rem_member_dashboard'] : '';
                $wp_rem_dashboard_link = $wp_rem_dashboard_page != '' ? wp_rem_wpml_lang_page_permalink($wp_rem_dashboard_page, 'page') : '';
                $user_properties_list = add_query_arg(array('dashboard' => 'properties'), $wp_rem_dashboard_link);
                $back_dash_btn = '<div class="property-back-dashboard"><a href="' . $user_properties_list . '">' . wp_rem_plugin_text_srt('wp_rem_property_back_dashboard') . '</a></div>';

                $update_dash_btn = '<div class="property-update-dashboard">';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_update'),
                    'cust_id' => '',
                    'cust_name' => 'do_updating_btn',
                    'cust_type' => 'submit',
                    'classes' => 'do_updating_btn',
                    'return' => true,
                );
                $update_dash_btn .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $update_dash_btn .= '</div>';
            }

            ob_start();

            $this->property_add_tag_before('loc-address-tab-container');

            echo($member_add_property_obj->property_location($property_type_id, $get_property_id));
            ?>

            <li>
                <?php
                $html = '
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="btns-section">
								<div class="field-holder">
									<div class="payment-holder">
										<div class="back-btn-field">
											<i class="icon-keyboard_arrow_left"></i>';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_back'),
                    'cust_id' => 'btn-back-loc-address',
                    'cust_name' => 'btn-back-loc-address',
                    'cust_type' => 'button',
                    'classes' => 'back-btn',
                    'extra_atr' => 'data-id="' . $property_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>
				<div class="dashboard-left-btns">
					' . $back_dash_btn . '
					' . $update_dash_btn . '
					<div class="next-btn-field">';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_next'),
                    'cust_id' => 'btn-next-loc-address',
                    'cust_name' => 'btn-next-loc-address',
                    'cust_type' => 'button',
                    'classes' => 'next-btn',
                    'extra_atr' => 'data-id="' . $property_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>
										</div>
									</div>
								</div> 
							</div> 
						</div>
					</div>';
                echo force_balance_tags($html);
                $this->after_property();
                ?>
            </li>
            <?php
            $this->property_add_tag_after();

            $html = ob_get_clean();

            if ($die_ret == 1) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array('html' => $html));
                die;
            }
        }

        public function property_show_property_photos($die_ret = '')
        {
            global $property_add_counter, $wp_rem_plugin_options, $wp_rem_form_fields_frontend;

            $property_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $property_add_counter;

            $get_property_id = wp_rem_get_input('property_id', 0);
            $is_updating = false;
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
            }

            $video_url = get_post_meta($get_property_id, 'wp_rem_property_video', true);
            $virtual_tour = get_post_meta($get_property_id, 'wp_rem_property_virtual_tour', true);

            $types_args = array('posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
            $cust_query = get_posts($types_args);
            $property_type_id = isset($cust_query[0]->ID) ? $cust_query[0]->ID : '';

            $member_add_property_obj = new wp_rem_member_property_actions();

            $back_dash_btn = '';
            $update_dash_btn = '';
            if ($is_updating === true) {
                $wp_rem_dashboard_page = isset($wp_rem_plugin_options['wp_rem_member_dashboard']) ? $wp_rem_plugin_options['wp_rem_member_dashboard'] : '';
                $wp_rem_dashboard_link = $wp_rem_dashboard_page != '' ? wp_rem_wpml_lang_page_permalink($wp_rem_dashboard_page, 'page') : '';
                $user_properties_list = add_query_arg(array('dashboard' => 'properties'), $wp_rem_dashboard_link);
                $back_dash_btn = '<div class="property-back-dashboard"><a href="' . $user_properties_list . '">' . wp_rem_plugin_text_srt('wp_rem_property_back_dashboard') . '</a></div>';

                $update_dash_btn = '<div class="property-update-dashboard">';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_update'),
                    'cust_id' => '',
                    'cust_name' => 'do_updating_btn',
                    'cust_type' => 'submit',
                    'classes' => 'do_updating_btn',
                    'return' => true,
                );
                $update_dash_btn .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $update_dash_btn .= '</div>';
            }

            ob_start();

            $this->property_add_tag_before('property-photos-tab-container');

            echo($this->property_gallery($property_type_id, $get_property_id));
            echo($this->property_attachments($property_type_id, $get_property_id));
            echo($this->property_floor_plans($property_type_id, $get_property_id));

            // Property Floor Plans pdf's frontend
            do_action('wp_rem_property_floor_plans_documents_frontend', $property_type_id, $get_property_id);

            $type_video = get_post_meta($property_type_id, 'wp_rem_video_element', true);
            $type_virtual_tour = get_post_meta($property_type_id, 'wp_rem_virtual_tour_element', true);
            $html = '
			<li id="wp-rem-property-video-holder" style="display: ' . ($type_video == 'on' ? 'block' : 'none') . ';">
			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="dashboard-element-title">
				<strong>
					' . wp_rem_plugin_text_srt('wp_rem_property_property_video') . '
					<span class="info-text">(' . wp_rem_plugin_text_srt('wp_rem_video_url_sites_example') . ')</span>
				</strong>
			</div>
			<div class="field-holder">';
            $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render(
                array(
                    'id' => 'video_url_' . $property_add_counter,
                    'cust_name' => 'wp_rem_property_video',
                    'std' => $video_url,
                    'desc' => '',
                    'classes' => '',
                    'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_video_url') . '"',
                    'return' => true,
                    'force_std' => true,
                    'hint_text' => '',
                )
            );
            $html .= '
			</div>
			</div>
			</div>
			</li>
			<li id="wp-rem-property-virtual-tour-holder" style="display: ' . ($type_virtual_tour == 'on' ? 'block' : 'none') . ';">
			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="dashboard-element-title">
				<strong>' . wp_rem_plugin_text_srt('wp_rem_add_property_virtual_tour') . '</strong>
			</div>
			<div class="field-holder">';
            $html .= $wp_rem_form_fields_frontend->wp_rem_form_textarea_render(
                array(
                    'name' => '',
                    'id' => 'virtual_tour_' . $property_add_counter,
                    'cust_name' => 'wp_rem_property_virtual_tour',
                    'std' => $virtual_tour,
                    'desc' => '',
                    'classes' => '',
                    'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_add_property_virtual_tour_desc') . '"',
                    'return' => true,
                    'force_std' => true,
                    'hint_text' => '',
                )
            );
            $html .= '
			</div>
			</div>
			</div>
			</li>';
            $photos_epc_tab = array('content' => '');
            $photos_epc_tab_content = apply_filters('wp_rem_photos_epc_tab', $get_property_id, $property_type_id, $photos_epc_tab);
            if (isset($photos_epc_tab_content['content']) && $photos_epc_tab_content['content'] != '') {
                $html .= $photos_epc_tab_content['content'];
            }
            echo force_balance_tags($html);
            ?>

            <li>
                <?php
                $html = '
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="btns-section">
					<div class="field-holder">
					<div class="payment-holder">
					<div class="back-btn-field">
					<i class="icon-keyboard_arrow_left"></i>';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_back'),
                    'cust_id' => 'btn-back-property-photos',
                    'cust_name' => 'btn-back-property-photos',
                    'cust_type' => 'button',
                    'classes' => 'back-btn',
                    'extra_atr' => 'data-id="' . $property_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>
				<div class="dashboard-left-btns">
					' . $back_dash_btn . '
					' . $update_dash_btn . '
					<div class="next-btn-field">';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_next'),
                    'cust_id' => 'btn-next-property-photos',
                    'cust_name' => 'btn-next-property-photos',
                    'cust_type' => 'button',
                    'classes' => 'next-btn',
                    'extra_atr' => 'data-id="' . $property_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>
					</div>
					</div>
					</div> 
					</div> 
					</div>
				</div>';
                echo force_balance_tags($html);
                $this->after_property();
                ?>
            </li>
            <?php
            $this->property_add_tag_after();

            $html = ob_get_clean();

            if ($die_ret == 1) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array('html' => $html));
                die;
            }
        }

        public function property_show_set_membership($die_ret = '')
        {
            global $property_add_counter, $wp_rem_plugin_options, $wp_rem_form_fields_frontend;

            $property_add_counter = isset($_POST['_main_counter']) ? $_POST['_main_counter'] : $property_add_counter;

            $get_property_id = wp_rem_get_input('property_id', 0);
            $is_updating = false;
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
            }

            $back_dash_btn = '';
            $update_dash_btn = '';
            if ($is_updating === true) {
                $wp_rem_dashboard_page = isset($wp_rem_plugin_options['wp_rem_member_dashboard']) ? $wp_rem_plugin_options['wp_rem_member_dashboard'] : '';
                $wp_rem_dashboard_link = $wp_rem_dashboard_page != '' ? wp_rem_wpml_lang_page_permalink($wp_rem_dashboard_page, 'page') : '';
                $user_properties_list = add_query_arg(array('dashboard' => 'properties'), $wp_rem_dashboard_link);
                $back_dash_btn = '<div class="property-back-dashboard"><a href="' . $user_properties_list . '">' . wp_rem_plugin_text_srt('wp_rem_property_back_dashboard') . '</a></div>';

                $update_dash_btn = '<div class="property-update-dashboard">';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_update'),
                    'cust_id' => '',
                    'cust_name' => 'do_updating_btn',
                    'cust_type' => 'submit',
                    'classes' => 'do_updating_btn',
                    'return' => true,
                );
                $update_dash_btn .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $update_dash_btn .= '</div>';
            }

            ob_start();

            $this->property_add_tag_before('package-tab-container wp-rem-dev-payment-form');
            ?>

            <li>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="dashboard-title">
                            <?php
                            if ($is_updating === true) {
                                ?>
                                <strong><?php echo wp_rem_plugin_text_srt('wp_rem_add_user_step_six_update'); ?></strong>
                                <?php
                            } else {
                                ?>
                                <strong><?php echo wp_rem_plugin_text_srt('wp_rem_add_user_step_six_review_pay'); ?></strong>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="wp-rem-dev-property-pckg-info"></div>
            </li>

            <li class="register-payment-gw-holder"<?php echo($is_updating === true ? ' style="display: none;"' : '') ?>>

                <div class="dashboard-element-title">
                    <strong><?php echo wp_rem_plugin_text_srt('wp_rem_add_user_payment_info'); ?></strong>
                </div>
                <?php
                ob_start();
                $_REQUEST['trans_id'] = 0;
                $_REQUEST['action'] = 'property-package';
                $_GET['trans_id'] = 0;
                $_GET['action'] = 'property-package';
                $trans_fields = array(
                    'trans_id' => 0,
                    'action' => 'property-package',
                    'back_button' => true,
                    'creating' => true,
                );
                do_action('wp_rem_payment_gateways', $trans_fields);
                $output = ob_get_clean();
                echo str_replace('col-lg-8 col-md-8', 'col-lg-12 col-md-12', $output);
                ?>
            </li>
            <li>
                <?php
                $submit_title = wp_rem_plugin_text_srt('wp_rem_property_submit_order');
                if ($is_updating === true) {
                    $submit_title = wp_rem_plugin_text_srt('wp_rem_property_update');
                }
                $html = '
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="btns-section">
							<div class="field-holder">
								<div class="payment-holder">
									<div class="back-btn-field">
										<i class="icon-keyboard_arrow_left"></i>';
                $wp_rem_opt_array = array(
                    'std' => wp_rem_plugin_text_srt('wp_rem_property_back'),
                    'cust_id' => 'btn-back-package',
                    'cust_name' => 'back-btn',
                    'cust_type' => 'button',
                    'classes' => 'back-btn',
                    'extra_atr' => 'data-id="' . $property_add_counter . '"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>';
                if ($is_updating == false) {
                    $html .= wp_rem_term_condition_form_field('term_policy', 'term_policy');
                }
                $html .= '<div class="dashboard-left-btns">
										' . $back_dash_btn . '
										<div class="next-btn-field wp-rem-property-submit-process">
											<div class="wp-rem-property-submit-loader">';
                $wp_rem_opt_array = array(
                    'std' => $submit_title,
                    'cust_id' => 'register-property-order',
                    'cust_name' => 'next-btn',
                    'cust_type' => 'submit',
                    'classes' => 'next-btn',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>
										</div>
									</div>
								</div>
							</div> 
						</div> 
					</div>
				</div>';
                echo force_balance_tags($html);
                ?>
            </li>
            <?php
            $this->property_add_tag_after();

            $html = ob_get_clean();
            if ($die_ret == 1) {
                echo force_balance_tags($html);
            } else {
                echo json_encode(array('html' => $html));
                die;
            }
        }

        public function property_show_payment_information()
        {

            $this->property_add_tag_before('payment-information-tab-container');
            ?>
            <li>
                <?php
                ob_start();
                $_REQUEST['trans_id'] = 0;
                $_REQUEST['action'] = 'property-package';
                $_GET['trans_id'] = 0;
                $_GET['action'] = 'property-package';
                $trans_fields = array(
                    'trans_id' => 0,
                    'action' => 'property-package',
                    'back_button' => true,
                    'creating' => true,
                );
                do_action('wp_rem_payment_gateways', $trans_fields);
                $output = ob_get_clean();
                echo str_replace('col-lg-8 col-md-8', 'col-lg-12 col-md-12', $output);
                ?>

            </li>
            <li>
                <div class="payment-process-form-container"></div>
            </li>

            <?php
            $this->property_add_tag_after();
        }

        public function property_show_activation_tab()
        {
            global $wp_rem_plugin_options;
            $img_id = isset($wp_rem_plugin_options['wp_rem_property_success_image']) ? $wp_rem_plugin_options['wp_rem_property_success_image'] : '';
            $success_message = isset($wp_rem_plugin_options['wp_rem_property_success_message']) ? $wp_rem_plugin_options['wp_rem_property_success_message'] : '';
            $success_phone = isset($wp_rem_plugin_options['wp_rem_property_success_phone']) ? $wp_rem_plugin_options['wp_rem_property_success_phone'] : '';
            $success_fax = isset($wp_rem_plugin_options['wp_rem_property_success_fax']) ? $wp_rem_plugin_options['wp_rem_property_success_fax'] : '';
            $success_email = isset($wp_rem_plugin_options['wp_rem_property_success_email']) ? $wp_rem_plugin_options['wp_rem_property_success_email'] : '';

            $review_message = isset($wp_rem_plugin_options['wp_rem_property_approval_message']) ? $wp_rem_plugin_options['wp_rem_property_approval_message'] : '';

            $admin_review = isset($wp_rem_plugin_options['wp_rem_properties_review_option']) ? $wp_rem_plugin_options['wp_rem_properties_review_option'] : '';
            ?>
            <ul class="register-add-property-tab-container activation-tab-container">
                <li>
                    <div class="activation-tab-message">
                        <div class="media-holder">
                            <figure>
                                <?php if ($img_id != '') : ?>
                                    <img src="<?php echo wp_get_attachment_url($img_id); ?>"
                                         alt="<?php echo wp_rem_plugin_text_srt('wp_rem_property_thank_you'); ?>">
                                <?php endif; ?>
                            </figure>
                        </div>
                        <div class="text-holder">
                            <strong><?php echo wp_rem_plugin_text_srt('wp_rem_property_thank_you'); ?></strong>
                            <?php
                            if ($admin_review == 'on') {
                                if ($review_message != '') :
                                    ?>
                                    <span><?php echo esc_html($review_message); ?></span>
                                <?php
                                endif;
                            } else {
                                if ($success_message != '') :
                                    ?>
                                    <span><?php echo esc_html($success_message); ?></span>
                                <?php
                                endif;
                            }
                            ?>
                        </div>

                        <?php if ($success_phone != '' || $success_fax != '' || $success_email != '') : ?>
                            <div class="thankyou-contacts">
                                <p><?php echo wp_rem_plugin_text_srt('wp_rem_property_for_cancellation'); ?></p>
                                <ul class="list-inline clearfix">
                                    <?php if ($success_phone != '') : ?>
                                        <li><i class="icon-phone4"></i><?php echo esc_html($success_phone); ?></li>
                                    <?php endif; ?>
                                    <?php if ($success_fax != '') : ?>
                                        <li><i class="icon-fax"></i><?php echo esc_html($success_fax); ?></li>
                                    <?php endif; ?>
                                    <?php if ($success_email != '') : ?>
                                        <li><i class="icon-envelope-o"></i><?php echo esc_html($success_email); ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                    </div>
                </li>
            </ul>
            <?php
            $this->property_add_tag_after();
        }

        /**
         * Gallery Photos
         * @return markup
         */
        public function property_gallery($type_id = '', $wp_rem_id = '')
        {
            global $property_add_counter, $wp_rem_form_fields_frontend;
            $html = '';
            $wp_rem_property_gallery = get_post_meta($type_id, 'wp_rem_image_gallery_element', true);

            $wp_rem_property_gallery_ids = get_post_meta($wp_rem_id, 'wp_rem_detail_page_gallery_ids', true);
            $attacment_placeholder = '';
            $attacment_sec_items = '';

            $trans_all_meta = get_post_meta($wp_rem_id, 'wp_rem_trans_all_meta', true);
            $num_pic_allows = isset($trans_all_meta[0]['value']) ? $trans_all_meta[0]['value'] : 0;


            if (is_array($wp_rem_property_gallery_ids) && sizeof($wp_rem_property_gallery_ids) > 0) {
                foreach ($wp_rem_property_gallery_ids as $img_item) {
                    $img_url_arr = wp_get_attachment_image_src($img_item, 'wp_rem_media_3');
                    $img_url = isset($img_url_arr[0]) ? $img_url_arr[0] : '';
                    $attacment_sec_items .= '
					<li class="gal-img">
						<div class="drag-list">
							<div class="item-thumb"><img class="thumbnail" src="' . $img_url . '" alt=""/></div>
							<div class="item-assts">
								<div class="list-inline pull-right">
									<div class="close-btn" data-id="' . $property_add_counter . '"><a href="javascript:void(0);"><i class="icon-cross"></i></a></div>
								</div>';
                    $wp_rem_opt_array = array(
                        'std' => esc_html($img_item),
                        'cust_id' => '',
                        'cust_name' => 'wp_rem_property_gallery_item[]',
                        'cust_type' => 'hidden',
                        'classes' => '',
                        'return' => true,
                    );
                    $attacment_sec_items .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                    $attacment_sec_items .= '</div>
						</div>
					</li>';
                }
            }

            $html .= '
			<li class="wp-rem-dev-appended">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-title">
						<strong>' . wp_rem_plugin_text_srt('wp_rem_property_step_5') . '</strong>
					</div>
				</div>
			</div>
			<div id="wp-rem-property-gallery-holder" class="row" style="display:' . ($wp_rem_property_gallery == 'on' ? 'block' : 'none') . ';">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>' . wp_rem_plugin_text_srt('wp_rem_property_photo_gallery') . '<span class="info-text">' . esc_html__('(Press ctrl to select and upload images in bulk at once for gallery.)', 'wp-rem') . '</span></strong>
						
					</div>
					<div class="field-holder">
						<ul id="wp-rem-dev-gal-attach-sec-' . $property_add_counter . '" class="wp-rem-gallery-holder">
							' . $attacment_sec_items . '
							<li class="gal-img-add">
								<div id="upload-gallery-' . $property_add_counter . '" class="upload-gallery">
									<a href="javascript:void(0);" class="upload-btn wp-rem-dev-gallery-upload-btn" data-id="' . $property_add_counter . '"><span><i class="icon-plus"></i> ' . wp_rem_plugin_text_srt('wp_rem_property_upload_image') . '</span></a>
								</div>
							</li>
						</ul>';
            $wp_rem_opt_array = array(
                'std' => '',
                'cust_id' => 'image-uploader-' . $property_add_counter,
                'cust_name' => 'wp_rem_property_gallery_images[]',
                'cust_type' => 'file',
                'classes' => 'wp-rem-dev-gallery-uploader wp_rem_dev_property_gallery_images',
                'return' => true,
                'extra_atr' => 'style="display:none;" data-id="' . $property_add_counter . '" data-test="' . $wp_rem_id . '" data-count="' . $num_pic_allows . '" multiple="multiple" onchange="wp_rem_handle_file_select(event, \'' . $property_add_counter . '\');"',
            );
            $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
            $html .= '</div>
				</div>
				<script>
				jQuery(document).ready(function ($) {
					$("#wp-rem-dev-gal-attach-sec-' . $property_add_counter . '").sortable({
                        handle: \'.drag-list\',
                        cursor: \'move\',
                        items : \'.gal-img\',
                    });
					//document.getElementById(\'image-uploader-' . $property_add_counter . '\').addEventListener(\'change\', function(){wp_rem_handle_file_select(\'' . $property_add_counter . '\');}, false);
				});
				</script>
			</div>
			</li>';

            return apply_filters('wp_rem_front_property_add_gallery_plugin', $html, $type_id, $wp_rem_id);
            // usage :: add_filter('wp_rem_front_property_add_gallery_plugin', 'my_callback_function', 10, 3);
        }

        /**
         * Basic Info
         * @return markup
         */
        public function title_description($html = '')
        {
            global $wp_rem_form_fields_frontend, $property_add_counter, $wp_rem_plugin_options;
            $wp_rem_property_title = '';
            $wp_rem_property_desc = '';
            $wp_rem_property_summary = '';

            $is_updating = false;
            $get_property_id = wp_rem_get_input('property_id', 0);
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
                $wp_rem_property_title = get_the_title($get_property_id);
                $wp_rem_property_desc = $this->property_post_content($get_property_id);
                $wp_rem_property_summary = get_post_meta($get_property_id, 'wp_rem_property_summary', true);

                $selected_type = get_post_meta($get_property_id, 'wp_rem_property_type', true);
            } else {
                $is_updating = false;
                $types_args = array('posts_per_page' => '1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
                $cust_query = get_posts($types_args);
                $selected_type = isset($cust_query[0]->post_name) ? $cust_query[0]->post_name : '';
            }
            $html .= '
			<li>
			<div class="row">';
            $wp_rem_property_announce_title = isset($wp_rem_plugin_options['wp_rem_property_announce_title']) ? $wp_rem_plugin_options['wp_rem_property_announce_title'] : '';
            $wp_rem_property_announce_description = isset($wp_rem_plugin_options['wp_rem_property_announce_description']) ? $wp_rem_plugin_options['wp_rem_property_announce_description'] : '';
            ob_start();
            if ((isset($wp_rem_property_announce_title) && $wp_rem_property_announce_title <> '') || (isset($wp_rem_property_announce_description) && $wp_rem_property_announce_description <> '')) {

            }
            $html .= ob_get_clean();
            $html .= '
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-title">
						<strong>' . wp_rem_plugin_text_srt('wp_rem_property_step_3') . '</strong>
					</div>
					<div class="property-title">
					<div class="field-holder">
						<label>' . wp_rem_plugin_text_srt('wp_rem_property_property_title') . '</label>';
            $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render(
                array(
                    'id' => 'property_title_' . $property_add_counter,
                    'cust_name' => 'wp_rem_property_title',
                    'std' => $wp_rem_property_title,
                    'desc' => '',
                    'classes' => 'wp-rem-dev-req-field',
                    'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_property_title') . '"',
                    'return' => true,
                    'force_std' => true,
                    'hint_text' => '',
                )
            );
            $html .= '
					</div>
					</div>';

            $display_summary_content = true;
            $display_summary_content = apply_filters('wp_rem_display_property_summary_content', $display_summary_content);
            if ($display_summary_content == true) {

                $richeditor_place = wp_rem_plugin_text_srt('wp_rem_property_property_desc');

                $html .= '
				<div class="property-desc">
				<div class="field-holder">
					<label>' . wp_rem_plugin_text_srt('wp_rem_property_description') . '</label>';
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_textarea_render(
                    array(
                        'name' => '',
                        'id' => 'property_desc_' . $property_add_counter,
                        'cust_name' => 'wp_rem_property_desc',
                        'classes' => 'wp-rem-dev-req-field ad-wp-rem-editor',
                        'std' => $wp_rem_property_desc,
                        'description' => '',
                        'return' => true,
                        'wp_rem_editor' => true,
                        'wp_rem_editor_placeholder' => ($is_updating === false ? $richeditor_place : ''),
                        'force_std' => true,
                        'hint' => ''
                    )
                );
                $html .= '
				</div>
				</div>';

                $html .= '
				<div class="property-desc">
				<div class="field-holder">
					<label>' . wp_rem_plugin_text_srt('wp_rem_property_summary') . '</label>';
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_textarea_render(
                    array(
                        'name' => '',
                        'id' => 'property_summary_' . $property_add_counter,
                        'cust_name' => 'wp_rem_property_summary',
                        'classes' => 'wp-rem-dev-req-field',
                        'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_list_key_features') . '"',
                        'std' => $wp_rem_property_summary,
                        'description' => '',
                        'return' => true,
                        'force_std' => true,
                        'hint' => ''
                    )
                );
                $html .= '
				</div>
				</div>';
            }
            $html .= '
				</div>
				</div>
			</li>';

            $fields_args = array('property_id' => $get_property_id, 'fields_content' => '');
            $fields_content = apply_filters('wp_rem_property_fields_frontend', $fields_args);
            if (isset($fields_content['fields_content']) && $fields_content['fields_content'] != '') {
                $html .= $fields_content['fields_content'];
            }

            $html .= '<li id="wp-rem-proprty-tags-holder" class="wp-rem-proprty-tags-holder">' . $this->property_tags($selected_type, $get_property_id) . '</li>';

            echo force_balance_tags($html);
        }

        /**
         * User Register Fields
         * @return markup
         */
        public function user_register_fields($html = '')
        {
            global $property_add_counter, $wp_rem_form_fields_frontend;

            if (!is_user_logged_in()) {
                $html .= '
				<li id="wp-rem-dev-user-signup-' . $property_add_counter . '">
				<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>' . wp_rem_plugin_text_srt('wp_rem_property_signup_fields') . '</strong>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="field-holder">
						<label>' . wp_rem_plugin_text_srt('wp_rem_property_user_name') . '</label>';
                $wp_rem_opt_array = array(
                    'std' => '',
                    'cust_id' => 'wp_rem_property_username',
                    'cust_name' => 'wp_rem_property_username',
                    'cust_type' => 'text',
                    'classes' => 'wp-rem-dev-username wp-rem-dev-req-field',
                    'return' => true,
                    'extra_atr' => 'placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_username') . '" data-id="' . $property_add_counter . '" data-type="username"',
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '<span class="field-info wp-rem-dev-username-check"></span>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="field-holder">
						<label>' . wp_rem_plugin_text_srt('wp_rem_property_email') . '</label>';
                $wp_rem_opt_array = array(
                    'std' => '',
                    'cust_id' => 'wp_rem_property_user_email',
                    'cust_name' => 'wp_rem_property_user_email',
                    'cust_type' => 'text',
                    'classes' => 'wp-rem-dev-user-email wp-rem-dev-req-field',
                    'return' => true,
                    'extra_atr' => 'placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_email_address') . '" data-id="' . $property_add_counter . '" data-type="useremail"',
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '<span class="field-info wp-rem-dev-useremail-check"></span>
					</div>
				</div>
				</div>
				</li>';
            }
            echo force_balance_tags($html);
        }

        /**
         * Select Property Type
         * @return markup
         */
        public function select_property_type($html = '')
        {
            global $property_add_counter, $wp_rem_form_fields_frontend;

            $selected_type = '';
            $get_property_id = wp_rem_get_input('property_id', 0);
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
                $selected_type = get_post_meta($get_property_id, 'wp_rem_property_type', true);
            } else {
                $is_updating = false;
                $types_args = array('posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
                $cust_query = get_posts($types_args);
                $selected_type = isset($cust_query[0]->post_name) ? $cust_query[0]->post_name : '';
            }

            if (isset($_COOKIE['wp_rem_was_create_property']) && is_user_logged_in() && $is_updating === false) {
                $pre_cookie_val = stripslashes($_COOKIE['wp_rem_was_create_property']);
                $pre_cookie_val = json_decode($pre_cookie_val, true);
                $selected_type = isset($pre_cookie_val['type']) ? $pre_cookie_val['type'] : '';
            }

            $types_options = '';
            $types_args = array('posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
            $cust_query = get_posts($types_args);
            $types_options .= '<option value="">' . wp_rem_plugin_text_srt('wp_rem_property_select_type') . '</option>';
            if (is_array($cust_query) && sizeof($cust_query) > 0) {
                $type_counter = 1;
                foreach ($cust_query as $type_post) {
                    $option_selected = '';
                    if ($selected_type != '' && $selected_type == $type_post->post_name) {
                        $option_selected = ' selected="selected"';
                    } else if ($type_counter == 1) {

                    }
                    $types_data[$type_post->post_name] = get_the_title($type_post->ID);
                    $types_options .= '<option' . $option_selected . ' value="' . $type_post->post_name . '">' . get_the_title($type_post->ID) . '</option>' . "\n";
                    $type_counter++;
                }
            }
            $html .= '
			<li id="wp-rem-type-sec-' . $property_add_counter . '">
			<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="dashboard-title">
					<strong>' . wp_rem_plugin_text_srt('wp_rem_property_step_1') . '</strong>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="property-types-holder">
			<div class="field-holder">';

            if ($is_updating === true) {
                $wp_rem_opt_array = array(
                    'std' => $selected_type,
                    'cust_id' => '',
                    'cust_name' => 'wp_rem_property_type',
                    'cust_type' => 'hidden',
                    'classes' => '',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $wp_rem_opt_array = array(
                    'std' => $get_property_id,
                    'cust_id' => '',
                    'cust_name' => 'get_property_id',
                    'cust_type' => 'hidden',
                    'classes' => '',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
            } else {
                if (is_array($cust_query) && sizeof($cust_query) > 0) {
                    $html .= '<ul class="property-radios">';
                    foreach ($cust_query as $post_typ) {

                        $type_icon = get_post_meta($post_typ->ID, 'wp_rem_property_type_icon_image', true);
                        $type_map = get_post_meta($post_typ->ID, 'wp_rem_location_element', true);
                        $type_icon_image = '';
                        if ($type_icon == 'image') {
                            $typ_imag = get_post_meta($post_typ->ID, 'wp_rem_property_type_image', true);
                            $typ_imag = wp_get_attachment_url($typ_imag);
                            $type_icon_image = '<img src="' . $typ_imag . '" alt="">';
                        } else {
                            $type_selected_icon = get_post_meta($post_typ->ID, 'wp_rem_property_type_icon', true);
                            $type_selected_icon_group = get_post_meta($post_typ->ID, 'wp_rem_property_type_icon_group', true);
                            $type_selected_icon_group = isset($type_selected_icon_group[0]) ? $type_selected_icon_group[0] : 'default';
                            wp_enqueue_style('cs_icons_data_css_' . $type_selected_icon_group);
                            $type_icon_image = isset($type_selected_icon[0]) ? '<small><i class="' . $type_selected_icon[0] . '"></i></small>' : '';
                        }
                        $html .= '	
						<li' . ($post_typ->post_name == $selected_type ? ' class="active"' : '') . '>
							<div class="type-holder-main">';
                        $wp_rem_opt_array = array(
                            'std' => $post_typ->post_name,
                            'cust_id' => 'property-type-' . $post_typ->ID,
                            'cust_name' => 'wp_rem_property_type',
                            'cust_type' => 'radio',
                            'classes' => 'wp-rem-dev-select-type',
                            'return' => true,
                            'extra_atr' => 'data-loc="' . $type_map . '" data-id="' . $property_add_counter . '" ' . ($post_typ->post_name == $selected_type ? ' checked="checked"' : '') . '',
                        );
                        $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                        $html .= '<label for="property-type-' . $post_typ->ID . '">' . ($type_icon_image != '' ? $type_icon_image : '') . '<span>' . $post_typ->post_title . '</span></label>
								<span class="loader-holder"><img src="' . wp_rem::plugin_url() . 'assets/frontend/images/ajax-loader.gif" alt=""></span>
							</div>
						</li>';
                    }
                    $html .= '</ul>';
                }
            }

            $html .= '
			</div>
			</div>
			</div>

			</div>
			</li>';
            echo force_balance_tags($html);
        }

        /**
         * Ajax Loader
         * @return markup
         */
        public function ajax_loader($echo = true)
        {
            global $property_add_counter;
            $html = '
				<div id="wp-rem-dev-loader-' . absint($property_add_counter) . '" class="wp-rem-loader"></div>
				<div id="wp-rem-dev-act-msg-' . absint($property_add_counter) . '" class="wp-rem-loader"></div>';
            if ($echo) {
                echo force_balance_tags($html);
            } else {
                return force_balance_tags($html);
            }
        }

        /**
         * Property Price
         * @return markup
         */
        public function property_price($select_type = '', $wp_rem_id = 0)
        {
            global $property_add_counter, $wp_rem_form_fields_frontend, $wp_rem_plugin_options, $current_user;
            $company_id = wp_rem_company_id_form_user_id($current_user->ID);
            $property_type_id = 0;
            $get_property_id = wp_rem_get_input('property_id', 0);
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
            } else {
                $is_updating = false;
            }
            if ($select_type != '') {
                $property_type_post = get_posts(array('posts_per_page' => '1', 'post_type' => 'property-type', 'name' => $select_type, 'post_status' => 'publish', 'suppress_filters' => '0'));
                $property_type_id = isset($property_type_post[0]->ID) ? $property_type_post[0]->ID : 0;
            }
            $price_type = get_post_meta($property_type_id, 'wp_rem_property_type_price_type', true);
            $wp_rem_property_type_price = get_post_meta($property_type_id, 'wp_rem_property_type_price', true);
            $wp_rem_property_type_price = isset($wp_rem_property_type_price) && $wp_rem_property_type_price != '' ? $wp_rem_property_type_price : 'off';
            $html = '';
            if ($wp_rem_property_type_price == 'on') {

                $html .= '<div class="row">';
                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $html .= '<div class="dashboard-element-title">';
                $html .= '<strong>' . wp_rem_plugin_text_srt('wp_rem_property_price_details') . '<span class="sub-title">' . wp_rem_plugin_text_srt('wp_rem_property_enter_price') . '</span></strong>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $html .= '<div class="field-holder">';
                $html .= '<div class="has-icon"><i class="icon-coins"></i>';
                $wp_rem_property_price_options = get_post_meta($wp_rem_id, 'wp_rem_property_price_options', true);
                $wp_rem_property_price = get_post_meta($wp_rem_id, 'wp_rem_property_price', true);
                $wp_rem_price_type = get_post_meta($wp_rem_id, 'wp_rem_price_type', true);
                $phone_number = get_post_meta($wp_rem_id, 'wp_rem_phone_number_property', true);

                $price_type_options = array(
                    'variant_week' => wp_rem_plugin_text_srt('wp_rem_property_per_week'),
                    'variant_month' => wp_rem_plugin_text_srt('wp_rem_property_per_calendar'),
                );

                $price_type_options = apply_filters('homevillas_variant_price_options', $price_type_options);

                if ($price_type == 'fixed') {
                    $price_type_options = isset($wp_rem_plugin_options['fixed_price_opt']) ? $wp_rem_plugin_options['fixed_price_opt'] : '';
                }

                $wp_rem_opt_array = array(
                    'std' => $wp_rem_property_price_options,
                    'id' => 'property_price_options',
                    'classes' => '',
                    'extra_atr' => 'onchange="wp_rem_property_price_change_frontend(this.value)"',
                    'options' => array('none' => wp_rem_plugin_text_srt('wp_rem_property_price_options'), 'on-call' => wp_rem_plugin_text_srt('wp_rem_property_price_on_call'), 'price' => wp_rem_plugin_text_srt('wp_rem_property_property_price'),),
                    'return' => true,
                );
                wp_enqueue_script('wp-rem-property-functions');
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_select_render($wp_rem_opt_array);
                $html .= '</div>';
                $html .= '</div>';
                $html .= "
				<script>
				jQuery(document).ready(function() { 
				    var property_price_options_val = jQuery('#wp_rem_property_price_options').val();
				    if(property_price_options_val == 'on-call'){
				         jQuery('#wp_rem_phone_number_property_frontend').parents('.phone_number').show();
				    }else{
				        jQuery('#wp_rem_phone_number_property_frontend').parents('.phone_number').hide();
				    } 
				});
                                    var abs = \"asasasas\";
					function wp_rem_property_price_change_frontend(price_selection) {
						if (price_selection == 'none' || price_selection == 'on-call') {
							jQuery('#wp_rem_property_price_toggle').hide();
							jQuery('#wp_rem_property_price_type_toggle').hide();
			    				if (price_selection == 'on-call') {
			    				    
								jQuery('#wp_rem_phone_number_property_frontend').parents('.phone_number').show();
								
								/*jQuery('#wp-rem-property-oncall-number').show();*/
								/*jQuery('#wp-rem-property-oncall-number').html('<div class=\"field-holder\"><input type=\"text\" placeholder=\"'+wp_rem_property_functions_string.add_prop_phone_num+'\" class=\"wp-rem-dev-req-field\" name=\"wp_rem_property_oncall_number\"></div>');*/
							} else {
								jQuery('#wp_rem_phone_number_property_frontend').parents('.phone_number').hide();
								
								/*jQuery('#wp-rem-property-oncall-number').hide();
								jQuery('#wp-rem-property-oncall-number').html('');*/
							}
						} else {
						    jQuery('#wp_rem_phone_number_property_frontend').parents('.phone_number').hide();
							jQuery('#wp_rem_property_price_toggle').show();
                            jQuery('#wp_rem_property_price_type_toggle').show();
							/*jQuery('#wp-rem-property-oncall-number').hide();
							jQuery('#wp-rem-property-oncall-number').html('');*/
						}
					}
					jQuery(\".chosen-select, .chosen-select-no-single\").chosen();
					$(\"#wp_rem_property_price_options\").chosen({
						\"disable_search\": true
					});
                    $(\"#wp_rem_price_type\").chosen({
						\"disable_search\": true
					});
				</script>";
                $html .= '</div>';

                /* Add phone number on select dropdown value price-on-call */
                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 phone_number">';
                $html .= '<div class="field-holder">';
                $html .= '<div class="has-icon">';
                $html .= '<i class="icon-mobile2"></i>';
                $wp_rem_opt_array = array(
                    'std' => $phone_number,
                    'id' => 'phone_number_property_frontend',
                    'extra_atr' => 'placeholder="'.__('Enter Phone Number', 'wp-rem').'"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';

                $wp_rem_phone_number_property = get_post_meta($get_property_id, 'wp_rem_phone_number_property', true);


                $hide_div = '';
                if ($wp_rem_property_price_options == '' || $wp_rem_property_price_options == 'none' || $wp_rem_property_price_options == 'on-call') {
                    $hide_div = 'style="display:none;"';
                }

                $html .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="wp_rem_property_price_toggle" ' . $hide_div . '>';
                $html .= '<div class="field-holder">';
                $html .= '<div class="has-icon"><i class="icon-coins"></i>';
                $wp_rem_opt_array = array(
                    'std' => $wp_rem_property_price,
                    'id' => 'property_price',
                    'extra_atr' => 'placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_price') . '" autocomplete="off"',
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>' . "\n";
                // Price Options
                $html .= '<div id="wp_rem_property_price_type_toggle" class="col-lg-6 col-md-6 col-sm-12 col-xs-12" ' . $hide_div . '>';
                $html .= '<div class="field-holder">';
                $html .= '<div class="price-loader" style="display: none;"></div>';
                $html .= '<div class="has-icon"><i class="icon-update"></i>';

                $wp_rem_opt_array = array(
                    'std' => $wp_rem_price_type,
                    'id' => 'price_type',
                    'classes' => '',
                    'extra_atr' => '',
                    'options' => $price_type_options,
                    'return' => true,
                );
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_select_render($wp_rem_opt_array);

                $html .= "
				<script>
                    $(\"#wp_rem_price_type\").chosen({
						\"disable_search\": true
					});
				</script>";

                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>' . "\n";


                if ($is_updating === false) {
                    $html .= '<div id="wp-rem-property-calculating-price" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none;"></div>';
                }
                if ($is_updating === true && $wp_rem_price_type && $wp_rem_property_price) {
                    if ($wp_rem_price_type == 'variant_month' || $wp_rem_price_type == 'variant_week') {

                        $calc_price_m = wp_rem_calculate_price($wp_rem_property_price, 'monthly');
                        $calc_price_w = wp_rem_calculate_price($wp_rem_property_price, 'weekly');
                        $html .= '<div id="wp-rem-property-calculating-price" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                        $html .= '<span class="property-calculating-price">' . sprintf(wp_rem_plugin_text_srt('wp_rem_property_weekly_price'), '<strong>' . wp_rem_get_currency($wp_rem_property_price, true)) . '</strong></span>';
                        $html .= '<span class="property-calculating-price">' . sprintf(wp_rem_plugin_text_srt('wp_rem_property_monthly_price'), '<strong>' . wp_rem_get_currency($calc_price_m, true)) . '</strong></span>';
                        $html .= '</div>';
                    }
                }
                $phone_number = get_post_meta($company_id, 'wp_rem_phone_number', true);
                if ($phone_number != '') {
                    $html .= '<div id="wp-rem-property-oncall-number" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display: none;"></div>';
                }
                $html .= '</div>';
            }

            return $html;
        }

        public function property_price_calculating()
        {
            $price = isset($_POST['price']) ? $_POST['price'] : '';
            $price_type = isset($_POST['price_type']) ? $_POST['price_type'] : '';

            $calc_price_m = wp_rem_calculate_price($price, 'monthly');
            $calc_price_w = wp_rem_calculate_price($price, 'weekly');

            if ($price_type == 'variant_month') {
                $ret_price = '<span class="property-calculating-price">' . sprintf(wp_rem_plugin_text_srt('wp_rem_property_weekly_price'), '<strong>' . wp_rem_get_currency($calc_price_w, true)) . '</strong></span>';
                $ret_price .= '<span class="property-calculating-price">' . sprintf(wp_rem_plugin_text_srt('wp_rem_property_monthly_price'), '<strong>' . wp_rem_get_currency($price, true)) . '</strong></span>';
            } else {
                $ret_price = '<span class="property-calculating-price">' . sprintf(wp_rem_plugin_text_srt('wp_rem_property_weekly_price'), '<strong>' . wp_rem_get_currency($price, true)) . '</strong></span>';
                $ret_price .= '<span class="property-calculating-price">' . sprintf(wp_rem_plugin_text_srt('wp_rem_property_monthly_price'), '<strong>' . wp_rem_get_currency($calc_price_m, true)) . '</strong></span>';
            }
            echo json_encode(array('price' => $ret_price));
            die;
        }

        /**
         * Property Categories
         * @return markup
         */
        public function property_categories($type_slug = '', $wp_rem_id = '')
        {
            global $property_add_counter, $wp_rem_form_fields, $wp_rem_property_meta;

            $html = '';

            $html .= '<div class="create-properties-cats">';
            $html .= $wp_rem_property_meta->property_categories($type_slug, $wp_rem_id, $backend = false);
            $html .= '</div>';
            return apply_filters('wp_rem_front_property_add_categories', $html, $type_slug, $wp_rem_id);
            // usage :: add_filter('wp_rem_front_property_add_categories', 'my_callback_function', 10, 3);
        }

        /**
         * Load wp_rem Meta Data
         * @return markup
         */
        public function wp_rem_register_user_and_property_load_cf_callback()
        {
            global $property_add_counter;
            $property_add_counter = wp_rem_get_input('property_add_counter', '');
            $property_type = wp_rem_get_input('select_type', '');
            $html = '';
            if ($property_type != '') {
                $property_type_post = get_posts(array('posts_per_page' => '1', 'post_type' => 'property-type', 'name' => "$property_type", 'post_status' => 'publish', 'suppress_filters' => '0'));
                $property_type_id = isset($property_type_post[0]->ID) ? $property_type_post[0]->ID : 0;
                $html = $this->property_categories($property_type_id, $get_property_id);
                $html .= $this->property_price($property_type_id, $get_property_id);
            }
            if (defined('DOING_AJAX') && DOING_AJAX) {
                ob_start();
                ?>
                <script type="text/javascript">
                    var propertyCategoryFilterAjax;

                    function wp_rem_load_category_models(selected_val, post_id, main_container, load_saved_value) {
                        "use strict";
                        var data_vals = '';
                        if (typeof (propertyCategoryFilterAjax) != "undefined") {
                            propertyCategoryFilterAjax.abort();
                        }
                        var wp_rem_property_category = jQuery("#wp_rem_property_category").val();
                        propertyCategoryFilterAjax = jQuery.ajax({
                            type: "POST",
                            dataType: "JSON",
                            url: wp_rem_globals.ajax_url,
                            data: data_vals + "&action=wp_rem_meta_property_categories&selected_val=" + selected_val + "&post_id=" + post_id + "&wp_rem_property_category=" + wp_rem_property_category + "&load_saved_value=" + load_saved_value,
                            success: function (response) {
                                jQuery("." + main_container).html(response.html);
                                jQuery(".chosen-select").chosen();
                            }
                        });
                    }
                </script>
                <?php
                $html = ob_get_clean() . $html;

                ob_start();
                ?>
                <script type="text/javascript">
                    (function ($) {
                        var container = $("li.wp-rem-dev-appended");
                        $(".chosen-select", container).chosen({width: "100%"});
                    })(jQuery);
                </script>
                <?php
                $html .= ob_get_clean();
                echo json_encode(array('main_html' => $html));
                wp_die();
            } else {
                echo force_balance_tags($html);
            }
        }

        /**
         * Features List
         * @return markup
         */
        public function property_features_list($type_id = '', $wp_rem_id = 0)
        {
            global $property_add_counter, $wp_rem_form_fields_frontend;

            $html = '';
            $wp_rem_property_features = get_post_meta($wp_rem_id, 'wp_rem_property_feature_list', true);
            $wp_rem_get_features = get_post_meta($type_id, 'feature_lables', true);
            $wp_rem_feature_icons = get_post_meta($type_id, 'wp_rem_feature_icon', true);
            $wp_rem_feature_icon_group = get_post_meta($type_id, 'wp_rem_feature_icon_group', true);


            $type_features_element = get_post_meta($type_id, 'wp_rem_features_element', true);

            if (is_array($wp_rem_get_features) && sizeof($wp_rem_get_features) > 0) {
                $html .= '
				<li id="wp-rem-property-features-holder" style="display: ' . ($type_features_element == 'on' ? 'block' : 'none') . ';">
				<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>' . wp_rem_plugin_text_srt('wp_rem_property_property_features') . '<span class="sub-title">' . wp_rem_plugin_text_srt('wp_rem_property_list_property_features') . '</span></strong>
						<a id="choose-all-apply-' . $property_add_counter . '" data-id="' . $property_add_counter . '" class="choose-all-apply" href="javascript:void(0);">' . wp_rem_plugin_text_srt('wp_rem_property_select_unselect') . '</a>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="row">
				<div class="field-holder">
				<ul id="features-check-list-' . $property_add_counter . '" class="checkbox-list">';
                $feature_counter = 1;
                foreach ($wp_rem_get_features as $feat_key => $features) {
                    if (isset($features) && !empty($features)) {

                        $wp_rem_feature_name = isset($features) ? $features : '';
                        $wp_rem_feature_icon = isset($wp_rem_feature_icons[$feat_key]) ? $wp_rem_feature_icons[$feat_key] : '';
                        $icon_group = isset($wp_rem_feature_icon_group[$feat_key]) ? $wp_rem_feature_icon_group[$feat_key] : '';
                        $html .= '<li class="col-lg-6 col-md-6 col-sm-12 col-xs-12">';

                        $wp_rem_opt_array = array(
                            'std' => $wp_rem_feature_name . "_icon" . $wp_rem_feature_icon . '_icon' . $icon_group,
                            'cust_id' => 'feature-list-check-' . $wp_rem_id . $feature_counter,
                            'cust_name' => 'wp_rem_property_feature[]',
                            'cust_type' => 'checkbox',
                            'classes' => '',
                            'return' => true,
                            'extra_atr' => (is_array($wp_rem_property_features) && in_array($wp_rem_feature_name . "_icon" . $wp_rem_feature_icon . '_icon' . $icon_group, $wp_rem_property_features) ? ' checked="checked"' : ''),
                        );
                        $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                        $html .= '<label for="feature-list-check-' . $wp_rem_id . $feature_counter . '">';
                        if ($wp_rem_feature_icon != '') {
                            $html .= '<i class="' . $wp_rem_feature_icon . '"></i>';
                        }
                        $html .= $wp_rem_feature_name . '</label>
						</li>';
                        $feature_counter++;
                    }
                }
                $html .= '</ul>
				</div>
				</div>
				</div>
				</div>
				</li>';
            }
            return apply_filters('wp_rem_front_property_add_features_list', $html, $type_id, $wp_rem_id);
            //add_filter('wp_rem_front_property_add_features_list', 'my_callback_function', 10, 3);
        }

        /**
         * Load Subscribed Packages
         * @return markup
         */
        public function property_user_subscribed_packages()
        {
            global $property_add_counter, $wp_rem_plugin_options, $wp_rem_form_fields_frontend;
            $html = '';
            $pkg_options = '';
            $wp_rem_currency_sign = isset($wp_rem_plugin_options['wp_rem_currency_sign']) ? $wp_rem_plugin_options['wp_rem_currency_sign'] : '$';

            $atcive_pkgs = $this->user_all_active_pkgs();
            if (is_array($atcive_pkgs) && sizeof($atcive_pkgs) > 0) {
                $pkgs_counter = 1;
                $html .= '<div class="all-pckgs-sec">';
                foreach ($atcive_pkgs as $atcive_pkg) {

                    $package_id = get_post_meta($atcive_pkg, 'wp_rem_transaction_package', true);
                    $package_type = get_post_meta($package_id, 'wp_rem_package_type', true);

                    $package_price = get_post_meta($atcive_pkg, 'wp_rem_transaction_amount', true);
                    $package_title = $package_id != '' ? get_the_title($package_id) : '';
                    $pkg_options .= '<div class="wp-rem-pkg-holder">';
                    $pkg_options .= '<div class="wp-rem-pkg-header field-holder">';
                    $pkg_options .= '
					<div class="pkg-title-price pull-left">
						<label class="pkg-title">' . $package_title . '</label>
						<span class="pkg-price">' . sprintf(wp_rem_plugin_text_srt('wp_rem_property_price_s'), wp_rem_get_currency($package_price, true)) . '</span>
					</div>
					<div class="pkg-detail-btn pull-right">';

                    $package_image_nums = get_post_meta($atcive_pkg, 'wp_rem_transaction_property_pic_num', true);
                    $package_doc_nums = get_post_meta($atcive_pkg, 'wp_rem_transaction_property_doc_num', true);

                    $wp_rem_opt_array = array(
                        'std' => $package_id . 'pt_' . $atcive_pkg,
                        'cust_id' => 'package-' . $package_id . 'pt_' . $atcive_pkg,
                        'cust_name' => 'wp_rem_property_active_package',
                        'cust_type' => 'radio',
                        'classes' => 'wp-rem-dev-req-field',
                        'return' => true,
                        'extra_atr' => 'style="display:none;" data-picnum="' . $package_image_nums . '" data-docnum="' . $package_doc_nums . '" data-main-id="' . $property_add_counter . '" data-id="' . $package_id . 'pt_' . $atcive_pkg . '" data-ptype="purchased" data-ppric="free"',
                    );
                    $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);

                    $pkg_options .= '<a href="javascript:void(0);" class="wp-rem-dev-detail-pkg" data-id="' . $package_id . 'pt_' . $atcive_pkg . '">' . wp_rem_plugin_text_srt('wp_rem_property_detail') . '</a>
						<span class="check-select dev-property-pakcge-step" data-picnum="' . $package_image_nums . '" data-docnum="' . $package_doc_nums . '" data-main-id="' . $property_add_counter . '" data-id="' . $package_id . 'pt_' . $atcive_pkg . '" data-ptype="purchased" data-ppric="free"><i class="icon-check-circle-o"></i></span>
					</div>';
                    $pkg_options .= '</div>';
                    $pkg_options .= $this->subs_package_info($package_id, $atcive_pkg);
                    $pkg_options .= '</div>';
                    $pkgs_counter++;
                }

                $html .= $pkg_options;
                $html .= '</div>';
            }

            return apply_filters('wp_rem_property_add_subscribed_packages', $html);
        }

        /**
         * Load Packages and Payment
         * @return markup
         */
        public function property_packages()
        {
            global $wp_rem_plugin_options, $property_add_counter;

            $html = '';

            $property_up_visi = 'block';
            $property_hide_btn = 'none';

            $get_property_id = wp_rem_get_input('property_id', 0);
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $property_up_visi = 'none';
                $property_hide_btn = 'inline-block';
            }

            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $is_updating = true;
                $selected_type = get_post_meta($get_property_id, 'wp_rem_property_type', true);
                $property_type_post = get_posts(array('posts_per_page' => '1', 'post_type' => 'property-type', 'name' => "$selected_type", 'post_status' => 'publish', 'suppress_filters' => '0'));
                $selected_type_id = isset($property_type_post[0]->ID) ? $property_type_post[0]->ID : 0;
            } else {
                $is_updating = false;
                $types_args = array('posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
                $cust_query = get_posts($types_args);
                $selected_type_id = isset($cust_query[0]->ID) ? $cust_query[0]->ID : 0;
            }

            if (isset($_POST['p_property_typ']) && $_POST['p_property_typ'] != '') {
                $selected_type = $_POST['p_property_typ'];
                $property_type_post = get_posts(array('posts_per_page' => '1', 'post_type' => 'property-type', 'name' => "$selected_type", 'post_status' => 'publish', 'suppress_filters' => '0'));
                $selected_type_id = isset($property_type_post[0]->ID) ? $property_type_post[0]->ID : 0;
            }

            $show_li = false;
            $show_pgt = false;

            $wp_rem_free_properties_switch = isset($wp_rem_plugin_options['wp_rem_free_properties_switch']) ? $wp_rem_plugin_options['wp_rem_free_properties_switch'] : '';
            $wp_rem_currency_sign = isset($wp_rem_plugin_options['wp_rem_currency_sign']) ? $wp_rem_plugin_options['wp_rem_currency_sign'] : '$';

            if ($wp_rem_free_properties_switch != 'on') {

                $all_pkgs_arr = array();
                // subscribed packages list
                $subscribed_active_pkgs = $this->property_user_subscribed_packages();

                if (isset($_GET['package_id']) && $_GET['package_id'] != '') {
                    $subscribed_active_pkgs = '';
                    $buying_pkg_id = $_GET['package_id'];
                }

                $new_pkg_btn_visibility = 'none';
                $new_pkgs_visibility = 'block';
                if ($subscribed_active_pkgs) {
                    $new_pkg_btn_visibility = 'block';
                    $new_pkgs_visibility = 'none';
                }

                if (isset($_COOKIE['wp_rem_was_create_property']) && is_user_logged_in()) {
                    $pre_cookie_val = stripslashes($_COOKIE['wp_rem_was_create_property']);
                    $pre_cookie_val = json_decode($pre_cookie_val, true);
                    $buying_pkg_id = isset($pre_cookie_val['package']) ? $pre_cookie_val['package'] : '';
                    //$subscribed_active_pkgs = '';
                }

                // Packages
                $packages_list = '';

                $cust_query = get_post_meta($selected_type_id, 'wp_rem_property_type_packages', true);
                if (empty($cust_query)) {
                    $args = array(
                        'post_type' => 'packages',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'fields' => 'ids',
                        'orderby' => 'title',
                        'order' => 'ASC',
                    );
                    $over_query = new WP_Query($args);
                    $cust_query = $over_query->posts;
                }
                if (is_array($cust_query) && sizeof($cust_query) > 0) {
                    $opts_counter = 0;
                    $packages_list_opts = '<div class="all-pckgs-sec table-responsive">';

                    $all_dyn_array = array();
                    foreach ($cust_query as $package_post) {
                        if (isset($package_post)) {
                            $dynamic_package_data = get_post_meta($package_post, 'wp_rem_package_fields', true);
                            if (is_array($dynamic_package_data) && sizeof($dynamic_package_data) > 0) {
                                foreach ($dynamic_package_data as $dynamic_data) {
                                    if (isset($dynamic_data['field_type']) && isset($dynamic_data['field_label']) && isset($dynamic_data['field_value'])) {
                                        $d_type = $dynamic_data['field_type'];
                                        $d_label = $dynamic_data['field_label'];
                                        $d_value = $dynamic_data['field_value'];
                                        $all_dyn_array[] = array('field_type' => $d_type, 'field_label' => $d_label, 'field_value' => $d_value);
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($all_dyn_array)) {
                        $all_dyn_array = $all_dyn_array;
                    }

                    foreach ($cust_query as $package_post) {
                        if (isset($package_post)) {
                            // Package Fields
                            $pakge_feature_fields = $this->property_pckage_meta_fields($package_post);
                            $show_li = true;
                            $packg_title = $package_post != '' ? get_the_title($package_post) : '';
                            $package_type = get_post_meta($package_post, 'wp_rem_package_type', true);
                            $package_price = get_post_meta($package_post, 'wp_rem_package_price', true);
                            $_package_data = get_post_meta($package_post, 'wp_rem_package_data', true);

                            $dynamic_package_data = get_post_meta($package_post, 'wp_rem_package_fields', true);

                            $package_duration = isset($_package_data['duration']['value']) ? $_package_data['duration']['value'] : 0;
                            $package_property_duration = isset($_package_data['property_duration']['value']) ? $_package_data['property_duration']['value'] : 0;
                            $package_total_properties = isset($_package_data['number_of_property_allowed']['value']) ? $_package_data['number_of_property_allowed']['value'] : 0;

                            $package_is_feature = isset($_package_data['number_of_featured_properties']['value']) ? $_package_data['number_of_featured_properties']['value'] : '';
                            $package_is_top_cat = isset($_package_data['number_of_top_cat_properties']['value']) ? $_package_data['number_of_top_cat_properties']['value'] : '';

                            $all_pkgs_arr['package_id'][] = $package_post;
                            $all_pkgs_arr['package_title'][] = $packg_title;
                            $all_pkgs_arr['package_price'][] = $package_price;
                            $all_pkgs_arr['package_type'][] = $package_type;
                            $all_pkgs_arr['package_duration'][] = $package_duration;
                            $all_pkgs_arr['total_properties'][] = $package_total_properties;
                            $all_pkgs_arr['property_duration'][] = $package_property_duration;
                            $all_pkgs_arr['featured'][] = $package_is_feature;
                            $all_pkgs_arr['top_category'][] = $package_is_top_cat;
                            foreach ($pakge_feature_fields as $pakge_feat) {
                                $all_pkgs_arr['feature_fields'][$pakge_feat['key']][] = array('title' => $pakge_feat['label'], 'value' => $pakge_feat['value']);
                            }

                            $opts_counter++;
                        }
                    }

                    $all_pkgs_d_arr = array();
                    if (is_array($all_dyn_array) && sizeof($all_dyn_array) > 0) {
                        $all_pkgs_d_contr = 0;
                        foreach ($all_dyn_array as $dynamic_data) {
                            if (isset($dynamic_data['field_type']) && isset($dynamic_data['field_label']) && isset($dynamic_data['field_value'])) {
                                $d_type = $dynamic_data['field_type'];
                                $d_label = $dynamic_data['field_label'];

                                foreach ($cust_query as $package_post) {
                                    $d_value = '';
                                    if (isset($package_post)) {
                                        $dynamic_package_data = get_post_meta($package_post, 'wp_rem_package_fields', true);
                                        if (is_array($dynamic_package_data) && sizeof($dynamic_package_data) > 0) {
                                            foreach ($dynamic_package_data as $dynamic_data2) {

                                                if (isset($dynamic_data2['field_label']) && isset($dynamic_data2['field_value']) && $dynamic_data2['field_label'] == $d_label) {
                                                    $d_value = $dynamic_data2['field_value'];
                                                }
                                            }
                                        }
                                    }
                                    $all_pkgs_d_arr[$all_pkgs_d_contr][] = array('field_type' => $d_type, 'field_label' => $d_label, 'field_value' => $d_value);
                                }
                                //
                            }
                            $all_pkgs_d_contr++;
                        }
                    }

                    if (is_array($all_pkgs_arr) && sizeof($all_pkgs_arr) > 0) {
                        $package_table = '<table class="pckgs-table">';
                        $package_table .= '<thead>';
                        $package_table .= '<tr>';
                        $package_table .= '<td>&nbsp;</td>';
                        $pakgs_size = sizeof($all_pkgs_arr['package_title']);
                        foreach ($all_pkgs_arr['package_title'] as $all_pkgs_title) {
                            $package_table .= '<td>' . $all_pkgs_title . '</td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '</thead>';
                        $package_table .= '<tbody>';
                        $package_table .= '<tr class="price-row">';
                        $package_table .= '<td><span>' . wp_rem_plugin_text_srt('wp_rem_property_price') . '</span></td>';
                        $pkgs_price_contr = 0;
                        foreach ($all_pkgs_arr['package_price'] as $all_pkgs_price) {
                            $pkgs_type = isset($all_pkgs_arr['package_type'][$pkgs_price_contr]) ? $all_pkgs_arr['package_type'][$pkgs_price_contr] : '';
                            if ($pkgs_type == 'paid') {
                                $package_table .= '<td><strong>' . wp_rem_get_currency($all_pkgs_price, true) . '</strong></td>';
                            } else {
                                $package_table .= '<td><strong>' . wp_rem_plugin_text_srt('wp_rem_property_free') . '</strong></td>';
                            }
                            $pkgs_price_contr++;
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr class="has-bg">';
                        $package_table .= '<td colspan="' . ($pakgs_size + 1) . '"><label class="pkg-inner-title">' . wp_rem_plugin_text_srt('wp_rem_property_packages') . '</td>';
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        $package_table .= '
						<td>
							<span>' . wp_rem_plugin_text_srt('wp_rem_property_package_duration') . '</span>
							<div class="info-tooltip">
								<i class="icon-info_outline"></i>
								<div class="info-content"><span>' . wp_rem_plugin_text_srt('wp_rem_property_package_expiry') . '</span></div>
							</div>
						</td>';
                        foreach ($all_pkgs_arr['package_duration'] as $all_pkgs_dur) {
                            $package_table .= '
							<td>
								<span>' . absint($all_pkgs_dur) . ' ' . wp_rem_plugin_text_srt('wp_rem_property_days') . '</span>
							</td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        $package_table .= '<td><span>' . wp_rem_plugin_text_srt('wp_rem_property_total_properties') . '</span></td>';
                        foreach ($all_pkgs_arr['total_properties'] as $all_pkgs_lists) {
                            $package_table .= '<td><span>' . absint($all_pkgs_lists) . '</span></td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr class="has-bg">';
                        $package_table .= '<td colspan="' . ($pakgs_size + 1) . '"><label class="pkg-inner-title">' . wp_rem_plugin_text_srt('wp_rem_property_property_properties') . '</td>';
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        $package_table .= '<td><span>' . wp_rem_plugin_text_srt('wp_rem_property_property_duration') . '</span></td>';
                        foreach ($all_pkgs_arr['property_duration'] as $all_pkgs_list_dur) {
                            $package_table .= '<td><span>' . absint($all_pkgs_list_dur) . ' ' . wp_rem_plugin_text_srt('wp_rem_property_days') . '</span></td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        $package_table .= '<td><span>' . wp_rem_plugin_text_srt('wp_rem_property_feature_properties') . '</span></td>';
                        foreach ($all_pkgs_arr['featured'] as $all_pkgs_feat) {
                            $package_table .= '<td>' . ($all_pkgs_feat == 'on' ? '<i class="icon-check2"></i>' : '<i class="icon-minus"></i>') . '</td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        $package_table .= '<td><span>' . wp_rem_plugin_text_srt('wp_rem_property_top_categories') . '</span></td>';
                        foreach ($all_pkgs_arr['top_category'] as $all_pkgs_top_cat) {
                            $package_table .= '<td>' . ($all_pkgs_top_cat == 'on' ? '<i class="icon-check2"></i>' : '<i class="icon-minus"></i>') . '</td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '<tr class="has-bg">';
                        $package_table .= '<td colspan="' . ($pakgs_size + 1) . '"><label class="pkg-inner-title">' . wp_rem_plugin_text_srt('wp_rem_property_features') . '</td>';
                        $package_table .= '</tr>';
                        $package_table .= '<tr>';
                        // var_dump($all_pkgs_arr['feature_fields']);
                        foreach ($all_pkgs_arr['feature_fields'] as $all_pkgs_fields) {
                            $package_table .= '<tr>';
                            $pckg_field_contr = 0;
                            foreach ($all_pkgs_fields as $pckg_field) {
                                if ($pckg_field_contr == 0) {
                                    $package_table .= '<td><span>' . $pckg_field['title'] . '</span></td>';
                                }
                                if ($pckg_field['value'] == 'on') {
                                    $package_table .= '<td><i class="icon-check2"></i></td>';
                                } else if ($pckg_field['value'] != '' && $pckg_field['value'] != 'on' && $pckg_field['value'] != 'off') {
                                    $package_table .= '<td><span>' . $pckg_field['value'] . '</span></td>';
                                } else {
                                    $package_table .= '<td><i class="icon-minus"></i></td>';
                                }
                                $pckg_field_contr++;
                            }
                            $package_table .= '</tr>';
                        }
                        if (is_array($all_pkgs_d_arr) && sizeof($all_pkgs_d_arr) > 0) {

                            foreach ($all_pkgs_d_arr as $dynamic_data_d) {
                                $pckg_field_contr = 0;
                                $package_table .= '<tr>';
                                foreach ($dynamic_data_d as $dyna_data_d) {
                                    if (isset($dyna_data_d['field_type']) && isset($dyna_data_d['field_label']) && isset($dyna_data_d['field_value'])) {
                                        $d_type = $dyna_data_d['field_type'];
                                        $d_label = $dyna_data_d['field_label'];
                                        $d_value = $dyna_data_d['field_value'];

                                        if ($pckg_field_contr == 0) {
                                            $package_table .= '<td><span>' . $d_label . '</span></td>';
                                        }

                                        if ($d_value == 'on' && $d_type == 'single-choice') {
                                            $package_table .= '<td><span><i class="icon-check2"></i></span></td>';
                                        } else if ($d_value != '' && $d_type != 'single-choice') {
                                            $package_table .= '<td><span>' . $d_value . '</span></td>';
                                        } else {
                                            $package_table .= '<td><span><i class="icon-minus"></i></span></td>';
                                        }
                                        $pckg_field_contr++;
                                    }
                                }
                                $package_table .= '</tr>';
                            }
                        }
                        $package_table .= '</tbody>';
                        $package_table .= '<tfoot>';
                        $package_table .= '<tr>';
                        $package_table .= '<td>&nbsp;</td>';
                        foreach ($all_pkgs_arr['package_id'] as $all_pkgs_id) {
                            $package_type = get_post_meta($all_pkgs_id, 'wp_rem_package_type', true);

                            $package_data_all = get_post_meta($all_pkgs_id, 'wp_rem_package_data', true);
                            $package_image_nums = isset($package_data_all['number_of_pictures']['value']) ? $package_data_all['number_of_pictures']['value'] : 0;
                            $package_doc_nums = isset($package_data_all['number_of_documents']['value']) ? $package_data_all['number_of_documents']['value'] : 0;
                            $package_table .= '
							<td>
								<input type="radio" class="table-pckges" id="package-' . $all_pkgs_id . '" style="display: none;" name="wp_rem_property_package"' . (isset($buying_pkg_id) && $buying_pkg_id == $all_pkgs_id ? ' checked="checked"' : '') . ' value="' . $all_pkgs_id . '" >
								<a href="javascript:void(0)" class="property-pkg-select ' . (is_user_logged_in() ? 'dev-property-pakcge-step' : 'dev-property-pakcge-login-step') . '" data-picnum="' . $package_image_nums . '" data-docnum="' . $package_doc_nums . '" data-main-id="' . $property_add_counter . '" data-id="' . $all_pkgs_id . '" data-ptype="buy" data-ppric="' . $package_type . '">' . wp_rem_plugin_text_srt('wp_rem_property_select') . '</a>
								<span id="pkg-selected-' . $all_pkgs_id . '" class="pkg-selected" style="display: ' . (isset($buying_pkg_id) && $buying_pkg_id == $all_pkgs_id ? 'block' : 'none') . ';"><i class="icon-check_circle"></i></span>
							</td>';
                        }
                        $package_table .= '</tr>';
                        $package_table .= '</tfoot>';
                        $package_table .= '</table>';
                    }

                    $packages_list_opts .= $package_table;

                    $packages_list_opts .= '</div>';

                    $packages_list .= '<div class="packages-main-holder">';

                    if ($subscribed_active_pkgs) {
                        $packages_list .= '
						<div id="purchased-package-head-' . $property_add_counter . '" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
							<div class="dashboard-element-title">
								<strong>' . wp_rem_plugin_text_srt('wp_rem_property_purchased_packages') . '</strong>
							</div>
						</div>';
                    }

                    $pckage_title = get_the_title($selected_type_id);
                    if ($pckage_title != '') {
                        $buy_title = sprintf(wp_rem_plugin_text_srt('wp_rem_property_s_price'), $pckage_title);
                    } else {
                        $buy_title = wp_rem_plugin_text_srt('wp_rem_property_buy_package');
                    }
                    $packages_list .= '
					<div id="buy-package-head-' . $property_add_counter . '" style="display:' . $new_pkgs_visibility . ';" class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
						<div class="dashboard-element-title">
							<strong>' . $buy_title . '</strong>
						</div>
					</div>';
                    if (!is_user_logged_in()) {
                        $packages_list .= '<input type="checkbox" checked="checked" style="display:none;" name="wp_rem_property_new_package_used">';
                    }
                    if (true === Wp_rem_Member_Permissions::check_permissions('packages')) {
                        if ($subscribed_active_pkgs) {
                            $packages_list .= '
							<div class="buy-new-pakg-actions">
								<input type="checkbox" style="display:none;" id="wp-rem-dev-new-pkg-checkbox-' . $property_add_counter . '" name="wp_rem_property_new_package_used">
								<label for="new-pkg-btn-' . $property_add_counter . '">
									<a id="wp-rem-dev-new-pkg-btn-' . $property_add_counter . '" class="dir-switch-packges-btn" data-id="' . $property_add_counter . '" href="javascript:void(0);">' . wp_rem_plugin_text_srt('wp_rem_property_buy_new_package') . '</a>
								</label>
								<a data-id="' . $property_add_counter . '" style="display:' . $property_hide_btn . ';" href="javascript:void(0);" class="wp-rem-dev-cancel-pkg"><i class="icon-cross"></i></a>
							</div>';
                        } else {
                            $packages_list .= '<input type="checkbox" checked="checked" style="display:none;" name="wp_rem_property_new_package_used">';
                            $packages_list .= '
							<div class="buy-new-pakg-actions" style="display:' . $property_hide_btn . ';">
								<a data-id="' . $property_add_counter . '" href="javascript:void(0);" class="wp-rem-dev-cancel-pkg"><i class="icon-cross"></i></a>
							</div>';
                        }
                    }

                    $packages_list .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                    if ($subscribed_active_pkgs) {
                        $packages_list .= '<div id="purchased-packages-' . $property_add_counter . '" class="dir-purchased-packages">' . $subscribed_active_pkgs . '</div>';
                    }
                    $packages_list .= '<div id="new-packages-' . $property_add_counter . '" style="display:' . $new_pkgs_visibility . ';" class="dir-new-packages">' . $packages_list_opts . '</div>';
                    $packages_list .= '</div>';
                    $packages_list .= '</div>';
                }
            }

            if ($show_li) {
                $html .= '
				<li id="property-packages-sec-' . $property_add_counter . '" style="display: ' . $property_up_visi . ';">
					<div class="row">
						' . $packages_list . '
					</div>
				</li>';
            }
            if (isset($_POST['p_property_typ']) && $_POST['p_property_typ'] != '') {
                return $html;
            } else {
                echo force_balance_tags($html);
            }
        }

        /**
         * Property Floor_plans
         * @return markup
         */
        public function property_floor_plans($type_id = '', $wp_rem_id = '')
        {
            global $property_add_counter, $wp_rem_plugin_options, $wp_rem_form_fields_frontend;
            $currency_sign = isset($wp_rem_plugin_options['wp_rem_currency_sign']) ? $wp_rem_plugin_options['wp_rem_currency_sign'] : '$';
            $html = '';
            $wp_rem_property_floor_plans = get_post_meta($type_id, 'wp_rem_floor_plans_options_element', true);
            $type_appartments = get_post_meta($type_id, 'wp_rem_appartments_options_element', true);
            $rand_id = rand(100000000, 999999999);
            $floor_plans_list = '';

            $get_property_floor_plans = get_post_meta($wp_rem_id, 'wp_rem_floor_plans', true);
            if (is_array($get_property_floor_plans) && sizeof($get_property_floor_plans) > 0) {
                foreach ($get_property_floor_plans as $img_item) {
                    $file_attachm = isset($img_item['floor_plan_image']) ? $img_item['floor_plan_image'] : '';
                    $floor_plan_title = isset($img_item['floor_plan_title']) ? $img_item['floor_plan_title'] : '';
                    $floor_plan_desc = isset($img_item['floor_plan_description']) ? $img_item['floor_plan_description'] : '';

                    $img_url_arr = wp_get_attachment_image_src($file_attachm, 'wp_rem_media_3');
                    $img_url = isset($img_url_arr[0]) ? $img_url_arr[0] : '';
                    $rand_img_id = rand(100000000, 999999999);
                    ob_start();
                    ?>
                    <div class="modal fade modal-form" id="add-floor-image-data-<?php echo esc_html($rand_img_id); ?>"
                         tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-faq" data-dismiss="modal"
                                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"
                                        id="faqModalLabel"><?php echo wp_rem_plugin_text_srt('wp_rem_edit_details'); ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder">
                                                <?php
                                                $wp_rem_opt_array = array(
                                                    'id' => 'property_floor_plan_title',
                                                    'cust_name' => 'wp_rem_property_floor_plan_title[]',
                                                    'classes' => 'form-control',
                                                    'std' => $floor_plan_title,
                                                    'extra_atr' => 'placeholder="' . wp_rem_plugin_text_srt('wp_rem_title') . ' *"',
                                                );
                                                $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder">
                                                <?php
                                                $wp_rem_opt_array = array(
                                                    'id' => 'wp_rem_property_floor_plan_desc',
                                                    'cust_name' => 'wp_rem_property_floor_plan_desc[]',
                                                    'classes' => 'form-control',
                                                    'std' => $floor_plan_desc,
                                                    'extra_atr' => 'placeholder="' . wp_rem_plugin_text_srt('wp_rem_floor_description') . ' *"',
                                                );
                                                $wp_rem_form_fields_frontend->wp_rem_form_textarea_render($wp_rem_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder faq-request-holder input-button-loader">
                                                <?php
                                                $wp_rem_opt_array = array(
                                                    'std' => wp_rem_plugin_text_srt('wp_rem_edit_details_update'),
                                                    'id' => 'add_floor_plan_data',
                                                    'cust_name' => 'add_floor_plan_data',
                                                    'return' => false,
                                                    'classes' => 'bgcolor wp_rem_add_floor_plan_data',
                                                    'cust_type' => 'button',
                                                    'extra_atr' => 'data-id="' . $rand_img_id . '"',
                                                    'force_std' => true,
                                                );
                                                $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $poup_html = ob_get_clean();


                    $floor_plans_list .= '
					<li class="gal-img">
						<div class="drag-list">
							<div class="item-thumb">
                                                        <a data-target="#add-floor-image-data-' . $rand_img_id . '" data-toggle="modal" class="edit-floor-data-btn edit-btn-link" href="javascript:void(0);"><i class="icon-mode_edit"></i></a>
                                                        <img class="thumbnail" src="' . $img_url . '" alt=""/>
                                                            <div class="add-floor-data-link-' . $rand_img_id . ' block-popup-data">' . $floor_plan_title . '</div>
                                                        </div>
							<div class="item-assts">
								<div class="list-inline pull-right">
									<div class="close-btn" data-id="' . $property_add_counter . '"><a href="javascript:void(0);"><i class="icon-cross"></i></a></div>
								</div>';
                    $wp_rem_opt_array = array(
                        'std' => $file_attachm,
                        'cust_id' => '',
                        'cust_name' => 'wp_rem_property_floor_plan_image[]',
                        'cust_type' => 'hidden',
                        'classes' => '',
                        'return' => true,
                    );
                    $floor_plans_list .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                    $floor_plans_list .= '</div>
						</div>
					' . $poup_html . '</li>';
                }
            }

            $html .= '
			<li id="wp-rem-property-floor-plans-holder" class="wp-rem-dev-appended" style="display: ' . ($wp_rem_property_floor_plans == 'on' ? 'block' : 'none') . ';">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>' . wp_rem_plugin_text_srt('wp_rem_property_floor_plans') . '</strong>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="field-holder">
						<ul id="wp-rem-dev-floor-attach-sec-' . $property_add_counter . '" class="wp-rem-gallery-holder">
							' . $floor_plans_list . '
							<li class="gal-img-add">
								<div id="upload-floor-' . $property_add_counter . '" class="upload-gallery">
									<a href="javascript:void(0);" class="upload-btn wp-rem-dev-floor-upload-btn" data-id="' . $property_add_counter . '"><span><i class="icon-plus"></i> ' . wp_rem_plugin_text_srt('wp_rem_property_upload_image') . '</span></a>
								</div>
							</li>
						</ul>';
            $wp_rem_opt_array = array(
                'std' => '',
                'cust_id' => 'floor-uploader-' . $property_add_counter,
                'cust_name' => 'wp_rem_property_floor_images[]',
                'cust_type' => 'file',
                'classes' => 'wp-rem-dev-floor-uploader wp_rem_property_floor_images',
                'return' => true,
                'extra_atr' => 'style="display:none;" multiple="multiple" onchange="wp_rem_handle_floor_file_select(event, \'' . $property_add_counter . '\')"',
            );
            $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
            $html .= '</div>
				</div>
				<script>
				jQuery(document).ready(function ($) {
					$("#wp-rem-dev-floor-attach-sec-' . $property_add_counter . '").sortable({
						handle: \'.drag-list\',
						cursor: \'move\',
						items : \'.gal-img\',

					});
				});
				</script>
			</div>
			</li>';
            if (isset($type_appartments) && $type_appartments == 'on') {

                $html .= '<div class="dashboard-element-title">
						<strong>' . wp_rem_plugin_text_srt('wp_rem_features_apartment_for_sale') . '</strong>
					</div>';
                $html .= '<div id="form-elements">';

                $html .= '<div id="apartment_repeater_fields">';

                if (isset($wp_rem_apartment_data) && is_array($wp_rem_apartment_data)) {

                    foreach ($wp_rem_apartment_data as $service_data) {
                        $html .= $this->wp_rem_apartment_repeating_fields_callback($service_data);
                    }
                }

                $html .= '</div>';


                $html .= '<div class="form-elements input-element wp-rem-form-button"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><a href="javascript:void(0);" id="click-more" class="apartment_repeater_btn wp-rem-add-more cntrl-add-new-row" data-id="apartment_repeater">' . wp_rem_plugin_text_srt('wp_rem_add_appartment') . '</a></div></div>';

                $html .= '</div>';
                /////////
            }
            return apply_filters('wp_rem_front_property_add_floor_plans', $html, $type_id, $wp_rem_id);
            // usage :: add_filter('wp_rem_front_property_add_floor_plans', 'my_callback_function', 10, 3);
        }

        public function property_attachments($type_id = '', $wp_rem_id = '')
        {
            global $property_add_counter, $wp_rem_plugin_options, $wp_rem_form_fields_frontend;

            $html = '';
            $wp_rem_attachments_options = get_post_meta($type_id, 'wp_rem_attachments_options_element', true);
            $trans_all_meta = get_post_meta($wp_rem_id, 'wp_rem_trans_all_meta', true);
            $num_doc_allows = isset($trans_all_meta[1]['value']) ? $trans_all_meta[1]['value'] : 0;

            $attacment_sec_items = '';

            $allowd_attachment_extensions = get_post_meta($type_id, 'wp_rem_property_allowd_attachment_extensions', true);
            $allowd_attachment_extensions = isset($allowd_attachment_extensions) ? $allowd_attachment_extensions : '';
            if (isset($allowd_attachment_extensions) && $allowd_attachment_extensions != '') {
                $allowd_attachment_extensions = implode(',', $allowd_attachment_extensions);
            }
            $rand_id = rand(100000000, 999999999);
            $attachments_list = '';

            $get_property_attachments = get_post_meta($wp_rem_id, 'wp_rem_attachments', true);
            if (is_array($get_property_attachments) && sizeof($get_property_attachments) > 0) {
                foreach ($get_property_attachments as $img_item) {
                    $file_attachm = isset($img_item['attachment_file']) ? $img_item['attachment_file'] : '';
                    $attachment_title = isset($img_item['attachment_title']) ? $img_item['attachment_title'] : '';
                    $file_attachm_url = wp_get_attachment_url($file_attachm);
                    $dot_array = explode('.', $file_attachm_url);
                    $file_attachm_url_ext = end($dot_array);
                    $img_url = wp_rem::plugin_url() . '/assets/common/attachment-images/attach-' . $file_attachm_url_ext . '.png';


                    $rand_img_id = rand(100000000, 999999999);
                    ob_start();
                    ?>
                    <div class="modal fade modal-form" id="add-attachment-data-<?php echo esc_html($rand_img_id); ?>"
                         tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-faq" data-dismiss="modal"
                                            aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title"
                                        id="faqModalLabel"><?php echo wp_rem_plugin_text_srt('wp_rem_edit_details'); ?></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder">
                                                <?php
                                                $wp_rem_opt_array = array(
                                                    'id' => 'property_attachment_title',
                                                    'cust_name' => 'wp_rem_property_attachment_title[]',
                                                    'classes' => 'form-control',
                                                    'std' => $attachment_title,
                                                    'extra_atr' => 'placeholder="' . wp_rem_plugin_text_srt('wp_rem_title') . ' *"',
                                                );
                                                $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="field-holder faq-request-holder input-button-loader">
                                                <?php
                                                $wp_rem_opt_array = array(
                                                    'std' => wp_rem_plugin_text_srt('wp_rem_edit_details_update'),
                                                    'id' => 'add_attachment_data',
                                                    'cust_name' => 'add_attachment_data',
                                                    'return' => false,
                                                    'classes' => 'bgcolor wp_rem_add_attachment_data',
                                                    'cust_type' => 'button',
                                                    'extra_atr' => 'data-id="' . $rand_img_id . '"',
                                                    'force_std' => true,
                                                );
                                                $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $poup_html = ob_get_clean();

                    $attacment_sec_items .= '
					<li class="gal-img">
						<div class="drag-list">
							<div class="item-thumb"><a data-target="#add-attachment-data-' . $rand_img_id . '" data-toggle="modal" class="edit-attachment-btn edit-btn-link" href="javascript:void(0);"><i class="icon-mode_edit"></i></a><img class="thumbnail" src="' . $img_url . '" alt=""/>
                                                            <div class="attachment-data-link-' . $rand_img_id . ' block-popup-data">' . $attachment_title . '</div>
                                                        </div>
							<div class="item-assts">
								<div class="list-inline pull-right">
									<div class="close-btn" data-id="' . $property_add_counter . '"><a href="javascript:void(0);"><i class="icon-cross"></i></a></div>
								</div>';
                    $wp_rem_opt_array = array(
                        'std' => $file_attachm,
                        'cust_id' => '',
                        'cust_name' => 'wp_rem_property_attachment_file[]',
                        'cust_type' => 'hidden',
                        'classes' => '',
                        'return' => true,
                    );
                    $attacment_sec_items .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                    $attacment_sec_items .= '</div>
						</div>
					' . $poup_html . '</li>';
                }
            }

            $html .= '
			<li id="wp-rem-property-attachments-holder" class="wp-rem-dev-appended" style="display: ' . ($wp_rem_attachments_options == 'on' ? 'block' : 'none') . ';">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="dashboard-element-title">
						<strong>
							' . wp_rem_plugin_text_srt('wp_rem_property_file_documents') . '
							<span class="info-text">(' . sprintf(wp_rem_plugin_text_srt('wp_rem_property_enery_performance'), str_replace(',', ', ', $allowd_attachment_extensions)) . ')</span>
						</strong>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="field-holder">
						<ul id="wp-rem-dev-docs-attach-sec-' . $property_add_counter . '" class="wp-rem-gallery-holder" data-allow-ext="' . $allowd_attachment_extensions . '" data-ext-error="' . sprintf(wp_rem_plugin_text_srt('wp_rem_property_extention_error'), str_replace(',', ', ', $allowd_attachment_extensions)) . '">
							' . $attacment_sec_items . '
							<li class="gal-img-add">
								<div id="upload-attachment-' . $property_add_counter . '" class="upload-gallery">
									<a href="javascript:void(0);" class="upload-btn wp-rem-dev-attachment-upload-btn" data-id="' . $property_add_counter . '"><span><i class="icon-plus"></i> ' . wp_rem_plugin_text_srt('wp_rem_property_upload_file') . '</span></a>
								</div>
							</li>
						</ul>';
            $wp_rem_opt_array = array(
                'std' => '',
                'cust_id' => 'attachment-uploader-' . $property_add_counter,
                'cust_name' => 'wp_rem_property_attachment_images[]',
                'cust_type' => 'file',
                'classes' => 'wp-rem-dev-gallery-uploader wp_rem_property_attachment_images',
                'return' => true,
                'extra_atr' => 'style="display:none;" data-count="' . $num_doc_allows . '" multiple="multiple" onchange="wp_rem_handle_attach_file_select(event, \'' . $property_add_counter . '\')"',
            );
            $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
            $html .= '</div>
				</div>
				<script>
				jQuery(document).ready(function ($) {
					$("#wp-rem-dev-docs-attach-sec-' . $property_add_counter . '").sortable({
						handle: \'.drag-list\',
						cursor: \'move\',
						items : \'.gal-img\',
					});
				});
				</script>
			</div>
			</li>';

            return apply_filters('wp_rem_front_property_add_attachments', $html, $type_id, $wp_rem_id);
            // usage :: add_filter('wp_rem_front_property_add_attachments', 'my_callback_function', 10, 3);
        }

        /**
         * Select Homevillas Featured
         * and Top Category
         * @return markup
         */
        public function property_featured_top_cat($pckg_id = '', $trans_id = '')
        {
            global $property_add_counter;

            $html = '';
            $property_featured = '';
            $property_top_cat = '';

            $get_property_id = wp_rem_get_input('property_id', 0);
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $property_featured = get_post_meta($get_property_id, 'wp_rem_property_is_featured', true);
                $property_top_cat = get_post_meta($get_property_id, 'wp_rem_property_is_top_cat', true);
            }

            $featured_num = '';
            $top_cat_num = '';
            if ($pckg_id != '' && $trans_id == '') {
                $packg_data = get_post_meta($pckg_id, 'wp_rem_package_data', true);
                $featured_num = isset($packg_data['number_of_featured_properties']['value']) ? $packg_data['number_of_featured_properties']['value'] : '';
                $top_cat_num = isset($packg_data['number_of_top_cat_properties']['value']) ? $packg_data['number_of_top_cat_properties']['value'] : '';
            } else if ($pckg_id != '' && $trans_id != '') {
                if ($user_package = $this->get_user_package_trans($pckg_id, $trans_id)) {

                    $featured_num = get_post_meta($trans_id, 'wp_rem_transaction_property_feature_list', true);

                    $top_cat_num = get_post_meta($trans_id, 'wp_rem_transaction_property_top_cat_list', true);
                }
            }

            if ($featured_num != 'on' && $top_cat_num != 'on') {
                return apply_filters('wp_rem_property_add_featured_top_cat', $html, $pckg_id, $trans_id);
            }

            $html .= '
				</div>
			</div>';

            return apply_filters('wp_rem_property_add_featured_top_cat', $html, $pckg_id, $trans_id);
        }

        /*
	 * Opening House Section
	 */

        /*
	 * opening_house Add and append code
	 */

        public function property_opening_house($type_id = '', $wp_rem_id = '', $creat_property = '')
        {
            global $property_add_counter, $wp_rem_plugin_options, $wp_rem_form_fields_frontend;
            $html = '';

            $wp_rem_property_opening_house = get_post_meta($type_id, 'wp_rem_property_type_open_house', true);
            if ($wp_rem_property_opening_house == 'on') {
                wp_enqueue_script('jquery-ui');

                $open_house_date = get_post_meta($wp_rem_id, 'wp_rem_open_house_date', true);
                $open_house_time_from = get_post_meta($wp_rem_id, 'wp_rem_open_house_time_from', true);
                $open_house_time_to = get_post_meta($wp_rem_id, 'wp_rem_open_house_time_to', true);

                $html = '<div class="property-openhouse-field">';

                $html .= '
				<div class="dashboard-element-title">
					<strong>
						' . wp_rem_plugin_text_srt('wp_rem_property_open_house') . '
						<span class="sub-title">' . wp_rem_plugin_text_srt('wp_rem_property_scheduled_period') . '</span>
					</strong>
				</div>';

                $html .= '<div class="row">';

                $html .= '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">';
                $html .= '<div class="field-holder">'
                    . '<div class="has-icon"><i class="icon-calendar3"></i>';
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render(
                    array(
                        'id' => 'open_house_date_' . $property_add_counter,
                        'cust_name' => 'wp_rem_open_house_date',
                        'std' => $open_house_date,
                        'desc' => '',
                        'classes' => '',
                        'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_date') . '"',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
                );
                $html .= '
					</div>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							jQuery("#wp_rem_open_house_date_' . $property_add_counter . '").datetimepicker({
								timepicker:false,
								format:	"Y/m/d",
							});
						});
					</script>
				</div>';
                $html .= '</div>' . "\n";

                $html .= '<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                $html .= '<div class="field-holder">'
                    . '<div class="has-icon"><i class="icon-update"></i>';
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render(
                    array(
                        'id' => 'open_house_time_from_' . $property_add_counter,
                        'cust_name' => 'wp_rem_open_house_time_from',
                        'std' => $open_house_time_from,
                        'desc' => '',
                        'classes' => '',
                        'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_time_from') . '"',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
                );
                $html .= '
					</div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						jQuery("#wp_rem_open_house_time_from_' . $property_add_counter . '").datetimepicker({
							timepicker:true,
							datepicker:false,
							format:	"H:i",
						});
					});
				</script>
				</div>';
                $html .= '</div>' . "\n";

                $html .= '<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
                $html .= '<div class="field-holder">
						<div class="has-icon"><i class="icon-update"></i>';
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render(
                    array(
                        'id' => 'open_house_time_to_' . $property_add_counter,
                        'cust_name' => 'wp_rem_open_house_time_to',
                        'std' => $open_house_time_to,
                        'desc' => '',
                        'classes' => '',
                        'extra_atr' => ' placeholder="' . wp_rem_plugin_text_srt('wp_rem_property_time_to') . '"',
                        'return' => true,
                        'force_std' => true,
                        'hint_text' => '',
                    )
                );
                $html .= '
					</div>
				<script type="text/javascript">
				jQuery(document).ready(function($) {
					jQuery("#wp_rem_open_house_time_to_' . $property_add_counter . '").datetimepicker({
						timepicker:true,
						datepicker:false,
						format:	"H:i",
					});
				});
				</script>';
                $html .= '</div>';
                $html .= '</div>' . "\n";

                $html .= '</div></div>';
            }

            return apply_filters('wp_rem_front_property_add_opening_house', $html, $type_id, $wp_rem_id);
            // usage :: add_filter('wp_rem_front_property_add_opening_house', 'my_callback_function', 10, 3);
        }

        /**
         * Terms and Conditions
         * and Submit Button
         * @return markup
         */
        public function property_submit_button()
        {
            global $property_add_counter, $wp_rem_form_fields_frontend;
            $check_box = '';
            $get_property_id = wp_rem_get_input('property_id', 0);
            $btn_text = wp_rem_plugin_text_srt('wp_rem_property_proceed');
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $btn_text = wp_rem_plugin_text_srt('wp_rem_property_update_ad');
            } else {
                $check_box = '
					<div class="checkbox-area">';
                $wp_rem_opt_array = array(
                    'std' => '',
                    'cust_id' => 'terms-' . $property_add_counter,
                    'cust_name' => 'terms-' . $property_add_counter,
                    'cust_type' => 'checkbox',
                    'classes' => 'wp-rem-dev-req-field',
                    'return' => true,
                );
                $check_box .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                $check_box .= '<label for="terms-' . $property_add_counter . '">' . wp_rem_plugin_text_srt('wp_rem_property_terms') . '</label>
					</div>';
            }
            $html = '
				<li>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="btns-section">
								<div class="field-holder">
									<div class="payment-holder">
										' . $check_box . '
										<div class="next-btn-field">';
            $wp_rem_opt_array = array(
                'std' => $btn_text,
                'cust_id' => '',
                'cust_name' => '',
                'cust_type' => 'submit',
                'classes' => 'next-btn',
                'return' => true,
            );
            $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
            $html .= '</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>';
            echo force_balance_tags($html);
        }

        /**
         * Time List
         * @return array
         */
        public function property_time_list($type_id = '')
        {

            $lapse = 15;

            $wp_rem_opening_hours_gap = get_post_meta($type_id, 'wp_rem_opening_hours_time_gap', true);
            if (isset($wp_rem_opening_hours_gap) && $wp_rem_opening_hours_gap != '') {
                $lapse = $wp_rem_opening_hours_gap;
            }

            $date = date("Y/m/d 12:00");
            $time = strtotime('12:00 am');
            $start_time = strtotime($date . ' am');
            $endtime = strtotime(date("Y/m/d h:i a", strtotime('1440 minutes', $start_time)));

            while ($start_time < $endtime) {
                $time = date("h:i a", strtotime('+' . $lapse . ' minutes', $time));
                $hours[$time] = $time;
                $time = strtotime($time);
                $start_time = strtotime(date("Y/m/d h:i a", strtotime('+' . $lapse . ' minutes', $start_time)));
            }

            return apply_filters('wp_rem_front_property_add_time_list', $hours, $type_id);
        }

        /**
         * Week Days
         * @return array
         */
        public function property_week_days()
        {

            $week_days = array(
                'monday' => wp_rem_plugin_text_srt('wp_rem_member_add_list_monday'),
                'tuesday' => wp_rem_plugin_text_srt('wp_rem_member_add_list_tuesday'),
                'wednesday' => wp_rem_plugin_text_srt('wp_rem_member_add_list_wednesday'),
                'thursday' => wp_rem_plugin_text_srt('wp_rem_member_add_list_thursday'),
                'friday' => wp_rem_plugin_text_srt('wp_rem_member_add_list_friday'),
                'saturday' => wp_rem_plugin_text_srt('wp_rem_member_add_list_saturday'),
                'sunday' => wp_rem_plugin_text_srt('wp_rem_member_add_list_sunday')
            );

            return apply_filters('wp_rem_front_property_add_week_days', $week_days);
        }

        /**
         * Creating wp_rem property
         * @return property id
         */
        public function property_insert($member_id = '')
        {
            global $wp_rem_plugin_options, $property_add_counter;

            $wp_rem_free_properties_switch = isset($wp_rem_plugin_options['wp_rem_free_properties_switch']) ? $wp_rem_plugin_options['wp_rem_free_properties_switch'] : '';

            $property_id = 0;
            $property_title = isset($_POST['wp_rem_property_title']) ? $_POST['wp_rem_property_title'] : '';
            $property_desc = isset($_POST['wp_rem_property_desc']) ? $_POST['wp_rem_property_desc'] : '';

            $add_property = false;
            if ($property_title != '' && $property_desc != '' && $member_id != '') {
                $add_property = true;
            }
            $add_property = apply_filters('wp_rem_is_add_property_frontend', $add_property, $_POST);

            if ($add_property == true) {

                $form_rand_numb = isset($_POST['form_rand_id']) ? $_POST['form_rand_id'] : '';

                $property_post = array(
                    'post_title' => wp_strip_all_tags($property_title),
                    'post_content' => $property_desc,
                    'post_status' => 'publish',
                    'post_type' => 'properties',
                    'post_date' => current_time('Y/m/d H:i:s', 1)
                );

                //insert post
                $property_id = wp_insert_post($property_post);

                $user_data = wp_get_current_user();
                update_post_meta($property_id, 'wp_rem_property_visibility', 'public');
                do_action('wp_rem_property_add_email', $user_data, $property_id);
            }

            return apply_filters('wp_rem_front_property_add_create', $property_id);
            // usage :: add_filter('wp_rem_front_property_add_create', 'my_callback_function', 10, 1);
        }

        /**
         * Save wp_rem property
         * @return
         */
        public function property_meta_save()
        {
            global $current_user, $property_add_counter;

            // Inser Property.
            if (is_user_logged_in()) {
                $company_id = wp_rem_company_id_form_user_id($current_user->ID);
                $member_id = $company_id;
                $publish_user_id = $current_user->ID;
                $property_id = $this->property_insert($member_id);
            } else {
                $member_id = '';
                $property_id = '';
                $get_username = wp_rem_get_input('wp_rem_property_username', '', 'STRING');
                $get_useremail = wp_rem_get_input('wp_rem_property_user_email', '', 'STRING');
                $reg_array = array(
                    'username' => $get_username,
                    'display_name' => $get_username,
                    'email' => $get_useremail,
                    'profile_type' => 'company',
                    'id' => $property_add_counter,
                    'wp_rem_user_role_type' => 'member',
                    'key' => '',
                );
                if ($this->is_form_submit()) {
                    $member_data = wp_rem_registration_validation('', $reg_array);

                    if (isset($member_data['type']) && $member_data['type'] == 'error') {
                        echo '<li><div class="row">' . $member_data['msg'] . '</div></li>';
                        return;
                    } else {
                        $member_id = isset($member_data[0]) ? $member_data[0] : '';
                        $publish_user_id = isset($member_data[1]) ? $member_data[1] : '';
                        $property_id = $this->property_insert($member_id);
                    }
                }
            }

            if ($property_id != '' && $property_id != 0 && $this->is_form_submit()) {

                // saving Property posted date
                update_post_meta($property_id, 'wp_rem_property_posted', strtotime(current_time('Y/m/d H:i:s', 1)));

                // saving Property Member
                update_post_meta($property_id, 'wp_rem_property_member', $member_id);
                if (isset($publish_user_id)) {
                    update_post_meta($property_id, 'wp_rem_property_username', $publish_user_id);
                }

                // updating company id
                $company_id = get_user_meta($member_id, 'wp_rem_company', true);
                update_post_meta($property_id, 'wp_rem_property_company', $company_id);

                // saving Property Type
                $wp_rem_property_type = wp_rem_get_input('wp_rem_property_type', '');
                update_post_meta($property_id, 'wp_rem_property_type', $wp_rem_property_type);

                // price save
                $property_type_post = get_posts(array('fields' => 'ids', 'posts_per_page' => '1', 'post_type' => 'property-type', 'name' => "$wp_rem_property_type", 'post_status' => 'publish', 'suppress_filters' => '0'));
                $property_type_id = isset($property_type_post[0]) && $property_type_post[0] != '' ? $property_type_post[0] : 0;
                $wp_rem_property_type_price = get_post_meta($property_type_id, 'wp_rem_property_type_price', true);
                $wp_rem_property_type_price = isset($wp_rem_property_type_price) && $wp_rem_property_type_price != '' ? $wp_rem_property_type_price : 'off';
                $html = '';
                if ($wp_rem_property_type_price == 'on') {
                    $wp_rem_property_price_options = wp_rem_get_input('wp_rem_property_price_options', 'STRING');
                    $wp_rem_property_price = wp_rem_get_input('wp_rem_property_price', 'STRING');

                    update_post_meta($property_id, 'wp_rem_property_price_options', $wp_rem_property_price_options);
                    update_post_meta($property_id, 'wp_rem_property_price', $wp_rem_property_price);
                }
                // end price save

                $property_cats_formate = 'single';

                $wp_rem_property_category_array = wp_rem_get_input('wp_rem_property_category', '', 'ARRAY');
                if (!empty($wp_rem_property_category_array) && is_array($wp_rem_property_category_array)) {
                    foreach ($wp_rem_property_category_array as $cate_slug => $cat_val) {
                        if ($cat_val) {
                            $term = get_term_by('slug', $cat_val, 'property-category');

                            if (isset($term->term_id)) {
                                $cat_ids = array();
                                $cat_ids[] = $term->term_id;
                                $cat_slugs = $term->slug;
                                wp_set_post_terms($property_id, $cat_ids, 'property-category', FALSE);
                                //update_post_meta( $property_id, 'wp_rem_property_category', $cat_slugs );
                            }
                        }
                    }

                    update_post_meta($property_id, 'wp_rem_property_category', $wp_rem_property_category_array);
                }

                // Check Free or Paid property
                // Assign Package in case of paid
                // Assign Status of property
                $this->property_save_assignments($property_id, $member_id);
            }
        }

        public function custom_fields_features()
        {
            global $property_add_counter;
            $cus_fields_html = '';
            $main_append_html = '';
            $price_append_html = '';
            $tags_append_html = '';


            $property_id = isset($_POST['get_property_id']) ? $_POST['get_property_id'] : 0;

            $property_add_counter = isset($_POST['property_add_counter']) ? $_POST['property_add_counter'] : '';
            $select_type = isset($_POST['select_type']) ? $_POST['select_type'] : '';
            if ($select_type != '') {
                $property_type_obj = get_page_by_path($select_type, OBJECT, 'property-type');
                $property_type_id = isset($property_type_obj->ID) ? $property_type_obj->ID : 0;
            }
            $member_add_property_obj = new wp_rem_member_property_actions();
            $cus_fields_html = '<div class="property-cf-fields"><div class="row">' . $this->custom_fields($property_type_id) . '</div></div>';
            $cus_fields_html .= $this->property_opening_house($property_type_id, $property_id, 'create');
            $cats_append_html = $this->property_categories($select_type);
            $price_append_html = $this->property_price($select_type);
            $pckgs_append_html = $this->property_packages();
            $tags_append_html = $this->property_tags($select_type);
            $features_append_html = $this->property_features_list($property_type_id);
            $location_html = $member_add_property_obj->property_location($property_type_id, '');


            $type_gallery = get_post_meta($property_type_id, 'wp_rem_image_gallery_element', true);
            $type_floor_plans = get_post_meta($property_type_id, 'wp_rem_floor_plans_options_element', true);
            $type_appartments = get_post_meta($property_type_id, 'wp_rem_appartments_options_element', true);
            $type_yelp_places = get_post_meta($property_type_id, 'wp_rem_yelp_places_element', true);
            $type_attachments = get_post_meta($property_type_id, 'wp_rem_attachments_options_element', true);
            $type_video = get_post_meta($property_type_id, 'wp_rem_video_element', true);
            $type_virtual_tour = get_post_meta($property_type_id, 'wp_rem_virtual_tour_element', true);
            $type_features = get_post_meta($property_type_id, 'wp_rem_features_element', true);
            $type_faqs = get_post_meta($property_type_id, 'wp_rem_faqs_options_element', true);

            $detail_page_options = array(
                'gallery' => $type_gallery,
                'floor_plans' => $type_floor_plans,
                'appartments' => $type_appartments,
                'yelp_places' => $type_yelp_places,
                'attachments' => $type_attachments,
                'video' => $type_video,
                'virtual_tour' => $type_virtual_tour,
                'features' => $type_features,
                'faqs' => $type_faqs,
            );

            echo json_encode(array('cf_html' => $cus_fields_html, 'cats_html' => $cats_append_html, 'price_html' => $price_append_html, 'pckgs_html' => $pckgs_append_html, 'tags_html' => $tags_append_html, 'features_html' => $features_append_html, 'detail_options' => $detail_page_options, 'loc_html' => $location_html));
            die;
        }

        /**
         * Assigning Status for Property
         * @return
         */
        public function property_update_status($property_id = '')
        {
            global $wp_rem_plugin_options;
            $wp_rem_properties_review_option = isset($wp_rem_plugin_options['wp_rem_properties_review_option']) ? $wp_rem_plugin_options['wp_rem_properties_review_option'] : '';

            $user_data = wp_get_current_user();
            if ($wp_rem_properties_review_option == 'on') {
                update_post_meta($property_id, 'wp_rem_property_status', 'awaiting-activation');
                // Property not approved
                do_action('wp_rem_property_not_approved_email', $user_data, $property_id);
            } else {
                update_post_meta($property_id, 'wp_rem_property_status', 'active');
                $property_member_id = get_post_meta($property_id, 'wp_rem_property_member', true);
                if ($property_member_id != '') {
                    do_action('wp_rem_plublisher_properties_increment', $property_member_id);
                }
                // Property approved
                do_action('wp_rem_property_approved_email', $user_data, $property_id);
                // social sharing
                $get_social_reach = get_post_meta($property_id, 'wp_rem_transaction_property_social', true);
                if ($get_social_reach == 'on') {
                    $this->social_post_after_activation($property_id);
                }
            }

            $wp_rem_free_properties_switch = isset($wp_rem_plugin_options['wp_rem_free_properties_switch']) ? $wp_rem_plugin_options['wp_rem_free_properties_switch'] : '';

            if ($wp_rem_free_properties_switch != 'on') {

                $wp_rem_package_id = get_post_meta($property_id, 'wp_rem_property_package', true);
                if ($wp_rem_package_id) {
                    $wp_rem_package_data = get_post_meta($wp_rem_package_id, 'wp_rem_package_data', true);

                    $property_duration = isset($wp_rem_package_data['property_duration']['value']) ? $wp_rem_package_data['property_duration']['value'] : 0;

                    // calculating property expiry date
                    $wp_rem_trans_property_expiry = $this->date_conv($property_duration, 'days');
                    update_post_meta($property_id, 'wp_rem_property_expired', strtotime($wp_rem_trans_property_expiry));
                }
            }
        }

        /**
         * checking member own post
         * @return boolean
         */
        public function is_member_property($property_id = '')
        {
            global $current_user;
            $company_id = wp_rem_company_id_form_user_id($current_user->ID);
            $wp_rem_member_id = get_post_meta($property_id, 'wp_rem_property_member', true);
            return (is_user_logged_in() && $company_id == $wp_rem_member_id);
        }

        /**
         * checking package
         * @return boolean
         */
        public function is_package($id = '')
        {
            $package = get_post($id);
            return (isset($package->post_type) && $package->post_type == 'packages');
        }

        /**
         * Checking is form submit
         * @return boolean
         */
        public function is_form_submit()
        {
            return isset($_POST['wp_rem_property_title']);
        }

        /**
         * Get Property Content
         * @return markup
         */
        public function property_post_content($id = '')
        {

            $content = get_post($id);
            $content = $content->post_content;
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            return apply_filters('wp_rem_front_property_post_content', $content, $id);
            // usage :: add_filter('wp_rem_front_property_post_content', 'my_callback_function', 10, 2);
        }

        /**
         * Get Property Transaction id
         * @return id
         */
        public function property_trans_id($property_id = '')
        {

            $get_subscripton_data = get_post_meta($property_id, "package_subscripton_data", true);
            if (is_array($get_subscripton_data)) {
                $last_subs = end($get_subscripton_data);
                $trans_id = isset($last_subs['transaction_id']) ? $last_subs['transaction_id'] : false;
                return $trans_id;
            }
        }

        /**
         * Check Free or Paid property
         * Assign Package in case of paid
         * Assign Status of property
         * @return
         */
        public function property_save_assignments($property_id = '', $member_id = '')
        {
            global $wp_rem_plugin_options;
            $wp_rem_free_properties_switch = isset($wp_rem_plugin_options['wp_rem_free_properties_switch']) ? $wp_rem_plugin_options['wp_rem_free_properties_switch'] : '';
            $wp_rem_property_default_expiry = isset($wp_rem_plugin_options['wp_rem_property_default_expiry']) ? $wp_rem_plugin_options['wp_rem_property_default_expiry'] : '';

            if ($wp_rem_free_properties_switch == 'on') {
                // Free Posting without any Package
                // Assign expire date
                $wp_rem_ins_exp = strtotime(current_time('Y/m/d H:i:s', 1));
                if ($wp_rem_property_default_expiry != '' && is_numeric($wp_rem_property_default_expiry) && $wp_rem_property_default_expiry > 0) {
                    $wp_rem_ins_exp = $this->date_conv($wp_rem_property_default_expiry, 'days');
                }
                update_post_meta($property_id, 'wp_rem_property_expired', strtotime($wp_rem_ins_exp));

                // Assign without package true
                update_post_meta($property_id, 'wp_rem_property_without_package', '1');

                // Assign Status of property
                $this->property_update_status($property_id);

                $response['status'] = true;
                $response['msg'] = wp_rem_plugin_text_srt('wp_rem_property_property_added');
                return $response;
            } else {
                $new_pkg_check = wp_rem_get_input('wp_rem_property_new_package_used', '');

                if ($new_pkg_check == 'on') {

                    $package_id = wp_rem_get_input('wp_rem_property_package', 0);
                    if ($this->is_package($package_id)) {
                        // package subscribe
                        // add transaction
                        $transaction_detail = $this->wp_rem_property_add_transaction('add-property', $property_id, $package_id, $member_id);
                        $response['status'] = true;
                        $response['msg'] = $transaction_detail;
                        return $response;
                    }
                    // end of using new package
                } else {
                    $active_package_key = wp_rem_get_input('wp_rem_property_active_package', 0);
                    $active_package_key = explode('pt_', $active_package_key);
                    $active_pckg_id = isset($active_package_key[0]) ? $active_package_key[0] : '';
                    $active_pckg_trans_id = isset($active_package_key[1]) ? $active_package_key[1] : '';
                    if ($this->is_package($active_pckg_id)) {
                        $t_package_feature_list = get_post_meta($active_pckg_trans_id, 'wp_rem_transaction_property_feature_list', true);
                        $t_package_top_cat_list = get_post_meta($active_pckg_trans_id, 'wp_rem_transaction_property_top_cat_list', true);

                        // if package subscribe
                        if ($this->wp_rem_is_pkg_subscribed($active_pckg_id, $active_pckg_trans_id)) {
                            // Get Transaction Properties array
                            // Merge new Property in Array
                            $get_trans_properties = get_post_meta($active_pckg_trans_id, "wp_rem_property_ids", true);
                            $updated_trans_properties = $this->merge_in_array($get_trans_properties, $property_id);
                            update_post_meta($active_pckg_trans_id, "wp_rem_property_ids", $updated_trans_properties);

                            $active_pckg_trans_title = $active_pckg_trans_id != '' ? str_replace('#', '', get_the_title($active_pckg_trans_id)) : '';
                            // updating package id in property
                            update_post_meta($property_id, "wp_rem_property_package", $active_pckg_id);

                            // updating transaction title id in property
                            update_post_meta($property_id, "wp_rem_trans_id", $active_pckg_trans_title);

                            // update property subscription renew
                            $get_subscripton_data = get_post_meta($property_id, "package_subscripton_data", true);
                            if (empty($get_subscripton_data)) {
                                $package_subscripton_data = array(
                                    array(
                                        'type' => 'update_package',
                                        'transaction_id' => $active_pckg_trans_id,
                                        'title_id' => $active_pckg_trans_title,
                                        'package_id' => $active_pckg_id,
                                        'subscribe_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                                    )
                                );
                            } else {
                                $package_subscripton_data = array(
                                    'type' => 'update_package',
                                    'transaction_id' => $active_pckg_trans_id,
                                    'title_id' => $active_pckg_trans_title,
                                    'package_id' => $active_pckg_id,
                                    'renew_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                                );
                            }
                            $merged_subscripton_data = $this->merge_in_array($get_subscripton_data, $package_subscripton_data, false);
                            update_post_meta($property_id, "package_subscripton_data", $merged_subscripton_data);

                            // update property featured
                            if ($t_package_feature_list == 'on') {
                                // featured from form
                                $get_property_featured = wp_rem_get_input('wp_rem_property_featured', '');
                                // featured from meta
                                $db_property_featured = get_post_meta($property_id, "wp_rem_property_is_featured", true);

                                if ($db_property_featured != 'on') {
                                    update_post_meta($property_id, "wp_rem_property_is_featured", 'on');
                                }
                            } else {
                                update_post_meta($property_id, "wp_rem_property_is_featured", '');
                            }

                            // update property top category
                            if ($t_package_top_cat_list == 'on') {
                                // Top Cat from form
                                $get_property_top_cat = wp_rem_get_input('wp_rem_property_top_cat', '');
                                // Top Cat from meta
                                $db_property_top_cat = get_post_meta($property_id, "wp_rem_property_is_top_cat", true);

                                if ($db_property_top_cat != 'on') {
                                    update_post_meta($property_id, "wp_rem_property_is_top_cat", 'on');
                                }
                            } else {
                                update_post_meta($property_id, "wp_rem_property_is_top_cat", '');
                            }
                            // updating property meta
                            // as per transaction meta
                            // do_action('wp_rem_property_assign_trans_meta', $property_id, $active_pckg_trans_id);
                            $this->property_assign_meta($property_id, $active_pckg_trans_id);

                            // Assign Status of property
                            $this->property_update_status($property_id);
                        }
                    }
                    // end of using existing package
                }
                // end assigning packages
                // and payment processs
                $response['status'] = true;
            }

            return $response;
        }

        public function wp_rem_payment_gateways_package_selected_callback()
        {
            $response = array(
                'status' => false,
                'msg' => wp_rem_plugin_text_srt('wp_rem_property_payment_error'),
            );
            $buy_order_action = wp_rem_get_input('wp_rem_buy_order_flag', 0);

            $get_trans_id = wp_rem_get_input('trans_id', 0);
            $transaction_return_url = wp_rem_get_input('transaction_return_url', site_url(), 'HTML');

            $order_type = get_post_meta($get_trans_id, 'wp_rem_order_type', true);
            $order_menu_list = get_post_meta($get_trans_id, 'menu_items_list', true);

            if ($buy_order_action == '1') {
                if (wp_rem_is_package_order($get_trans_id)) {

                    $trans_user_id = get_post_meta($get_trans_id, 'wp_rem_transaction_user', true);
                    $wp_rem_trans_pkg = get_post_meta($get_trans_id, 'wp_rem_transaction_package', true);
                    $wp_rem_trans_amount = get_post_meta($get_trans_id, 'wp_rem_transaction_amount', true);

                    $wp_rem_trans_pay_method = wp_rem_get_input('wp_rem_property_gateway', '', 'STRING');

                    $wp_rem_trans_array = array(
                        'transaction_id' => $get_trans_id, // order id
                        'transaction_user' => $trans_user_id,
                        'transaction_package' => $wp_rem_trans_pkg,
                        'transaction_amount' => $wp_rem_trans_amount,
                        'transaction_order_type' => 'package-order',
                        'transaction_pay_method' => $wp_rem_trans_pay_method,
                        'transaction_return_url' => $transaction_return_url,
                        'exit' => false,
                    );

                    ob_start();
                    $transaction_detail = wp_rem_payment_process($wp_rem_trans_array);
                    $output = ob_get_clean();

                    if (!empty($output)) {
                        $response = array(
                            'payment_gateway' => 'wooCommerce',
                            'status' => true,
                            'msg' => $output,
                        );
                        echo json_encode($response);
                        wp_die();
                    }

                    $response = array(
                        'payment_gateway' => $wp_rem_trans_pay_method,
                        'status' => true,
                        'msg' => force_balance_tags($transaction_detail),
                    );
                }
            }
            echo json_encode($response);
            wp_die();
        }

        /**
         * Adding Transaction
         * @return id
         */
        public function wp_rem_property_add_transaction($type = '', $property_id = 0, $package_id = 0, $member_id = '')
        {
            global $wp_rem_plugin_options;
            $wp_rem_vat_switch = isset($wp_rem_plugin_options['wp_rem_vat_switch']) ? $wp_rem_plugin_options['wp_rem_vat_switch'] : '';
            $wp_rem_pay_vat = isset($wp_rem_plugin_options['wp_rem_payment_vat']) ? $wp_rem_plugin_options['wp_rem_payment_vat'] : '';
            $woocommerce_enabled = isset($wp_rem_plugin_options['wp_rem_use_woocommerce_gateway']) ? $wp_rem_plugin_options['wp_rem_use_woocommerce_gateway'] : '';
            $wp_rem_trans_id = rand(10000000, 99999999);
            $transaction_detail = '';
            $transaction_post = array(
                'post_title' => '#' . $wp_rem_trans_id,
                'post_status' => 'publish',
                'post_type' => 'package-orders',
                'post_date' => current_time('Y/m/d H:i:s', 1)
            );
            //insert the transaction
            if ($member_id != '') {
                $trans_id = wp_insert_post($transaction_post);
            }

            if (isset($trans_id) && $type != '' && $trans_id > 0) {

                $packge_order_post = array(
                    'ID' => $trans_id,
                    'post_date' => current_time('Y/m/d H:i:s', 1),
                    'post_date_gmt' => current_time('Y/m/d H:i:s', 1),
                );
                wp_update_post($packge_order_post);

                $pay_process = true;

                $wp_rem_trans_pkg = '';
                $wp_rem_trans_pkg_expiry = '';
                $package_property_allowed = 0;
                $package_property_duration = 0;

                $wp_rem_trans_amount = 0;

                if ($package_id != '' && $package_id != 0) {
                    $wp_rem_trans_pkg = $package_id;

                    $wp_rem_package_data = get_post_meta($package_id, 'wp_rem_package_data', true);

                    $package_duration = isset($wp_rem_package_data['duration']['value']) ? $wp_rem_package_data['duration']['value'] : 0;
                    $package_property_duration = isset($wp_rem_package_data['property_duration']['value']) ? $wp_rem_package_data['property_duration']['value'] : 0;
                    $package_property_allowed = isset($wp_rem_package_data['number_of_property_allowed']['value']) ? $wp_rem_package_data['number_of_property_allowed']['value'] : 0;

                    $package_amount = get_post_meta($package_id, 'wp_rem_package_price', true);

                    // calculating package expiry date
                    $wp_rem_trans_pkg_expiry = $this->date_conv($package_duration, 'days');
                    $wp_rem_trans_pkg_expiry = strtotime($wp_rem_trans_pkg_expiry);

                    // calculating_amount
                    $wp_rem_trans_amount += is_numeric($package_amount) ? ($package_amount) : 0;

                    if ($woocommerce_enabled != 'on') {
                        if ($wp_rem_vat_switch == 'on' && $wp_rem_pay_vat > 0 && $wp_rem_trans_amount > 0) {
                            $wp_rem_vat_amount = $wp_rem_trans_amount * ($wp_rem_pay_vat / 100);
                            $wp_rem_trans_amount += ($wp_rem_vat_amount);
                        }
                    }

                    // transaction offer fields
                    $t_package_pic_num = isset($wp_rem_package_data['number_of_pictures']['value']) ? $wp_rem_package_data['number_of_pictures']['value'] : 0;
                    $t_package_doc_num = isset($wp_rem_package_data['number_of_documents']['value']) ? $wp_rem_package_data['number_of_documents']['value'] : 0;
                    $t_package_tags_num = isset($wp_rem_package_data['number_of_tags']['value']) ? $wp_rem_package_data['number_of_tags']['value'] : 0;
                    $t_package_feature_list = isset($wp_rem_package_data['number_of_featured_properties']['value']) ? $wp_rem_package_data['number_of_featured_properties']['value'] : '';
                    $t_package_top_cat_list = isset($wp_rem_package_data['number_of_top_cat_properties']['value']) ? $wp_rem_package_data['number_of_top_cat_properties']['value'] : '';
                    $t_package_phone = isset($wp_rem_package_data['phone_number']['value']) ? $wp_rem_package_data['phone_number']['value'] : '';
                    $t_package_website = isset($wp_rem_package_data['website_link']['value']) ? $wp_rem_package_data['website_link']['value'] : '';
                    $t_package_social = isset($wp_rem_package_data['social_impressions_reach']['value']) ? $wp_rem_package_data['social_impressions_reach']['value'] : '';
                    $t_package_reviews = isset($wp_rem_package_data['reviews']['value']) ? $wp_rem_package_data['reviews']['value'] : '';
                    $t_package_ror = isset($wp_rem_package_data['respond_to_reviews']['value']) ? $wp_rem_package_data['respond_to_reviews']['value'] : '';
                    $t_package_dynamic_values = get_post_meta($package_id, 'wp_rem_package_fields', true);
                }

                $wp_rem_trans_array = array(
                    'transaction_id' => $trans_id,
                    'transaction_user' => $member_id,
                    'transaction_package' => $wp_rem_trans_pkg,
                    'transaction_amount' => $wp_rem_trans_amount,
                    'transaction_expiry_date' => $wp_rem_trans_pkg_expiry,
                    'transaction_properties' => $package_property_allowed,
                    'transaction_property_expiry' => $package_property_duration,
                    'transaction_property_pic_num' => isset($t_package_pic_num) ? $t_package_pic_num : '',
                    'transaction_property_doc_num' => isset($t_package_doc_num) ? $t_package_doc_num : '',
                    'transaction_property_tags_num' => isset($t_package_tags_num) ? $t_package_tags_num : '',
                    'transaction_property_feature_list' => isset($t_package_feature_list) ? $t_package_feature_list : '',
                    'transaction_property_top_cat_list' => isset($t_package_top_cat_list) ? $t_package_top_cat_list : '',
                    'transaction_property_phone' => isset($t_package_phone) ? $t_package_phone : '',
                    'transaction_property_website' => isset($t_package_website) ? $t_package_website : '',
                    'transaction_property_social' => isset($t_package_social) ? $t_package_social : '',
                    'transaction_property_reviews' => isset($t_package_reviews) ? $t_package_reviews : '',
                    'transaction_property_ror' => isset($t_package_ror) ? $t_package_ror : '',
                    'transaction_dynamic' => isset($t_package_dynamic_values) ? $t_package_dynamic_values : '',
                    'transaction_ptype' => $type,
                );

                if ($package_id != '' && $package_id != 0) {
                    if ($wp_rem_trans_amount <= 0) {
                        $wp_rem_trans_array['transaction_pay_method'] = '-';
                        $wp_rem_trans_array['transaction_status'] = 'approved';
                        $pay_process = false;
                    }
                    $package_type = get_post_meta($package_id, 'wp_rem_package_type', true);
                    if ($package_type == 'free') {
                        $wp_rem_trans_array['transaction_pay_method'] = '-';
                        $wp_rem_trans_array['transaction_status'] = 'approved';
                        $pay_process = false;
                    }
                }

                if (($type == 'add-property' || $type == 'update-property') && $property_id != '' && $property_id != 0) {

                    // update property expiry, featured, top category
                    // this change will be temporary
                    update_post_meta($property_id, "wp_rem_property_expired", strtotime(current_time('Y/m/d H:i:s', 1)));
                    update_post_meta($property_id, "wp_rem_property_is_featured", '');
                    update_post_meta($property_id, "wp_rem_property_is_top_cat", '');

                    // updating property ids in transaction
                    $wp_rem_trans_array['property_ids'] = array($property_id);
                    // updating transaction id in property
                    update_post_meta($property_id, "wp_rem_trans_id", $wp_rem_trans_id);

                    // updating package id in property
                    update_post_meta($property_id, "wp_rem_property_package", $package_id);

                    // update property subscription
                    if ($type == 'add-property') {
                        $package_subscripton_data = array(
                            array(
                                'type' => ($type == 'add-property' ? 'add_package' : 'update_package'),
                                'transaction_id' => $trans_id,
                                'title_id' => $wp_rem_trans_id,
                                'package_id' => $package_id,
                                'subscribe_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                            )
                        );
                    } else {
                        $package_subscripton_data = array(
                            'type' => ($type == 'add-property' ? 'add_package' : 'update_package'),
                            'transaction_id' => $trans_id,
                            'title_id' => $wp_rem_trans_id,
                            'package_id' => $package_id,
                            'subscribe_date' => strtotime(current_time('Y/m/d H:i:s', 1)),
                        );
                    }
                    $get_subscripton_data = get_post_meta($property_id, "package_subscripton_data", true);
                    $merged_subscripton_data = $this->merge_in_array($get_subscripton_data, $package_subscripton_data, false);
                    update_post_meta($property_id, "package_subscripton_data", $merged_subscripton_data);

                    // update property featured
                    if (isset($wp_rem_package_data) && !empty($wp_rem_package_data)) {
                        // Top Cat from form
                        $get_property_featured = wp_rem_get_input('wp_rem_property_featured', '');
                        if ($t_package_feature_list == 'on') {
                            update_post_meta($property_id, "wp_rem_property_is_featured", 'on');
                            $wp_rem_trans_array['featured_ids'] = array($property_id);
                        }
                    }

                    // update property top category
                    if (isset($wp_rem_package_data) && !empty($wp_rem_package_data)) {
                        // Top Cat from form
                        $get_property_top_cat = wp_rem_get_input('wp_rem_property_top_cat', '');
                        if ($t_package_top_cat_list == 'on') {
                            update_post_meta($property_id, "wp_rem_property_is_top_cat", 'on');
                            $wp_rem_trans_array['top_cat_ids'] = array($property_id);
                        }
                    }
                }

                // update package dynamic fields in transaction
                $wp_rem_package_dynamic = get_post_meta($package_id, 'wp_rem_package_fields', true);
                $wp_rem_trans_array['transaction_dynamic'] = $wp_rem_package_dynamic;

                // updating all fields of transaction
                foreach ($wp_rem_trans_array as $trans_key => $trans_val) {
                    update_post_meta($trans_id, "wp_rem_{$trans_key}", $trans_val);
                }

                // Inserting VAT amount in array
                if (isset($wp_rem_vat_amount) && $wp_rem_vat_amount > 0) {
                    $wp_rem_trans_array['vat_amount'] = $wp_rem_vat_amount;
                }

                // Inserting random id in array
                $wp_rem_trans_array['trans_rand_id'] = $wp_rem_trans_id;

                // Inserting item id in array
                if ($property_id != '' && $property_id != 0) {
                    $wp_rem_trans_array['trans_item_id'] = $property_id;
                    update_post_meta($trans_id, "order_item_id", $property_id);
                } else {
                    $wp_rem_trans_array['trans_item_id'] = $wp_rem_trans_id;
                }

                if (($type == 'add-property' || $type == 'update-property') && $property_id != '' && $property_id != 0) {
                    // updating property meta
                    // as per transaction meta
                    $this->property_assign_meta($property_id, $trans_id);
                    if ($package_type == 'free') {
                        $this->property_update_status($property_id);
                    }
                }

                // Payment Process
                if ($pay_process) {
                    $response = array(
                        'status' => true,
                        'msg' => $trans_id,
                    );
                    echo json_encode($response);
                    wp_die();
                }
            }
            return apply_filters('wp_rem_property_add_transaction', $transaction_detail, $type, $property_id, $package_id, $member_id);
        }

        /**
         * Check user package subscription
         * @return id
         */
        public function wp_rem_is_pkg_subscribed($wp_rem_package_id = 0, $trans_id = 0)
        {
            global $post, $current_user;

            $company_id = wp_rem_company_id_form_user_id($current_user->ID);

            if ($trans_id == '') {
                $trans_id = 0;
            }
            $transaction_id = false;
            $wp_rem_current_date = strtotime(date('d-m-Y'));
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'package-orders',
                'post_status' => 'publish',
                'post__in' => array($trans_id),
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_rem_transaction_package',
                        'value' => $wp_rem_package_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_rem_transaction_user',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_rem_transaction_expiry_date',
                        'value' => $wp_rem_current_date,
                        'compare' => '>',
                    ),
                    array(
                        'key' => 'wp_rem_transaction_status',
                        'value' => 'approved',
                        'compare' => '=',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
            $wp_rem_trans_count = $custom_query->post_count;

            if ($wp_rem_trans_count > 0) {
                while ($custom_query->have_posts()) : $custom_query->the_post();
                    $wp_rem_pkg_list_num = get_post_meta($post->ID, 'wp_rem_transaction_properties', true);
                    $wp_rem_property_ids = get_post_meta($post->ID, 'wp_rem_property_ids', true);

                    if (empty($wp_rem_property_ids)) {
                        $wp_rem_property_ids_size = 0;
                    } else {
                        $wp_rem_property_ids_size = absint(sizeof($wp_rem_property_ids));
                    }
                    $wp_rem_ids_num = $wp_rem_property_ids_size;
                    if ((int)$wp_rem_ids_num < (int)$wp_rem_pkg_list_num) {
                        $wp_rem_trnasaction_id = $post->ID;
                    }
                endwhile;
                wp_reset_postdata();
            }

            if (isset($wp_rem_trnasaction_id) && $wp_rem_trnasaction_id > 0) {
                $transaction_id = $wp_rem_trnasaction_id;
            }
            return apply_filters('wp_rem_property_is_package_subscribe', $transaction_id, $wp_rem_package_id, $trans_id);
        }

        /**
         * Get all active packages of current user
         * @return array
         */
        public function user_all_active_pkgs()
        {
            global $post, $current_user;

            $company_id = wp_rem_company_id_form_user_id($current_user->ID);

            $trans_ids = array();
            $wp_rem_current_date = strtotime(date('d-m-Y'));
            $args = array(
                'posts_per_page' => "-1",
                'post_type' => 'package-orders',
                'post_status' => 'publish',
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_rem_transaction_user',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_rem_transaction_expiry_date',
                        'value' => $wp_rem_current_date,
                        'compare' => '>',
                    ),
                    array(
                        'key' => 'wp_rem_transaction_status',
                        'value' => 'approved',
                        'compare' => '=',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
            $wp_rem_trans_count = $custom_query->post_count;

            if ($wp_rem_trans_count > 0) {
                while ($custom_query->have_posts()) : $custom_query->the_post();
                    $wp_rem_pkg_list_num = get_post_meta($post->ID, 'wp_rem_transaction_properties', true);
                    $wp_rem_property_ids = get_post_meta($post->ID, 'wp_rem_property_ids', true);

                    if (empty($wp_rem_property_ids)) {
                        $wp_rem_property_ids_size = 0;
                    } else {
                        $wp_rem_property_ids_size = absint(sizeof($wp_rem_property_ids));
                    }

                    $wp_rem_ids_num = $wp_rem_property_ids_size;
                    if ((int)$wp_rem_ids_num < (int)$wp_rem_pkg_list_num) {
                        $trans_ids[] = $post->ID;
                    }
                endwhile;
                wp_reset_postdata();
            }

            return apply_filters('wp_rem_property_user_active_packages', $trans_ids);
        }

        /**
         * Get User Package Trans
         * @return id
         */
        public function get_user_package_trans($wp_rem_package_id = 0, $trans_id = 0)
        {
            global $post, $current_user;

            $company_id = wp_rem_company_id_form_user_id($current_user->ID);

            if ($trans_id == '') {
                $trans_id = 0;
            }
            $transaction_id = false;
            $args = array(
                'posts_per_page' => "1",
                'post_type' => 'package-orders',
                'post_status' => 'publish',
                'post__in' => array($trans_id),
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'wp_rem_transaction_package',
                        'value' => $wp_rem_package_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_rem_transaction_user',
                        'value' => $company_id,
                        'compare' => '=',
                    ),
                    array(
                        'key' => 'wp_rem_transaction_status',
                        'value' => 'approved',
                        'compare' => '=',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
            $wp_rem_trans_count = $custom_query->post_count;

            if ($wp_rem_trans_count > 0) {
                while ($custom_query->have_posts()) : $custom_query->the_post();
                    $wp_rem_trnasaction_id = $post->ID;
                endwhile;
                wp_reset_postdata();
            }

            if (isset($wp_rem_trnasaction_id) && $wp_rem_trnasaction_id > 0) {
                $transaction_id = $wp_rem_trnasaction_id;
            }
            return apply_filters('wp_rem_property_user_package_trans', $transaction_id, $wp_rem_package_id, $trans_id);
        }

        /**
         * field container size
         * @return class
         */
        public function field_size_class($size = '')
        {
            switch ($size) {
                case('large'):
                    $class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
                    break;
                case('medium'):
                    $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12';
                    break;
                default :
                    $class = 'col-lg-6 col-md-6 col-sm-12 col-xs-12';
                    break;
            }
            return $class;
        }

        /**
         * Custom Fields
         * @return markup
         */
        public function custom_fields($type_id = '', $wp_rem_id = '')
        {
            global $wp_rem_form_fields, $wp_rem_form_fields_frontend;
            $html = '';
            $wp_rem_cus_fields = get_post_meta($type_id, "wp_rem_property_type_cus_fields", true);
            if (is_array($wp_rem_cus_fields) && sizeof($wp_rem_cus_fields) > 0) {
                foreach ($wp_rem_cus_fields as $cus_field) {
                    $cus_type = isset($cus_field['type']) ? $cus_field['type'] : '';
                    $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                    if ($cus_font_icon != '') {
                        $cus_font_icon_group = isset($cus_field['fontawsome_icon_group']) ? $cus_field['fontawsome_icon_group'] : 'default';
                        wp_enqueue_style('cs_icons_data_css_' . $cus_font_icon_group);
                    }
                    switch ($cus_type) {
                        case('text'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-rem-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ($wp_rem_id != '') {
                                $cus_default_val = get_post_meta((int)$wp_rem_id, "$cus_meta_key", true);
                            }

                            if ($cus_meta_key != '') {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
								<label>' . esc_attr($cus_label) . '</label>';
                                if ($cus_font_icon != '') {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }
                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required,
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'return' => true,
                                );

                                if (isset($cus_field['placeholder']) && $cus_field['placeholder'] != '') {
                                    $cus_opt_array['extra_atr'] = ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                if (isset($cus_field['required']) && $cus_field['required'] == 'yes') {
                                    $cus_opt_array['classes'] = 'wp-rem-dev-req-field';
                                }
                                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($cus_opt_array);

                                if ($cus_help_txt <> '') {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ($cus_font_icon != '') {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('number'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-rem-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ($wp_rem_id != '') {
                                $cus_default_val = get_post_meta((int)$wp_rem_id, "$cus_meta_key", true);
                            }

                            if ($cus_meta_key != '') {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
								<div class="cus-num-field">
								<label>' . esc_attr($cus_label) . '</label>';
                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required,
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => isset($cus_default_val) && $cus_default_val != '' ? $cus_default_val : 0,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'return' => true,
                                );

                                if (isset($cus_field['placeholder']) && $cus_field['placeholder'] != '') {
                                    $cus_opt_array['extra_atr'] = ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                if (isset($cus_field['required']) && $cus_field['required'] == 'yes') {
                                    $cus_opt_array['classes'] = 'wp-rem-dev-req-field';
                                }

                                if ($cus_help_txt <> '') {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                $html .= '
								<div class="select-categories">
									<ul class="minimum-loading-list">
										<li>
											<div class="spinner-btn input-group spinner">
												<span><i class="' . $cus_font_icon . '"></i></span>
												' . $wp_rem_form_fields_frontend->wp_rem_form_text_render($cus_opt_array) . '
												
												<div class="input-group-btn-vertical">
													<button class="btn-decrementmin-num caret-btn btn-default " type="button"><i class="icon-minus-circle"></i></button>
													<button class="btn-incrementmin-num caret-btn btn-default" type="button"><i class="icon-plus-circle"></i></button>
												</div>
											</div>
										</li>
									</ul>
                                </div>';
                                $html .= '</div>';
                                $html .= '
								<script>
									jQuery(document).ready(function ($) {
										$("#wp_rem_' . $cus_field['meta_key'] . '").keypress(function (e) {
											if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
												return false;
											}
										});
									});
								</script>';


                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('textarea'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_rows = isset($cus_field['rows']) ? $cus_field['rows'] : '';
                            $cus_cols = isset($cus_field['cols']) ? $cus_field['cols'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-rem-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ($wp_rem_id != '') {
                                $cus_default_val = get_post_meta((int)$wp_rem_id, "$cus_meta_key", true);
                            }
                            if ($cus_meta_key != '') {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
									<label>' . esc_attr($cus_label) . '</label>';
                                if ($cus_font_icon != '') {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }

                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required,
                                    'extra_atr' => 'rows="' . $cus_rows . '" cols="' . $cus_cols . '"',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'return' => true,
                                );

                                if (isset($cus_field['required']) && $cus_field['required'] == 'yes') {
                                    $cus_opt_array['classes'] = 'wp-rem-dev-req-field';
                                }

                                $html .= $wp_rem_form_fields_frontend->wp_rem_form_textarea_render($cus_opt_array);

                                if ($cus_help_txt <> '') {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ($cus_font_icon != '') {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('dropdown'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-rem-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';

                            if ($wp_rem_id != '') {
                                $cus_default_val = get_post_meta((int)$wp_rem_id, "$cus_meta_key", true);
                            }
                            $cus_dr_name = ' name="wp_rem_cus_field[' . sanitize_html_class($cus_meta_key) . ']"';
                            $cus_dr_mult = '';
                            if (isset($cus_field['post_multi']) && $cus_field['post_multi'] == 'yes') {
                                $cus_dr_name = ' name="wp_rem_cus_field[' . sanitize_html_class($cus_meta_key) . '][]"';
                                $cus_dr_mult = ' multiple="multiple"';
                            }

                            $a_options = array();

                            $cus_options_mark = '';

                            if (isset($cus_field['options']['value']) && is_array($cus_field['options']['value']) && sizeof($cus_field['options']['value']) > 0) {
                                if (isset($cus_field['first_value']) && $cus_field['first_value'] != '') {
                                    $cus_options_mark .= '<option value="">' . $cus_field['first_value'] . '</option>';
                                }
                                $cus_opt_counter = 0;
                                foreach ($cus_field['options']['value'] as $cus_option) {

                                    if (isset($cus_field['post_multi']) && $cus_field['post_multi'] == 'yes') {

                                        $cus_checkd = '';
                                        if (is_array($cus_default_val) && in_array($cus_option, $cus_default_val)) {
                                            $cus_checkd = ' selected="selected"';
                                        }
                                    } else {
                                        $cus_checkd = $cus_option == $cus_default_val ? ' selected="selected"' : '';
                                    }

                                    $cus_opt_label = $cus_field['options']['label'][$cus_opt_counter];
                                    $cus_options_mark .= '<option value="' . $cus_option . '"' . $cus_checkd . '>' . $cus_opt_label . '</option>';
                                    $cus_opt_counter++;
                                }
                            }

                            if ($cus_meta_key != '') {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
								<label>' . esc_attr($cus_label) . '</label>';
                                if ($cus_font_icon != '') {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }

                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => 'chosen-select' . $cus_required,
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => isset($cus_field['default_value']) ? $cus_field['default_value'] : '',
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'options' => $cus_options_mark,
                                    'options_markup' => true,
                                    'cus_field' => true,
                                    'description' => '',
                                    'return' => true,
                                );

                                if (isset($cus_field['first_value']) && $cus_field['first_value'] != '') {
                                    $cus_opt_array['extra_atr'] = ' data-placeholder="' . $cus_field['first_value'] . '"';
                                }

                                if (isset($cus_field['required']) && $cus_field['required'] == 'yes') {
                                    $cus_opt_array['classes'] = 'chosen-select form-control wp-rem-dev-req-field';
                                }
                                if (isset($cus_field['post_multi']) && $cus_field['post_multi'] == 'yes') {
                                    $html .= $wp_rem_form_fields_frontend->wp_rem_custom_form_multiselect_render($cus_opt_array);
                                } else {
                                    $html .= $wp_rem_form_fields_frontend->wp_rem_form_select_render($cus_opt_array);
                                }

                                if ($cus_help_txt <> '') {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }

                                if ($cus_font_icon != '') {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('date'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-rem-dev-req-field' : '';
                            $cus_format = isset($cus_field['date_format']) ? $cus_field['date_format'] : 'd-m-Y';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ($wp_rem_id != '') {
                                $cus_default_val = get_post_meta((int)$wp_rem_id, "$cus_meta_key", true);
                            }

                            if ($cus_meta_key != '') {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
								<label>' . esc_attr($cus_label) . '</label>';
                                if ($cus_font_icon != '') {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }

                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required . ' wp-rem-date-field',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'format' => $cus_format,
                                    'return' => true,
                                );

                                if (isset($cus_field['placeholder']) && $cus_field['placeholder'] != '') {
                                    $cus_opt_array['extra_atr'] = ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                $html .= $wp_rem_form_fields_frontend->wp_rem_form_date_render($cus_opt_array);

                                if ($cus_help_txt <> '') {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ($cus_font_icon != '') {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('email'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-rem-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ($wp_rem_id != '') {
                                $cus_default_val = get_post_meta((int)$wp_rem_id, "$cus_meta_key", true);
                            }

                            if ($cus_meta_key != '') {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
								<div class="field-holder">
								<label>' . esc_attr($cus_label) . '</label>';
                                if ($cus_font_icon != '') {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }
                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required . ' wp-rem-email-field',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'return' => true,
                                );

                                if (isset($cus_field['placeholder']) && $cus_field['placeholder'] != '') {
                                    $cus_opt_array['extra_atr'] = ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($cus_opt_array);
                                if ($cus_help_txt <> '') {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ($cus_font_icon != '') {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                        case('url'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-rem-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            if ($wp_rem_id != '') {
                                $cus_default_val = get_post_meta((int)$wp_rem_id, "$cus_meta_key", true);
                            }

                            if ($cus_meta_key != '') {
                                $html .= '
								<div class="' . $this->field_size_class($cus_size) . '">
									<div class="field-holder">
									<label>' . esc_attr($cus_label) . '</label>';
                                if ($cus_font_icon != '') {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }

                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required . ' wp-rem-url-field',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'return' => true,
                                );

                                if (isset($cus_field['placeholder']) && $cus_field['placeholder'] != '') {
                                    $cus_opt_array['extra_atr'] = ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($cus_opt_array);

                                if ($cus_help_txt <> '') {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ($cus_font_icon != '') {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                                break;
                            }
                        case('range'):
                            $cus_label = isset($cus_field['label']) ? $cus_field['label'] : '';
                            $cus_meta_key = isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '';
                            $cus_default_val = isset($cus_field['default_value']) ? $cus_field['default_value'] : '';
                            $cus_required = isset($cus_field['required']) && $cus_field['required'] == 'yes' ? ' wp-rem-dev-req-field' : '';
                            $cus_help_txt = isset($cus_field['help']) ? $cus_field['help'] : '';
                            $cus_font_icon = isset($cus_field['fontawsome_icon']) ? $cus_field['fontawsome_icon'] : '';
                            $cus_size = isset($cus_field['field_size']) ? $cus_field['field_size'] : '';
                            if ($wp_rem_id != '') {
                                $cus_default_val = get_post_meta((int)$wp_rem_id, "$cus_meta_key", true);
                            }

                            if ($cus_meta_key != '') {
                                $html .= '
										<div class="' . $this->field_size_class($cus_size) . '">
											<div class="field-holder">
												<label>' . esc_attr($cus_label) . '</label>';
                                if ($cus_font_icon != '') {
                                    $html .= '<div class="has-icon"><i class="' . $cus_font_icon . '"></i>';
                                }

                                $cus_opt_array = array(
                                    'name' => isset($cus_field['label']) ? $cus_field['label'] : '',
                                    'desc' => '',
                                    'classes' => $cus_required . ' wp-rem-range-field',
                                    'hint_text' => isset($cus_field['help']) ? $cus_field['help'] : '',
                                    'std' => $cus_default_val,
                                    'id' => isset($cus_field['meta_key']) ? $cus_field['meta_key'] : '',
                                    'cus_field' => true,
                                    'extra_atr' => 'data-min="' . $cus_field['min'] . '" data-max="' . $cus_field['max'] . '"',
                                    'return' => true,
                                );

                                if (isset($cus_field['placeholder']) && $cus_field['placeholder'] != '') {
                                    $cus_opt_array['extra_atr'] .= ' placeholder="' . $cus_field['placeholder'] . '"';
                                }

                                $html .= $wp_rem_form_fields_frontend->wp_rem_form_text_render($cus_opt_array);

                                if ($cus_help_txt <> '') {
                                    $html .= '<span class="cs-caption">' . $cus_help_txt . '</span>';
                                }
                                if ($cus_font_icon != '') {
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                $html .= '</div>';
                            }
                            break;
                    }
                }
            }
            return $html;
        }

        /**
         * Load wp_rem Custom Fields
         * @return markup
         */
        public function property_custom_fields()
        {
            $get_property_id = wp_rem_get_input('property_id', 0);
            if ($get_property_id != '' && $get_property_id != 0 && $this->is_member_property($get_property_id)) {
                $property_type = get_post_meta($get_property_id, 'wp_rem_property_type', true);
                $is_updating = true;
            } else {
                $is_updating = false;
                $types_args = array('posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => '0');
                $cust_query = get_posts($types_args);
                $property_type = isset($cust_query[0]->post_name) ? $cust_query[0]->post_name : '';
            }
            if (isset($_COOKIE['wp_rem_was_create_property']) && is_user_logged_in() && $is_updating === false) {
                $pre_cookie_val = stripslashes($_COOKIE['wp_rem_was_create_property']);
                $pre_cookie_val = json_decode($pre_cookie_val, true);
                $property_type = isset($pre_cookie_val['type']) ? $pre_cookie_val['type'] : '';
            }
            if ($property_type != '') {
                $property_type_post = get_posts(array('posts_per_page' => '1', 'post_type' => 'property-type', 'name' => "$property_type", 'post_status' => 'publish', 'suppress_filters' => '0'));
                $property_type_id = isset($property_type_post[0]->ID) ? $property_type_post[0]->ID : 0;
                $html = '<div class="property-cf-fields"><div class="row">' . $this->custom_fields($property_type_id, $get_property_id) . '</div></div>';
                echo force_balance_tags($html);
            }
        }

        /**
         * Purchased Package Info Field Create
         * @return markup
         */
        public function purchase_package_info_field_show($value = '', $label = '', $value_plus = '')
        {

            if ($value != '' && $value != 'on' && $value != 'off') {
                $html = '<li><label>' . $label . '</label><span>' . $value . ' ' . $value_plus . '</span></li>';
            } else if ($value != '' && $value == 'on') {
                $html = '<li><label>' . $label . '</label><span><i class="icon-check2"></i></span></li>';
            } else {
                $html = '<li><label>' . $label . '</label><span><i class="icon-minus"></i></span></li>';
            }

            return $html;
        }

        /**
         * Get Subscribe Package info
         * @return html
         */
        public function subs_package_info($package_id = 0, $trans_id = 0, $in_update = '')
        {
            global $property_add_counter;
            $html = '';
            $inner_html = '';

            if ($user_package = $this->get_user_package_trans($package_id, $trans_id)) {
                $title_id = $user_package != '' ? get_the_title($user_package) : '';
                $trans_packg_id = get_post_meta($trans_id, 'wp_rem_transaction_package', true);
                $packg_title = $trans_packg_id != '' ? get_the_title($trans_packg_id) : '';

                $trans_packg_expiry = get_post_meta($trans_id, 'wp_rem_transaction_expiry_date', true);
                $trans_packg_list_num = get_post_meta($trans_id, 'wp_rem_transaction_properties', true);
                $trans_packg_list_expire = get_post_meta($trans_id, 'wp_rem_transaction_property_expiry', true);
                $wp_rem_property_ids = get_post_meta($trans_id, 'wp_rem_property_ids', true);

                if (empty($wp_rem_property_ids)) {
                    $wp_rem_property_used = 0;
                } else {
                    $wp_rem_property_used = absint(sizeof($wp_rem_property_ids));
                }

                $wp_rem_property_remain = '0';
                if ((int)$trans_packg_list_num > (int)$wp_rem_property_used) {
                    $wp_rem_property_remain = (int)$trans_packg_list_num - (int)$wp_rem_property_used;
                }

                $trans_featured = get_post_meta($trans_id, 'wp_rem_transaction_property_feature_list', true);

                $trans_top_cat = get_post_meta($trans_id, 'wp_rem_transaction_property_top_cat_list', true);
                $trans_pics_num = get_post_meta($trans_id, 'wp_rem_transaction_property_pic_num', true);
                $trans_docs_num = get_post_meta($trans_id, 'wp_rem_transaction_property_doc_num', true);
                $trans_tags_num = get_post_meta($trans_id, 'wp_rem_transaction_property_tags_num', true);

                $trans_phone = get_post_meta($trans_id, 'wp_rem_transaction_property_phone', true);
                $trans_website = get_post_meta($trans_id, 'wp_rem_transaction_property_website', true);
                $trans_social = get_post_meta($trans_id, 'wp_rem_transaction_property_social', true);
                $trans_reviews = get_post_meta($trans_id, 'wp_rem_transaction_property_reviews', true);
                $trans_ror = get_post_meta($trans_id, 'wp_rem_transaction_property_ror', true);
                $trans_dynamic_f = get_post_meta($trans_id, 'wp_rem_transaction_dynamic', true);

                $pkg_expire_date = date_i18n(get_option('date_format'), $trans_packg_expiry);

                if ($in_update == 'in_update') {
                    $html .= '<div class="dashboard-element-title"><strong>' . wp_rem_plugin_text_srt('wp_rem_property_package_info') . '</strong></div>';
                }

                $html .= '<div id="package-detail-' . $package_id . 'pt_' . $trans_id . '" style="display:' . ($in_update == 'in_update' ? 'block' : 'none') . ';" class="package-info-sec property-info-sec">';
                $html .= '<div class="row">';
                $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $html .= '<ul class="property-pkg-points">';

                $html .= $this->purchase_package_info_field_show($pkg_expire_date, wp_rem_plugin_text_srt('wp_rem_property_expiry_date'));
                $html .= '<li><label>' . wp_rem_plugin_text_srt('wp_rem_property_properties') . '</label><span>' . absint($wp_rem_property_used) . '/' . absint($trans_packg_list_num) . '</span></li>';
                $html .= $this->purchase_package_info_field_show($trans_packg_list_expire, wp_rem_plugin_text_srt('wp_rem_property_property_duration'), wp_rem_plugin_text_srt('wp_rem_property_days'));
                if ($trans_featured == 'on') {
                    $html .= '<li><label>' . wp_rem_plugin_text_srt('wp_rem_property_featured_properties') . '</label><span><i class="icon-check2"></i></span></li>';
                } else {
                    $html .= '<li><label>' . wp_rem_plugin_text_srt('wp_rem_property_featured_properties') . '</label><span><i class="icon-minus"></i></span></li>';
                }
                if ($trans_top_cat == 'on') {
                    $html .= '<li><label>' . wp_rem_plugin_text_srt('wp_rem_property_top_cat_properties') . '</label><span><i class="icon-check2"></i></span></li>';
                } else {
                    $html .= '<li><label>' . wp_rem_plugin_text_srt('wp_rem_property_top_cat_properties') . '</label><span><i class="icon-minus"></i></span></li>';
                }

                $html .= $this->purchase_package_info_field_show($trans_pics_num, wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_pictures'));
                $html .= $this->purchase_package_info_field_show($trans_docs_num, wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_docs'));
                $html .= $this->purchase_package_info_field_show($trans_tags_num, wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_tags'));
                $html .= $this->purchase_package_info_field_show($trans_phone, wp_rem_plugin_text_srt('wp_rem_member_add_list_phone_number'));
                $html .= $this->purchase_package_info_field_show($trans_website, wp_rem_plugin_text_srt('wp_rem_member_add_list_web_link'));
                $html .= $this->purchase_package_info_field_show($trans_social, wp_rem_plugin_text_srt('wp_rem_member_add_list_social_reach'));
                //$html .= $this->purchase_package_info_field_show($trans_reviews, wp_rem_plugin_text_srt('wp_rem_reviews_pakage_order_reviews'));
                //$html .= $this->purchase_package_info_field_show($trans_ror, wp_rem_plugin_text_srt('wp_rem_reviews_pakage_order_response_reviews'));

                $dyn_fields_html = '';
                if (is_array($trans_dynamic_f) && sizeof($trans_dynamic_f) > 0) {
                    foreach ($trans_dynamic_f as $trans_dynamic) {
                        if (isset($trans_dynamic['field_type']) && isset($trans_dynamic['field_label']) && isset($trans_dynamic['field_value'])) {
                            $d_type = $trans_dynamic['field_type'];
                            $d_label = $trans_dynamic['field_label'];
                            $d_value = $trans_dynamic['field_value'];

                            if ($d_value == 'on' && $d_type == 'single-choice') {
                                $html .= '<li><label>' . $d_label . '</label><span><i class="icon-check2"></i></span></li>';
                            } else if ($d_value != '' && $d_type != 'single-choice') {
                                $html .= '<li><label>' . $d_label . '</label><span>' . $d_value . '</span></li>';
                            } else {
                                $html .= '<li><label>' . $d_label . '</label><span><i class="icon-minus"></i></span></li>';
                            }
                        }
                    }
                    // end foreach
                }
                // emd of Dynamic fields
                // other Features
                $html .= '
				</ul>
				</div>';

                $html .= '
				</div>
				</div>';
            }

            return apply_filters('wp_rem_property_user_subs_package_info', $html, $package_id, $trans_id);
        }

        /**
         * Package Info Field Create
         * @return markup
         */
        public function package_info_field_show($info_meta = '', $index = '', $label = '', $value_plus = '', $absint = '')
        {
            if (isset($info_meta[$index]['value'])) {
                $value = $info_meta[$index]['value'];

                if (true === $absint) {
                    $value = absint($info_meta[$index]['value']);
                }
                $trans_array = array(
                    'on' => wp_rem_plugin_text_srt('wp_rem_skrill_options_on'),
                    'off' => wp_rem_plugin_text_srt('wp_rem_skrill_options_off'),
                );
                if (array_key_exists($value, $trans_array)) {
                    $trans_value = $trans_array[$value];
                    $html = '<li><label>' . $label . '</label><span>' . $trans_value . ' ' . $value_plus . '</span></li>';
                }
                if (($value == 0) || ($value != '' && $value != 'on' && $value != 'off')) {
                    $html = '<li><label>' . $label . '</label><span>' . $value . ' ' . $value_plus . '</span></li>';
                }
                if (($value == 0) || ($value != '' && $value != 'on' && $value != 'off')) {
                    //$html = '<li><label>' . $label.'1' . '</label><span>' . $trans_value . ' ' . $value_plus . '</span></li>';
                } else if ($value != '' && $value == 'on') {
                    $html = '<li><label>' . $label . '</label><span><i class="icon-check2"></i></span></li>';
                } else {
                    $html = '<li><label>' . $label . '</label><span><i class="icon-minus"></i></span></li>';
                }

                return $html;
            }
        }

        /**
         * Get New Package info
         * @return html
         */
        public function new_package_info($package_id = 0)
        {
            global $property_add_counter;
            $html = '';

            $packg_title = $package_id != '' ? get_the_title($package_id) : '';
            $trans_all_meta = get_post_meta($package_id, 'wp_rem_package_data', true);

            $html .= '<div class="dashboard-element-title"><strong>' . wp_rem_plugin_text_srt('wp_rem_property_package_info') . '</strong></div>';
            $html .= '<div id="package-detail-' . $package_id . '" class="package-info-sec property-info-sec">';
            $html .= '<div class="row">';
            $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';

            $html .= '<ul class="property-pkg-points">';


            $html .= $this->package_info_field_show($trans_all_meta, 'duration', wp_rem_plugin_text_srt('wp_rem_property_package_duration'), wp_rem_plugin_text_srt('wp_rem_property_days'), true);
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_property_allowed', wp_rem_plugin_text_srt('wp_rem_property_total_properties'), '', true);
            $html .= $this->package_info_field_show($trans_all_meta, 'property_duration', wp_rem_plugin_text_srt('wp_rem_property_property_duration'), wp_rem_plugin_text_srt('wp_rem_property_days'), true);
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_featured_properties', wp_rem_plugin_text_srt('wp_rem_property_featured_properties'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_top_cat_properties', wp_rem_plugin_text_srt('wp_rem_property_top_cat_properties'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_pictures', wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_pictures'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_documents', wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_docs'));
            $html .= $this->package_info_field_show($trans_all_meta, 'number_of_tags', wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_tags'));
            //
            $html .= $this->package_info_field_show($trans_all_meta, 'phone_number', wp_rem_plugin_text_srt('wp_rem_member_add_list_phone_number'));
            $html .= $this->package_info_field_show($trans_all_meta, 'website_link', wp_rem_plugin_text_srt('wp_rem_member_add_list_web_link'));
            $html .= $this->package_info_field_show($trans_all_meta, 'social_impressions_reach', wp_rem_plugin_text_srt('wp_rem_member_add_list_social_reach'));
            //$html .= $this->package_info_field_show($trans_all_meta, 'reviews', wp_rem_plugin_text_srt('wp_rem_reviews_pakage_order_reviews'));
            //$html .= $this->package_info_field_show($trans_all_meta, 'respond_to_reviews', wp_rem_plugin_text_srt('wp_rem_reviews_pakage_order_response_reviews'));

            $trans_dynamic_f = get_post_meta($package_id, 'wp_rem_package_fields', true);

            if (is_array($trans_dynamic_f) && sizeof($trans_dynamic_f) > 0) {
                foreach ($trans_dynamic_f as $trans_dynamic) {
                    if (isset($trans_dynamic['field_type']) && isset($trans_dynamic['field_label']) && isset($trans_dynamic['field_value'])) {
                        $d_type = $trans_dynamic['field_type'];
                        $d_label = $trans_dynamic['field_label'];
                        $d_value = $trans_dynamic['field_value'];

                        if ($d_value == 'on' && $d_type == 'single-choice') {
                            $html .= '<li><label>' . $d_label . '</label><span><i class="icon-check2"></i></span></li>';
                        } else if ($d_value != '' && $d_type != 'single-choice') {
                            $html .= '<li><label>' . $d_label . '</label><span>' . $d_value . '</span></li>';
                        } else {
                            $html .= '<li><label>' . $d_label . '</label><span><i class="icon-minus"></i></span></li>';
                        }
                    }
                }
                // end foreach
            }
            // end of Dynamic fields
            // other Features
            $html .= '
			</ul>
			</div>';

            $html .= '
			</div>
			</div>';

            return apply_filters('wp_rem_property_user_new_package_info', $html, $package_id);
        }

        public function property_gallery_upload($Fieldname = 'media_upload', $property_id = '')
        {
            $img_resized_name = '';
            $property_gallery = array();
            $count = 0;

            if (isset($_FILES[$Fieldname]) && $_FILES[$Fieldname] != '') {

                $multi_files = isset($_FILES[$Fieldname]) ? $_FILES[$Fieldname] : '';

                if (isset($multi_files['name']) && is_array($multi_files['name'])) {
                    $img_name_array = array();
                    foreach ($multi_files['name'] as $multi_key => $multi_value) {
                        if ($multi_files['name'][$multi_key]) {
                            $loop_file = array(
                                'name' => $multi_files['name'][$multi_key],
                                'type' => $multi_files['type'][$multi_key],
                                'tmp_name' => $multi_files['tmp_name'][$multi_key],
                                'error' => $multi_files['error'][$multi_key],
                                'size' => $multi_files['size'][$multi_key]
                            );

                            $json = array();
                            require_once ABSPATH . 'wp-admin/includes/image.php';
                            require_once ABSPATH . 'wp-admin/includes/file.php';
                            require_once ABSPATH . 'wp-admin/includes/media.php';
                            $allowed_image_types = array(
                                'jpg|jpeg|jpe' => 'image/jpeg',
                                'png' => 'image/png',
                                'gif' => 'image/gif',
                            );

                            $status = wp_handle_upload($loop_file, array('test_form' => false, 'mimes' => $allowed_image_types));

                            if (empty($status['error'])) {

                                $image = wp_get_image_editor($status['file']);
                                $img_resized_name = $status['file'];

                                if (is_wp_error($image)) {

                                    echo '<span class="error-msg">' . $image->get_error_message() . '</span>';
                                } else {
                                    $wp_upload_dir = wp_upload_dir();
                                    $img_name_array[] = isset($status['url']) ? $status['url'] : '';
                                    $filename = $img_name_array[$count];
                                    $filetype = wp_check_filetype(basename($filename), null);

                                    if ($filename != '') {
                                        // Prepare an array of post data for the attachment.

                                        $attachment = array(
                                            'guid' => ($filename),
                                            'post_mime_type' => $filetype['type'],
                                            'post_title' => preg_replace('/\.[^.]+$/', '', ($loop_file['name'])),
                                            'post_content' => '',
                                            'post_status' => 'inherit'
                                        );
                                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                                        // Insert the attachment.
                                        $attach_id = wp_insert_attachment($attachment, $status['file']);
                                        if ($property_id != '') {
                                            wp_update_post(
                                                array(
                                                    'ID' => $attach_id,
                                                    'post_parent' => $property_id
                                                )
                                            );
                                        }
                                        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                                        $attach_data = wp_generate_attachment_metadata($attach_id, $status['file']);
                                        wp_update_attachment_metadata($attach_id, $attach_data);
                                        $property_gallery[] = $attach_id;
                                        $count++;
                                    }
                                }
                            }
                        }
                    }

                    $img_resized_name = $property_gallery;
                } else {
                    $img_resized_name = '';
                }
            }

            return $img_resized_name;
        }

        public function property_attach_file_upload($Fieldname = 'media_upload', $property_id = '')
        {
            $img_resized_name = '';
            $property_gallery = array();
            $count = 0;

            if (isset($_FILES[$Fieldname]) && $_FILES[$Fieldname] != '') {

                $multi_files = isset($_FILES[$Fieldname]) ? $_FILES[$Fieldname] : '';

                if (isset($multi_files['name']) && is_array($multi_files['name'])) {
                    $img_name_array = array();
                    foreach ($multi_files['name'] as $multi_key => $multi_value) {
                        if ($multi_files['name'][$multi_key]) {
                            $loop_file = array(
                                'name' => $multi_files['name'][$multi_key],
                                'type' => $multi_files['type'][$multi_key],
                                'tmp_name' => $multi_files['tmp_name'][$multi_key],
                                'error' => $multi_files['error'][$multi_key],
                                'size' => $multi_files['size'][$multi_key]
                            );

                            $json = array();
                            require_once ABSPATH . 'wp-admin/includes/image.php';
                            require_once ABSPATH . 'wp-admin/includes/file.php';
                            require_once ABSPATH . 'wp-admin/includes/media.php';

                            $status = wp_handle_upload($loop_file, array('test_form' => false));

                            if (empty($status['error'])) {


                                $wp_upload_dir = wp_upload_dir();
                                $img_name_array[] = isset($status['url']) ? $status['url'] : '';
                                $filename = $img_name_array[$count];
                                $filetype = wp_check_filetype(basename($filename), null);

                                if ($filename != '') {
                                    // Prepare an array of post data for the attachment.

                                    $attachment = array(
                                        'guid' => ($filename),
                                        'post_mime_type' => $filetype['type'],
                                        'post_title' => preg_replace('/\.[^.]+$/', '', ($loop_file['name'])),
                                        'post_content' => '',
                                        'post_status' => 'inherit'
                                    );
                                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                                    // Insert the attachment.
                                    $attach_id = wp_insert_attachment($attachment, $status['file']);
                                    if ($property_id != '') {
                                        wp_update_post(
                                            array(
                                                'ID' => $attach_id,
                                                'post_parent' => $property_id
                                            )
                                        );
                                    }
                                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                                    $attach_data = wp_generate_attachment_metadata($attach_id, $status['file']);
                                    wp_update_attachment_metadata($attach_id, $attach_data);
                                    $property_gallery[] = $attach_id;
                                    $count++;
                                }
                            }
                        }
                    }

                    $img_resized_name = $property_gallery;
                } else {
                    $img_resized_name = '';
                }
            }

            return $img_resized_name;
        }

        public function show_property_pkg_info()
        {
            $package_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';
            $package_pric = isset($_POST['p_price']) ? $_POST['p_price'] : '';
            $package_type = isset($_POST['p_type']) ? $_POST['p_type'] : '';

            if ($package_type == 'purchased') {
                $pkg_ids = explode('pt_', $package_id);
                $pkg_id = $pkg_ids[0];
                $t_pkg_id = $pkg_ids[1];
                $html = $this->subs_package_info($pkg_id, $t_pkg_id, 'in_update');
                $show_pay = 'hide';
            } else {
                $html = $this->new_package_info($package_id);
                if ($package_pric == 'free') {
                    $show_pay = 'hide';
                } else {
                    $show_pay = 'show';
                }
            }

            echo json_encode(array('html' => $html, 'show_pay' => $show_pay));
            die;
        }

        /**
         * Updating transaction meta into property meta
         * @return
         */
        public function property_assign_meta($property_id = '', $trans_id = '')
        {
            $assign_array = array();

            $trans_get_value = get_post_meta($trans_id, 'wp_rem_transaction_property_pic_num', true);
            $assign_array[] = array(
                'key' => 'wp_rem_transaction_property_pic_num',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_pictures'),
                'value' => $trans_get_value,
            );
            $trans_get_value = get_post_meta($trans_id, 'wp_rem_transaction_property_doc_num', true);
            $assign_array[] = array(
                'key' => 'wp_rem_transaction_property_doc_num',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_docs'),
                'value' => $trans_get_value,
            );
            $trans_get_value = get_post_meta($trans_id, 'wp_rem_transaction_property_tags_num', true);
            $assign_array[] = array(
                'key' => 'wp_rem_transaction_property_tags_num',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_tags'),
                'value' => $trans_get_value,
            );

            $trans_get_value = get_post_meta($trans_id, 'wp_rem_transaction_property_phone', true);
            $assign_array[] = array(
                'key' => 'wp_rem_transaction_property_phone',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_phone_number'),
                'value' => $trans_get_value,
            );
            $trans_get_value = get_post_meta($trans_id, 'wp_rem_transaction_property_website', true);
            $assign_array[] = array(
                'key' => 'wp_rem_transaction_property_website',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_web_link'),
                'value' => $trans_get_value,
            );
            $trans_get_value = get_post_meta($trans_id, 'wp_rem_transaction_property_social', true);
            $assign_array[] = array(
                'key' => 'wp_rem_transaction_property_social',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_social_reach'),
                'value' => $trans_get_value,
            );

//			$trans_get_value = get_post_meta($trans_id, 'wp_rem_transaction_property_reviews', true);
//            $assign_array[] = array(
//                'key' => 'wp_rem_transaction_property_reviews',
//                'label' => wp_rem_plugin_text_srt('wp_rem_reviews_pakage_order_reviews'),
//                'value' => $trans_get_value,
//            );
//            $trans_get_value = get_post_meta($trans_id, 'wp_rem_transaction_property_ror', true);
//            $assign_array[] = array(
//                'key' => 'wp_rem_transaction_property_ror',
//                'label' => wp_rem_plugin_text_srt('wp_rem_reviews_pakage_order_response_reviews'),
//                'value' => $trans_get_value,
//            );

            $trans_get_value = get_post_meta($trans_id, 'wp_rem_transaction_dynamic', true);
            $assign_array[] = array(
                'key' => 'wp_rem_transaction_dynamic',
                'label' => wp_rem_plugin_text_srt('wp_rem_property_other_features'),
                'value' => $trans_get_value,
            );

            if ($property_id != '' && $trans_id != '') {
                foreach ($assign_array as $assign) {
                    update_post_meta($property_id, $assign['key'], $assign['value']);
                }
                update_post_meta($property_id, 'wp_rem_trans_all_meta', $assign_array);
            }

            return $assign_array;
        }

        /**
         * Package Fields List
         * @return
         */
        public function property_pckage_meta_fields($package_id = '')
        {
            $assign_array = array();
            $_package_data = get_post_meta($package_id, 'wp_rem_package_data', true);

            $trans_get_value = isset($_package_data['phone_number']['value']) ? $_package_data['phone_number']['value'] : '';
            $assign_array[] = array(
                'key' => 'phone_number',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_phone_number'),
                'value' => $trans_get_value,
            );

            $trans_get_value = isset($_package_data['website_link']['value']) ? $_package_data['website_link']['value'] : '';
            $assign_array[] = array(
                'key' => 'website_link',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_web_link'),
                'value' => $trans_get_value,
            );
            $trans_get_value = isset($_package_data['number_of_pictures']['value']) ? $_package_data['number_of_pictures']['value'] : '';
            $assign_array[] = array(
                'key' => 'number_of_pictures',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_pictures'),
                'value' => $trans_get_value,
            );
            $trans_get_value = isset($_package_data['number_of_documents']['value']) ? $_package_data['number_of_documents']['value'] : '';
            $assign_array[] = array(
                'key' => 'number_of_documents',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_docs'),
                'value' => $trans_get_value,
            );
            $trans_get_value = isset($_package_data['social_impressions_reach']['value']) ? $_package_data['social_impressions_reach']['value'] : '';
            $assign_array[] = array(
                'key' => 'social_impressions_reach',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_social_reach'),
                'value' => $trans_get_value,
            );

            $trans_get_value = isset($_package_data['number_of_tags']['value']) ? $_package_data['number_of_tags']['value'] : '';
            $assign_array[] = array(
                'key' => 'number_of_tags',
                'label' => wp_rem_plugin_text_srt('wp_rem_member_add_list_no_of_tags'),
                'value' => $trans_get_value,
            );

//			$trans_get_value = isset($_package_data['reviews']['value']) ? $_package_data['reviews']['value'] : '';
//            $assign_array[] = array(
//                'key' => 'reviews',
//                'label' => wp_rem_plugin_text_srt('wp_rem_reviews_pakage_order_reviews'),
//                'value' => $trans_get_value,
//            );
//
//			$trans_get_value = isset($_package_data['respond_to_reviews']['value']) ? $_package_data['respond_to_reviews']['value'] : '';
//            $assign_array[] = array(
//                'key' => 'respond_to_reviews',
//                'label' => wp_rem_plugin_text_srt('wp_rem_reviews_pakage_order_response_reviews'),
//                'value' => $trans_get_value,
//            );

            return $assign_array;
        }

        /**
         * Date plus period
         * @return date
         */
        public function date_conv($duration, $format = 'days')
        {
            if ($format == "months") {
                $adexp = date('Y/m/d H:i:s', strtotime("+" . absint($duration) . " months"));
            } else if ($format == "years") {
                $adexp = date('Y/m/d H:i:s', strtotime("+" . absint($duration) . " years"));
            } else {
                $adexp = date('Y/m/d H:i:s', strtotime("+" . absint($duration) . " days"));
            }
            return $adexp;
        }

        /**
         * Array merge
         * @return Array
         */
        public function merge_in_array($array, $value = '', $with_array = true)
        {
            $ret_array = '';
            if (is_array($array) && sizeof($array) > 0 && $value != '') {
                $array[] = $value;
                $ret_array = $array;
            } else if (!is_array($array) && $value != '') {
                $ret_array = $with_array ? array($value) : $value;
            }
            return $ret_array;
        }

        /**
         * Property Tag Open
         * @return markup
         */
        public function property_add_tag_before($class = '', $display = 'none')
        {
            global $property_add_counter;
            $property_add_counter = rand(10000000, 99999999);
            echo '<ul id="wp-rem-dev-main-con-' . $property_add_counter . '" class="register-add-property-tab-container ' . $class . '" style="display: ' . $display . ';">';
        }

        /**
         * Property Tag Close
         * @return markup
         */
        public function property_add_tag_after()
        {

            echo '</ul>';
        }

        /**
         * Steps before
         * @return markup
         */
        public function before_property($html = '')
        {
            global $wp_rem_plugin_options, $Payment_Processing;
            $wp_rem_property_announce_title = isset($wp_rem_plugin_options['wp_rem_property_announce_title']) ? $wp_rem_plugin_options['wp_rem_property_announce_title'] : '';
            $wp_rem_property_announce_description = isset($wp_rem_plugin_options['wp_rem_property_announce_description']) ? $wp_rem_plugin_options['wp_rem_property_announce_description'] : '';
            $wp_rem_announce_bg_color = isset($wp_rem_plugin_options['wp_rem_announce_bg_color']) ? $wp_rem_plugin_options['wp_rem_announce_bg_color'] : '#2b8dc4';
            $property_color = 'style="background-color:' . $wp_rem_announce_bg_color . '"';
            update_option('wooCommerce_current_page', wp_rem_server_protocol() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

            $wp_rem_order_data = $Payment_Processing->custom_order_status_display();
            if (isset($wp_rem_order_data) && !empty($wp_rem_order_data)) {
                ?>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="field-holder">
                        <div class="user-message alert" <?php echo esc_html($property_color); ?>>
                            <a href="#" data-dismiss="alert" class="close"><i class="icon-cross"></i></a>
                            <?php
                            global $woocommerce;
                            if (class_exists('WooCommerce')) {
                                WC()->payment_gateways();
                                echo '<h2>' . $wp_rem_order_data['status_message'] . '</h2>';
                                do_action('woocommerce_thankyou_' . $wp_rem_order_data['payment_method'], $wp_rem_order_data['order_id']);
                                $Payment_Processing->remove_raw_data($wp_rem_order_data['order_id']);
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                $active = '';
            }

            $html .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="field-holder">
                                        <div class="user-message alert" ' . $property_color . '>
                                                <a href="#" data-dismiss="alert" class="close"><i class="icon-cross"></i></a>
                                                <h2>' . $wp_rem_property_announce_title . '</h2>
                                                <p>' . htmlspecialchars_decode($wp_rem_property_announce_description) . '</p>
                                        </div>				
                                </div>
                        </div>';
            echo force_balance_tags($html);
        }

        /**
         * Property Submit Msg
         * @return markup
         */
        public function property_submit_msg($msg = '')
        {

            $html = '';
            if ($msg != '') {
                $msg_arr = array('msg' => $msg, 'type' => 'success');
                $msg_arr = json_encode($msg_arr);
                $html = '
				<script>
				jQuery(document).ready(function () {
					wp_rem_show_response(' . $msg_arr . ');
				});
				</script>';
            }
            echo force_balance_tags($html);
        }

        /**
         * Steps after
         * @return markup
         */
        public function after_property($html = '')
        {
            global $property_add_counter;
            echo force_balance_tags($html);
        }

        /**
         * Social Post
         * @return
         */
        public function social_post_after_activation($property_id)
        {

            global $wp_rem_plugin_options, $wp_rem_Class;
            $wp_rem_Class = new wp_rem();


            if ($property_id == '') {
                return;
            }
            $property_post = get_post($property_id);

            if (is_object($property_post)) {
                $name = $property_post->post_title;
                $name = apply_filters('the_title', $name);
                $name = html_entity_decode($name, ENT_QUOTES, get_bloginfo('charset'));
                $name = strip_tags($name);
                $name = strip_shortcodes($name);

                $content = $property_post->post_content;
                $content = apply_filters('the_content', $content);
                $content = wp_kses($content, array());

                $description = $content;

                $excerpt = '';
                $caption = '';
                $user_nicename = '';

                $post_thumbnail_id = get_post_thumbnail_id($property_id);
                $attachmenturl = '';
                if ($post_thumbnail_id) {
                    $attachmenturl = wp_get_attachment_url($post_thumbnail_id);
                }
                $link = get_permalink($property_post->ID);
            } else {
                return;
            }
            // Twitter Posting Start
            $wp_rem_twitter_posting_switch = isset($wp_rem_plugin_options['wp_rem_twitter_autopost_switch']) ? $wp_rem_plugin_options['wp_rem_twitter_autopost_switch'] : '';

            if ($wp_rem_twitter_posting_switch == 'on') {

                if (!class_exists('SMAPTwitterOAuth')) {
                    include_once($wp_rem_Class->plugin_dir . 'frontend/templates/dashboards/member/social-api/twitteroauth.php');
                }

                $tappid = isset($wp_rem_plugin_options['wp_rem_consumer_key']) ? $wp_rem_plugin_options['wp_rem_consumer_key'] : '';
                $tappsecret = isset($wp_rem_plugin_options['wp_rem_consumer_secret']) ? $wp_rem_plugin_options['wp_rem_consumer_secret'] : '';
                $taccess_token = isset($wp_rem_plugin_options['wp_rem_access_token']) ? $wp_rem_plugin_options['wp_rem_access_token'] : '';
                $taccess_token_secret = isset($wp_rem_plugin_options['wp_rem_access_token_secret']) ? $wp_rem_plugin_options['wp_rem_access_token_secret'] : '';

                $post_twitter_image_permission = 1;

                $messagetopost = '{POST_TITLE} - {PERMALINK}{POST_CONTENT}';

                $img_status = "";
                if ($post_twitter_image_permission == 1) {

                    $wp_remote_get_args = array(
                        'timeout' => 50,
                        'compress' => false,
                        'decompress' => true,
                    );
                    $img = array();
                    if ($attachmenturl != "")
                        $img = wp_remote_get($attachmenturl, $wp_remote_get_args);

                    if (is_array($img)) {
                        if (isset($img['body']) && trim($img['body']) != '') {
                            $image_found = 1;
                            if (($img['headers']['content-length']) && trim($img['headers']['content-length']) != '') {
                                $img_size = $img['headers']['content-length'] / (1024 * 1024);
                                if ($img_size > 3) {
                                    $image_found = 0;
                                    $img_status = "Image skipped(greater than 3MB)";
                                }
                            }

                            $img = $img['body'];
                        } else
                            $image_found = 0;
                    }
                }
                ///Twitter upload image end/////

                $messagetopost = str_replace("&nbsp;", "", $messagetopost);

                preg_match_all("/{(.+?)}/i", $messagetopost, $matches);
                $matches1 = $matches[1];
                $substring = "";
                $islink = 0;
                $issubstr = 0;
                $len = 118;
                if ($image_found == 1)
                    $len = $len - 24;

                foreach ($matches1 as $key => $val) {
                    $val = "{" . $val . "}";
                    if ($val == "{POST_TITLE}") {
                        $replace = $name;
                    }
                    if ($val == "{POST_CONTENT}") {
                        $replace = $description;
                    }
                    if ($val == "{PERMALINK}") {
                        $replace = "{PERMALINK}";
                        $islink = 1;
                    }
                    if ($val == "{POST_EXCERPT}") {
                        $replace = $excerpt;
                    }
                    if ($val == "{BLOG_TITLE}")
                        $replace = $caption;

                    if ($val == "{USER_NICENAME}")
                        $replace = $user_nicename;

                    $append = mb_substr($messagetopost, 0, mb_strpos($messagetopost, $val));

                    if (mb_strlen($append) < ($len - mb_strlen($substring))) {
                        $substring .= $append;
                    } else if ($issubstr == 0) {
                        $avl = $len - mb_strlen($substring) - 4;
                        if ($avl > 0)
                            $substring .= mb_substr($append, 0, $avl) . "...";

                        $issubstr = 1;
                    }


                    if ($replace == "{PERMALINK}") {
                        $chkstr = mb_substr($substring, 0, -1);
                        if ($chkstr != " ") {
                            $substring .= " " . $replace;
                            $len = $len + 12;
                        } else {
                            $substring .= $replace;
                            $len = $len + 11;
                        }
                    } else {

                        if (mb_strlen($replace) < ($len - mb_strlen($substring))) {
                            $substring .= $replace;
                        } else if ($issubstr == 0) {

                            $avl = $len - mb_strlen($substring) - 4;
                            if ($avl > 0)
                                $substring .= mb_substr($replace, 0, $avl) . "...";

                            $issubstr = 1;
                        }
                    }
                    $messagetopost = mb_substr($messagetopost, mb_strpos($messagetopost, $val) + strlen($val));
                }

                if ($islink == 1) {
                    $substring = str_replace('{PERMALINK}', $link, $substring);
                }

                $twobj = new SMAPTwitterOAuth(array('consumer_key' => $tappid, 'consumer_secret' => $tappsecret, 'user_token' => $taccess_token, 'user_secret' => $taccess_token_secret, 'curl_ssl_verifypeer' => false));

                if ($image_found == 1 && $post_twitter_image_permission == 1) {
                    $resultfrtw = $twobj->request('POST', 'https://api.twitter.com/1.1/statuses/update_with_media.json', array('media[]' => $img, 'status' => $substring), true, true);

                    if ($resultfrtw != 200) {
                        if ($twobj->response['response'] != "") {
                            $tw_publish_status["statuses/update_with_media"] = print_r($twobj->response['response'], true);
                        } else {
                            $tw_publish_status["statuses/update_with_media"] = $resultfrtw;
                        }
                    }
                } else {
                    $resultfrtw = $twobj->request('POST', $twobj->url('1.1/statuses/update'), array('status' => $substring));

                    if ($resultfrtw != 200) {
                        if ($twobj->response['response'] != "") {
                            $tw_publish_status["statuses/update"] = print_r($twobj->response['response'], true);
                        } else {
                            $tw_publish_status["statuses/update"] = $resultfrtw;
                        }
                    } else if ($img_status != "") {
                        $tw_publish_status["statuses/update_with_media"] = $img_status;
                    }
                }
            }
            // Linkedin
            $lk_client_id = isset($wp_rem_plugin_options['wp_rem_linkedin_app_id']) ? $wp_rem_plugin_options['wp_rem_linkedin_app_id'] : '';
            $lk_secret_id = isset($wp_rem_plugin_options['wp_rem_linkedin_secret']) ? $wp_rem_plugin_options['wp_rem_linkedin_secret'] : '';
            $lk_posting_switch = isset($wp_rem_plugin_options['wp_rem_linkedin_autopost_switch']) ? $wp_rem_plugin_options['wp_rem_linkedin_autopost_switch'] : '';

            $lnpost_permission = 1;

            if ($lk_posting_switch == 'on' && $lk_client_id != "" && $lk_secret_id != "" && $lnpost_permission == 1) {
                if (!class_exists('SMAPLinkedInOAuth2')) {
                    include_once($wp_rem_Class->plugin_dir . 'frontend/templates/dashboards/member/social-api/linkedin.php');
                }

                $authorized_access_token = isset($wp_rem_plugin_options['wp_rem_linkedin_access_token']) ? $wp_rem_plugin_options['wp_rem_linkedin_access_token'] : '';

                $lmessagetopost = '{POST_TITLE} - {PERMALINK}{POST_CONTENT}';

                $contentln = array();

                $description_li = wp_rem_property_string_limit($description, 362);
                $caption_li = wp_rem_property_string_limit($caption, 200);
                $name_li = wp_rem_property_string_limit($name, 200);

                $message1 = str_replace('{POST_TITLE}', $name, $lmessagetopost);
                $message2 = str_replace('{BLOG_TITLE}', $caption, $message1);
                $message3 = str_replace('{PERMALINK}', $link, $message2);
                $message4 = str_replace('{POST_EXCERPT}', $excerpt, $message3);
                $message5 = str_replace('{POST_CONTENT}', $description, $message4);
                $message5 = str_replace('{USER_NICENAME}', $user_nicename, $message5);

                $message5 = str_replace("&nbsp;", "", $message5);

                $contentln['comment'] = $message5;
                $contentln['content']['title'] = $name_li;
                $contentln['content']['submitted-url'] = $link;
                if ($attachmenturl != "") {
                    $contentln['content']['submitted-image-url'] = $attachmenturl;
                }
                $contentln['content']['description'] = $description_li;

                $contentln['visibility']['code'] = 'anyone';

                $ln_publish_status = array();

                $ObjLinkedin = new SMAPLinkedInOAuth2($authorized_access_token);
                $contentln = wp_rem_linkedin_attachment_metas($contentln, $link);

                $arrResponse = $ObjLinkedin->shareStatus($contentln);
            }

            // Facebook
            $fb_posting_switch = isset($wp_rem_plugin_options['wp_rem_facebook_autopost_switch']) ? $wp_rem_plugin_options['wp_rem_facebook_autopost_switch'] : '';

            $fb_app_id = isset($wp_rem_plugin_options['wp_rem_facebook_app_id']) ? $wp_rem_plugin_options['wp_rem_facebook_app_id'] : '';
            $fb_secret = isset($wp_rem_plugin_options['wp_rem_facebook_secret']) ? $wp_rem_plugin_options['wp_rem_facebook_secret'] : '';
            $fb_access_token = isset($wp_rem_plugin_options['wp_rem_facebook_access_token']) ? $wp_rem_plugin_options['wp_rem_facebook_access_token'] : '';

            if ($fb_posting_switch == 'on' && $fb_app_id != "" && $fb_secret != "" && $fb_access_token != "") {
                $descriptionfb_li = wp_rem_property_string_limit($description, 10000);

                if (!class_exists('SMAPFacebook')) {
                    include_once($wp_rem_Class->plugin_dir . 'frontend/templates/dashboards/member/social-api/facebook.php');
                }
                $disp_type = 'feed';

                $lmessagetopost = '{POST_TITLE} - {PERMALINK}{POST_CONTENT}';

                $wp_rem_property_pages_ids = get_option('wp_rem_fb_pages_ids');
                if ($wp_rem_property_pages_ids == "") {
                    $wp_rem_property_pages_ids = -1;
                }

                $wp_rem_property_pages_ids1 = explode(",", $wp_rem_property_pages_ids);

                foreach ($wp_rem_property_pages_ids1 as $key => $value) {
                    if ($value != -1) {
                        $value1 = explode("-", $value);
                        $acces_token = $value1[1];
                        $page_id = $value1[0];

                        $fb = new SMAPFacebook(array(
                            'appId' => $fb_app_id,
                            'secret' => $fb_secret,
                            'cookie' => true
                        ));
                        $message1 = str_replace('{POST_TITLE}', $name, $lmessagetopost);
                        $message2 = str_replace('{BLOG_TITLE}', $caption, $message1);
                        $message3 = str_replace('{PERMALINK}', $link, $message2);
                        $message4 = str_replace('{POST_EXCERPT}', $excerpt, $message3);
                        $message5 = str_replace('{POST_CONTENT}', $description, $message4);
                        $message5 = str_replace('{USER_NICENAME}', $user_nicename, $message5);

                        $message5 = str_replace("&nbsp;", "", $message5);

                        $attachment = array(
                            'message' => $message5,
                            'access_token' => $acces_token,
                            'link' => $link,
                            'name' => $name,
                            'caption' => $caption,
                            'description' => $descriptionfb_li,
                            'actions' => array(
                                array(
                                    'name' => $name,
                                    'link' => $link
                                )
                            ),
                            'picture' => $attachmenturl
                        );

                        $attachment = wp_rem_fbapp_attachment_metas($attachment, $link);

                        $result = $fb->api('/' . $page_id . '/' . $disp_type . '/', 'post', $attachment);
                    }
                }
            }
        }

        /**
         * Assign free package to member
         * @return
         */
        public function assign_free_package_to_member($member_id)
        {
            global $wp_rem_plugin_options;

            $free_package_switch = isset($wp_rem_plugin_options['wp_rem_member_register_package']) ? $wp_rem_plugin_options['wp_rem_member_register_package'] : '';
            $package_id = isset($wp_rem_plugin_options['wp_rem_member_assign_package']) ? $wp_rem_plugin_options['wp_rem_member_assign_package'] : '';

            if ($free_package_switch == 'on' && $package_id > 0) {
                return $this->wp_rem_property_add_transaction('assign-package', 0, $package_id, $member_id);
            }
        }

    }

}

new Wp_rem_Member_Register_User_And_Property();
