<?php
/**
 * File Type: Properties Shortcode Frontend
 */
if ( ! class_exists('Wp_rem_Shortcode_Featured_Properties_Frontend') ) {

    class Wp_rem_Shortcode_Featured_Properties_Frontend {

        /**
         * Constant variables
         */
        var $PREFIX = 'wp_rem_featured_properties';

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_rem_featured_properties_shortcode_callback' ));
            add_action('wp_ajax_wp_rem_featured_properties_content', array( $this, 'wp_rem_featured_properties_content_callback' ));
            add_action('wp_ajax_nopriv_wp_rem_featured_properties_content', array( $this, 'wp_rem_featured_properties_content_callback' ));
            add_action('wp_rem_property_pagination', array( $this, 'wp_rem_property_pagination_callback' ), 11, 1);
        }

        /*
         * Shortcode View on Frontend
         */

        public function wp_rem_featured_properties_shortcode_callback($atts, $content = "") {
            wp_enqueue_script('wp-rem-property-functions');
            do_action('wp_rem_notes_frontend_modal_popup');
            $property_short_counter = rand(10000000, 99999999);
            $page_element_size = isset($atts['wp_rem_properties_element_size']) ? $atts['wp_rem_properties_element_size'] : 100;

            if ( function_exists('wp_rem_cs_var_page_builder_element_sizes') ) {
                echo '<div class="' . wp_rem_cs_var_page_builder_element_sizes($page_element_size, $atts) . ' ">';
            }

             do_action('wp_rem_property_compare_sidebar');
            do_action('wp_rem_property_enquiries_sidebar');
            ?>
            <div class="wp-rem-property-content" id="wp-rem-property-content-<?php echo esc_html($property_short_counter); ?>">
                <?php
                $property_arg = array(
                    'property_short_counter' => $property_short_counter,
                    'atts' => $atts,
                    'content' => $content,
                    'page_url' => get_permalink(get_the_ID()),
                );
                $this->wp_rem_featured_properties_content($property_arg);
                ?>
            </div>   
            <?php
            if ( function_exists('wp_rem_cs_var_page_builder_element_sizes') ) {
                echo '</div>';
            }
        }

        public function wp_rem_featured_properties_content($property_arg = '') {
            global $wpdb, $wp_rem_form_fields_frontend, $wp_rem_search_fields;

            // getting arg array from ajax
            if ( isset($_REQUEST['property_arg']) && $_REQUEST['property_arg'] ) {
                $property_arg = $_REQUEST['property_arg'];
                $property_arg = json_decode(str_replace('\"', '"', $property_arg));
                $property_arg = $this->toArray($property_arg);
            }
            if ( isset($property_arg) && $property_arg != '' && ! empty($property_arg) ) {
                extract($property_arg);
            }

            $posts_per_page = '6';

            $element_filter_arr = array();
            $content_columns = 'col-lg-12 col-md-12 col-sm-12 col-xs-12'; // if filteration not true
            $paging_var = 'paged_id';
            $default_date_time_formate = 'd-m-Y H:i:s';

            // element attributes
            $featured_properties_title = isset($atts['featured_properties_title']) ? $atts['featured_properties_title'] : '';
            $properties_subtitle = isset($atts['properties_subtitle']) ? $atts['properties_subtitle'] : '';
            $featured_properties_title_alignment = isset($atts['featured_properties_title_alignment']) ? $atts['featured_properties_title_alignment'] : '';
            $property_view = isset($atts['property_view']) ? $atts['property_view'] : '';
            $property_featured = isset($atts['property_featured']) ? $atts['property_featured'] : 'all';
            $property_type = isset($atts['property_type']) ? $atts['property_type'] : '';
            $posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '6';
            $wp_rem_featured_properties_seperator_style = isset($atts['wp_rem_featured_properties_seperator_style']) ? $atts['wp_rem_featured_properties_seperator_style'] : '6';

            $wp_rem_featured_property_element_title_color = isset($atts['wp_rem_featured_property_element_title_color']) ? $atts['wp_rem_featured_property_element_title_color'] : '';
            $wp_rem_featured_property_element_subtitle_color = isset($atts['wp_rem_featured_property_element_subtitle_color']) ? $atts['wp_rem_featured_property_element_subtitle_color'] : '';
            $element_title_color = '';
            if ( isset($wp_rem_featured_property_element_title_color) && $wp_rem_featured_property_element_title_color != '' ) {
                $element_title_color = ' style="color:' . $wp_rem_featured_property_element_title_color . ' ! important"';
            }
            $element_subtitle_color = '';
            if ( isset($wp_rem_featured_property_element_subtitle_color) && $wp_rem_featured_property_element_subtitle_color != '' ) {
                $element_subtitle_color = ' style="color:' . $wp_rem_featured_property_element_subtitle_color . ' ! important"';
            }


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

            if ( $property_type != '' && $property_type != 'all' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_rem_property_type',
                    'value' => $property_type,
                    'compare' => '=',
                );
            }

            if ( $property_featured == 'only-featured' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_rem_property_is_featured',
                    'value' => 'on',
                    'compare' => '=',
                );
            }
			
			if ( function_exists('wp_rem_property_visibility_query_args') ) {
				$element_filter_arr = wp_rem_property_visibility_query_args($element_filter_arr);
			}

            $paged = isset($_REQUEST[$paging_var]) ? $_REQUEST[$paging_var] : 1;
            $args = array(
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'post_type' => 'properties',
                'post_status' => 'publish',
                'fields' => 'ids', // only load ids
                'meta_query' => array(
                    $element_filter_arr,
                ),
            );

            $property_loop_obj = wp_rem_get_cached_obj('property_result_cached_loop_obj', $args, 12, false, 'wp_query');
            $property_totnum = $property_loop_obj->found_posts;



            if ( $property_view == 'single' ) {
                if ( $featured_properties_title != '' || $properties_subtitle != '' ) {
                    ?>
                    <div class="element-title <?php echo ($featured_properties_title_alignment); ?>">
                        <?php if ( $featured_properties_title ) { ?>
                            <h2<?php echo wp_rem_allow_special_char($element_title_color); ?>><?php echo esc_html($featured_properties_title); ?></h2>
                        <?php } ?>
                        <?php if ( $properties_subtitle ) { ?>
                            <p<?php echo wp_rem_allow_special_char($element_subtitle_color); ?>><?php echo esc_html($properties_subtitle); ?></p>
                            <?php
                        }
                        if ( isset($wp_rem_featured_properties_seperator_style) && ! empty($wp_rem_featured_properties_seperator_style) ) {
                            $wp_rem_featured_properties_seperator_html = '';
                            if ( $wp_rem_featured_properties_seperator_style == 'classic' ) {
                                $wp_rem_featured_properties_seperator_html .='<div class="classic-separator ' . $featured_properties_title_alignment . '"><span></span></div>';
                            }
                            if ( $wp_rem_featured_properties_seperator_style == 'zigzag' ) {
                                $wp_rem_featured_properties_seperator_html .='<div class="separator-zigzag ' . $featured_properties_title_alignment . '">
                                            <figure><img src="' . trailingslashit(wp_rem::plugin_url()) . 'assets/images/zigzag-img1.png" alt=""/></figure>
                                        </div>';
                            }
                            echo force_balance_tags($wp_rem_featured_properties_seperator_html);
                        }
                        ?>
                    </div>
                    <?php
                }
                set_query_var('property_loop_obj', $property_loop_obj);
                set_query_var('property_short_counter', $property_short_counter);
                set_query_var('atts', $atts);
                wp_rem_get_template_part('property', 'featured-single', 'properties');
            } else {
				wp_enqueue_style('swiper');
				wp_enqueue_script('swiper');
                ?>
                <div class="real-estate-property">
                    <div class="element-title <?php echo ($featured_properties_title_alignment); ?>">
                        <?php if ( $featured_properties_title ) { ?>
                            <h2<?php echo wp_rem_allow_special_char($element_title_color); ?>><?php echo esc_html($featured_properties_title); ?></h2>
                        <?php } ?>
                        <?php if ( $properties_subtitle ) { ?>
                            <p<?php echo wp_rem_allow_special_char($element_subtitle_color); ?>><?php echo esc_html($properties_subtitle); ?></p>
                        <?php
                        }
                        if ( isset($wp_rem_featured_properties_seperator_style) && ! empty($wp_rem_featured_properties_seperator_style) ) {
                            $wp_rem_featured_properties_seperator_html = '';
                            if ( $wp_rem_featured_properties_seperator_style == 'classic' ) {
                                $wp_rem_featured_properties_seperator_html .='<div class="classic-separator ' . $featured_properties_title_alignment . '"><span></span></div>';
                            }
                            if ( $wp_rem_featured_properties_seperator_style == 'zigzag' ) {
                                $wp_rem_featured_properties_seperator_html .='<div class="separator-zigzag ' . $featured_properties_title_alignment . '">
                                            <figure><img src="' . trailingslashit(wp_rem::plugin_url()) . 'assets/images/zigzag-img1.png" alt=""/></figure>
                                        </div>';
                            }
                            echo force_balance_tags($wp_rem_featured_properties_seperator_html);
                        }
                        ?>
                        <div class="pull-right">
                            <div class="swiper-button-next default"><i class="icon-keyboard_arrow_left"></i></div>
                            <div class="swiper-button-prev default"><i class="icon-keyboard_arrow_right"></i></div>
                        </div>
                    </div>
                    <div class="swiper-container grid-default-slider featured-multiple-properties-<?php echo $property_short_counter; ?>">
                        <div class="swiper-wrapper">
                            <?php
                            set_query_var('property_loop_obj', $property_loop_obj);
                            set_query_var('property_short_counter', $property_short_counter);
                            set_query_var('atts', $atts);
                            wp_rem_get_template_part('property', 'featured-multiple', 'properties');
                            ?>
                        </div>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            if ("" != jQuery(".featured-multiple-properties-<?php echo $property_short_counter; ?>").length) {
                                var mySlider= new Swiper(".featured-multiple-properties-<?php echo $property_short_counter; ?>", {
                                    slidesPerView: 3,
                                    paginationClickable: !1,
                                    nextButton: ".swiper-button-prev.default",
                                    prevButton: ".swiper-button-next.default",
                                    spaceBetween: 30,
                                    speed: 2000,
                                    onInit: function (swiper) {
                                        jQuery.fn.matchHeight._update();
                                    },
                                    breakpoints: {
                                        991: {
                                            slidesPerView: 2,
                                            spaceBetween: 20
                                        },
                                        600: {
                                            slidesPerView: 1,
                                            spaceBetween: 15
                                        }
                                    }
                                });
                                var elementWidth = $(".wp-rem-property-content").width();
                                if (elementWidth<992 && elementWidth>600) mySlider.params.slidesPerView = 2;
                                if (elementWidth<600) mySlider.params.slidesPerView = 1;
                                mySlider.update();
                                $(window).trigger('resize');
                            }
                        });
                    </script>
                </div>
                <?php
            }
        }

    }

    global $wp_rem_shortcode_properties_filters_frontend;
    $wp_rem_shortcode_properties_filters_frontend = new Wp_rem_Shortcode_Featured_Properties_Frontend();
}
    