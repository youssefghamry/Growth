<?php

/**
 * File Type: Form Fields
 */
if ( ! class_exists('wp_rem_html_fields') ) {

    class wp_rem_html_fields extends wp_rem_form_fields {

        public function __construct() {

            // Do something...
					
        }


        /**
         * opening field markup
         * 
         */
        public function wp_rem_opening_field($params = '') {
            extract($params);
            $id = isset($id) ? $id : '';
            $wp_rem_output = '';
            $wp_rem_output .= '
			<div class="form-elements">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" id="' . $id . '">
					<label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';

            return $wp_rem_output;
        }

        /**
         * full opening field markup
         * 
         */
        public function wp_rem_full_opening_field($params = '') {
            extract($params);
            $wp_rem_output = '';
            $wp_rem_output .= '<div class="form-elements"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';

            return $wp_rem_output;
        }

        /**
         * closing field markup
         * 
         */
        public function wp_rem_closing_field($params = '') {
            extract($params);
            $wp_rem_output = '';
            $desc = ( isset($desc) ) ? $desc : '';
            if( $desc != '' ){
                $wp_rem_output .= '<p>' . esc_html($desc) . '</p>';
            }
            $wp_rem_output .= '</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '</div>';

            return $wp_rem_output;
        }

        /**
         * heading markup
         * 
         */
        public function wp_rem_heading_render($params = '') {
            global $post;
            extract($params);
            $id = ( isset($id) ) ? $id : '';
            $wp_rem_output = '
			<div class="theme-help" id="' . sanitize_html_class($id) . '">
				<h4 style="padding-bottom:0px;">' . esc_attr($name) . '</h4>
				<div class="clear"></div>
			</div>';
            $echo = ( isset($echo) ) ? $echo : '';
            if ( false !== $echo ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return force_balance_tags($wp_rem_output);
            }
        }

        /**
         * heading markup
         * 
         */
        public function wp_rem_set_heading($params = '') {
            extract($params);
            $wp_rem_output = '';
            $wp_rem_output .= '<li><a title="' . esc_html($name) . '" href="#"><i class="' . sanitize_html_class($fontawesome) . '"></i>
				<span class="cs-title-menu">' . esc_html($name) . '</span></a>';
            if ( is_array($options) && sizeof($options) > 0 ) {
                $active = '';
                $wp_rem_output .= '<ul class="sub-menu">';
                foreach ( $options as $key => $value ) {
                    $active = ( $key == "tab-general-page-settings" ) ? 'active' : '';
                    $wp_rem_output .= '<li class="' . sanitize_html_class($key) . ' ' . $active . '"><a href="#' . $key . '" onClick="toggleDiv(this.hash);return false;">' . esc_html($value) . '</a></li>';
                }
                $wp_rem_output .= '</ul>';
            }
            $wp_rem_output .= '
			</li>';

            return $wp_rem_output;
        }

        /**
         * main heading markup
         * 
         */
        public function wp_rem_set_main_heading($params = '') {
            extract($params);
            $wp_rem_output = '';
            $wp_rem_output .= '<li><a title="' . $name . '" href="#' . $id . '" onClick="toggleDiv(this.hash);return false;"><i class="' . sanitize_html_class($fontawesome) . '"></i>
			<span class="cs-title-menu">' . esc_html($name) . '</span>
			</a>
			</li>';

            return $wp_rem_output;
        }

        /**
         * sub heading markup
         * 
         */
        public function wp_rem_set_sub_heading($params = '') {
            extract($params);
            $wp_rem_output = '';
            $style = '';
            if ( $counter > 1 ) {
                $wp_rem_output .= '</div>';
            }
            if ( $id != 'tab-general-page-settings' ) {
                $style = 'style="display:none;"';
            }
            $extra_attr_html = '';
            if ( isset($extra) ) {
                $extra_attr_html = $extra;
            }
            $wp_rem_output .= '<div  id="' . $id . '" ' . $style . ' ' . $extra_attr_html . '>';
            $wp_rem_output .= '<div class="theme-header"><h1>' . esc_html($name) . '</h1>
			</div>';
            $wp_rem_output .= '<div class="col-holder">';

            return $wp_rem_output;
        }

        /**
         * announcement markup
         * 
         */
        public function wp_rem_set_announcement($params = '') {
            extract($params);
            $wp_rem_output = '';
            $wp_rem_output .= '<div id="' . $id . '" class="alert alert-info fade in nomargin theme_box"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&#215;</button>';
            if ( isset($name) && $name != '' ) {
                $wp_rem_output .= '<h4>' . esc_html($name) . '</h4>';
            }
            if ( isset($std) && $std != '' ) {
                $wp_rem_output .= '<p>' . ($std) . '</p></div>';
            }
            return $wp_rem_output;
        }

        /**
         * settings col right markup
         * 
         */
        public function wp_rem_set_col_right($params = '') {
            extract($params);
            $wp_rem_output = '';
            if ( (isset($col_heading) && $col_heading != '') || (isset($help_text) && $help_text <> '') ) {
                
            }
            $wp_rem_output .= '</div>';
            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * settings section markup
         * 
         */
        public function wp_rem_set_section($params = '') {
            extract($params);
            $wp_rem_output = '';
			
            if ( isset($accordion) && $accordion == true ) {
                if ( isset($active) && $active == true ) {
                    $active = '';
                } else {
                    $active = ' class="collapsed"';
                }
                $wp_rem_output .= '<div class="panel-heading"><a' . $active . ' href="#accordion-' . esc_attr($id) . '" data-parent="#accordion-' . esc_attr($parrent_id) . '" data-toggle="collapse"><h4>' . esc_html($std) . '</h4>';
            } else {
                $wp_rem_output .= '<div class="theme-help"><h4>' . esc_html($std) . '</h4><div class="clear"></div></div>';
            }
            if ( isset($accordion) && $accordion == true ) {
                $wp_rem_output .= '</a></div>';
            }

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * text field markup
         * 
         */
        public function wp_rem_text_field($params = '') {
            extract($params);
            $wp_rem_output = '';

            $wp_rem_styles = '';
            if ( isset($styles) && $styles != '' ) {
                $wp_rem_styles = ' style="' . $styles . '"';
            }
            $main_wraper_start = '';
            $main_wraper_end = '';
            if ( isset($main_wraper) && $main_wraper == true ) {
                $main_wraper_class_str = '';
                if ( isset($main_wraper_class) && $main_wraper_class != '' ) {
                    $main_wraper_class_str = $main_wraper_class;
                }
                $main_wraper_extra_str = '';
                if ( isset($main_wraper_extra) && $main_wraper_extra != '' ) {
                    $main_wraper_extra_str = $main_wraper_extra;
                }
                $main_wraper_start = '<div class="' . $main_wraper_class_str . '" ' . $main_wraper_extra_str . '>';
                $main_wraper_end = '</div>';
            }

            $cust_id = isset($id) ? ' id="' . $id . '"' : '';
            $extra_attr = isset($extra_att) ? ' ' . $extra_att . ' ' : '';
            $name = isset($name) ? $name : '';
            $field_params = isset($field_params) ? $field_params : '';
            $desc = isset($desc) ? $desc : '';
            $wp_rem_output .= $main_wraper_start;
            $wp_rem_output .= '<div' . $cust_id . $extra_attr . ' class="form-elements"' . $wp_rem_styles . '><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '</div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            $wp_rem_output .= parent::wp_rem_form_text_render($field_params);
            if ( $desc ) {
                $wp_rem_output .= '<p>' . esc_html($desc) . '</p>';
            }
            $wp_rem_output .= '</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '</div>';
            $wp_rem_output .= $main_wraper_end;
            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * date field markup
         * 
         */
        public function wp_rem_date_field($params = '') {
            extract($params);
            $wp_rem_output = '';

            $wp_rem_styles = '';
            if ( isset($styles) && $styles != '' ) {
                $wp_rem_styles = ' style="' . $styles . '"';
            }

            $cust_id = isset($id) ? ' id="' . $id . '"' : '';
            $extra_attr = isset($extra_att) ? ' ' . $extra_att . ' ' : '';
            $wp_rem_output .= '
			<div' . $cust_id . $extra_attr . ' class="form-elements"' . $wp_rem_styles . '>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '</div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            $wp_rem_output .= parent::wp_rem_form_date_render($field_params);
            $wp_rem_output .= '<p>' . esc_html($desc) . '</p></div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '</div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * textarea field markup
         * 
         */
        public function wp_rem_textarea_field($params = '') {
            extract($params);
            $wp_rem_output = '';
            $wp_rem_styles = '';
            if ( isset($styles) && $styles != '' ) {
                $wp_rem_styles = ' style="' . $styles . '"';
            }

            $cust_id = isset($id) ? ' id="' . $id . '"' : '';
            $extra_attr = isset($extra_att) ? ' ' . $extra_att . ' ' : '';
            $wp_rem_output .= '<div' . $cust_id . $extra_attr . ' class="form-elements"' . $wp_rem_styles . '><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '</div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            $wp_rem_output .= parent::wp_rem_form_textarea_render($field_params);
            if ( $desc != '' ) {
                $wp_rem_output .= '<p>' . $desc . '</p>';
            }
            $wp_rem_output .= '</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '</div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * radio field markup
         * 
         */
        public function wp_rem_radio_field($params = '') {
            extract($params);
            $wp_rem_output = '';

            $wp_rem_output .= '
			<div class="input-sec">';
            $wp_rem_output .= parent::wp_rem_form_radio_render($field_params);
            $wp_rem_output .= $description;
            $wp_rem_output .= '
			</div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * select field markup
         * 
         */
        public function wp_rem_select_field($params = '') {
            extract($params);
            $wp_rem_output = '';
            $wp_rem_styles = '';
            $desc = isset($desc) ? $desc : '';
            if ( isset($styles) && $styles != '' ) {
                $wp_rem_styles = ' style="' . $styles . '"';
            }
            $main_wraper_start = '';
            $main_wraper_end = '';
            if ( isset($main_wraper) && $main_wraper == true ) {
                $main_wraper_class_str = '';
                if ( isset($main_wraper_class) && $main_wraper_class != '' ) {
                    $main_wraper_class_str = $main_wraper_class;
                }
                $main_wraper_extra_str = '';
                if ( isset($main_wraper_extra) && $main_wraper_extra != '' ) {
                    $main_wraper_extra_str = $main_wraper_extra;
                }
                $main_wraper_start = '<div class="' . $main_wraper_class_str . '" ' . $main_wraper_extra_str . '>';
                $main_wraper_end = '</div>';
            }

            $cust_id = isset($id) ? ' id="' . $id . '"' : '';
            $extra_attr = isset($extra_att) ? ' ' . $extra_att . ' ' : '';
            $wp_rem_output .= $main_wraper_start;
            $wp_rem_output .= '<div' . $cust_id . $extra_attr . ' class="form-elements"' . $wp_rem_styles . '><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '</div><div' . (isset($col_id) ? ' id="' . $col_id . '"' : '') . ' class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';

            if ( isset($array) && $array == true ) {
                $wp_rem_random_id = WP_REM_FUNCTIONS()->rand_id();
                $html_id = ' id="wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '"';
            }
            if ( isset($multi) && $multi == true ) {
                $wp_rem_output .= parent::wp_rem_form_multiselect_render($field_params);
            } else {
                $wp_rem_output .= parent::wp_rem_form_select_render($field_params);
            }
            if ( $desc != '' ) {
                $wp_rem_output .= '<em style="display: inline-block; width:100%; margin:10px 0 0; font-size:12px;">' . ($desc) . '</em>';
            }
            $wp_rem_output .= '</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '</div>';
            $wp_rem_output .= $main_wraper_end;
            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * checkbox field markup
         * 
         */
        public function wp_rem_checkbox_field($params = '') {
            extract($params);
            $wp_rem_output = '';
            $wp_rem_styles = '';
            if ( isset($styles) && $styles != '' ) {
                $wp_rem_styles = ' style="' . $styles . '"';
            }

            $cust_id = isset($id) ? ' id="' . $id . '"' : '';
            $extra_attr = isset($extra_att) ? ' ' . $extra_att . ' ' : '';
            $wp_rem_output .= '
			<div' . $cust_id . $extra_attr . ' class="form-elements"' . $wp_rem_styles . '>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            $wp_rem_output .= parent::wp_rem_form_checkbox_render($field_params);
            if ( $desc ) {
                $wp_rem_output .= '<em style="display: inline-block; width:100%; margin:10px 0 0; font-size:12px;">' . ($desc) . '</em>';
            }
            $wp_rem_output .= '</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '
			</div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }
		
		
		/**
         * @ render Checkbox field
         */
        public function wp_rem_custom_checkbox_render($params = '') {
            global $post, $pagenow;
            extract($params);
            $id = isset( $id ) ? $id : '';
            $std = isset( $std ) ? $std : '';
            if ($pagenow == 'post.php') {
                $wp_rem_value = get_post_meta($post->ID, 'wp_rem_' . $id, true);
            } else {
                $wp_rem_value = $std;
            }
            if (isset($wp_rem_value) && $wp_rem_value != '') {
                $value = $wp_rem_value;
            } else {
                $value = $std;
            }
            $wp_rem_output = '';
            $wp_rem_styles = '';
            if (isset($styles) && $styles != '') {
                $wp_rem_styles = ' style="' . $styles . '"';
            }

            $cust_id = isset($id) ? ' id="' . $id . '"' : '';
            $extra_attr = isset($extra_att) ? ' ' . $extra_att . ' ' : '';

            $wp_rem_rand_id = time();
            $html_id = ' id="wp_rem_' . sanitize_html_class($id) . '"';
            $btn_name = ' name="wp_rem_' . sanitize_html_class($id) . '"';
            $html_name = ' name="wp_rem_' . sanitize_html_class($id) . '"';
            if (isset($array) && $array == true) {
                $html_id = ' id="wp_rem_' . sanitize_html_class($id) . $wp_rem_rand_id . '"';
                $btn_name = ' name="wp_rem_' . sanitize_html_class($id) . $wp_rem_rand_id . '"';
                $html_name = ' name="wp_rem_' . sanitize_html_class($id) . '_array[]"';
            }
            if( isset( $field_params['id'] ) && $field_params['id'] != '' ){
                $checkbox_id    = $html_id = 'wp_rem_' . sanitize_html_class($field_params['id']);
            }
            $checked = isset($value) && $value == 'on' ? ' checked="checked"' : '';
            $wp_rem_output       = '';
            if( isset( $simple ) && $simple != true ){
                $wp_rem_output .= '<ul class="form-elements">';
                $wp_rem_output .= '<li class="to-field has_input">';
            }
            $wp_rem_output .= parent::wp_rem_form_checkbox_render($field_params);
            $wp_rem_output .= '<label for="' . $checkbox_id . '">';
                $wp_rem_output .= $name;
            $wp_rem_output .= '</label>';
            if( isset( $simple ) && $simple != true ){
                $wp_rem_output .= '<span class="pbwp-box"></span>';
                $wp_rem_output .= $this->wp_rem_form_description($description);
                $wp_rem_output .= '</li>';
                $wp_rem_output .= '</ul>';
            }
            if (isset($echo) && $echo == true) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * upload media field markup
         * 
         */
        public function wp_rem_media_url_field($params = '') {
            extract($params);
            $wp_rem_output = '';
            $wp_rem_output .= '<div class="form-elements"><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '</div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            $wp_rem_output .= parent::wp_rem_media_url($field_params);
            $wp_rem_output .= '<p>' . esc_html($desc) . '</p>
				</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '</div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * upload file field markup
         * 
         */
        public function wp_rem_upload_file_field($params = '') {
            global $post, $pagenow, $image_val;

            extract($params);

            $std = isset($std) ? $std : '';

            if ( $pagenow == 'post.php' ) {

                if ( isset($dp) && $dp == true ) {
                    $wp_rem_value = get_post_meta($post->ID, $id, true);
                } else {
                    $wp_rem_value = get_post_meta($post->ID, 'wp_rem_' . $id, true);
                }
            } elseif ( isset($user) && ! empty($user) ) {

                if ( isset($dp) && $dp == true ) {

                    $wp_rem_value = get_the_author_meta($id, $user->ID);
                } else {
                    $wp_rem_value = get_the_author_meta('wp_rem_' . $id, $user->ID);
                }
            } else {
                $wp_rem_value = $std;
            }

            if ( isset($wp_rem_value) && $wp_rem_value != '' ) {
                $value = $wp_rem_value;

                if ( isset($dp) && $dp == true ) {

                    $value = wp_get_attachment_id($wp_rem_value);
                } else {
                    $value = $wp_rem_value;
                }
            } else {

                $value = wp_get_attachment_url($std);
            }

            if ( isset($force_std) && $force_std == true ) {
                $value = $std;
            }

            if ( isset($feature_img) && $feature_img == true ) {
                if ( $pagenow == 'post.php' ) {
                    $wp_rem_value = get_post_meta($post->ID, 'wp_rem_' . $id, true);
                    $value = $wp_rem_value;
                    $get_attachment_id = $this->get_attachment_id($wp_rem_value);
                }
            }

            if ( isset($value) && $value != '' ) {
                $value = wp_get_attachment_url($value);
                $display = ' style="display:block !important;"';
            } else {
                $display = ' style="display:none !important;"';
            }

            $wp_rem_random_id = '_rand';
            $html_id = ' id="wp_rem_' . sanitize_html_class($id) . '"';
            if ( isset($array) && $array == true ) {
                $wp_rem_random_id = WP_REM_FUNCTIONS()->rand_id();
                $html_id = ' id="wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '"';
            }

            $field_params['wp_rem_random_id'] = $wp_rem_random_id;

            $wp_rem_output = '';
            if ( isset($form_element_wrapper) && $form_element_wrapper == false ) {
                $wp_rem_output .= '';
            } else {
                $wp_rem_output .= '<div class="form-elements">';
            }
            $wp_rem_output .= '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '</div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            $wp_rem_output .= parent::wp_rem_form_fileupload_render($field_params);
            $wp_rem_output .= '<div class="page-wrap" ' . $display . ' id="wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '_box">';
            $wp_rem_output .= '<div class="gal-active">';
            $wp_rem_output .= '<div class="dragareamain" style="padding-bottom:0px;">';
            $wp_rem_output .= '<ul id="gal-sortable">';
            $wp_rem_output .= '<li class="ui-state-default" id="">';
            $wp_rem_output .= '<div class="thumb-secs"> <img src="' . esc_url($value) . '" id="wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '_img" width="100" alt="" />';
            $wp_rem_output .= '<div class="gal-edit-opts"><a href="javascript:del_media(\'wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '\')" class="delete"></a> </div>';
            $wp_rem_output .= '</div>';
            $wp_rem_output .= '</li>';
            $wp_rem_output .= '</ul>';
            $wp_rem_output .= '</div>';
            $wp_rem_output .= '</div>';
            $wp_rem_output .= '</div>';

            $wp_rem_output .= '<p>' . esc_html($desc) . '</p>
				</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            if ( isset($form_element_wrapper) && $form_element_wrapper == false ) {
                $wp_rem_output .= '';
            } else {
                $wp_rem_output .= '</div>';
            }
            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * upload file field markup
         * 
         */
        public function wp_rem_custom_upload_file_field($params = '') {
            global $post, $pagenow, $image_val;

            extract($params);
            $std = isset($std) ? $std : '';
            if ( $pagenow == 'post.php' ) {

                if ( isset($dp) && $dp == true ) {
                    $wp_rem_value = get_post_meta($post->ID, $id, true);
                } else {
                    $wp_rem_value = get_post_meta($post->ID, 'wp_rem_' . $id, true);
                }
            } elseif ( isset($user) && ! empty($user) ) {

                if ( isset($dp) && $dp == true ) {

                    $wp_rem_value = get_the_author_meta($id, $user->ID);
                } else {
                    $wp_rem_value = get_the_author_meta('wp_rem_' . $id, $user->ID);
                }
            } else {
                $wp_rem_value = $std;
            }

            if ( isset($wp_rem_value) && $wp_rem_value != '' ) {
                $value = $wp_rem_value;

                if ( isset($dp) && $dp == true ) {

                    $value = wp_rem_get_img_url($wp_rem_value, 'wp_rem_media_5');
                } else {
                    $value = $wp_rem_value;
                }
            } else {
                $value = $std;
            }

            if ( isset($force_std) && $force_std == true ) {
                $value = $std;
            }
            if ( isset($value) && $value != '' ) {
                $display = 'style=display:block';
            } else {
                $display = 'style=display:none';
            }

            $wp_rem_random_id = '';
            $html_id = ' id="wp_rem_' . sanitize_html_class($id) . '"';
            if ( isset($array) && $array == true ) {
                $wp_rem_random_id = WP_REM_FUNCTIONS()->rand_id();
                $html_id = ' id="wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '"';
            }

            $field_params['wp_rem_random_id'] = $wp_rem_random_id;

            $wp_rem_output = '';
            $wp_rem_output .= '<div class="form-elements"><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
	    <label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '</div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            $wp_rem_output .= parent::wp_rem_form_custom_fileupload_render($field_params);
            $wp_rem_output .= '<div class="page-wrap" ' . $display . ' id="wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '_box">';
            $wp_rem_output .= '<div class="gal-active">';
            $wp_rem_output .= '<div class="dragareamain" style="padding-bottom:0px;">';
            $wp_rem_output .= '<ul id="gal-sortable">';
            $wp_rem_output .= '<li class="ui-state-default" id="">';
            $wp_rem_output .= '<div class="thumb-secs"> <img src="' . esc_url($value) . '" id="wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '_img" width="100" alt="" />';
            $wp_rem_output .= '<div class="gal-edit-opts"><a href="javascript:del_media(\'wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '\')" class="delete"></a> </div>';
            $wp_rem_output .= '</div>';
            $wp_rem_output .= '</li>';
            $wp_rem_output .= '</ul>';
            $wp_rem_output .= '</div>';
            $wp_rem_output .= '</div>';
            $wp_rem_output .= '</div>';

            $wp_rem_output .= '<p>' . esc_html($desc) . '</p>
				</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '
			</div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        /**
         * select page field markup
         * 
         */
        public function wp_rem_custom_select_page_field($params = '') {
            extract($params);
            $wp_rem_output = '';
            $wp_rem_output .= '
			<div class="form-elements">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="select-style pages-loader-holder">';
						$wp_rem_output .= '<div class="args_'. $id.'" style="display:none;">'. json_encode($args) .'</div>';
						if( $std != '' && is_numeric($std)){
							$pages = array( $std => get_the_title($std) );
						}else{
							$pages = array( '' => wp_rem_plugin_text_srt('wp_rem_options_select_a_page') );
						}
						$wp_rem_output .= '<div id="'. $id.'_holder">';
							$wp_rem_output .= '<div id="'. $id.'" onclick="wp_rem_load_all_pages(\''. $id .'\');">';
								$wp_rem_output .= '<span class="pages-loader loader-' . $id . '"></span>';
								$wp_rem_opt_array = array(
									'std' => $std,
									'cust_id' => $id,
									'cust_name' => $id,
									'classes' => 'chosen-select-no-single',
									'options' => $pages,
									'return' => true,
								);
								$wp_rem_output .= parent::wp_rem_form_select_render( $wp_rem_opt_array );
							$wp_rem_output .= '</div>';
						$wp_rem_output .= '</div>';
						if ( '' != $desc ) {
							$wp_rem_output .= '<p>' . esc_html($desc) . '</p>';
						}
					$wp_rem_output .= '</div>
				</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '
			</div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }
		
		/**
         * select page field markup
         * 
         */
        public function wp_rem_select_page_field($params = '') {
            extract($params);
            $wp_rem_output = '';
            $wp_rem_output .= '
			<div class="form-elements">
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<div class="select-style">';
						$wp_rem_output .= wp_dropdown_pages($args);
						if ( '' != $desc ) {
							$wp_rem_output .= '<p>' . esc_html($desc) . '</p>';
						}
					$wp_rem_output .= '</div>
				</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '
			</div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        public function wp_rem_multi_fields($params = '') {//var_dump($params);
            extract($params);
            $wp_rem_output = '';

            $wp_rem_styles = '';
            if ( isset($styles) && $styles != '' ) {
                $wp_rem_styles = ' style="' . $styles . '"';
            }
            $cust_id = isset($id) ? ' id="' . $id . '"' : '';
            $extra_attr = isset($extra_att) ? ' ' . $extra_att . ' ' : '';
            $wp_rem_output .= '
			<div' . $cust_id . $extra_attr . ' class="form-elements"' . $wp_rem_styles . '>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
					<label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            if ( isset($fields_list) && is_array($fields_list) ) {
                foreach ( $fields_list as $field_array ) {
                    if ( $field_array['type'] == 'text' ) {
                        $wp_rem_output .= parent::wp_rem_form_text_render($field_array['field_params']);
                    } elseif ( $field_array['type'] == 'hidden' ) {
                        $wp_rem_output .= parent::wp_rem_form_hidden_render($field_array['field_params']);
                    } elseif ( $field_array['type'] == 'select' ) {
                        $wp_rem_output .= parent::wp_rem_form_select_render($field_array['field_params']);
                    } elseif ( $field_array['type'] == 'multiselect' ) {
                        $wp_rem_output .= parent::wp_rem_form_multiselect_render($field_array['field_params']);
                    } elseif ( $field_array['type'] == 'checkbox' ) {
                        $wp_rem_output .= parent::wp_rem_form_checkbox_render($field_array['field_params']);
                    } elseif ( $field_array['type'] == 'radio' ) {
                        $wp_rem_output .= parent::wp_rem_form_radio_render($field_array['field_params']);
                    } elseif ( $field_array['type'] == 'date' ) {
                        $wp_rem_output .= parent::wp_rem_form_radio_render($field_array['field_params']);
                    } elseif ( $field_array['type'] == 'textarea' ) {
                        $wp_rem_output .= parent::wp_rem_form_textarea_render($field_array['field_params']);
                    } elseif ( $field_array['type'] == 'media' ) {
                        $wp_rem_output .= parent::wp_rem_media_url($field_array['field_params']);
                    } elseif ( $field_array['type'] == 'fileupload' ) {
                        $wp_rem_output .= '<div class="page-wrap" ' . $display . ' id="wp_rem_' . sanitize_html_class($id) . '_box">';
                        $wp_rem_output .= '<div class="gal-active">';
                        $wp_rem_output .= '<div class="dragareamain" style="padding-bottom:0px;">';
                        $wp_rem_output .= '<ul id="gal-sortable">';
                        $wp_rem_output .= '<li class="ui-state-default" id="">';
                        $wp_rem_output .= '<div class="thumb-secs"> <img src="' . esc_url($value) . '" id="wp_rem_' . sanitize_html_class($id) . '_img" width="100" alt="" />';
                        $wp_rem_output .= '<div class="gal-edit-opts"><a href="javascript:del_media(\'wp_rem_' . sanitize_html_class($id) . '\')" class="delete"></a> </div>';
                        $wp_rem_output .= '</div>';
                        $wp_rem_output .= '</li>';
                        $wp_rem_output .= '</ul>';
                        $wp_rem_output .= '</div>';
                        $wp_rem_output .= '</div>';
                        $wp_rem_output .= '</div>';
                        $wp_rem_output .= parent::wp_rem_form_fileupload_render($field_params);
                    } elseif ( $field_array['type'] == 'dropdown_pages' ) {
                        $wp_rem_output .= wp_dropdown_pages($args);
                    }
                }
            }

            $wp_rem_output .= '<p>' . esc_html($desc) . '</p>
				</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '
			</div>';
            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        public function wp_rem_gallery_render($params = '') {
            global $post;
            extract($params);
            $post_id = (isset($post_id) && $post_id != '' ) ? $post_id : $post->ID;
            $wp_rem_random_id = rand(156546, 956546);
            $wp_rem_output = '';

            $wp_rem_output .= '<div class="form-elements">';
            $wp_rem_output .= ' <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div id="gallery_container_' . esc_attr($wp_rem_random_id) . '" data-csid="wp_rem_' . esc_attr($id) . '">
                        <script>
                            jQuery(document).ready(function () {
                                jQuery("#gallery_sortable_' . esc_attr($wp_rem_random_id) . '").sortable({
                                    out: function (event, ui) {
                                        wp_rem_gallery_sorting_list("wp_rem_' . sanitize_html_class($id) . '", "' . esc_attr($wp_rem_random_id) . '");
                                    }
                                });

                                gal_num_of_items("' . esc_attr($id) . '", "' . absint($wp_rem_random_id) . '", "");

                                jQuery("#gallery_container_' . esc_attr($wp_rem_random_id) . '").on("click", "a.delete", function () {
                                    gal_num_of_items("' . esc_attr($id) . '", "' . absint($wp_rem_random_id) . '", 1);
                                    jQuery(this).closest("li.image").remove();
                                    wp_rem_gallery_sorting_list("wp_rem_' . sanitize_html_class($id) . '", "' . esc_attr($wp_rem_random_id) . '");
                                });
                            });
                        </script>
                        <ul class="gallery_images" id="gallery_sortable_' . esc_attr($wp_rem_random_id) . '">';
            $gallery = get_post_meta($post_id, 'wp_rem_' . $id . '_ids', true);
            $gallery_titles = get_post_meta($post_id, 'wp_rem_' . $id . '_title', true);
            $gallery_descs = get_post_meta($post_id, 'wp_rem_' . $id . '_desc', true);
            $wp_rem_gal_counter = 0;
            if ( is_array($gallery) && sizeof($gallery) > 0 ) {
                foreach ( $gallery as $wp_rem_attach_id ) {
                    $attach_url = wp_get_attachment_url($wp_rem_attach_id);
                    if ( $attach_url != '' ) {

                        $wp_rem_gal_id = rand(156546, 956546);

                        $wp_rem_gallery_title = isset($gallery_titles[$wp_rem_gal_counter]) ? $gallery_titles[$wp_rem_gal_counter] : '';
                        $wp_rem_gallery_desc = isset($gallery_descs[$wp_rem_gal_counter]) ? $gallery_descs[$wp_rem_gal_counter] : '';

                        $wp_rem_attach_img = $this->wp_rem_get_icon_for_attachment($wp_rem_attach_id);
                        $wp_rem_output .= '
                                            <li class="image" data-attachment_id="' . esc_attr($wp_rem_gal_id) . '">
                                                    ' . $wp_rem_attach_img . '
                                                    <input type="hidden" value="' . esc_attr($wp_rem_attach_id) . '" name="wp_rem_' . $id . '_ids[]" />
                                                    <div class="actions">
                                                            <span><a href="javascript:;" class="delete tips" data-tip="' . esc_attr('wp_rem_delete_image') . '"><i class="icon-cross"></i></a></span>
                                                    </div>
                                                    <tr class="parentdelete" id="edit_track' . absint($wp_rem_gal_id) . '">
                                                      <td style="width:0">
                                                      <div id="edit_track_form' . absint($wp_rem_gal_id) . '" style="display: none;" class="table-form-elem">
                                                              <div class="form-elements">
                                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                                      <label>&nbsp;</label>
                                                                    </div>
                                                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                                                      ' . $wp_rem_attach_img . '
                                                                    </div>
                                                              </div>
                                                            </div>
                                                            </td>
                                                    </tr>
                                            </li>';
                    }
                    $wp_rem_gal_counter ++;
                }
            }
            $wp_rem_output .= '</ul>
                    </div>
                    <div id="wp_rem_' . esc_attr($id) . '_temp"></div>
                    <input type="hidden" value="" name="wp_rem_' . esc_attr($id) . '_num" />
                    <div style="width:100%; display:inline-block; margin:20px 0;">
                        <label class="browse-icon add_gallery_plugin hide-if-no-js" data-id="wp_rem_' . sanitize_html_class($id) . '" data-rand_id="' . esc_attr($wp_rem_random_id) . '">
                            <input type="button" class="left" data-choose="' . esc_attr($name) . '" data-update="' . esc_attr($name) . '" data-delete="' . esc_attr('wp_rem_delete') . '" data-text="' . esc_attr('wp_rem_delete') . '"  value="' . esc_attr($name) . '">
                        </label>
                    </div>
                </div>
            </div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        public function wp_rem_gallery_render_user($params = '') {
            extract($params);
            if ( isset($user) ) {
                $user_id = $user->ID;
            }
            $wp_rem_random_id = rand(156546, 956546);
            $wp_rem_output = '';

            $wp_rem_output .= '<div class="form-elements">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <label>' . esc_attr($name) . ' </label>
                </div>';
            $wp_rem_output .= ' <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div id="gallery_container_' . esc_attr($wp_rem_random_id) . '" data-csid="wp_rem_' . esc_attr($id) . '">
                        <script>
                            jQuery(document).ready(function () {
                                jQuery("#gallery_sortable_' . esc_attr($wp_rem_random_id) . '").sortable({
                                    out: function (event, ui) {
                                        wp_rem_gallery_sorting_list("wp_rem_' . sanitize_html_class($id) . '", "' . esc_attr($wp_rem_random_id) . '");
                                    }
                                });

                                gal_num_of_items("' . esc_attr($id) . '", "' . absint($wp_rem_random_id) . '", "");

                                jQuery("#gallery_container_' . esc_attr($wp_rem_random_id) . '").on("click", "a.delete", function () {
                                    gal_num_of_items("' . esc_attr($id) . '", "' . absint($wp_rem_random_id) . '", 1);
                                    jQuery(this).closest("li.image").remove();
                                    wp_rem_gallery_sorting_list("wp_rem_' . sanitize_html_class($id) . '", "' . esc_attr($wp_rem_random_id) . '");
                                });
                            });
                        </script>
                        <ul class="gallery_images user_gallery" id="gallery_sortable_' . esc_attr($wp_rem_random_id) . '">';
            $wp_rem_attach_id = get_user_meta($user_id, 'wp_rem_' . $id, true);

            $add_button_text = wp_rem_plugin_text_srt('wp_rem_add') . ' ' . esc_attr($name);


            if ( $wp_rem_attach_id ) {

                $attach_url = wp_get_attachment_url($wp_rem_attach_id);
                if ( $attach_url != '' ) {
                    $add_button_text = wp_rem_plugin_text_srt('wp_rem_update') . ' ' . esc_attr($name);
                    $wp_rem_gal_id = rand(156546, 956546);
                    $wp_rem_attach_img = $this->wp_rem_get_icon_for_attachment($wp_rem_attach_id);
                    $wp_rem_output .= '
										<li class="image" data-attachment_id="' . esc_attr($wp_rem_attach_id) . '">
												' . $wp_rem_attach_img . '
												<input type="hidden" value="' . esc_attr($wp_rem_attach_id) . '" name="wp_rem_' . $id . '" />
												<div class="actions">
													<span><a href="javascript:;" class="delete tips" data-tip="' . esc_attr('wp_rem_delete_image') . '"><i class="icon-times"></i></a></span>
												</div>
												<tr class="parentdelete" id="edit_track' . absint($wp_rem_gal_id) . '">
												  <td style="width:0">
												  <div id="edit_track_form' . absint($wp_rem_gal_id) . '" style="display: none;" class="table-form-elem">
														  <div class="form-elements">
																<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
																  <label>&nbsp;</label>
																</div>
																<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
																  ' . $wp_rem_attach_img . '
																</div>
														  </div>
														</div>
														</td>
												</tr>
										</li>';
                }
            }
            $wp_rem_output .= '</ul>
                    </div>
                    <div id="wp_rem_' . esc_attr($id) . '_temp"></div>
                    <input type="hidden" value="" name="wp_rem_' . esc_attr($id) . '_num" />
                    <div style="width:100%; display:inline-block; margin:20px 0;">
                        <label class="browse-icon add_gallery_plugin hide-if-no-js" data-id="wp_rem_' . sanitize_html_class($id) . '" data-rand_id="' . esc_attr($wp_rem_random_id) . '" data-button_label="' . esc_attr($add_button_text) . '" data-multiple="false">
                            <input type="button" class="left" data-choose="' . esc_attr($add_button_text) . '" data-update="' . esc_attr($add_button_text) . '" data-delete="' . esc_attr('wp_rem_delete') . '" data-text="' . esc_attr('wp_rem_delete') . '"  value="' . esc_attr($add_button_text) . '">
                        </label>
                    </div>
                </div>
            </div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        public function wp_rem_gallery_render_plugin_option($params = '') {
            global $wp_rem_plugin_options;
            extract($params);

            $wp_rem_random_id = rand(156546, 956546);
            $wp_rem_output = '';

            $wp_rem_output .= '<div class="form-elements">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <label>' . esc_attr($name) . ' </label>
                </div>';
            $wp_rem_output .= ' <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <div id="gallery_container_' . esc_attr($wp_rem_random_id) . '" data-csid="wp_rem_' . esc_attr($id) . '">
                        <script>
                            jQuery(document).ready(function () {
                                jQuery("#gallery_sortable_' . esc_attr($wp_rem_random_id) . '").sortable({
                                    out: function (event, ui) {
                                        wp_rem_gallery_sorting_list("wp_rem_' . sanitize_html_class($id) . '", "' . esc_attr($wp_rem_random_id) . '");
                                    }
                                });

                                gal_num_of_items("' . esc_attr($id) . '", "' . absint($wp_rem_random_id) . '", "");

                                jQuery("#gallery_container_' . esc_attr($wp_rem_random_id) . '").on("click", "a.delete", function () {
                                    gal_num_of_items("' . esc_attr($id) . '", "' . absint($wp_rem_random_id) . '", 1);
                                    jQuery(this).closest("li.image").remove();
                                    wp_rem_gallery_sorting_list("wp_rem_' . sanitize_html_class($id) . '", "' . esc_attr($wp_rem_random_id) . '");
                                });
                            });
                        </script>
                        <ul class="gallery_images user_gallery" id="gallery_sortable_' . esc_attr($wp_rem_random_id) . '">';
            $gallery = isset($wp_rem_plugin_options['wp_rem_' . $id . '_ids']) ? $wp_rem_plugin_options['wp_rem_' . $id . '_ids'] : '';

            $wp_rem_gal_counter = 0;
            if ( is_array($gallery) && sizeof($gallery) > 0 ) {
                foreach ( $gallery as $wp_rem_attach_id ) {
                    $attach_url = wp_get_attachment_url($wp_rem_attach_id);
                    if ( $attach_url != '' ) {
                        $wp_rem_gal_id = rand(156546, 956546);
                        $wp_rem_attach_img = $this->wp_rem_get_icon_for_attachment($wp_rem_attach_id);
                        $wp_rem_output .= '
                                            <li class="image" data-attachment_id="' . esc_attr($wp_rem_attach_id) . '">
                                                    ' . $wp_rem_attach_img . '
                                                    <input type="hidden" value="' . esc_attr($wp_rem_attach_id) . '" name="wp_rem_' . $id . '_ids[]" />
                                                    <div class="actions">
                                                            <span><a href="javascript:;" class="delete tips" data-tip="' . esc_attr('wp_rem_delete_image') . '"><i class="icon-times"></i></a></span>
                                                    </div>
                                                    <tr class="parentdelete" id="edit_track' . absint($wp_rem_gal_id) . '">
                                                      <td style="width:0">
                                                      <div id="edit_track_form' . absint($wp_rem_gal_id) . '" style="display: none;" class="table-form-elem">
                                                              <div class="form-elements">
                                                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                                                      <label>&nbsp;</label>
                                                                    </div>
                                                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                                                      ' . $wp_rem_attach_img . '
                                                                    </div>
                                                              </div>
                                                            </div>
                                                            </td>
                                                    </tr>
                                            </li>';
                    }
                    $wp_rem_gal_counter ++;
                }
            }
            $wp_rem_output .= '</ul>
                    </div>
                    <div id="wp_rem_' . esc_attr($id) . '_temp"></div>
                    <input type="hidden" value="" name="wp_rem_' . esc_attr($id) . '_num" />
                    <div style="width:100%; display:inline-block; margin:20px 0;">
                        <label class="browse-icon add_gallery_plugin hide-if-no-js" data-id="wp_rem_' . sanitize_html_class($id) . '" data-rand_id="' . esc_attr($wp_rem_random_id) . '" data-button_label="' . esc_attr($desc) . '">
                            <input type="button" class="left" data-choose="' . esc_attr($desc) . '" data-update="' . esc_attr($desc) . '" data-delete="' . esc_attr('wp_rem_delete') . '" data-text="' . esc_attr('wp_rem_delete') . '"  value="' . esc_attr($desc) . '">
                        </label>
                    </div>
                </div>
            </div>';

            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

        public function get_attachment_id($attachment_url) {
            global $wpdb;
            $attachment_id = false;
            //  If there is no url, return. 
            if ( '' == $attachment_url )
                return;
            // Get the upload wp_rem paths 
            $upload_dir_paths = wp_upload_dir();
            if ( false !== strpos($attachment_url, $upload_dir_paths['baseurl']) ) {
                //  If this is the URL of an auto-generated thumbnail, get the URL of the original image 
                $attachment_url = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url);
                // Remove the upload path base wp_rem from the attachment URL 
                $attachment_url = str_replace($upload_dir_paths['baseurl'] . '/', '', $attachment_url);

                $attachment_id = $wpdb->get_var($wpdb->prepare("SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url));
            }
            return $attachment_id;
        }

        public function wp_rem_get_icon_for_attachment($post_id) {
            return wp_get_attachment_image($post_id, 'thumbnail');
        }

        function wp_rem_upload_attachment_file_field($params = '') {
            global $post, $pagenow, $image_val;

            extract($params);

            $std = isset($std) ? $std : '';

            if ( $pagenow == 'post.php' ) {

                if ( isset($dp) && $dp == true ) {
                    $wp_rem_value = get_post_meta($post->ID, $id, true);
                } else {
                    $wp_rem_value = get_post_meta($post->ID, 'wp_rem_' . $id, true);
                }
            } elseif ( isset($user) && ! empty($user) ) {

                if ( isset($dp) && $dp == true ) {

                    $wp_rem_value = get_the_author_meta($id, $user->ID);
                } else {
                    $wp_rem_value = get_the_author_meta('wp_rem_' . $id, $user->ID);
                }
            } else {
                $wp_rem_value = $std;
            }

            if ( isset($wp_rem_value) && $wp_rem_value != '' ) {
                $value = $wp_rem_value;

                if ( isset($dp) && $dp == true ) {

                    $value = wp_get_attachment_id($wp_rem_value);
                } else {
                    $value = $wp_rem_value;
                }
            } else {

                $value = wp_get_attachment_url($std);
            }

            if ( isset($force_std) && $force_std == true ) {
                $value = $std;
            }


            if ( isset($value) && $value != '' ) {
                $value = wp_get_attachment_url($value);
                $display = ' style="display:block !important;"';
            } else {
                $display = ' style="display:none !important;"';
            }

            if ( $value != '' ) {
                $filet_type = wp_check_filetype($value);
                $filet_type = isset($filet_type['ext']) ? $filet_type['ext'] : '';
                $value = wp_rem::plugin_url() . '/assets/common/attachment-images/attach-' . $filet_type . '.png';
            }

            $wp_rem_random_id = '_rand';
            $html_id = ' id="wp_rem_' . sanitize_html_class($id) . '"';
            if ( isset($array) && $array == true ) {
                $wp_rem_random_id = WP_REM_FUNCTIONS()->rand_id();
                $html_id = ' id="wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '"';
            }

            $field_params['wp_rem_random_id'] = $wp_rem_random_id;

            $wp_rem_output = '';
            $wp_rem_output .= '<div class="form-elements"><div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<label>' . esc_attr($name) . '</label>';
            if ( isset($hint_text) && $hint_text != '' ) {
                $wp_rem_output .= wp_rem_tooltip_text(esc_html($hint_text));
            }
            if ( isset($label_desc) && $label_desc != '' ) {
                $wp_rem_output .= '<p class="label-desc">' . force_balance_tags($label_desc) . '</p>';
            }
            $wp_rem_output .= '</div><div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">';
            $wp_rem_output .= parent::wp_rem_form_attachemnt_fileupload_render($field_params);
            $wp_rem_output .= '<div class="page-wrap" ' . $display . ' id="wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '_box">';
            $wp_rem_output .= '<div class="gal-active">';
            $wp_rem_output .= '<div class="dragareamain" style="padding-bottom:0px;">';
            $wp_rem_output .= '<ul id="gal-sortable">';
            $wp_rem_output .= '<li class="ui-state-default" id="">';
            $wp_rem_output .= '<div class="thumb-secs"> <img src="' . esc_url($value) . '" id="wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '_img" width="100" alt="" />';
            $wp_rem_output .= '<div class="gal-edit-opts"><a href="javascript:del_media(\'wp_rem_' . sanitize_html_class($id) . $wp_rem_random_id . '\')" class="delete"></a> </div>';
            $wp_rem_output .= '</div>';
            $wp_rem_output .= '</li>';
            $wp_rem_output .= '</ul>';
            $wp_rem_output .= '</div>';
            $wp_rem_output .= '</div>';
            $wp_rem_output .= '</div>';

            $wp_rem_output .= '<p>' . html_entity_decode($desc) . '</p>
				</div>';
            if ( isset($split) && $split == true ) {
                $wp_rem_output .= '<div class="splitter"></div>';
            }
            $wp_rem_output .= '
			</div>';
            if ( isset($echo) && $echo == true ) {
                echo force_balance_tags($wp_rem_output);
            } else {
                return $wp_rem_output;
            }
        }

    }

    global $wp_rem_html_fields;
    $wp_rem_html_fields = new wp_rem_html_fields();
}
