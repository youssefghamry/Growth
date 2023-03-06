<?php
/**
 * File Type: Nearby Properties Page Element
 */
if ( ! class_exists('wp_rem_nearby_properties_element') ) {

    class wp_rem_nearby_properties_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_rem_nearby_properties_element_html', array( $this, 'wp_rem_nearby_properties_element_html_callback' ), 11, 1);
        }

        public function wp_rem_nearby_properties_element_html_callback($property_id = '') {

            global $post, $wp_rem_plugin_options, $wp_rem_post_property_types;
            wp_enqueue_script('wp-rem-prettyPhoto');
            wp_enqueue_style('wp-rem-prettyPhoto');
			wp_enqueue_style('swiper');
            wp_enqueue_script('swiper');
            $http_request = wp_rem_server_protocol();
            $wp_rem_cs_inline_script = '
                jQuery(document).ready(function () {
                     jQuery("a.property-video-btn[data-rel^=\'prettyPhoto\']").prettyPhoto({animation_speed:"fast",slideshow:10000, hideflash: true,autoplay:true,autoplay_slideshow:false});
                });';
            wp_rem_cs_inline_enqueue_script($wp_rem_cs_inline_script, 'wp-rem-custom-inline');
            $default_property_no_custom_fields = isset($wp_rem_plugin_options['wp_rem_property_no_custom_fields']) ? $wp_rem_plugin_options['wp_rem_property_no_custom_fields'] : '';
            if ( $property_id != '' ) {
                $wp_rem_default_radius_circle = isset($wp_rem_plugin_options['wp_rem_default_radius_circle']) ? $wp_rem_plugin_options['wp_rem_default_radius_circle'] : '';
                $property_address = get_post_meta($property_id, 'wp_rem_post_loc_address_property', true);
                $property_latitude = get_post_meta($property_id, 'wp_rem_post_loc_latitude_property', true);
                $property_longitude = get_post_meta($property_id, 'wp_rem_post_loc_longitude_property', true);
                if ( $property_address != '' && $wp_rem_default_radius_circle > 0 ) {
                    $location_rslt = $this->property_nearby_filter($property_address, $wp_rem_default_radius_circle, $property_latitude, $property_longitude, $property_id);
                    $wp_rem_base_query_args = '';
                    if ( function_exists('wp_rem_base_query_args') ) {
                        $wp_rem_base_query_args = wp_rem_base_query_args();
                    }
                    if ( function_exists('wp_rem_property_visibility_query_args') ) {
                        $wp_rem_base_query_args = wp_rem_property_visibility_query_args($wp_rem_base_query_args);
                    }
                    if ( $location_rslt == '' || empty($location_rslt) ) {
                        $location_rslt = array( 0 );
                    }
                    $args = array(
                        'post_type' => 'properties',
                        'posts_per_page' => 10,
                        'post__in' => $location_rslt,
                        'meta_query' => array(
                            'relation' => 'AND',
                            $wp_rem_base_query_args,
                        ),
                    );
                    $rel_qry = new WP_Query($args);
                    if ( $rel_qry->have_posts() ) {
                        $flag = 1;
                        ?>
                        <div class="page-section detail-nearby-properties">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="property-grid-slider real-estate-property">
                                    <div class="element-title">
                                        <h3><?php echo wp_rem_plugin_text_srt('wp_rem_nearby_properties_heading'); ?></h3>
                                    </div>
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            <?php
                                            $list_count = 1;
                                            while ( $rel_qry->have_posts() ) : $rel_qry->the_post();
                                                global $post, $wp_rem_member_profile;
                                                $property_id = $post->ID;
                                                $post_id = $post->ID;
                                                $gallery_image_count = '';
                                                $property_random_id = rand(1111111, 9999999);
                                                $Wp_rem_Locations = new Wp_rem_Locations();
                                                $property_location = $Wp_rem_Locations->get_location_by_property_id($property_id);
                                                $wp_rem_property_username = get_post_meta($property_id, 'wp_rem_property_username', true);
                                                $wp_rem_property_is_featured = get_post_meta($property_id, 'wp_rem_property_is_featured', true);
                                                $wp_rem_profile_image = $wp_rem_member_profile->member_get_profile_image($wp_rem_property_username);
                                                $wp_rem_property_price_options = get_post_meta($property_id, 'wp_rem_property_price_options', true);
                                                $wp_rem_property_type = get_post_meta($property_id, 'wp_rem_property_type', true);
                                                // checking review in on in property type
                                                $wp_rem_property_type = isset($wp_rem_property_type) ? $wp_rem_property_type : '';
                                                if ( $property_type_post = get_page_by_path($wp_rem_property_type, OBJECT, 'property-type') )
                                                    $property_type_id = $property_type_post->ID;
                                                $property_type_id = isset($property_type_id) ? $property_type_id : '';
                                                $property_type_id = wp_rem_wpml_lang_page_id($property_type_id, 'property-type');
                                                $wp_rem_user_reviews = get_post_meta($property_type_id, 'wp_rem_user_reviews', true);
                                                $wp_rem_property_type_price_switch = get_post_meta($property_type_id, 'wp_rem_property_type_price', true);
                                                // end checking review on in property type
                                                $wp_rem_property_price = '';
                                                if ( $wp_rem_property_price_options == 'price' ) {
                                                    $wp_rem_property_price = get_post_meta($property_id, 'wp_rem_property_price', true);
                                                } else if ( $wp_rem_property_price_options == 'on-call' ) {
                                                    $wp_rem_property_price = wp_rem_plugin_text_srt('wp_rem_nearby_properties_price_on_request');
                                                }
                                                // get all categories
                                                $wp_rem_cate = '';
                                                $wp_rem_cate_str = '';
                                                $wp_rem_property_category = get_post_meta($property_id, 'wp_rem_property_category', true);
                                                if ( ! empty($wp_rem_property_category) && is_array($wp_rem_property_category) ) {
                                                    $comma_flag = 0;
                                                    foreach ( $wp_rem_property_category as $cate_slug => $cat_val ) {
                                                        $wp_rem_cate = get_term_by('slug', $cat_val, 'property-category');
                                                        if ( ! empty($wp_rem_cate) ) {
                                                            $cate_link = wp_rem_property_category_link($property_type_id, $cat_val);
                                                            if ( $comma_flag != 0 ) {
                                                                $wp_rem_cate_str .= ', ';
                                                            }
                                                            $wp_rem_cate_str = '<a href="' . $cate_link . '">' . $wp_rem_cate->name . '</a>';
                                                            $comma_flag ++;
                                                        }
                                                    }
                                                }
                                                $nearby_property_id = $post->ID;
                                                $wp_rem_property_nearby_price_options = get_post_meta($nearby_property_id, 'wp_rem_property_price_options', true);
                                                $wp_rem_property_nearby_price = '';
                                                $wp_rem_property_price = '';
                                                if ( $wp_rem_property_nearby_price_options == 'price' ) {
                                                    $wp_rem_property_nearby_price = get_post_meta($nearby_property_id, 'wp_rem_property_price', true);
                                                } else if ( $wp_rem_property_nearby_price_options == 'on-call' ) {
                                                    $wp_rem_property_nearby_price = wp_rem_plugin_text_srt('wp_rem_nearby_properties_price_on_request');
                                                }
                                                $wp_rem_property_gallery_ids = get_post_meta($nearby_property_id, 'wp_rem_detail_page_gallery_ids', true);
                                                
                                                $gallery_image_count = is_array($wp_rem_property_gallery_ids)? count($wp_rem_property_gallery_ids) : 0;
                                                $wp_rem_property_type = get_post_meta($nearby_property_id, 'wp_rem_property_type', true);
                                                $wp_rem_property_type = isset($wp_rem_property_type) ? $wp_rem_property_type : '';
                                                if ( $property_type_post = get_page_by_path($wp_rem_property_type, OBJECT, 'property-type') )
                                                    $property_type_nearby_id = $property_type_post->ID;
                                                $property_type_nearby_id = wp_rem_wpml_lang_page_id($property_type_nearby_id, 'property-type');
                                                $wp_rem_property_type_price_nearby_switch = get_post_meta($property_type_nearby_id, 'wp_rem_property_type_price', true);
                                                $wp_rem_property_is_featured = get_post_meta($nearby_property_id, 'wp_rem_property_is_featured', true);
                                                
                                                /*
                                                 * Video and gallery from type 
                                                 */
                                                $wp_rem_video_element = get_post_meta($property_type_nearby_id, 'wp_rem_video_element', true);
                                                $wp_rem_image_gallery_element = get_post_meta($property_type_nearby_id, 'wp_rem_image_gallery_element', true);
                                                $wp_rem_video_element = isset($wp_rem_video_element) ? $wp_rem_video_element : '';
                                                $wp_rem_image_gallery_element = isset($wp_rem_image_gallery_element) ? $wp_rem_image_gallery_element : '';
                                                /*
                                                 * End Video and gallery 
                                                 */
                                                
                                                
                                                ?>
                                                <div class="swiper-slide" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product" >
                                                    <div class="property-grid">
                                                        <div class="img-holder">
                                                            <figure>
                                                                <a href="<?php the_permalink(); ?>">
                                                                    <?php
                                                                    if ( function_exists('property_gallery_first_image') ) {
                                                                        $gallery_image_args = array(
                                                                            'property_id' => $property_id,
                                                                            'size' => 'wp_rem_cs_media_5',
                                                                            'class' => 'img-grid',
                                                                            'default_image_src' => esc_url(wp_rem::plugin_url() . 'assets/frontend/images/no-image9x6.jpg')
                                                                        );
                                                                        echo $property_gallery_first_image = property_gallery_first_image($gallery_image_args);
                                                                    }
                                                                    ?></a>
                                                                <figcaption>
                                                                    <?php 
                                                                    wp_rem_property_sold_html($property_id);
                                                                    if ( $wp_rem_property_is_featured == 'on' ) { ?>
                                                                        <span class="featured"><?php echo wp_rem_plugin_text_srt('wp_rem_property_featrd'); ?></span>
                                                                    <?php } ?>
                                                                    <div class="caption-inner">
                                                                        <ul class="rem-property-options">
                                                                            <?php
                                                                            $figcaption_div = true;
                                                                            $book_mark_args = array(
                                                                                'before_html' => '<li class="property-like-opt"><div class="option-holder">',
                                                                                'after_html' => '</div></li>',
                                                                                'before_label' => wp_rem_plugin_text_srt('wp_rem_property_save_to_favourite'),
                                                                                'after_label' => wp_rem_plugin_text_srt('wp_rem_property_remove_to_favourite'),
                                                                                'before_icon' => 'icon-heart-o',
                                                                                'after_icon' => 'icon-heart5',
                                                                                'show_tooltip' => 'no',
                                                                            );
                                                                            do_action('wp_rem_property_favourite_button_frontend', $nearby_property_id, $book_mark_args, $figcaption_div);
                                                                            ?>
                                                                            <?php
                                                                            $property_video_url = get_post_meta($nearby_property_id, 'wp_rem_property_video', true);
                                                                            $property_video_url = isset($property_video_url) ? $property_video_url : '';
                                                                            if ( $property_video_url != '' && $wp_rem_video_element == 'on') {
                                                                                ?>
                                                                                <?php $property_video_url = str_replace("player.vimeo.com/video", "vimeo.com", $property_video_url); ?>
                                                                                <li class="property-video-opt">
                                                                                    <div class="option-holder">
                                                                                        <a class="property-video-btn" data-rel="prettyPhoto" href="<?php echo esc_url($property_video_url); ?>">
                                                                                            <i class="icon-film3"></i>
                                                                                            <div class="option-content"><span><?php echo wp_rem_plugin_text_srt('wp_rem_subnav_item_3'); ?></span></div>
                                                                                        </a>
                                                                                    </div>
                                                                                </li>
                                                                            <?php } ?>
                                                                            <?php 
                                                                            if ( isset($gallery_image_count) && !empty($wp_rem_property_gallery_ids) && $gallery_image_count > 0 && $wp_rem_image_gallery_element == 'on') { ?>
                                                                                <li class="property-photo-opt">
                                                                                    <div id="galley-img<?php echo absint($property_random_id) ?>" class="option-holder">
                                                                                        <a href="javascript:void(0)" class="rem-pretty-photos" data-id="<?php echo absint($property_id) ?>" data-rand="<?php echo absint($property_random_id) ?>"> 
                                                                                            <i class="icon-camera6"></i><span class="capture-count"><?php echo absint($gallery_image_count); ?></span>
                                                                                            <div class="option-content">
                                                                                                <span><?php echo wp_rem_plugin_text_srt('wp_rem_element_tooltip_icon_camera'); ?></span>
                                                                                            </div>
                                                                                        </a>
                                                                                    </div>
                                                                                </li>
                                                                            <?php } ?>
                                                                        </ul>
                                                                    </div>
                                                                </figcaption>
                                                            </figure>
                                                        </div>
                                                        <div class="text-holder">
                                                            <?php if ( $wp_rem_property_type_price_nearby_switch == 'on' && $wp_rem_property_nearby_price_options != 'none' ) { ?>
                                                                <span class="property-price-wrap" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                                                    <?Php
                                                                    if ( $wp_rem_property_nearby_price_options == 'on-call' ) {
                                                                        echo '<span class="property-price">' . force_balance_tags($wp_rem_property_nearby_price) . '</span>';
                                                                    } else {
                                                                        $property_info_price = wp_rem_property_price($nearby_property_id, $wp_rem_property_nearby_price, '<span class="guid-price">', '</span>');
                                                                        $wp_rem_get_currency_sign = wp_rem_get_currency_sign('code');
                                                                        echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_rem_get_currency_sign . '"></span>';
                                                                        echo '<span class="property-price" content="' . $wp_rem_property_nearby_price . '"  itemprop="price">' . force_balance_tags($property_info_price) . '</span>';
                                                                    }
                                                                    ?>
                                                                </span>
                                                            <?php } ?>

                                                            <?php if ( get_the_title($nearby_property_id) != '' ) { ?>
                                                                <div class="post-title">
                                                                    <h4 itemprop="name"><a href="<?php echo esc_url(get_permalink($property_id)); ?>"><?php echo esc_html(get_the_title($property_id)); ?></a></h4>
                                                                </div>
                                                            <?php } ?>
                                                            <?php
                                                            // property custom fields.
                                                            $cus_fields = array( 'content' => '' );
                                                            $cus_fields = apply_filters('wp_rem_custom_fields', $nearby_property_id, $cus_fields, $default_property_no_custom_fields);
                                                            if ( isset($cus_fields['content']) && $cus_fields['content'] != '' ) {
                                                                ?>
                                                                <ul class="post-category-list" itemprop="category">
                                                                    <?php echo wp_rem_allow_special_char($cus_fields['content']); ?>
                                                                </ul>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                                $list_count ++;
                                            endwhile;
                                            wp_reset_postdata();
                                            ?>
                                        </div>
                                    </div>
                                    <?php if ( $list_count > 5 ) { ?>
                                        <div class="swiper-button-prev"> <i class="icon-chevron-thin-left"></i></div>
                                        <div class="swiper-button-next"><i class="icon-chevron-thin-right"></i></div>
                                        <?php } ?>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                            jQuery(document).ready(function ($) {
                                if (jQuery(".property-grid-slider.real-estate-property .swiper-container").length != "") {
                                    "use strict";
                                    var swiper = new Swiper(".property-grid-slider.real-estate-property .swiper-container", {
                                        slidesPerView: 4,
                                        slidesPerColumn: 1,
                                        loop: false,
                                        paginationClickable: true,
                                        grabCursor: false,
                                        autoplay: false,
                                        spaceBetween: 30,
                                        nextButton: ".property-grid-slider.real-estate-property .swiper-button-next",
                                        prevButton: ".property-grid-slider.real-estate-property .swiper-button-prev",
                                        breakpoints: {
                                            1024: {
                                                slidesPerView: 3,
                                                spaceBetween: 40
                                            },
                                            991: {
                                                slidesPerView: 2,
                                                spaceBetween: 30
                                            },
                                            600: {
                                                slidesPerView: 1,
                                                spaceBetween: 15
                                            }
                                        }
                                    });
                                    var elementWidth = $(".property-grid-slider.real-estate-property").width();
                                    if (elementWidth<992 && elementWidth>600) swiper.params.slidesPerView = 2;
                                    if (elementWidth<600) swiper.params.slidesPerView = 1;
                                    swiper.update();
                                    $(window).trigger('resize');
                                }
                            });
                        </script>
                        <?php
                    }
                }
            }
        }

        public function property_nearby_filter($location_slug, $radius, $lat = '', $lng = '', $current_property_id = '') {
            global $wp_rem_plugin_options;
            $distance_symbol = isset($wp_rem_plugin_options['wp_rem_distance_measure_by']) ? $wp_rem_plugin_options['wp_rem_distance_measure_by'] : 'km';
            if ( $distance_symbol == 'km' ) {
                $radius = $radius / 1.60934; // 1.60934 == 1 Mile
            }
            if ( (isset($location_slug) && $location_slug != '') || ($lat != '' && $lng != '') ) {
                if ( $lat == '' || $lng == '' ) {
                    $Wp_rem_Locations = new Wp_rem_Locations();
                    $location_response = $Wp_rem_Locations->wp_rem_get_geolocation_latlng_callback($location_slug);
                    $lat = isset($location_response->lat) ? $location_response->lat : '';
                    $lng = isset($location_response->lng) ? $location_response->lng : '';
                }

                $radiusCheck = new RadiusCheck($lat, $lng, $radius);
                $minLat = $radiusCheck->MinLatitude();
                $maxLat = $radiusCheck->MaxLatitude();
                $minLong = $radiusCheck->MinLongitude();
                $maxLong = $radiusCheck->MaxLongitude();
                $wp_rem_compare_type = 'CHAR';
                if ( $radius > 0 ) {
                    $wp_rem_compare_type = 'DECIMAL(10,6)';
                }
                $location_condition_arr = array(
                    'relation' => 'OR',
                    array(
                        'key' => 'wp_rem_post_loc_latitude_property',
                        'value' => array( $minLat, $maxLat ),
                        'compare' => 'BETWEEN',
                        'type' => $wp_rem_compare_type
                    ),
                    array(
                        'key' => 'wp_rem_post_loc_longitude_property',
                        'value' => array( $minLong, $maxLong ),
                        'compare' => 'BETWEEN',
                        'type' => $wp_rem_compare_type
                    ),
                );
                $args_count = array(
                    'posts_per_page' => "-1",
                    'post_type' => 'properties',
                    'post_status' => 'publish',
                    'fields' => 'ids', // only load ids
                    'meta_query' => array(
                        $location_condition_arr,
                    ),
                );
                if ( isset($current_property_id) && $current_property_id != '' ) {
                    $args_count['post__not_in'] = array( $current_property_id );
                }

                $location_rslt = get_posts($args_count);
                return $location_rslt;
                $rslt = '';
            }
        }

    }

    global $wp_rem_nearby_properties;
    $wp_rem_nearby_properties = new wp_rem_nearby_properties_element();
}