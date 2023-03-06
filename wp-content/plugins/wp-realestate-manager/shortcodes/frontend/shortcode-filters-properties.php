<?php
/**
 * File Type: Properties Shortcode Frontend
 */
if ( ! class_exists('Wp_rem_Shortcode_Properties_with_Filters_Frontend') ) {

    class Wp_rem_Shortcode_Properties_with_Filters_Frontend {

        /**
         * Constant variables
         */
        var $PREFIX = 'wp_rem_properties_with_filters';

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_shortcode($this->PREFIX, array( $this, 'wp_rem_properties_shortcode_callback' ));
            add_action('wp_ajax_wp_rem_properties_filters_content', array( $this, 'wp_rem_properties_filters_content_callback' ));
            add_action('wp_ajax_nopriv_wp_rem_properties_filters_content', array( $this, 'wp_rem_properties_filters_content_callback' ));
            add_action('wp_rem_property_pagination', array( $this, 'wp_rem_property_pagination_callback' ), 11, 1);
        }

        /*
         * Shortcode View on Frontend
         */

        public function wp_rem_properties_shortcode_callback($atts, $content = "") {
            wp_enqueue_script('wp-rem-property-functions');
            wp_enqueue_script('jquery-mixitup');
            wp_enqueue_script('wp-rem-matchHeight-script');
			do_action('wp_rem_notes_frontend_modal_popup');
            $property_short_counter = rand(10000000, 99999999);
            $page_element_size = isset($atts['wp_rem_properties_element_size']) ? $atts['wp_rem_properties_element_size'] : 100;

            if ( function_exists('wp_rem_cs_var_page_builder_element_sizes') ) {
                echo '<div class="' . wp_rem_cs_var_page_builder_element_sizes($page_element_size,$atts) . ' ">';
            }
            do_action('property_checks_enquire_lists_submit');
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
                $this->wp_rem_properties_filters_content($property_arg);
                ?>
            </div>   
            <?php
            if ( function_exists('wp_rem_cs_var_page_builder_element_sizes') ) {
                echo '</div>';
            }
            $wp_rem_cs_inline_script = 'jQuery(document).ready(function($) {
                var wrapHeight;
//                $(window).load(function() {
//                    wrapHeight=$(".real-estate-property .tab-content > .tab-pane.active").outerHeight();
//                    $(".real-estate-property .tab-content").height(wrapHeight);
//                    $(".real-estate-property").addClass("tabs-loaded");
//                });
                $(window).resize(function(){
                    wrapHeight=$(".real-estate-property .tab-content > .tab-pane.active").outerHeight();
                    $(".real-estate-property.tabs-loaded .tab-content").height(wrapHeight);
                });
                $(\'.real-estate-property a[data-toggle="tab"]\').on("shown.bs.tab", function (e) {
                   e.target
                   e.relatedTarget
                   var target=$(e.target).attr("href");
                   var prevTarget=$(e.relatedTarget).attr("href");
                   var wrapHeight=$(target).outerHeight();
                   $(".real-estate-property .tab-content").height(wrapHeight);
                   $(prevTarget).addClass("active-moment").find(".animated").removeClass("slideInUp").addClass("fadeOutDown");
                   $(target).find(".animated").addClass("slideInUp").removeClass("fadeOutDown");
                   setTimeout(function(){
                      $(prevTarget).removeClass("active-moment").find(".animated").removeClass("fadeOutDown");
                    }, 800);
                    if($(".tab-pane").length>0){
                        $(target).find(".property-grid.v1").matchHeight._update();
                        $(target).find(".property-grid.modern.v2 .text-holder").matchHeight._update();
                        $(target).find(".property-grid.modern.v1 .text-holder").matchHeight._update();
                    }

                });
            });';
            wp_rem_cs_inline_enqueue_script($wp_rem_cs_inline_script, 'wp-rem-custom-inline');
        }

        public function wp_rem_properties_filters_content($property_arg = '') {
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
            $rem_shortcode_counter = $property_short_counter;
            wp_enqueue_script('wp-rem-prettyPhoto');
            wp_enqueue_style('wp-rem-prettyPhoto');
            $wp_rem_cs_inline_script = '
                jQuery(document).ready(function () {
                     jQuery("a.property-video-btn[data-rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:"fast",slideshow:10000, hideflash: true,autoplay:true,autoplay_slideshow:false});
                    });';
            wp_rem_cs_inline_enqueue_script($wp_rem_cs_inline_script, 'wp-rem-custom-inline');

            $posts_per_page = '-1';
            $pagination = 'no';
            $element_filter_arr = array();
            $content_columns = 'col-lg-12 col-md-12 col-sm-12 col-xs-12'; // if filteration not true
            $paging_var = 'paged_id';
            $default_date_time_formate = 'd-m-Y H:i:s';
            // element attributes
            $property_view = isset($atts['property_view']) ? $atts['property_view'] : '';
            $property_property_featured = isset($atts['property_featured']) ? $atts['property_featured'] : '';
            $property_type = isset($atts['filters_property_type']) ? $atts['filters_property_type'] : '';
            $posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '-1';
            $pagination = isset($atts['pagination']) ? $atts['pagination'] : 'no';
			
			
			if( $property_type == '' || $property_type == 'all' ){
				$property_types_data = array();
				$wp_rem_property_args = array( 'posts_per_page' => '-1', 'post_type' => 'property-type', 'orderby' => 'title', 'post_status' => 'publish', 'order' => 'ASC', 'suppress_filters' => false );
				$cust_query = get_posts($wp_rem_property_args);
				if ( is_array($cust_query) && sizeof($cust_query) > 0 ) {
					foreach ( $cust_query as $wp_rem_property_type ) {
						$property_types_data[] = $wp_rem_property_type->post_name;
					}
				}
				$property_type = implode(',', $property_types_data);
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

            if ( $property_property_featured == 'only-featured' || $property_property_featured == '' ) {
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
            
            ?>
            <div class="real-estate-property show-more-property <?php echo esc_html($property_view); ?>">
                <?php
                $filters_properties_title = isset($atts['filters_properties_title']) ? $atts['filters_properties_title'] : '';
                $properties_subtitle = isset($atts['properties_subtitle']) ? $atts['properties_subtitle'] : '';
                $properties_filters_alagnment = isset($atts['properties_filters_alagnment']) ? $atts['properties_filters_alagnment'] : '';
                $show_more_button = isset($atts['show_more_button']) ? $atts['show_more_button'] : '';
                $show_more_button_url = isset($atts['show_more_button_url']) ? $atts['show_more_button_url'] : '';
                $wp_rem_filter_properties_seperator_style = isset($atts['wp_rem_filter_properties_seperator_style']) ? $atts['wp_rem_filter_properties_seperator_style'] : '';
                $wp_rem_filter_properties_element_title_color = isset($atts['wp_rem_filter_properties_element_title_color']) ? $atts['wp_rem_filter_properties_element_title_color'] : '';
                $wp_rem_filter_properties_element_subtitle_color = isset($atts['wp_rem_filter_properties_element_subtitle_color']) ? $atts['wp_rem_filter_properties_element_subtitle_color'] : '';
                $element_title_color = '';
                if ( isset($wp_rem_filter_properties_element_title_color) && $wp_rem_filter_properties_element_title_color != '' ) {
                    $element_title_color = ' style="color:' . $wp_rem_filter_properties_element_title_color . ' ! important"';
                }
                $element_subtitle_color = '';
                if ( isset($wp_rem_filter_properties_element_subtitle_color) && $wp_rem_filter_properties_element_subtitle_color != '' ) {
                    $element_subtitle_color = ' style="color:' . $wp_rem_filter_properties_element_subtitle_color . ' ! important"';
                }
                ?>
                <div class="element-title <?php echo ($properties_filters_alagnment); ?>">
                    <?php if ( $filters_properties_title != '' ) { ?>
                        <h2<?php echo wp_rem_allow_special_char($element_title_color); ?>><?php echo esc_html($filters_properties_title); ?></h2>
                    <?php } ?>
                    <?php if ( $properties_subtitle != '' ) { ?>
                        <p<?php echo wp_rem_allow_special_char($element_subtitle_color); ?>><?php echo esc_html($properties_subtitle); ?></p>
                        <?php
                    }

                    if ( isset($wp_rem_filter_properties_seperator_style) && ! empty($wp_rem_filter_properties_seperator_style) ) {
                        $wp_rem_featured_properties_seperator_html = '';
                        if ( $wp_rem_filter_properties_seperator_style == 'classic' ) {
                            $wp_rem_featured_properties_seperator_html .='<div class="classic-separator ' . $properties_filters_alagnment . '"><span></span></div>';
                        }
                        if ( $wp_rem_filter_properties_seperator_style == 'zigzag' ) {
                            $wp_rem_featured_properties_seperator_html .='<div class="separator-zigzag ' . $properties_filters_alagnment . '">
                                            <figure><img src="' . trailingslashit(wp_rem::plugin_url()) . 'assets/images/zigzag-img1.png" alt=""/></figure>
                                        </div>';
                        }
                        echo force_balance_tags($wp_rem_featured_properties_seperator_html);
                    }
                    ?>
                    <ul id="filters" class="clearfix">
                        <?php
                        if ( isset($property_type) && ! empty($property_type) ) {
                            $property_type = explode(',', $property_type);
                            $active_tab = 'active';
                            $count = 1;
                            foreach ( $property_type as $type_slug ) {
                                $type_obj = get_page_by_path($type_slug, OBJECT, 'property-type');
                                if ( is_object($type_obj) ) {
                                    ?>
                                    <li class="tab<?php echo intval($property_short_counter . $count); ?> <?php echo esc_html($active_tab); ?>"><span><a data-toggle="tab" href="#tab<?php echo intval($property_short_counter . $count); ?>">
                                                <?php
                                                if ( $property_property_featured == 'only-featured' || $property_property_featured == '' ) {
                                                    echo wp_rem_plugin_text_srt('wp_rem_listfilter_advanced') . ' ';
                                                }
                                                ?><?php echo esc_html($type_obj->post_title); ?>
                                            </a></span></li>
                                    <?php
                                    $active_tab = '';
                                    $count ++;
                                }
                            }
                        }
                        ?>
                    </ul>
                    <?php if ( $show_more_button == 'yes' && $show_more_button_url != '' && $property_view != 'v2' ) { ?>
                        <a href="<?php echo esc_url($show_more_button_url); ?>" class="show-more-property"><?php echo wp_rem_plugin_text_srt('wp_rem_listfilter_showmore'); ?></a>
                    <?php } ?>
                </div>
                <?php if ( isset($property_type) && ! empty($property_type) ) { ?>
                    <div class="row">
                        <div class="<?php echo esc_html($content_columns); ?>">
                            <div class="tab-content clearfix">
                                <?php
                                $count = 1;
                                foreach ( $property_type as $type_slug ) {
                                    if ( is_object($type_obj) ) {
                                        $type_args = $args;
                                        $type_args['meta_query'][] = array(
                                            'key' => 'wp_rem_property_type',
                                            'value' => $type_slug,
                                            'compare' => '=',
                                        );
                                        $property_short_counter = rand(12345, 54321);
                                        $property_loop_obj = wp_rem_get_cached_obj('property_result_cached_loop_obj', $type_args, 12, false, 'wp_query');
                                        $property_found_count = $property_loop_obj->found_posts;

                                        $type_obj = get_page_by_path($type_slug, OBJECT, 'property-type');
                                        $current_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : '';
                                        $active_class = ( $count == 1 ) ? 'active' : '';
                                        if ( isset($current_tab) && $current_tab == $type_slug ) {
                                            $active_class = 'active';
                                        }
                                        ?>
                                        <div class="tab-pane in <?php echo esc_attr($active_class); ?>" id="tab<?php echo intval($rem_shortcode_counter . $count); ?>">
                                            <?php
                                            $wp_rem_form_fields_frontend->wp_rem_form_hidden_render(
                                                    array(
                                                        'return' => false,
                                                        'cust_name' => '',
                                                        'classes' => 'property-counter',
                                                        'std' => $property_short_counter,
                                                    )
                                            );
                                            ?>
                                            <div style="display:none" id='property_arg<?php echo absint($property_short_counter); ?>'>
                                                <?php $property_arg['property_short_counter'] = $property_short_counter; ?>
                                                <?php echo json_encode($property_arg); ?>
                                            </div>
                                            <div id="property-tab-content-<?php echo esc_attr($property_short_counter); ?>">
                                                <?php
                                                set_query_var('property_loop_obj', $property_loop_obj);
                                                set_query_var('property_short_counter', $property_short_counter);
                                                set_query_var('atts', $atts);
                                                wp_rem_get_template_part('property', 'filters-grid', 'properties');
                                                // apply paging
                                                $paging_args = array(
                                                    'property_view' => $property_view,
                                                    'tab' => $type_slug,
                                                    'total_posts' => $property_found_count,
                                                    'posts_per_page' => $posts_per_page,
                                                    'paging_var' => $paging_var,
                                                    'show_pagination' => $pagination,
                                                    'property_short_counter' => $property_short_counter,
                                                );
                                                $this->wp_rem_property_pagination_callback($paging_args);
                                                ?>
                                            </div>
                                        </div>
                                        <?php wp_reset_postdata(); ?>
                                        <?php
                                        $count ++;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php
        }

        public function wp_rem_properties_filters_content_callback($property_arg = '') {
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

            $posts_per_page = '-1';
            $pagination = 'no';
            $element_filter_arr = '';
            $content_columns = 'col-lg-12 col-md-12 col-sm-12 col-xs-12'; // if filteration not true
            $paging_var = 'paged_id';
            $default_date_time_formate = 'd-m-Y H:i:s';
            // element attributes
            $property_property_featured = isset($atts['property_featured']) ? $atts['property_featured'] : 'all';
            $property_type = isset($atts['filters_property_type']) ? $atts['filters_property_type'] : '';
            $posts_per_page = isset($atts['posts_per_page']) ? $atts['posts_per_page'] : '-1';
            $pagination = isset($atts['pagination']) ? $atts['pagination'] : 'no';

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

            if ( isset($_REQUEST['tab']) && $_REQUEST['tab'] != '' ) {
                $element_filter_arr[] = array(
                    'key' => 'wp_rem_property_type',
                    'value' => $_REQUEST['tab'],
                    'compare' => '=',
                );
            }
            $element_filter_arr[] = array(
                'key' => 'wp_rem_property_is_featured',
                'value' => 'on',
                'compare' => '=',
            );


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

            $tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'for-sale';
            $property_loop_obj = wp_rem_get_cached_obj('property_result_cached_loop_obj', $args, 12, false, 'wp_query');
            $property_found_count = $property_loop_obj->found_posts;
            set_query_var('property_loop_obj', $property_loop_obj);
            set_query_var('property_short_counter', $property_short_counter);
            set_query_var('atts', $atts);
            wp_rem_get_template_part('property', 'filters-grid', 'properties');
            // apply paging
            $paging_args = array(
                'tab' => $tab,
                'total_posts' => $property_found_count,
                'posts_per_page' => $posts_per_page,
                'paging_var' => $paging_var,
                'show_pagination' => $pagination,
                'property_short_counter' => $property_short_counter,
            );
            $this->wp_rem_property_pagination_callback($paging_args);
            wp_reset_postdata();
            wp_die();
        }

        public function toArray($obj) {
            if ( is_object($obj) ) {
                $obj = (array) $obj;
            }
            if ( is_array($obj) ) {
                $new = array();
                foreach ( $obj as $key => $val ) {
                    $new[$key] = $this->toArray($val);
                }
            } else {
                $new = $obj;
            }
            return $new;
        }

        public function wp_rem_property_pagination_callback($args) {
            global $wp_rem_form_fields_frontend;
            $total_posts = '';
            $posts_per_page = '5';
            $paging_var = 'paged_id';
            $show_pagination = 'yes';
            $tab = 'for-sale';
            $property_short_counter = '';

            extract($args);
            if ( $show_pagination <> 'yes' ) {
                return;
            } else if ( $total_posts <= $posts_per_page ) {
                return;
            } else {
                if ( ! isset($_REQUEST['page_id']) ) {
                    $_REQUEST['page_id'] = '';
                }
                $html = '';
                $dot_pre = '';
                $dot_more = '';
                $total_page = 0;
                if ( $total_posts <> 0 )
                    $total_page = ceil($total_posts / $posts_per_page);
                $paged_id = 1;
                if ( isset($_REQUEST[$paging_var]) && $_REQUEST[$paging_var] != '' ) {
                    $paged_id = $_REQUEST[$paging_var];
                }
                $loop_start = $paged_id - 2;

                $loop_end = $paged_id + 2;

                if ( $paged_id < 3 ) {

                    $loop_start = 1;

                    if ( $total_page < 5 )
                        $loop_end = $total_page;
                    else
                        $loop_end = 5;
                }
                else if ( $paged_id >= $total_page - 1 ) {

                    if ( $total_page < 5 )
                        $loop_start = 1;
                    else
                        $loop_start = $total_page - 4;

                    $loop_end = $total_page;
                }
                $html .= $wp_rem_form_fields_frontend->wp_rem_form_hidden_render(
                        array(
                            'simple' => true,
                            'cust_id' => $paging_var . '-' . $property_short_counter,
                            'cust_name' => $paging_var,
                            'std' => '',
                            'extra_atr' => 'onchange="wp_rem_property_filters_content(\'' . $property_short_counter . '\');"',
                        )
                );
                $html .= '<div class="row"><div class="portfolio grid-fading animated col-lg-12 col-md-12 col-sm-12 col-xs-12 page-nation"><ul class="pagination pagination-large">';
                if ( $paged_id > 1 ) {
                    $html .= '<li><a onclick="wp_rem_property_filters_pagenation_ajax(\'' . $paging_var . '\', \'' . ($paged_id - 1) . '\', \'' . ($property_short_counter) . '\' , \'' . ($tab) . '\');" href="javascript:void(0);">';
                    $html .= wp_rem_plugin_text_srt('wp_rem_shortcode_filter_prev') . '</a></li>';
                }
                if ( $paged_id > 3 and $total_page > 5 ) {


                    $html .= '<li><a onclick="wp_rem_property_filters_pagenation_ajax(\'' . $paging_var . '\', \'' . (1) . '\', \'' . ($property_short_counter) . '\', \'' . ($tab) . '\');" href="javascript:void(0);">';
                    $html .= '1</a></li>';
                }
                if ( $paged_id > 4 and $total_page > 6 ) {
                    $html .= '<li class="disabled"><span><a>. . .</a></span><li>';
                }

                if ( $total_page > 1 ) {

                    for ( $i = $loop_start; $i <= $loop_end; $i ++ ) {

                        if ( $i <> $paged_id ) {

                            $html .= '<li><a onclick="wp_rem_property_filters_pagenation_ajax(\'' . $paging_var . '\', \'' . ($i) . '\', \'' . ($property_short_counter) . '\', \'' . ($tab) . '\');" href="javascript:void(0);">';
                            $html .= $i . '</a></li>';
                        } else {
                            $html .= '<li class="active"><span><a class="page-numbers active">' . $i . '</a></span></li>';
                        }
                    }
                }
                if ( $loop_end <> $total_page and $loop_end <> $total_page - 1 ) {
                    $html .= '<li><a>. . .</a></li>';
                }
                if ( $loop_end <> $total_page ) {
                    $html .= '<li><a onclick="wp_rem_property_filters_pagenation_ajax(\'' . $paging_var . '\', \'' . ($total_page) . '\', \'' . ($property_short_counter) . '\', \'' . ($tab) . '\');" href="javascript:void(0);">';
                    $html .= $total_page . '</a></li>';
                }
                if ( $total_posts > 0 and $paged_id < ($total_posts / $posts_per_page) ) {
                    $html .= '<li><a onclick="wp_rem_property_filters_pagenation_ajax(\'' . $paging_var . '\', \'' . ($paged_id + 1) . '\', \'' . ($property_short_counter) . '\', \'' . ($tab) . '\');" href="javascript:void(0);">';
                    $html .= wp_rem_plugin_text_srt('wp_rem_shortcode_filter_next') . '</a></li>';
                }
                $html .= "</ul></div></div>";
                echo force_balance_tags($html);
            }
        }

    }

    global $wp_rem_shortcode_properties_filters_frontend;
    $wp_rem_shortcode_properties_filters_frontend = new Wp_rem_Shortcode_Properties_with_Filters_Frontend();
}
    