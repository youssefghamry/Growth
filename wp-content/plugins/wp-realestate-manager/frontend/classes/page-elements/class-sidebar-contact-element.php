<?php
/**
 * File Type: Property Sidebar Member info Page Element
 */
if ( ! class_exists('wp_rem_sidebar_contact_element') ) {

    class wp_rem_sidebar_contact_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_rem_sidebar_contact_html', array( $this, 'wp_rem_sidebar_contact_html_callback' ), 11, 2);
        }

        public function wp_rem_sidebar_contact_html_callback($property_id = '', $view = '') {
            global $wp_rem_plugin_options, $wp_rem_form_fields_frontend, $Wp_rem_Captcha;
            wp_enqueue_script('wp-rem-validation-script');
            $sidebar_contact_info = wp_rem_element_hide_show($property_id, 'sidebar_contact_info');
            if ( $sidebar_contact_info != 'on' ) {
                return;
            }
            
            /*
             * login case data fetch
             */
            
            $user_id = $company_id = 0;
            $user_id = get_current_user_id();
            $display_name = '';
            $email_address = '';
            if ( $user_id != 0 ) {
                $company_id = get_user_meta($user_id, 'wp_rem_company', true);
                $user_data = get_userdata($user_id);
                $display_name = esc_html(get_the_title($company_id));
                $email_address = get_post_meta($company_id, 'wp_rem_email_address', true);
            }
            /*
             * login case data fetch end
             */
            
            ?>
            <div class="contact-member-form member-detail">
                <?php
                $wp_rem_cs_email_counter = rand(100000, 900000);
                $property_member = get_post_meta($property_id, 'wp_rem_property_member', true);
                $wp_rem_captcha_switch = isset($wp_rem_plugin_options['wp_rem_captcha_switch']) ? $wp_rem_plugin_options['wp_rem_captcha_switch'] : '';
                $wp_rem_sitekey = isset($wp_rem_plugin_options['wp_rem_sitekey']) ? $wp_rem_plugin_options['wp_rem_sitekey'] : '';
                $wp_rem_secretkey = isset($wp_rem_plugin_options['wp_rem_secretkey']) ? $wp_rem_plugin_options['wp_rem_secretkey'] : '';
                ?>
                <?php do_action('wp_rem_author_info_html', $property_id, 'view-5'); ?>
                <form id="contactfrm<?php echo absint($wp_rem_cs_email_counter); ?>" class="contactform_name" name="contactform_name" onsubmit="return wp_rem_contact_send_message(<?php echo absint($wp_rem_cs_email_counter); ?>)">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div class="field-holder">
                                <i class="icon- icon-user4"></i>
                                <?php
                                
                                $wp_rem_opt_array = array(
                                    'std' =>$display_name,
                                    'cust_name' => 'contact_full_name',
                                    'return' => false,
                                    'classes' => 'input-field wp-rem-dev-req-field',
                                    'extra_atr' => ' onkeypress="wp_rem_contact_form_valid_press(this,\'text\')" placeholder=" ' . wp_rem_plugin_text_srt('wp_rem_member_contact_your_name') . '"',
                                );
                                $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="field-holder">
                                <i class="icon- icon-envelope3"></i>
                                <?php
                                $wp_rem_opt_array = array(
                                     'std' =>$email_address,
                                    'cust_name' => 'contact_email_add',
                                    'return' => false,
                                    'classes' => 'input-field wp-rem-dev-req-field wp-rem-email-field',
                                    'extra_atr' => ' onkeypress="wp_rem_contact_form_valid_press(this,\'email\')"  placeholder=" ' . wp_rem_plugin_text_srt('wp_rem_member_contact_your_email') . '"',
                                );
                                $wp_rem_form_fields_frontend->wp_rem_form_text_render($wp_rem_opt_array);
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="field-holder">
                                <i class="icon-message"></i>
                                <?php
                                $wp_rem_opt_array = array(
                                    'std' => '',
                                    'id' => 'contact_message_field',
                                    'name' => '',
                                    'cust_name' => 'contact_message_field',
                                    'classes' => 'wp-rem-dev-req-field',
                                    'return' => false,
                                    'extra_atr' => ' onkeypress="wp_rem_contact_form_valid_press(this,\'text\')" placeholder="' . wp_rem_plugin_text_srt('wp_rem_member_contact_your_message') . '"',
                                );
                                $wp_rem_form_fields_frontend->wp_rem_form_textarea_render($wp_rem_opt_array);
                                ?>
                            </div>
                        </div>
                        <?php
                        if ( $wp_rem_captcha_switch == 'on' ) {
                            if ( $wp_rem_sitekey <> '' and $wp_rem_secretkey <> '' ) {
                                wp_rem_google_recaptcha_scripts();
                                ?>
                                <script>
                                    var recaptcha_memberr;
                                    var wp_rem_multicap = function () {
                                        //Render the recaptcha1 on the element with ID "recaptcha1"
                                        recaptcha_memberr = grecaptcha.render('recaptcha_member_sidebar_<?php echo $wp_rem_cs_email_counter;?>', {
                                            'sitekey': '<?php echo ($wp_rem_sitekey); ?>', //Replace this with your Site key
                                            'theme': 'light'
                                        });

                                    };
                                </script>
                                <?php
                            }
                            if ( class_exists('Wp_rem_Captcha') ) {
                                $output = '<div class="col-md-12 recaptcha-reload" id="member_sidebar_div">';
                                $output .= $Wp_rem_Captcha->wp_rem_generate_captcha_form_callback('recaptcha_member_sidebar_'.$wp_rem_cs_email_counter.'', 'true');
                                $output .= '</div>';
                                echo force_balance_tags($output);
                            }
                        }
                        ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="field-holder">
                                <div class="contact-message-submit input-button-loader">
                                    <?php
                                    if ( is_user_logged_in() ) {
                                        $wp_rem_form_fields_frontend->wp_rem_form_text_render(
                                                array(
                                                    'cust_id' => 'message_submit',
                                                    'cust_name' => 'contact_message_submit',
                                                    'classes' => 'bgcolor',
                                                    'std' => wp_rem_plugin_text_srt('wp_rem_prop_detail_contact_cnt_agent') . '',
                                                    'cust_type' => "submit",
                                                )
                                        );
                                    } else {
                                        
                                        $wp_rem_form_fields_frontend->wp_rem_form_text_render(
                                                array(
                                                    'cust_id' => 'contact_message_submit',
                                                    'cust_name' => 'contact_message_submit',
                                                    'classes' => 'bgcolor wp-rem-open-signin-tab',
                                                    'std' => wp_rem_plugin_text_srt('wp_rem_prop_detail_contact_cnt_agent') . '',
                                                    'cust_type' => "button",
                                                )
                                        );
                                        ?>
                                       
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php
            $wp_rem_email_address = get_post_meta($property_member, 'wp_rem_email_address', true);
            $error = wp_rem_plugin_text_srt('wp_rem_prop_detail_contact_error_mgs');
            $success = wp_rem_plugin_text_srt('wp_rem_prop_detail_contact_success_mgs');
            $wp_rem_cs_inline_script = '   
			function wp_rem_contact_send_message(form_id) {
                            
                                var returnType = wp_rem_validation_process(jQuery("#contactfrm' . ($wp_rem_cs_email_counter) . '"));
                                if (returnType == false) {
                                    return false;
                                }else{
				var wp_rem_cs_mail_id = \'' . esc_js($wp_rem_cs_email_counter) . '\';
				var thisObj = jQuery(".contact-message-submit");
				wp_rem_show_loader(".contact-message-submit", "", "button_loader", thisObj);
				if (form_id == wp_rem_cs_mail_id) {
					var $ = jQuery;
					var datastring = $("#contactfrm' . esc_js($wp_rem_cs_email_counter) . '").serialize() + "&wp_rem_member_email=' . esc_html($wp_rem_email_address) . '&wp_rem_cs_contact_succ_msg=' . esc_js($success) . '&wp_rem_cs_contact_error_msg=' . esc_js($error) . '&action=wp_rem_contact_message_send";
					$.ajax({
						type: \'POST\',
						url: \'' . esc_js(esc_url(admin_url('admin-ajax.php'))) . '\',
						data: datastring,
						dataType: "json",
						success: function (response) {
							wp_rem_show_response( response, "", thisObj);
						}
					});
				}
                                 return false;
                             }
			}';
            wp_rem_cs_inline_enqueue_script($wp_rem_cs_inline_script, 'wp-rem-custom-inline');
        }

    }

    global $wp_rem_sidebar_contact;
    $wp_rem_sidebar_contact = new wp_rem_sidebar_contact_element();
}