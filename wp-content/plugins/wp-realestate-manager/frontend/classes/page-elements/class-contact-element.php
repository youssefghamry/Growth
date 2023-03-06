<?php
/**
 * File Type: Services Page Element
 */
if ( ! class_exists('wp_rem_contact_element') ) {

    class wp_rem_contact_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_rem_contact_element_html', array( $this, 'wp_rem_contact_element_html_callback' ), 11, 1);
            add_action('wp_rem_contact_form_element_html', array( $this, 'wp_rem_contact_form_element_html_callback' ), 11, 1);
        }

        /*
         * Output features html for frontend on property detail page.
         */

        public function wp_rem_contact_element_html_callback($post_id) {
            global $wp_rem_plugin_options;


            // property type fields
            $wp_rem_property_type = get_post_meta($post_id, 'wp_rem_property_type', true);
            $list_type = get_page_by_path($wp_rem_property_type, OBJECT, 'property-type');
            $property_type_id = isset($list_type) ? $list_type->ID : '';
			$property_type_id = wp_rem_wpml_lang_page_id( $property_type_id, 'property-type' );
            $property_type_marker_image_id = get_post_meta($property_type_id, 'wp_rem_property_type_marker_image', true);
            $property_type_marker_image = $property_type_marker_image_id;

            $wp_rem_property_type_loc_map_switch = get_post_meta($property_type_id, 'wp_rem_location_element', true);
            $wp_rem_property_type_open_hours_switch = get_post_meta($property_type_id, 'wp_rem_opening_hours_element', true);

            // property fields
            $wp_rem_post_loc_address_property = get_post_meta($post_id, 'wp_rem_post_loc_address_property', true);
            $wp_rem_post_loc_latitude = get_post_meta($post_id, 'wp_rem_post_loc_latitude_property', true);
            $wp_rem_post_loc_longitude = get_post_meta($post_id, 'wp_rem_post_loc_longitude_property', true);
            $wp_rem_loc_radius_property = get_post_meta($post_id, 'wp_rem_loc_radius_property', true);

            // user profile fields

            $user_profile_data = get_post_meta($post_id, 'wp_rem_user_profile_data', true);
            // package fields
            $wp_rem_user_phone_number = get_post_meta($post_id, 'wp_rem_property_contact_phone', true);
            $wp_rem_user_website = get_post_meta($post_id, 'wp_rem_property_contact_web', true);
            $wp_rem_user_email = get_post_meta($post_id, 'wp_rem_property_contact_email', true);
            $phone_number_limit = wp_rem_cred_limit_check($post_id, 'wp_rem_transaction_property_phone');
            $website_limit = wp_rem_cred_limit_check($post_id, 'wp_rem_transaction_property_website');
            $map_zoom_level_default = isset($wp_rem_plugin_options['wp_rem_map_zoom_level']) ? $wp_rem_plugin_options['wp_rem_map_zoom_level'] : '10';
            $map_zoom_level_post = get_post_meta($post_id, 'wp_rem_post_loc_zoom_property', true);
            if ( $map_zoom_level_post == '' || ! isset($map_zoom_level_post) ) {
                $map_zoom_level_post = $map_zoom_level_default;
            }
            if ( $wp_rem_loc_radius_property == 'on' ) {
                $property_type_marker_image = ' ';
            }
            if ( isset($wp_rem_plugin_options['wp_rem_plugin_gallery_contact']) && $wp_rem_plugin_options['wp_rem_plugin_gallery_contact'] == 'content' ) {

                $wp_rem_post_loc_address_property = wp_trim_words($wp_rem_post_loc_address_property, 12);
                $wp_rem_property_type_loc_map_switch = 'on';
                if ( $wp_rem_property_type_loc_map_switch == 'on' && $wp_rem_post_loc_longitude != '' && $wp_rem_post_loc_latitude != '' ) {
                    ?>
                    <div class="map-sec-holder">
                        <?php
                        $map_atts = array(
                            'map_height' => '180',
                            'map_lat' => $wp_rem_post_loc_latitude,
                            'map_lon' => $wp_rem_post_loc_longitude,
                            'map_zoom' => $map_zoom_level_post,
                            'map_type' => '',
                            'map_info' => '',
                            'map_info_width' => '200',
                            'map_info_height' => '200',
                            'map_marker_icon' => $property_type_marker_image,
                            'map_show_marker' => 'true',
                            'map_controls' => 'true',
                            'map_draggable' => 'true',
                            'map_scrollwheel' => 'false',
                            'map_border' => '',
                            'map_border_color' => '',
                            'wp_rem_map_style' => '',
                            'wp_rem_map_class' => '',
                            'wp_rem_map_directions' => 'off',
                            'wp_rem_map_circle' => $wp_rem_loc_radius_property,
                        );
                        wp_rem_map_content($map_atts);
                        ?>

                    </div>
                    <?php
                }

                if ( $wp_rem_property_type_loc_map_switch == 'on' || $wp_rem_property_type_open_hours_switch == 'on' ) {
                    ?>
                    <div class="contact-info-detail">
                        <div class="row">
                            <?php
                            $contact_flag = false;
                            if ( ( $phone_number_limit == 'on' && $wp_rem_user_phone_number != '' ) || ( $website_limit == 'on' && $wp_rem_user_website != '' ) || $wp_rem_user_email != '' || $wp_rem_post_loc_address_property != '' ) {
                                $contact_flag = true;
                            }
                            if ( $wp_rem_property_type_loc_map_switch == 'on' ) {
                                ?>
                                <?php if ( $contact_flag ) { ?>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="contact-info">

                                            <h4><?php echo wp_rem_plugin_text_srt('wp_rem_contact_details'); ?></h4>
                                            <?php echo apply_filters('the_content', $wp_rem_post_loc_address_property); ?>
                                            <ul>
                                                <?php if ( $phone_number_limit == 'on' && $wp_rem_user_phone_number != '' ) { ?>
                                                    <li class="cell"><i class=" icon-phone2"></i><a href="tel:<?php echo esc_html($wp_rem_user_phone_number); ?>"><?php echo esc_html($wp_rem_user_phone_number); ?></a></li>
                                                <?php } ?>
                                                <?php if ( $website_limit == 'on' && $wp_rem_user_website != '' ) { ?>
                                                    <li class="pizzaeast-"><i class="icon-globe3"></i><a href="<?php echo esc_url($wp_rem_user_website); ?>"><?php echo esc_html($wp_rem_user_website); ?></a></li>
                                                <?php } ?>
                                                <?php if ( $wp_rem_user_email != '' ) { ?>
                                                    <li class="email"><i class="icon-mail"></i><a href="mailto:<?php echo sanitize_email($wp_rem_user_email); ?>" class="text-color"><?php echo wp_rem_plugin_text_srt('wp_rem_contact_send_enquiry'); ?></a></li>
                                                <?php } ?>
                                            </ul>

                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                            }
                            if ( $wp_rem_property_type_open_hours_switch == 'on' ) {
                                ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <?php do_action('wp_rem_opening_hours_element_html', $post_id, 'none'); ?>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
            } elseif ( isset($wp_rem_plugin_options['wp_rem_plugin_gallery_contact']) && $wp_rem_plugin_options['wp_rem_plugin_gallery_contact'] == 'sidebar' ) {
                // property fields
                $wp_rem_post_loc_address_property = get_post_meta($post_id, 'wp_rem_post_loc_address_property', true);
                $wp_rem_post_loc_latitude = get_post_meta($post_id, 'wp_rem_post_loc_latitude_property', true);
                $wp_rem_post_loc_longitude = get_post_meta($post_id, 'wp_rem_post_loc_longitude_property', true);

                if ( $wp_rem_property_type_loc_map_switch == 'on' ) {
                    ?>
                    <div class="widget widget-contact">
                        <div class="widget-map">
                            <?php
                            if ( $wp_rem_post_loc_longitude != '' && $wp_rem_post_loc_latitude != '' ) {
                                $map_atts = array(
                                    'map_height' => '180',
                                    'map_lat' => $wp_rem_post_loc_latitude,
                                    'map_lon' => $wp_rem_post_loc_longitude,
                                    'map_zoom' => $map_zoom_level_post,
                                    'map_type' => '',
                                    'map_info' => '',
                                    'map_info_width' => '200',
                                    'map_info_height' => '200',
                                    'map_marker_icon' => $property_type_marker_image,
                                    'map_show_marker' => 'true',
                                    'map_controls' => 'true',
                                    'map_draggable' => 'true',
                                    'map_scrollwheel' => 'false',
                                    'map_border' => '',
                                    'map_border_color' => '',
                                    'wp_rem_map_style' => '',
                                    'wp_rem_map_class' => '',
                                    'wp_rem_map_directions' => 'off',
                                    'wp_rem_map_circle' => $wp_rem_loc_radius_property,
                                );
                                wp_rem_map_content($map_atts);
                            }
                            ?>
                        </div>
                        <div class="text-holder">
                            <h4><?php echo wp_rem_plugin_text_srt('wp_rem_contact_details'); ?></h4>
                            <?php echo apply_filters('the_content', $wp_rem_post_loc_address_property); ?>
                        </div>
                    </div>
                    <?php
                }
            }
        }

        /*
         * contact form for member property
         */

        public function wp_rem_contact_form_element_html_callback($member) {
            global $wp_rem_form_fields, $wp_rem_plugin_options, $Wp_rem_Captcha;
            wp_enqueue_script('wp-rem-validation-script');
            $wp_rem_captcha_switch = '';
            $wp_rem_captcha_switch = isset($wp_rem_plugin_options['wp_rem_captcha_switch']) ? $wp_rem_plugin_options['wp_rem_captcha_switch'] : '';
            $wp_rem_sitekey = isset($wp_rem_plugin_options['wp_rem_sitekey']) ? $wp_rem_plugin_options['wp_rem_sitekey'] : '';
            $wp_rem_secretkey = isset($wp_rem_plugin_options['wp_rem_secretkey']) ? $wp_rem_plugin_options['wp_rem_secretkey'] : '';
            $wp_rem_term_policy_switch = isset($wp_rem_plugin_options['wp_rem_term_policy_switch']) ? $wp_rem_plugin_options['wp_rem_term_policy_switch'] : '';
            $wp_rem_cs_email_counter = rand(3433, 7865);
            $wp_rem_email_address = get_post_meta($member, 'wp_rem_email_address', true);
            $wp_rem_email_address = isset($wp_rem_email_address) ? $wp_rem_email_address : '';
            
            
            /*
             * sign in 
             */
            $user_id = $company_id = 0;
            $user_id = get_current_user_id();
            $display_name = '';
            $phone_number = '';
            $email_address = '';
            if ( $user_id != 0 ) {
                $company_id = get_user_meta($user_id, 'wp_rem_company', true);
                $user_data = get_userdata($user_id);
                $display_name = esc_html(get_the_title($company_id));
                $email_address = get_post_meta($company_id, 'wp_rem_email_address', true);
            }
            
            /*
             * sign in end
             */
            
            
            $target_modal = '';
            $target_class = ' wp-rem-open-signin-tab';
            if ( is_user_logged_in() ) {
                $target_class = '';
                $target_modal = ' data-toggle="modal" data-target="#member-contant-modal' . absint($member) . '"';
            }
            ?>
            <div class="member-contact">
                <a class="contact-btn <?php echo esc_attr($target_class); ?>" href="javascript:void(0);" <?php echo ($target_modal); ?>><i class="icon-contact_mail"></i> <?php echo wp_rem_plugin_text_srt('wp_rem_contact_heading'); ?></a>
            </div> 
            <!-- Modal -->
            <div class="modal modal-form fade" id="member-contant-modal<?php echo absint($member); ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo wp_rem_plugin_text_srt('wp_rem_contact_close'); ?>"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="contact-myModalLabel"><?php echo wp_rem_plugin_text_srt('wp_rem_contact_heading'); ?> <?php echo esc_html(get_the_title($member)); ?></h4>
                        </div>

                        <div class="modal-body" id="profile-valid-<?php echo intval($member); ?>">
                            <form id="contact-members-<?php echo intval($member); ?>" method="POST">
                            <div id="message<?php echo absint($member); ?>" class="response-message"></div>
                            <script>
                                function wp_rem_contact_send_message_<?php echo intval($member); ?>(counter_id) {
                                    var member_id = "<?php echo intval($member); ?>";
                                    var returnType = wp_rem_validation_process(jQuery("#profile-valid-<?php echo intval($member); ?>"));
                                    if (returnType == false) {
                                        return false;
                                    }
                                    var member_email = "<?php echo esc_html($wp_rem_email_address); ?>";
                                    var thisObj = jQuery(".contact-message-submit-" + member_id);
                                    wp_rem_show_loader(".contact-message-submit-" + member_id, "", "button_loader", thisObj);
                                    var contact_full_name = jQuery("#contact_full_name" + member_id).val();
                                    var contact_email_add = jQuery("#contact_email_add" + member_id).val();
                                    var contact_message_field = jQuery("#contact_message_field" + member_id).val();
                                    
                                    var term_condition_field = "";
                                    if (jQuery("#member_detail_term_policy" + member_id).length) {
                                        if (jQuery("#member_detail_term_policy" + member_id).is(':checked')) {
                                            term_condition_field = "&member_detail_term_policy=on";
                                            form_data.append('member_detail_term_policy', 'on');
                                        } else {
                                            term_condition_field = "&member_detail_term_policy=off";
                                            form_data.append('member_detail_term_policy', 'off');
                                        }
                                    }
                                    var form_data = new FormData(jQuery("#contact-members-<?php echo intval($member); ?>")[0]);
                                    
                                    form_data.append('member_id', "<?php echo intval($member); ?>");
                                    form_data.append('wp_rem_member_email', "<?php echo esc_html($wp_rem_email_address); ?>");
                                    form_data.append('action', 'wp_rem_contact_message_send');
                                    jQuery.ajax({
                                        type: "POST",
                                        dataType: "json",
                                        url: "<?php echo esc_url(admin_url('admin-ajax.php')); ?>",
                                        data: form_data,
                                        processData: false,
                                        contentType: false,
                                        success: function (response) {
                                            wp_rem_show_response(response, "", thisObj);
                                        }
                                    });
                                }
                            </script>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> 
                                    <div class="field-holder">
                                        <i class="icon- icon-user4"></i>
                                        <?php
                                        $wp_rem_opt_array = array(
                                            'std'=>$display_name,
                                            'cust_id' => 'contact_full_name' . $member,
                                            'cust_name' => 'contact_full_name',
                                            'return' => false,
                                            'classes' => 'wp-rem-dev-req-field input-field',
                                            'extra_atr' => ' onkeypress="wp_rem_contact_form_valid_press(this,\'text\')"  placeholder=" ' . wp_rem_plugin_text_srt('wp_rem_contact_enter_name') . '"',
                                        );
                                        $wp_rem_form_fields->wp_rem_form_text_render($wp_rem_opt_array);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-holder">
                                        <i class="icon- icon-envelope3"></i>
                                        <?php
                                        $wp_rem_opt_array = array(
                                            'std'=>$email_address,
                                            'cust_id' => 'contact_email_add' . $member,
                                            'cust_name' => 'contact_email_add',
                                            'return' => false,
                                            'classes' => 'wp-rem-dev-req-field wp-rem-email-field input-field',
                                            'extra_atr' => ' onkeypress="wp_rem_contact_form_valid_press(this,\'email\')"  placeholder=" ' . wp_rem_plugin_text_srt('wp_rem_contact_enter_email') . '"',
                                        );
                                        $wp_rem_form_fields->wp_rem_form_text_render($wp_rem_opt_array);
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-holder">
                                        <i class="icon-message"></i>
                                        <?php
                                        $wp_rem_opt_array = array(
                                            'std' => '',
                                            'classes' => 'wp-rem-dev-req-field',
                                            'cust_id' => 'contact_message_field' . $member,
                                            'cust_name' => 'contact_message_field',
                                            'return' => false,
                                            'extra_atr' => ' onkeypress="wp_rem_contact_form_valid_press(this,\'text\')" placeholder=" ' . wp_rem_plugin_text_srt('wp_rem_contact_enter_message') . '"',
                                        );
                                        $wp_rem_form_fields->wp_rem_form_textarea_render($wp_rem_opt_array);
                                        ?>
                                    </div>
                                </div>
                                <?php
                                if ( $wp_rem_captcha_switch == 'on' ) {
                                    if ( $wp_rem_sitekey <> '' and $wp_rem_secretkey <> '' ) {
                                        if( function_exists('wp_rem_google_recaptcha_scripts') ){
                                            wp_rem_google_recaptcha_scripts();
                                        }
                                        ?>
                                        <script>
                                            var recaptcha_member_contact;
                                            var wp_rem_multicap = function () {
                                                recaptcha_member_contact = grecaptcha.render('recaptcha_member_contact' +<?php echo intval($member); ?>, {
                                                    'sitekey': '<?php echo ($wp_rem_sitekey); ?>', //Replace this with your Site key
                                                    'theme': 'light'
                                                });
                                            };
                                        </script>
                                        <?php
                                    }
                                    if ( class_exists('Wp_rem_Captcha') ) {
                                        $output = '<div class="col-md-12 recaptcha-reload" id="recaptcha_member_contact' . $member . '_div">';
                                        $output .= $Wp_rem_Captcha->wp_rem_generate_captcha_form_callback('recaptcha_member_contact' . $member . '', 'true');
                                        $output .='</div>';
                                        echo force_balance_tags($output);
                                    }
                                }
                                ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="field-holder">
                                        <div class="contact-message-submit-<?php echo intval($member); ?> input-button-loader">
                                            <input id="message_submit" type="button"  onclick="javascript:wp_rem_contact_send_message_<?php echo intval($member); ?>(<?php echo intval($wp_rem_cs_email_counter); ?>)" name="contact_message_submit" value="<?php echo wp_rem_plugin_text_srt('wp_rem_contact_send_message'); ?>" class="bgcolor" />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div> 
            <?php
        }

    }

    global $wp_rem_contact_element;
    $wp_rem_contact_element = new wp_rem_contact_element();
}