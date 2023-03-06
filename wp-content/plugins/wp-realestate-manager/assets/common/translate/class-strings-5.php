<?php

/**
 * Static string 5
 */
if ( ! class_exists('wp_rem_plugin_all_strings_5') ) {

    class wp_rem_plugin_all_strings_5 {

        public function __construct() {

            add_filter('wp_rem_plugin_text_strings', array( $this, 'wp_rem_plugin_text_strings_callback' ), 4);
        }

        public function wp_rem_plugin_text_strings_callback($wp_rem_static_text) {
            global $wp_rem_static_text;

            /*
             * Common
             */
            $wp_rem_static_text['wp_rem_select_proprty_type'] = esc_html__('Select Property Types', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_type_features_element'] = esc_html__('Features Element', 'wp-rem');
            $wp_rem_static_text['wp_rem_select_location_option'] = esc_html__('Select Location Options', 'wp-rem');
            $wp_rem_static_text['wp_rem_add_property_virtual_tour'] = esc_html__('360&deg; Virtual Tour', 'wp-rem');
            $wp_rem_static_text['wp_rem_add_property_virtual_tour_desc'] = esc_html__('Embed Iframe code', 'wp-rem');
            $wp_rem_static_text['wp_rem_add_property_visibility'] = esc_html__('Visibility', 'wp-rem');
            $wp_rem_static_text['wp_rem_add_property_public'] = esc_html__('Public', 'wp-rem');
            $wp_rem_static_text['wp_rem_add_property_invisible'] = esc_html__('Invisible', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_invisible_update_success'] = esc_html__('You have changed property visibility successfully.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_invisible_update_error'] = esc_html__('Sorry! Property visibility has not been changed.', 'wp-rem');
            $wp_rem_static_text['wp_rem_print'] = esc_html__('Print', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_type_yelp_places'] = esc_html__('Yelp Places', 'wp-rem');
            $wp_rem_static_text['wp_rem_location_element_properties_listed'] = esc_html__('Properties Listed', 'wp-rem');
            $wp_rem_static_text['wp_rem_search_element_title_colorrr'] = esc_html__('Element Title Color', 'wp-rem');
            $wp_rem_static_text['wp_rem_location_element_style_classic'] = esc_html__('Classic', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_property_excerpt_length'] = esc_html__('Length of Excerpt', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_property_excerpt_length_hint'] = esc_html__('Add number of excerpt words here for display on properties.', 'wp-rem');
            $wp_rem_static_text['wp_rem_widget_top_properties_styles'] = esc_html__('Properties Styles', 'wp-rem');
            $wp_rem_static_text['wp_rem_widget_top_properties_styles_classic'] = esc_html__('Classic', 'wp-rem');
            $wp_rem_static_text['wp_rem_widget_top_properties_styles_simple'] = esc_html__('Simple', 'wp-rem');
            $wp_rem_static_text['wp_rem_widget_top_properties_styles_modern'] = esc_html__('Modern', 'wp-rem');
            $wp_rem_static_text['wp_rem_single_prop_gallery_count_photos'] = esc_html__('Photos', 'wp-rem');
            $wp_rem_static_text['wp_rem_single_prop_print_this_page'] = esc_html__('Print this Page', 'wp-rem');
            $wp_rem_static_text['wp_rem_single_prop_email_a_frnd'] = esc_html__('Email a friend', 'wp-rem');
            $wp_rem_static_text['wp_rem_widget_top_properties_title_length'] = esc_html__('Property Title Length', 'wp-rem');
            $wp_rem_static_text['wp_rem_locations_view_all_locations'] = esc_html__('view all locations', 'wp-rem');
            $wp_rem_static_text['wp_rem_video_url_sites_example'] = esc_html__('Put URL from video sites like youtube, vimeo etc', 'wp-rem');


            // Import/Export users
            $wp_rem_static_text['wp_rem_property_users_zip_file'] = esc_html__('Zip file', 'wp-rem');
            $wp_rem_static_text['wp_rem_import_may_want_to_see'] = esc_html__('You may want to see', 'wp-rem');
            $wp_rem_static_text['wp_rem_import_the_demo_file'] = esc_html__('the demo file', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_zip_notification'] = esc_html__('Notification', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_zip_send_new_users'] = esc_html__('Send to new users', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_password_nag'] = esc_html__('Password nag', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_password_nag_hint'] = esc_html__('Show password nag on new users signon', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_update'] = esc_html__('Users update', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_update_hint'] = esc_html__('Update user when a username or email exists', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_import_users'] = esc_html__('Import Users', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_export_all_users'] = esc_html__('Export All Users', 'wp-rem');

            // Import/Export users errors/Notices
            $wp_rem_static_text['wp_rem_property_users_update'] = esc_html__('Import / Export Users', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_export'] = esc_html__('Export Users', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_data_import_error'] = esc_html__('There is an error in your users data import, please try later', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_import_notice'] = esc_html__('Notice: please make the wp_rem %s writable so that you can see the error log.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_error_file_upload'] = esc_html__('Error during file upload.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_cannot_extract_data'] = esc_html__('Cannot extract data from uploaded file or no file was uploaded.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_not_imported'] = esc_html__('No user was successfully imported%s.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_imported_some_success'] = esc_html__('Some users were successfully imported but some were not%s.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_import_successful'] = esc_html__('Users import was successful.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_invalid_file_type'] = esc_html__('You have selected invalid file type, Please try again.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_export_successful'] = esc_html__('Users has been done export successful.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_import_user_data'] = esc_html__('Import User Data', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_users_sufficient_permissions'] = esc_html__('You do not have sufficient permissions to access this page.', 'wp-rem');

            /* Start Dashboard Notification */
            $wp_rem_static_text['wp_rem_notification_hide_your_property'] = esc_html__('hide your property', 'wp-rem');
            $wp_rem_static_text['wp_rem_notification_removed_your_property_from_hidden'] = esc_html__('removed your property from hidden', 'wp-rem');
            $wp_rem_static_text['wp_rem_notification_added_notes_on_your_property'] = esc_html__('added notes on your property', 'wp-rem');
            $wp_rem_static_text['wp_rem_notification_removed_notes_on_your_property'] = esc_html__('removed notes on your property', 'wp-rem');
            $wp_rem_static_text['wp_rem_notification_submitted_enquiry'] = esc_html__('has submitted an enquiry on your property', 'wp-rem');
            $wp_rem_static_text['wp_rem_notification_submitted_viewing'] = esc_html__('request a viewing on your property', 'wp-rem');
            $wp_rem_static_text['wp_rem_notification_update_viewing_status'] = esc_html__('your viewing request', 'wp-rem');
            /* End Dashboard Notification */


            /*
             * Map Block
             */
            $wp_rem_static_text['wp_rem_plugin_options_distance_measure_by'] = esc_html__('Distance measure by', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_km'] = esc_html__('KM', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_miles'] = esc_html__('Miles', 'wp-rem');
            $wp_rem_static_text['wp_rem_func_km'] = esc_html__('Km', 'wp-rem');


            /*
             * Map Block
             */
            $wp_rem_static_text['wp_rem_sidebar_compare_properties_lable'] = esc_html__('Compare Properties', 'wp-rem');
            $wp_rem_static_text['wp_rem_sidebar_compare_button_lable'] = esc_html__('Compare', 'wp-rem');
            $wp_rem_static_text['wp_rem_sidebar_compare_reset_button_lable'] = esc_html__('Reset', 'wp-rem');


            /*
             * Compare Popup
             */
            $wp_rem_static_text['wp_rem_reset'] = esc_html__('Reset', 'wp-rem');
            $wp_rem_static_text['wp_rem_send_enquiry'] = esc_html__('Send Enquiry', 'wp-rem');
            $wp_rem_static_text['wp_rem_selected_enquiries'] = esc_html__('Selected Enquiries', 'wp-rem');

            /*
             * Options Single Property Options
             */
            $wp_rem_static_text['wp_rem_single_options_view_1_heading'] = esc_html__('View 1 Options', 'wp-rem');
            $wp_rem_static_text['wp_rem_single_options_view_2_heading'] = esc_html__('View 2 Options', 'wp-rem');
            $wp_rem_static_text['wp_rem_single_options_view_3_heading'] = esc_html__('View 3 Options', 'wp-rem');
            $wp_rem_static_text['wp_rem_single_options_view_4_heading'] = esc_html__('View 4 Options', 'wp-rem');
            $wp_rem_static_text['wp_rem_single_options_view_5_heading'] = esc_html__('View 5 Options', 'wp-rem');
            $wp_rem_static_text['wp_rem_single_options_top_gallery_with_map'] = esc_html__('Top Gallery with Map', 'wp-rem');
            $wp_rem_static_text['wp_rem_single_options_sidebar_contact_info'] = esc_html__('Sidebar Member with Contact Box', 'wp-rem');

            /*
             * Claims/Flags Module
             */
            $wp_rem_static_text['wp_rem_received_property_claim'] = esc_html__('Received Property Claim', 'wp-rem');
	    $wp_rem_static_text['wp_rem_property_claim_resolved'] = esc_html__('Property Claim Resolved', 'wp-rem');
            $wp_rem_static_text['wp_rem_print_switch'] = esc_html__('Print Option', 'wp-rem');
            $wp_rem_static_text['wp_rem_claim_switch'] = esc_html__('Claim Option', 'wp-rem');
            $wp_rem_static_text['wp_rem_flag_switch'] = esc_html__('Flag Option', 'wp-rem');

            $wp_rem_static_text['wp_rem_received_property_claim_email'] = esc_html__('This template is used to send email when administrator receive property claim.', 'wp-rem');
	    $wp_rem_static_text['wp_rem_property_claim_email_resolved'] = esc_html__('This template is used to send email when property claim resolved.', 'wp-rem');
            $wp_rem_static_text['wp_rem_received_property_flag'] = esc_html__('Received Flag Property', 'wp-rem');
	    $wp_rem_static_text['wp_rem_received_property_flag_status'] = esc_html__('Flag Property Resolved', 'wp-rem');
            $wp_rem_static_text['wp_rem_received_property_flag_email'] = esc_html__('This template is used to send email when administrator receive flag property.', 'wp-rem');


            /*
             * Forgot Password
             */
            $wp_rem_static_text['wp_rem_forgot_pass_enter_username_email'] = esc_html__('Enter Username/Email Address', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_enter_new_pass'] = esc_html__('Enter new password', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_confirm_new_pass'] = esc_html__('Confirm new password', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_confirm_not_match'] = esc_html__('The passwords do not match.', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_username_email_empty'] = esc_html__('Please enter a username or email address.', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_username_error'] = esc_html__('There is no user registered with that username.', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_email_error'] = esc_html__('There is no user registered with that email address.', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_not_allow'] = esc_html__('Sorry! password reset is not allowed.', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_wp_error'] = esc_html__('Sorry! there is a wp error.', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_link_sent'] = esc_html__('Link for password reset has been emailed to you. Please check your email.', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_title'] = esc_html__('%s Password Reset', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_link_invalid'] = esc_html__('Your password reset link appears to be invalid. Please request a new link below.', 'wp-rem');
            $wp_rem_static_text['wp_rem_forgot_pass_link_expired'] = esc_html__('Your password reset link has expired. Please request a new link below.', 'wp-rem');

            /*
             * Backend Review
             */
            $wp_rem_static_text['wp_rem_review_id_column'] = esc_html__('Review ID', 'wp-rem');
            $wp_rem_static_text['wp_rem_review_member_name_column'] = esc_html__('Member Name', 'wp-rem');
            $wp_rem_static_text['wp_rem_review_property_name_column'] = esc_html__('Property Name', 'wp-rem');
            $wp_rem_static_text['wp_rem_review_helpful_column'] = esc_html__('Helpful', 'wp-rem');
            $wp_rem_static_text['wp_rem_review_flag_column'] = esc_html__('Flag', 'wp-rem');
            $wp_rem_static_text['wp_rem_review_start_date_field_label'] = esc_html__('Start Date', 'wp-rem');
            $wp_rem_static_text['wp_rem_review_end_date_field_label'] = esc_html__('End Date', 'wp-rem');

            // Email to friend strings
            $wp_rem_static_text['wp_rem_email_to_frnd_mail_subject'] = esc_html__('Email from friend', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_frnd_mail_name_field_error'] = esc_html__('Name field should not be empty.', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_frnd_mail_email_field_empty'] = esc_html__('Email field should not be empty.', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_frnd_mail_email_field_error'] = esc_html__('Email field is not valid.', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_frnd_mail_msg_field_error'] = esc_html__('Message field should not be empty.', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_msg_txt_name'] = esc_html__('Your Name', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_msg_txt_property'] = esc_html__('Property', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_msg_txt_msg'] = esc_html__('Message', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_msg_success_msg'] = esc_html__('Message sent successfully.', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_msg_error_msg'] = esc_html__('Message not sent.', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_form_email_to_fr'] = esc_html__('Email to Friend', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_form_your_name'] = esc_html__('Your Name *', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_form_friends_email'] = esc_html__('Friend\'s email address *', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_form_your_message'] = esc_html__('Your Message *', 'wp-rem');
            $wp_rem_static_text['wp_rem_email_to_form_send_message'] = esc_html__('Send Message', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_title_color'] = esc_html__('Element Title Color', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_title_color_hint'] = esc_html__('Set the element title color here', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_subtitle_color'] = esc_html__('Element Subtitle Color', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_subtitle_color_hint'] = esc_html__('Set the element subtitle color here', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_search_modern_v4_search'] = esc_html__('Search Properties', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_properties_grid_size'] = esc_html__('Grid Size', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_properties_grid_size_hint'] = esc_html__('Set the column size of the view.', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_properties_grid_size_4_column'] = esc_html__('Four Column', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_properties_grid_size_3_column'] = esc_html__('Three Column', 'wp-rem');

            $wp_rem_static_text['wp_rem_plugin_element_title_seperator'] = esc_html__('Seperator', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_title_seperator_hint'] = esc_html__('Set the element title seperator here', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_title_seperator_style_none'] = esc_html__('None', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_title_seperator_style_classic'] = esc_html__('Classic Seperator', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_element_title_seperator_style_zigzag'] = esc_html__('Zigzag Seperator', 'wp-rem');

            $wp_rem_static_text['wp_rem_map_full_screen_text'] = esc_html__('Full Screen', 'wp-rem');
            $wp_rem_static_text['wp_rem_map_exit_full_screen_text'] = esc_html__('Exit Full Screen', 'wp-rem');

            // sold property strings
            $wp_rem_static_text['wp_rem_plugin_property_sold'] = esc_html__('Property Sold', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_sold_confirm_notice'] = esc_html__('Do you really want to mark this property as sold. You cannot undo this action. Proceed anyway?', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_sold_action_failed_notice'] = esc_html__('Action Failed.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_sold_mark_as_sold'] = esc_html__('Mark as Sold', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_sold_marked_as_sold'] = esc_html__('Property marked as Sold.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_sold_single_txt'] = esc_html__('Sold', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_sold_out_txt'] = esc_html__('Sold Out', 'wp-rem');

            $wp_rem_static_text['wp_rem_property_sold_sold_properties'] = esc_html__('Sold out', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_visibility_updated_msg'] = esc_html__('your DB structure updated successfully.', 'wp-rem');

            // Start Activity Notifications Modules Options.
            $wp_rem_static_text['wp_rem_post_type_notification_name'] = esc_html__('Notifications', 'wp-rem');
            $wp_rem_static_text['wp_rem_post_type_notification_singular_name'] = esc_html__('Notification', 'wp-rem');
            $wp_rem_static_text['wp_rem_post_type_notification_not_found'] = esc_html__('Notification not found', 'wp-rem');
            $wp_rem_static_text['wp_rem_post_type_notification_not_found_in_trash'] = esc_html__('Notification not found', 'wp-rem');
            $wp_rem_static_text['wp_rem_activity_notifications'] = esc_html__('Notifications', 'wp-rem');
            $wp_rem_static_text['wp_rem_activity_notifications_settings'] = esc_html__('Activity Notifications Settings', 'wp-rem');
            $wp_rem_static_text['wp_rem_activity_notifications_heading'] = esc_html__('Activity Notifications', 'wp-rem');
            $wp_rem_static_text['wp_rem_activity_notifications_notifications'] = esc_html__('Notifications', 'wp-rem');
            $wp_rem_static_text['wp_rem_activity_notifications_notification'] = esc_html__('Notification', 'wp-rem');
            $wp_rem_static_text['wp_rem_activity_notifications_add_notification'] = esc_html__('Add Notification', 'wp-rem');
            $wp_rem_static_text['wp_rem_activity_notifications_edit_notification'] = esc_html__('Edit Notification', 'wp-rem');
            $wp_rem_static_text['wp_rem_activity_notifications_turn_on'] = esc_html__('Trun on this switch to show notifications for each user on member dashboard.', 'wp-rem');
            $wp_rem_static_text['wp_rem_activity_notification_message'] = esc_html__('Notification Message', 'wp-rem');

            // Start Helper Generals.
            $wp_rem_static_text['wp_rem_helper_currency'] = esc_html__('Currency', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_select_currency'] = esc_html__('Select Currency', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_member_msg_received'] = esc_html__('Member Contact Message Received', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_member_email_not_valid'] = esc_html__('Member email is invalid or empty', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_name_empty'] = esc_html__('Name should not be empty', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_email_empty'] = esc_html__('Email should not be empty', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_email_not_valid'] = esc_html__('Not a valid email address', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_msg_empty'] = esc_html__('Message should not be empty', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_name'] = esc_html__('Name', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_email'] = esc_html__('Email', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_message'] = esc_html__('Message', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_ip_address'] = esc_html__('IP Address', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_sent_msg_successfully'] = esc_html__('Sent message successfully', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_msg_not_sent'] = esc_html__('Message not sent', 'wp-rem');
            $wp_rem_static_text['wp_rem_helper_read_terms_conditions'] = esc_html__('Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy', 'wp-rem');

            // Start Price Table Meta.
            $wp_rem_static_text['wp_rem_price_table_add_package'] = esc_html__('Add Package', 'wp-rem');
            $wp_rem_static_text['wp_rem_price_table_add_row'] = esc_html__('Add Row', 'wp-rem');
            $wp_rem_static_text['wp_rem_price_table_add_section'] = esc_html__('Add Section', 'wp-rem');
            $wp_rem_static_text['wp_rem_price_table_reset_all'] = esc_html__('Reset All', 'wp-rem');
            $wp_rem_static_text['wp_rem_price_table_buy_now'] = esc_html__('Buy Now', 'wp-rem');

            // End Price Table Meta.
            // Start Social Login.
            $wp_rem_static_text['wp_rem_social_login_check_fb_account'] = esc_html__('Please check facebook account developers settings.', 'wp-rem');
            $wp_rem_static_text['wp_rem_social_login_profile_already_linked'] = esc_html__('This profile is already linked with other account. Linking process failed!', 'wp-rem');
            $wp_rem_static_text['wp_rem_social_login_contact_site_admin'] = esc_html__('Contact site admin to provide a valid Twitter connect credentials.', 'wp-rem');

            // End Social Login.
            // Start Arrange Viewing.
            $wp_rem_static_text['wp_rem_viewing_name_empty'] = esc_html__('Name should not be empty', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_phone_empty'] = esc_html__('Phone number should not be empty', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_email_empty'] = esc_html__('Email address should not be empty', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_email_not_valid'] = esc_html__('Email address is not valid', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_msg_empty'] = esc_html__('Message should not be empty', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_own_property_error'] = esc_html__("You can't sent message on your own property.", 'wp-rem');
            $wp_rem_static_text['wp_rem_member_viewings_recent_viewings'] = esc_html__('Recent Arrange Viewings', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_viewings_my_viewings'] = esc_html__('My Arrange Viewings', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_viewings_received_viewings'] = esc_html__('Received Arrange Viewings', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_viewings_not_received_viewing'] = esc_html__('You don\'t have any received arrange viewing', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_viewings_not_viewing'] = esc_html__('You don\'t have any arrange viewing', 'wp-rem');
            $wp_rem_static_text['wwp_rem_member_viewings_title'] = esc_html__('Title', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_viewings_date'] = esc_html__('Date', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_viewings_buyer'] = esc_html__('Buyer', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_viewings_member'] = esc_html__('Member', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_viewings_status'] = esc_html__('Status', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_register_arrange_viewings'] = esc_html__('Arrange Viewings', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_arrange_viewing'] = esc_html__('Arrange Viewing', 'wp-rem');
            $wp_rem_static_text['wp_rem_member_viewing_detail'] = esc_html__('Viewing Detail', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_detail_status'] = esc_html__('Viewing Status', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_detail_viewing_completed'] = esc_html__('Your viewing is completed.', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_detail_closed_viewing'] = esc_html__('Close Viewing', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_detail_viewing_is'] = esc_html__('Your viewing is ', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_detail_mark_read'] = esc_html__('Mark viewing Read', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_detail_mark_unread'] = esc_html__('Mark viewing Unread', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_unread'] = esc_html__('The viewing has been marked as unread.', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_read'] = esc_html__('The viewing has been marked as read.', 'wp-rem');
            $wp_rem_static_text['wp_rem_arrange_viewing_detail_date'] = esc_html__('Date', 'wp-rem');
            $wp_rem_static_text['wp_rem_arrange_viewing_detail_name'] = esc_html__('Name', 'wp-rem');
            $wp_rem_static_text['wp_rem_arrange_viewing_detail_phone'] = esc_html__('Phone Number', 'wp-rem');
            $wp_rem_static_text['wp_rem_arrange_viewing_detail_email'] = esc_html__('Email', 'wp-rem');
            $wp_rem_static_text['wp_rem_arrange_viewing_detail_msg'] = esc_html__('Message', 'wp-rem');
            $wp_rem_static_text['wp_rem_arrange_viewing_detail_'] = esc_html__('Please', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_status_changed'] = esc_html__('Viewing status has been changed.', 'wp-rem');
            $wp_rem_static_text['wp_rem_viewing_status_closed'] = esc_html__('Your viewing has been closed.', 'wp-rem');
            $wp_rem_static_text['wp_rem_arrange_viewing_detail_procceing'] = esc_html__('Processing', 'wp-rem');
            $wp_rem_static_text['wp_rem_arrange_viewing_detail_completed'] = esc_html__('Completed', 'wp-rem');
            $wp_rem_static_text['wp_rem_arrange_viewing_detail_closed'] = esc_html__('Closed', 'wp-rem');
            $wp_rem_static_text['wp_rem_options_delete_selected_backup_file'] = esc_html__('This action will delete your selected Backup File. Do you still want to continue?', 'wp-rem');

            // End Arrange Viewing.
            /*
             * search Modern
             */
            $wp_rem_static_text['wp_rem_property_search_view_enter_kywrd'] = esc_html__('Enter Your Keyword', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_search_view_enter_kywrd_label'] = esc_html__('Keyword', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_search_view_enter_location_label'] = esc_html__('Location', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_search_view_enter_type_label'] = esc_html__('Select Type', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_search_view_enter_property_type_label'] = esc_html__('Property Type', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_search_view_label_color'] = esc_html__('Label Color', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_search_view_label_color_hint'] = esc_html__('Select a color for search fields labels(modern view only).', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_view_element_seperator'] = esc_html__('Element Seperator', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_view_element_seperator_hint'] = esc_html__('Select yes/no for element title/subtitle seperator.', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_view_simplee'] = esc_html__('Simple', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_view_advancee'] = esc_html__('Advance', 'wp-rem');
            $wp_rem_static_text['wp_rem_texonomy_location_location_img'] = esc_html__('Location Image', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_location_shortcode_name'] = esc_html__('REM: Locations', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_location_shortcode_options'] = esc_html__('Locations Options', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_location_shortcode_locations'] = esc_html__('Locations', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_location_shortcode_locations_hint'] = esc_html__('Select locations from this dropdown.', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_location_shortcode_all_locations_url'] = esc_html__('All Location URL', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_location_shortcode_all_locations_url_hint'] = esc_html__('Enter a page url to show all locations', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_location_shortcode_styles'] = esc_html__('Styles', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_location_shortcode_styles_hint'] = esc_html__('Select a location style from this dropdown.', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_location_shortcode_styles_simple'] = esc_html__('Simple', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_location_shortcode_styles_modern'] = esc_html__('Modern', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_search_advance_view_placeholder_enter_word'] = esc_html__('Enter Keyword ');
            $wp_rem_static_text['wp_rem_element_search_advance_view_placeholder_ie'] = esc_html__(' i.e   Modern Apartment', 'wp-rem');
            $wp_rem_static_text['wp_rem_element_tooltip_icon_camera'] = esc_html__('Photos', 'wp-rem');
            $wp_rem_static_text['wp_rem_shortcode_members_slider'] = esc_html__('Grid Slider', 'wp-rem');
            $wp_rem_static_text['wp_rem_map_places_found'] = esc_html__('results found', 'wp-rem');
            $wp_rem_static_text['wp_rem_map_places_radius'] = esc_html__('Radius', 'wp-rem');
            $wp_rem_static_text['wp_rem_map_places_put_radius_value'] = esc_html__('Select Radius value (km)', 'wp-rem');
            $wp_rem_static_text['wp_rem_hidden_properties'] = esc_html__('Hidden Properties', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_notes'] = esc_html__('Property Notes', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_add_notes'] = esc_html__('Add Notes', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_notes_added'] = esc_html__('Notes added', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_prop_notes_deleted'] = esc_html__('Property notes deleted.', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_properties_notes'] = esc_html__('Properties Notes', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_no_result_notes'] = esc_html__('No result found.', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_no_500_words_allow'] = esc_html__('Text more then 500 characters not allowed.', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_saved_msg'] = esc_html__('Property notes saved.', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_removed_msg'] = esc_html__('Property notes removed successfully.', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_not_removed_msg'] = esc_html__('Property notes not removed.', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_add_notes_for'] = esc_html__('Add Notes for', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_type_here'] = esc_html__('Type here...', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_submit'] = esc_html__('Submit', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_max_chars_allowed'] = esc_html__('Max characters allowed 500.', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_some_txt_error'] = esc_html__('Please type some text first.', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_show_more'] = esc_html__('Show more', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_notes_show_less'] = esc_html__('Show less', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_detail_contact_success_mgs'] = esc_html__('Email sent successfully', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_detail_contact_error_mgs'] = esc_html__('There is some error in sending email.', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_detail_contact_cnt_agent'] = esc_html__('Contact Agent', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_detail_contact_cnt_num_hide'] = esc_html__('Hide', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_detail_contact_cnt_num_show'] = esc_html__('Show', 'wp-rem');
            $wp_rem_static_text['wp_rem_prop_detail_near_by_places'] = esc_html__('Near by Places', 'wp-rem');
            $wp_rem_static_text['wp_rem_select_pkg_img_num_more_than'] = esc_html__('You cannot upload more than', 'wp-rem');
            $wp_rem_static_text['wp_rem_select_pkg_img_num_change_pkg'] = esc_html__('images. Please change your package to upload more.', 'wp-rem');
            $wp_rem_static_text['wp_rem_select_pkg_doc_num_change_pkg'] = esc_html__('documents. Please change your package to upload more.', 'wp-rem');
            $wp_rem_static_text['wp_rem_search_element_background_colorrr'] = esc_html__('Search Background Color', 'wp-rem');
            $wp_rem_static_text['wp_rem_search_element_style_modern_v2'] = esc_html__('Modern V2', 'wp-rem');
            $wp_rem_static_text['wp_rem_search_element_style_modern_v3'] = esc_html__('Modern V3', 'wp-rem');
            $wp_rem_static_text['wp_rem_search_element_style_modern_v4'] = esc_html__('Modern V4', 'wp-rem');

            /*
             *  start neaby places marker type
             */

            $wp_rem_static_text['wp_rem_marker_opions_accounting'] = esc_html__('Accounting', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_airport'] = esc_html__('Airport', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_amusement_park'] = esc_html__('Amusement Park', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_aquarium'] = esc_html__('Aquarium', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_art_gallery'] = esc_html__('Art Gallery', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_atm'] = esc_html__('Atm', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_bakery'] = esc_html__('Bakery', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_bank'] = esc_html__('Bank', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_bar'] = esc_html__('Bar', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_beauty_salon'] = esc_html__('Beauty Salon', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_bicycle_store'] = esc_html__('Bicycle Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_book_store'] = esc_html__('Book Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_bowling_alley'] = esc_html__('Bowling Alley', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_bus_station'] = esc_html__('Bus Station', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_cafe'] = esc_html__('Cafe', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_campground'] = esc_html__('Campground', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_car_dealer'] = esc_html__('Car Dealer', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_car_rental'] = esc_html__('Car Rental', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_car_repair'] = esc_html__('Car Repair', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_car_wash'] = esc_html__('Car Wash', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_casino'] = esc_html__('Casino', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_cemetery'] = esc_html__('Cemetery', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_church'] = esc_html__('Church', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_city_hall'] = esc_html__('City Hall', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_clothing_store'] = esc_html__('Clothing Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_convenience_store'] = esc_html__('Convenience Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_courthouse'] = esc_html__('Courthouse', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_dentist'] = esc_html__('Dentist', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_department_store'] = esc_html__('Department Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_doctor'] = esc_html__('Doctor', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_electrician'] = esc_html__('Electrician', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_electronics_store'] = esc_html__('Electronics Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_embassy'] = esc_html__('Embassy', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_establishment_deprecated'] = esc_html__('Establishment (deprecated)', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_finance_deprecated'] = esc_html__('Finance (deprecated)', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_fire_station'] = esc_html__('Fire Station', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_florist'] = esc_html__('Florist', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_food_deprecated'] = esc_html__('Food (deprecated)', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_funeral_home'] = esc_html__('Funeral Home', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_furniture_store'] = esc_html__('Furniture Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_gas_station'] = esc_html__('Gas Station', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_general_contractor_deprecated'] = esc_html__('General Contractor (deprecated)', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_grocery_or_supermarket_deprecated'] = esc_html__('Grocery or Supermarket (deprecated)', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_gym'] = esc_html__('Gym', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_hair_care'] = esc_html__('Hair Care', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_hardware_store'] = esc_html__('Hardware Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_health_deprecated'] = esc_html__('Health (deprecated)', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_hindu_temple'] = esc_html__('Hindu Temple', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_home_goods_store'] = esc_html__('Home Goods Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_hospital'] = esc_html__('Hospital', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_insurance_agency'] = esc_html__('Insurance Agency', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_jewelry_store'] = esc_html__('Jewelry Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_laundry'] = esc_html__('Laundry', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_lawyer'] = esc_html__('Lawyer', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_library'] = esc_html__('Library', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_liquor_store'] = esc_html__('Liquor Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_local_government_office'] = esc_html__('Local Government Office', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_locksmith'] = esc_html__('Locksmith', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_lodging'] = esc_html__('Lodging', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_meal_delivery'] = esc_html__('Meal Delivery', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_meal_takeaway'] = esc_html__('Meal Takeaway', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_mosque'] = esc_html__('Mosque', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_movie_rental'] = esc_html__('Movie Rental', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_movie_theater'] = esc_html__('Movie Theater', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_moving_company'] = esc_html__('Moving Company', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_museum'] = esc_html__('Museum', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_night_club'] = esc_html__('Night Club', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_painter'] = esc_html__('Painter', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_park'] = esc_html__('Park', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_parking'] = esc_html__('Parking', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_pet_store'] = esc_html__('Pet Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_pharmacy'] = esc_html__('Pharmacy', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_physiotherapist'] = esc_html__('Physiotherapist', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_place_of_worship_deprecated'] = esc_html__('Place of Worship (deprecated)', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_plumber'] = esc_html__('Plumber', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_police'] = esc_html__('Police', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_post_office'] = esc_html__('Post Office', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_real_estate_agency'] = esc_html__('Real Estate Agency', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_restaurant'] = esc_html__('Restaurant', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_roofing_contractor'] = esc_html__('Roofing Contractor', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_rv_park'] = esc_html__('Rv Park', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_school'] = esc_html__('School', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_shoe_store'] = esc_html__('Shoe Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_shopping_mall'] = esc_html__('Shopping Mall', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_spa'] = esc_html__('Spa', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_stadium'] = esc_html__('Stadium', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_storage'] = esc_html__('Storage', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_store'] = esc_html__('Store', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_subway_station'] = esc_html__('Subway Station', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_synagogue'] = esc_html__('Synagogue', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_taxi_stand'] = esc_html__('Taxi Stand', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_train_station'] = esc_html__('Train Station', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_transit_station'] = esc_html__('Transit Station', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_travel_agency'] = esc_html__('Travel Agency', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_university'] = esc_html__('University', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_veterinary_care'] = esc_html__('Veterinary Care', 'wp-rem');
            $wp_rem_static_text['wp_rem_marker_opions_zoo'] = esc_html__('Zoo', 'wp-rem');

            /*
             * FAQ
             */

            $wp_rem_static_text['wp_rem_faq_add_to_list'] = esc_html__('Add FAQ to List', 'wp-rem');
            $wp_rem_static_text['wp_rem_faq_update_faq'] = esc_html__('Update FAQ', 'wp-rem');
            $wp_rem_static_text['wp_rem_faq_update_to_list'] = esc_html__('Update FAQ to List', 'wp-rem');
            $wp_rem_static_text['wp_rem_faq_added_to_list'] = esc_html__('FAQ added to list successfully.', 'wp-rem');
            $wp_rem_static_text['wp_rem_faq_updated_to_list'] = esc_html__('FAQ updated to list successfully.', 'wp-rem');
            $wp_rem_static_text['wp_rem_faq_title_empty'] = esc_html__('FAQ title should not be empty.', 'wp-rem');
            $wp_rem_static_text['wp_rem_faq_desc_empty'] = esc_html__('FAQ description should not be empty.', 'wp-rem');
            $wp_rem_static_text['wp_rem_detail_page_settings'] = esc_html__('Detail Page Settings', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_ziparchive_missing'] = esc_html__('The PHP extention "ZipArchive" is missing on your server.', 'wp-rem');
            $wp_rem_static_text['wp_rem_activity_notifications_load_more'] = esc_html__('Load More...', 'wp-rem');
            $wp_rem_static_text['wp_rem_edit_details'] = esc_html__('Edit Details', 'wp-rem');
            $wp_rem_static_text['wp_rem_edit_details_update'] = esc_html__('Update', 'wp-rem');
            $wp_rem_static_text['wp_rem_edit_details_edit'] = esc_html__('Edit', 'wp-rem');
            $wp_rem_static_text['wp_rem_price_plan_most_popular'] = esc_html__('Most Popular', 'wp-rem');
            $wp_rem_static_text['wp_rem_cs_var_size'] = __('Size', 'wp-rem');
            $wp_rem_static_text['wp_rem_cs_var_column_hint'] = __('Select column width. This width will be calculated depend page width.', 'wp-rem');
            $wp_rem_static_text['wp_rem_cs_var_one_half'] = __('One half', 'wp-rem');
            $wp_rem_static_text['wp_rem_cs_var_one_third'] = __('One third', 'wp-rem');
            $wp_rem_static_text['wp_rem_cs_var_two_third'] = __('Two third', 'wp-rem');
            $wp_rem_static_text['wp_rem_cs_var_one_fourth'] = __('One fourth', 'wp-rem');
            $wp_rem_static_text['wp_rem_cs_var_three_fourth'] = __('Three fourth', 'wp-rem');
            
            $wp_rem_static_text['wp_rem_cs_var_filter_lowest_price'] = __('Low Price', 'wp-rem');
            $wp_rem_static_text['wp_rem_cs_var_filter_highest_price'] = __('High Price', 'wp-rem');
			
			
            $wp_rem_static_text['wp_rem_add_property_suggested_tags'] = __('Suggested Tags', 'wp-rem');
            $wp_rem_static_text['wp_rem_add_property_key_tags'] = __('Keywords/Tags', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_type_meta_tags_element'] = __('Tags Element', 'wp-rem');
            
            
            $wp_rem_static_text['wp_rem_valid_email_error'] = __('Please provide valid email address.', 'wp-rem');
            $wp_rem_static_text['wp_rem_theme_demo_error'] = __('Please select demo before continue.', 'wp-rem');
			
            $wp_rem_static_text['wp_rem_plugin_options_user_reg_package'] = __('User Registration Package', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_user_reg_package_asign'] = __('Assign Package on Registration', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_user_reg_package_asign_hint'] = __('Assign one default package for every user at registration.', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_user_reg_package_txt'] = __('Package', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_user_reg_package_select'] = __('Select Package', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_user_reg_package_txt_hint'] = __('Choose one from packages which will assign every user at registration. Only free packages will list here. Add or manage packages <a href="'.admin_url('edit.php?post_type=packages').'" target="_blank">here</a>', 'wp-rem');
            
			
            $wp_rem_static_text['wp_rem_plugin_options_success_msg_stings'] = __('Property Success Settings', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_success_msg'] = __('Success Message', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_success_msg_img'] = __('Success Image', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_success_msg_hint'] = __('This message will show when user property will auto approved.', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_success_msg_default'] = __('You have successfully created your property, to add more details, go to your email inbox for login details.', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_success_rev_msg'] = __('Review Message', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_success_msg_review'] = __('Your property is under review for approval.', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_success_rev_msg_hint'] = __('This message will show when user property will not auto approved.', 'wp-rem');
            $wp_rem_static_text['wp_rem_plugin_options_success_msg_phn'] = __('Success Phone', 'wp-rem');
			$wp_rem_static_text['wp_rem_plugin_options_success_msg_fax'] = __('Success Fax', 'wp-rem');
			$wp_rem_static_text['wp_rem_plugin_options_success_msg_email'] = __('Success Email', 'wp-rem');
                        
                         $wp_rem_static_text['wp_rem_property_options'] = esc_html__('Properties Options', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_select'] = esc_html__('Select', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_posted_on'] = esc_html__('Posted on:', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_expired_on'] = esc_html__('Expired on:', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_yes'] = esc_html__('Yes', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_no'] = esc_html__('No', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_package'] = esc_html__('Package', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_status'] = esc_html__('Status', 'wp-rem');
            $wp_rem_static_text['wp_rem_select_property_status'] = esc_html__('Property Status', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_awaiting_activation'] = esc_html__('Awaiting Activation', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_active'] = esc_html__('Active', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_inactive'] = esc_html__('Inactive', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_delete'] = esc_html__('Delete', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_deleted'] = esc_html__('Deleted', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_expire'] = esc_html__('Expire', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_old_status'] = esc_html__('Property Old Status', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_style'] = esc_html__('Style', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_default'] = esc_html__('Default - Selected From Plugin Options', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_2_columns'] = esc_html__('2 Columns', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_3_columns'] = esc_html__('3 Columns', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_classic'] = esc_html__('Classic', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_fancy'] = esc_html__('Fancy', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_map_view'] = esc_html__('Map View', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_type'] = esc_html__('Property Type', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_calendar_demo'] = esc_html__('Calendar Demo', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_type_hint'] = esc_html__('Select Property Type', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_custom_fields'] = esc_html__('Custom Fields', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_organization'] = esc_html__('Organization', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_mailing_information'] = esc_html__('Mailing Information', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_locations_settings'] = esc_html__('Locations Settings', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_env_res'] = esc_html__('ENVIRONMENTAL RESPONSIBILITY', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_select_categories'] = esc_html__('Select Categories', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_categories'] = esc_html__('How would you describe the ', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_no_custom_field_found'] = esc_html__('No Custom Field Found', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_off_days'] = esc_html__('Off Days', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_opening_hours'] = esc_html__('Opening Hours', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_features'] = esc_html__('Features', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_favourite'] = esc_html__('Favourite', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_save_to_favourite'] = esc_html__('Save to Favourite', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_remove_to_favourite'] = esc_html__('Removed from Favorites', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_v5_save_to_favourite'] = esc_html__('Favourite', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_v5_remove_to_favourite'] = esc_html__('Unfavourite', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_social_share_text'] = esc_html__('Share', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_featured'] = esc_html__('Featured', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_price_start_from'] = esc_html__('Start from', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_locations'] = esc_html__('Locations', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_add_location'] = esc_html__('Add Location', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_new_location'] = esc_html__('New Location', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_add_new_location'] = esc_html__('Add New Location', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_edit_location'] = esc_html__('Edit Location', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_no_locations_found.'] = esc_html__('No locations found.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_name'] = esc_html__('Name', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_slug'] = esc_html__('Slug', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_posts'] = esc_html__('Posts', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_properties'] = esc_html__('Properties', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_add_new_property'] = esc_html__('Add New Property', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_edit_property'] = esc_html__('Edit Property', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_new_property_item'] = esc_html__('New Property Item', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_view_property_item'] = esc_html__('View Property Item', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_search'] = esc_html__('Search', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_nothing_found'] = esc_html__('Nothing found', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_nothing_found_in_trash'] = esc_html__('Nothing found in Trash', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_company'] = esc_html__('Member', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_type'] = esc_html__('Property Type', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_posted'] = esc_html__('Posted', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_filter_search_for_member'] = esc_html__('Search for a member...', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_filter_search_for_member'] = esc_html__('Search for a member...', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_image'] = esc_html__('Image', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_expired'] = esc_html__('Expired', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_status'] = esc_html__('Status', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_column_property_image'] = esc_html__('Property Image', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_categories'] = esc_html__('Property Categories', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_category'] = esc_html__('Category', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_sub_category'] = esc_html__('Sub Category', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_all_categories'] = esc_html__('All Categories', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_parent_category'] = esc_html__('Parent Category', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_parent_category_clone'] = esc_html__('Parent Category Clone', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_edit_category'] = esc_html__('Edit Category', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_update_category'] = esc_html__('Update Category', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_add_new_category'] = esc_html__('Add New Category', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_no_locations_found'] = esc_html__('No locations found.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_column_name'] = esc_html__('Name', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_column_slug'] = esc_html__('Slug', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_column_posts'] = esc_html__('Posts', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_features'] = esc_html__('PROPERTY FEATURES', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_property_desc'] = esc_html__('PROPERTY DESCRIPTION', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_walk_scores'] = esc_html__('Walk Scores', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_walk_scores_more_detail'] = esc_html__('More Details Here', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_walk_scores_more_detail_simple'] = esc_html__('More Detail', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_transit_score'] = esc_html__('Transit Score', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_bike_score'] = esc_html__('Bike Score', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_score_error_occured'] = esc_html__('An error occurred while fetching walk scores.', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_contact_member'] = esc_html__('Contact Member', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_contact_details'] = esc_html__('Contact Details', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_datepicker_apply'] = esc_html__('Apply', 'wp-rem');
            $wp_rem_static_text['wp_rem_property_datepicker_cancel'] = esc_html__('Cancel', 'wp-rem');
            
                        
                        
			
            return $wp_rem_static_text;
        }

    }

    new wp_rem_plugin_all_strings_5;
}
