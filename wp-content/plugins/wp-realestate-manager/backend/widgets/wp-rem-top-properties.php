<?php
/**
 * @Top Properties widget Class
 *
 *
 */
if ( ! class_exists('wp_rem_top_properties') ) {

    class wp_rem_top_properties extends WP_Widget {
        /**
         * Outputs the content of the widget
         * @param array $args
         * @param array $instance
         */

        /**
         * @init User list Module
         */
        public function __construct() {

            parent::__construct(
                    'wp_rem_top_properties', // Base ID
                    wp_rem_plugin_text_srt('wp_rem_top_properties_widget'), // Name
                    array( 'classname' => 'widget_top_properties', 'description' => wp_rem_plugin_text_srt('wp_rem_top_properties_widget_desc'), ) // Args
            );
        }

        /**
         * @User list html form
         */
        function form($instance = array()) {
            global $wp_rem_html_fields;
            $instance = wp_parse_args((array) $instance, array( 'title' => '' ));
            $title = $instance['title'];
            $showcount = isset($instance['showcount']) ? esc_attr($instance['showcount']) : '';
            $property_widget_style = isset($instance['property_widget_style']) ? esc_attr($instance['property_widget_style']) : '';
            $property_title_length = isset($instance['property_title_length']) ? esc_attr($instance['property_title_length']) : '';
            $wp_rem_opt_array = array(
                'name' => wp_rem_plugin_text_srt('wp_rem_top_properties_title_field'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr($title),
                    'id' => ($this->get_field_id('title')),
                    'classes' => '',
                    'cust_id' => ($this->get_field_name('title')),
                    'cust_name' => ($this->get_field_name('title')),
                    'return' => true,
                    'required' => false
                ),
            );
            $wp_rem_html_fields->wp_rem_text_field($wp_rem_opt_array);
            $wp_rem_opt_array = array(
                'name' => wp_rem_plugin_text_srt('wp_rem_widget_top_properties_styles'),
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'cust_name' => wp_rem_allow_special_char($this->get_field_name('property_widget_style')),
                    'cust_id' => wp_rem_allow_special_char($this->get_field_id('property_widget_style')),
                    'return' => true,
                    'classes' => 'chosen-select',
                    'std' => $property_widget_style,
                    'options' => array(
                        '' => wp_rem_plugin_text_srt('wp_rem_widget_top_properties_styles_classic'),
                        'simple' => wp_rem_plugin_text_srt('wp_rem_widget_top_properties_styles_simple'),
                        'modern' => wp_rem_plugin_text_srt('wp_rem_widget_top_properties_styles_modern'),
                    ),
                ),
            );
            $wp_rem_html_fields->wp_rem_select_field($wp_rem_opt_array);
            $wp_rem_opt_array = array(
                'name' => wp_rem_plugin_text_srt('wp_rem_top_properties_num_post'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr($showcount),
                    'id' => wp_rem_cs_allow_special_char($this->get_field_id('showcount')),
                    'classes' => '',
                    'cust_id' => wp_rem_cs_allow_special_char($this->get_field_name('showcount')),
                    'cust_name' => wp_rem_cs_allow_special_char($this->get_field_name('showcount')),
                    'return' => true,
                    'required' => false
                ),
            );
            $wp_rem_html_fields->wp_rem_text_field($wp_rem_opt_array);
            $wp_rem_opt_array = array(
                'name' => wp_rem_plugin_text_srt('wp_rem_widget_top_properties_title_length'),
                'desc' => '',
                'hint_text' => '',
                'echo' => true,
                'field_params' => array(
                    'std' => esc_attr($property_title_length),
                    'id' => ($this->get_field_id('property_title_length')),
                    'classes' => '',
                    'cust_id' => ($this->get_field_name('property_title_length')),
                    'cust_name' => ($this->get_field_name('property_title_length')),
                    'return' => true,
                    'required' => false
                ),
            );
            $wp_rem_html_fields->wp_rem_text_field($wp_rem_opt_array);
        }

        /**
         * @User list update data
         */
        function update($new_instance = array(), $old_instance = array()) {
            $instance = $old_instance;
            $instance['title'] = $new_instance['title'];
            $instance['showcount'] = esc_sql($new_instance['showcount']);
            $instance['property_widget_style'] = $new_instance['property_widget_style'];
            $instance['property_title_length'] = $new_instance['property_title_length'];

            return $instance;
        }

        /**
         * @Display User list widget */
        function widget($args = array(), $instance = array()) {
            extract($args, EXTR_SKIP);
            global $wpdb, $post, $cs_theme_options;
            $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
            $title = htmlspecialchars_decode(stripslashes($title));
            $showcount = $instance['showcount'];
            $property_widget_style = isset($instance['property_widget_style']) ? $instance['property_widget_style'] : '';
            $property_title_length = isset($instance['property_title_length']) ? $instance['property_title_length'] : '4';

            $view_class = '';
            if ( isset($property_widget_style) && $property_widget_style == 'simple' ) {
                $view_class = ' simple';
            }

            // WIDGET display CODE Start
            echo balanceTags($before_widget, false);
            $cs_page_id = '';

            if ( isset($instance['title']) && $instance['title'] != '' ) {
                if ( strlen($title) <> 1 || strlen($title) <> 0 ) {
                    echo balanceTags($before_title . $title . $after_title, false);
                }
            }
            $showcount = $showcount <> '' ? $showcount : '10';
            $default_date_time_formate = 'd-m-Y H:i:s';
            // posted date check
            $element_filter_arr[] = array(
                'key' => 'wp_rem_property_posted',
                'value' => strtotime(date($default_date_time_formate)),
                'compare' => '<=',
            );

            $element_filter_arr[] = array(
                'key' => 'wp_rem_property_expired',
                'value' => strtotime(date($default_date_time_formate)),
                'compare' => '>=',
            );

            $element_filter_arr[] = array(
                'key' => 'wp_rem_property_status',
                'value' => 'active',
                'compare' => '=',
            );
            // check if member not inactive
            $element_filter_arr[] = array(
                'key' => 'property_member_status',
                'value' => 'active',
                'compare' => '=',
            );
            $element_filter_arr[] = array(
                'key' => 'wp_rem_property_is_top_cat',
                'value' => 'on',
                'compare' => '=',
            );
            $paging_var = isset($paging_var) ? $paging_var : '';
            $args = array(
                'posts_per_page' => $showcount,
                'paged' => isset($_REQUEST[$paging_var]) ? $_REQUEST[$paging_var] : 1,
                'post_type' => 'properties',
                'post_status' => 'publish',
                'fields' => 'ids', // only load ids 
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            $top_properties_loop_obj = wp_rem_get_cached_obj('top_properties_result_cached_loop_obj', $args, 12, false, 'wp_query');
            if ( $top_properties_loop_obj->have_posts() ) {

                $property_location_options = 'city,country';
                if ( $property_location_options != '' ) {
                    $property_location_options = explode(',', $property_location_options);
                }
                ?>
                <div class="top-properties-property<?php echo wp_rem_cs_allow_special_char($view_class); ?>">
                    <?php
                    while ( $top_properties_loop_obj->have_posts() ) : $top_properties_loop_obj->the_post();
                        global $post;
                        $property_id = $post;
                        $Wp_rem_Locations = new Wp_rem_Locations();
                        $get_property_location = $Wp_rem_Locations->get_element_property_location($property_id, $property_location_options);
                        $wp_rem_property_price_options = get_post_meta($property_id, 'wp_rem_property_price_options', true);
                        $wp_rem_property_type = get_post_meta($property_id, 'wp_rem_property_type', true);
                        // checking review in on in property type
                        $wp_rem_property_type = isset($wp_rem_property_type) ? $wp_rem_property_type : '';
                        if ( $property_type_post = get_page_by_path($wp_rem_property_type, OBJECT, 'property-type') )
                            $property_type_id = $property_type_post->ID;
                        $property_type_id = isset($property_type_id) ? $property_type_id : '';
						$property_type_id = wp_rem_wpml_lang_page_id( $property_type_id, 'property-type' );
                        $wp_rem_user_reviews = get_post_meta($property_type_id, 'wp_rem_user_reviews', true);
                        $wp_rem_property_type_price_switch = get_post_meta($property_type_id, 'wp_rem_property_type_price', true);
                        // end checking review on in property type
                        $wp_rem_property_price = '';
                        if ( $wp_rem_property_price_options == 'price' ) {
                            $wp_rem_property_price = get_post_meta($property_id, 'wp_rem_property_price', true);
                        } else if ( $wp_rem_property_price_options == 'on-call' ) {
                            $wp_rem_property_price = wp_rem_plugin_text_srt('wp_rem_properties_price_on_request');
                        }
                        ?>
                        <div class="properties-post"> 
                            <?php if ( isset($property_widget_style) && $property_widget_style != 'simple' ) { ?>
                                <div class="img-holder">
                                    <figure>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php
                                            $size = '';
                                            if ( $property_widget_style == 'modern' ) {
                                                $size = 'thumbnail';
                                            } else {
                                                $size = 'wp_rem_cs_media_4';
                                            }
                                            if ( function_exists('property_gallery_first_image') ) {
                                                $gallery_image_args = array(
                                                    'property_id' => $property_id,
                                                    'size' => $size,
                                                    'class' => 'img-list',
                                                    'default_image_src' => esc_url(wp_rem::plugin_url() . 'assets/frontend/images/no-image4x3.jpg')
                                                );
                                                echo $property_gallery_first_image = property_gallery_first_image($gallery_image_args);
                                            }
                                            ?>
                                        </a>
                                        <figcaption>
                                            <?php wp_rem_property_sold_html($property_id);?>
                                        </figcaption>
                                    </figure>
                                </div>
                            <?php } ?>
                            <div class="text-holder">
                                <?php if ( $wp_rem_property_type_price_switch == 'on' && $wp_rem_property_price != '' && $property_widget_style == 'simple' ) { ?>
                                    <span class="property-price">
                                        <span class="new-price text-color">
                                            <?php
                                            if ( $wp_rem_property_price_options == 'on-call' ) {
                                                $phone_number = get_post_meta($property_id, 'wp_rem_phone_number_property', true);
                                                echo force_balance_tags($wp_rem_property_price).' '.$phone_number;
                                            } else {
                                                $property_info_price = wp_rem_property_price($property_id, $wp_rem_property_price, '<span class="guid-price">', '</span>');
                                                echo force_balance_tags($property_info_price);
                                            }
                                            ?>
                                        </span>
                                    </span>
                                <?php } ?>
                                <?php if ( $property_widget_style == 'simple' ) { ?>
                                    <a href="<?php echo esc_url(get_permalink($property_id)); ?>"><?php echo wp_trim_words(get_the_title($property_id), $property_title_length); ?></a>
                                <?php } else { ?>
                                    <div class="post-title">
                                        <h4><a href="<?php echo esc_url(get_permalink($property_id)); ?>"><?php echo wp_trim_words(get_the_title($property_id), $property_title_length); ?></a></h4> 
                                    </div>
                                <?php } ?>
                                <?php if ( $wp_rem_property_type_price_switch == 'on' && $wp_rem_property_price != '' && $property_widget_style != 'simple' && $property_widget_style != 'modern' ) { ?>
                                    <span class="property-price">
                                        <span class="new-price text-color">
                                            <?php
                                            if ( $wp_rem_property_price_options == 'on-call' ) {
                                                $phone_number = get_post_meta($property_id, 'wp_rem_phone_number_property', true);
                                                echo force_balance_tags($wp_rem_property_price).' '.$phone_number;
                                            } else {
                                                $property_info_price = wp_rem_property_price($property_id, $wp_rem_property_price, '<span class="guid-price">', '</span>');
                                                echo force_balance_tags($property_info_price);
                                            }
                                            ?>
                                        </span>
                                    </span>
                                <?php }
                                ?>
                                <?php if ( ! empty($get_property_location) && ( $property_widget_style == 'simple' || $property_widget_style == 'modern') ) { ?>
                                    <ul class="location-list">
                                        <li><i class="icon-map-marker"></i><span><?php echo esc_html(implode(' / ', $get_property_location)); ?></span></li>
                                    </ul>
                                <?php } ?>


                            </div>
                        </div> 
                        <?php
                    endwhile;
                    ?>
                </div>
                <?php
            } else {
                echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-member-match-error"><h6><i class="icon-warning"></i><strong> ' . wp_rem_plugin_text_srt('wp_rem_top_properties_widget_sorry') . '</strong>&nbsp; ' . wp_rem_plugin_text_srt('wp_rem_top_properties_widget_dosen_match') . ' </h6></div>';
            }
            echo balanceTags($after_widget, false);
        }

    }

}
    add_action('widgets_init', function() {
        register_widget('wp_rem_top_properties');
    });



