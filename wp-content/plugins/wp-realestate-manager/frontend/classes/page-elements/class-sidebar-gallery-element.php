<?php
/**
 * File Type: Property Sidebar Gallery Page Element
 */
if ( ! class_exists('wp_rem_sidebar_gallery_element') ) {

    class wp_rem_sidebar_gallery_element {

        /**
         * Start construct Functions
         */
        public function __construct() {
            add_action('wp_rem_sidebar_gallery_html', array( $this, 'wp_rem_sidebar_gallery_html_callback' ), 11, 1);
            add_action('wp_rem_sidebar_gallery_map_html', array( $this, 'wp_rem_sidebar_gallery_map_html_callback' ), 11, 1);
        }

        public function wp_rem_sidebar_gallery_html_callback($property_id = '') {
            global $post, $wp_rem_plugin_options;
            $sidebar_gallery = wp_rem_element_hide_show($property_id, 'sidebar_gallery');
            if ( $sidebar_gallery != 'on' ) {
                return;
            }
            wp_enqueue_style('swiper');
            wp_enqueue_script('swiper');
            if ( $property_id == '' ) {
                $property_id = $post->ID;
            }
            if ( $property_id != '' ) {
                $wp_rem_property_type = get_post_meta($property_id, 'wp_rem_property_type', true);
                $wp_rem_property_type = isset($wp_rem_property_type) ? $wp_rem_property_type : '';
                if ( $property_type_post = get_page_by_path($wp_rem_property_type, OBJECT, 'property-type') )
                    $property_type_id = $property_type_post->ID;
                $property_type_id = isset($property_type_id) ? $property_type_id : '';
                $property_type_id = wp_rem_wpml_lang_page_id($property_type_id, 'property-type');
                $gallery_pics_allowed = get_post_meta($property_id, 'wp_rem_transaction_property_pic_num', true);
                if ( $gallery_pics_allowed > 0 && is_numeric($gallery_pics_allowed) ) {
                    $gallery_ids_list = get_post_meta($property_id, 'wp_rem_detail_page_gallery_ids', true);
                    if ( is_array($gallery_ids_list) && sizeof($gallery_ids_list) > 0 ) {
                        $count_all = count($gallery_ids_list);
                        if ( $count_all > $gallery_pics_allowed ) {
                            $count_all = $gallery_pics_allowed;
                        }
                        ?>
                        <div class="flickr-gallery-slider photo-gallery gallery ">
                            <div class="swiper-container">
                                <div class="swiper-wrapper">
                                    <?php
                                    $counter = 1;
                                    foreach ( $gallery_ids_list as $gallery_idd ) {
                                        $image = wp_get_attachment_image_src($gallery_idd, 'wp_rem_media_8');
                                        if ( isset($image[0]) ) {
                                            if ( $counter <= $gallery_pics_allowed ) {
                                                $first_class = ( $counter == 1) ? 'gallery-first-img' : '';
                                                ?>
                                                <div class="swiper-slide"><a class="pretty-photo-img <?php echo esc_attr($first_class); ?>" data-rel="prettyPhoto[gallery]" href="<?php echo esc_url(wp_get_attachment_url($gallery_idd)) ?>"><img src="<?php echo esc_url($image[0]); ?>" alt="<?php echo wp_rem_plugin_text_srt('wp_rem_slider_image'); ?>" /></a></div>
                                                <?php
                                            }
                                            $counter ++;
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                            <span><a href="javascript:;" class="pretty-photo-slider"><?php echo wp_rem_plugin_text_srt('wp_rem_slider_view_all_photos'); ?> (<?php echo intval($count_all); ?>)</a></span>
                        </div>
                        <?php
                        $wp_rem_cs_inline_script = '
                        jQuery(document).ready(function () {
                            jQuery(document).on("click", ".pretty-photo-slider", function() {
                                "use strict";
                                jQuery(".gallery-first-img").click();
                            });
                        });';
                        wp_rem_cs_inline_enqueue_script($wp_rem_cs_inline_script, 'wp-rem-custom-inline');
                    }
                }
            }
        }

        public function wp_rem_sidebar_gallery_map_html_callback($property_id = '') {
            global $wp_rem_plugin_options, $post, $wp_rem_plugin_options;
            $top_gallery_map = wp_rem_element_hide_show($property_id, 'top_gallery_map');
            if ( $top_gallery_map != 'on' ) {
                return;
            }
            wp_enqueue_style('wp-rem-prettyPhoto');
            wp_enqueue_script('wp-rem-prettyPhoto');

            if ( $property_id == '' ) {
                $property_id = $post->ID;
            }
            if ( $property_id != '' ) {
                $wp_rem_property_type = get_post_meta($property_id, 'wp_rem_property_type', true);
                $wp_rem_property_type = isset($wp_rem_property_type) ? $wp_rem_property_type : '';
                if ( $property_type_post = get_page_by_path($wp_rem_property_type, OBJECT, 'property-type') ) {
                    $property_type_id = $property_type_post->ID;
                }
                $property_type_id = isset($property_type_id) ? $property_type_id : '';
                $property_type_id = wp_rem_wpml_lang_page_id($property_type_id, 'property-type');

                $top_gal_map = true;

                $top_gal_with_map_set = isset($wp_rem_plugin_options['wp_rem_detail_view5_top_gallery_map']) ? $wp_rem_plugin_options['wp_rem_detail_view5_top_gallery_map'] : '';
                if ( $top_gal_with_map_set == 'on' ) {
                    $top_gal_map = true;
                } else {
                    $top_gal_map = false;
                }

                $top_gal_with_map = get_post_meta($property_type_id, 'wp_rem_detail_view5_top_gallery_map', true);

                if ( $top_gal_with_map == 'on' ) {
                    $top_gal_map = true;
                } else {
                    $top_gal_map = false;
                }

                if ( $top_gal_map === false ) {
                    return false;
                }

                $gallery_pics_allowed = get_post_meta($property_id, 'wp_rem_transaction_property_pic_num', true);
                if ( $gallery_pics_allowed > 0 && is_numeric($gallery_pics_allowed) ) {
                    $gallery_ids_list = get_post_meta($property_id, 'wp_rem_detail_page_gallery_ids', true);
                    if ( is_array($gallery_ids_list) && sizeof($gallery_ids_list) > 0 ) {
                        $count_all = count($gallery_ids_list);
                        ?>
                        <div class="map-gallery-container">
                            <ul class="gallery photo-gallery">

                                <li class="map-part">
                                    <?php
                                    $default_zoom_level = ( isset($wp_rem_plugin_options['wp_rem_map_zoom_level']) && $wp_rem_plugin_options['wp_rem_map_zoom_level'] != '' ) ? $wp_rem_plugin_options['wp_rem_map_zoom_level'] : 10;
                                    $wp_rem_post_loc_latitude = get_post_meta($property_id, 'wp_rem_post_loc_latitude_property', true);
                                    $wp_rem_post_loc_longitude = get_post_meta($property_id, 'wp_rem_post_loc_longitude_property', true);
                                    $wp_rem_post_loc_address_property = get_post_meta($property_id, 'wp_rem_post_loc_address_property', true);
                                    $wp_rem_property_zoom = get_post_meta($property_id, 'wp_rem_post_loc_zoom_property', true);
                                    if ( $wp_rem_property_zoom == '' || $wp_rem_property_zoom == 0 ) {
                                        $wp_rem_property_zoom = $default_zoom_level;
                                    }
                                    $property_type_id = '';
                                    $property_type = get_post_meta($property_id, 'wp_rem_property_type', true);
                                    if ( $property_type != '' ) {
                                        $property_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'property-type', 'name' => "$property_type", 'post_status' => 'publish' ));
                                        $property_type_id = isset($property_type_post[0]->ID) ? $property_type_post[0]->ID : 0;
                                    }
                                    $map_marker_icon = get_post_meta($property_type_id, 'wp_rem_property_type_marker_image', true);
                                    $map_marker_icon = wp_get_attachment_url($map_marker_icon);
                                    $map_atts = array(
                                        'map_height' => '480',
                                        'map_lat' => $wp_rem_post_loc_latitude,
                                        'map_lon' => $wp_rem_post_loc_longitude,
                                        'map_zoom' => $wp_rem_property_zoom,
                                        'map_type' => '',
                                        'map_info' => $wp_rem_post_loc_address_property, //$wp_rem_post_comp_address,
                                        'map_info_width' => '200',
                                        'map_info_height' => '350',
                                        'map_marker_icon' => $map_marker_icon,
                                        'map_show_marker' => 'true',
                                        'map_controls' => 'false',
                                        'map_draggable' => 'true',
                                        'map_scrollwheel' => 'false',
                                        'map_border' => '',
                                        'map_border_color' => '',
                                        'wp_rem_map_style' => '',
                                        'wp_rem_map_class' => '',
                                        'wp_rem_map_directions' => 'off',
                                        'wp_rem_map_circle' => '',
                                        'wp_rem_nearby_places' => false,
                                        'map_det_view' => 'detial-v5-gallery'
                                    );
                                    if ( function_exists('wp_rem_map_content') ) {
                                        wp_rem_map_content($map_atts);
                                    }
                                    ?>
                                </li>

                                <li class="first-big-image gallery">
                                    <?php
                                    foreach ( $gallery_ids_list as $gallery_idd ) {
                                        $image = wp_get_attachment_image_src($gallery_idd, 'wp_rem_media_12');
                                        if ( isset($image[0]) ) {
                                            ?>
                                            <a data-id="gal-img-1" style="background-image:url('<?php echo esc_url($image[0]); ?>');"></a>
                                            <?php
                                        }
                                        break;
                                    }
                                    echo '<div id="gallery-expander" data-id="' . $property_id . '"><i class="icon-fullscreen"></i><span>' . intval($count_all) . '</span>' . wp_rem_plugin_text_srt('wp_rem_single_prop_gallery_count_photos') . ' <strong class="loader-img"></strong></div>';
                                    echo '<div id="gallery-appender-' . $property_id . '"></div>';
                                    ?>
                                </li>

                                <?php
                                if ( sizeof($gallery_ids_list) > 1 ) {
                                    ?>
                                    <li class="all-remian-images gallery">
                                        <?php
                                        $counter = 1;
                                        foreach ( $gallery_ids_list as $gallery_idd ) {
                                            if ( ! wp_is_mobile() ) {
                                                if ( $counter == 1 ) {
                                                    $counter ++;
                                                    continue;
                                                }
                                            }
                                            $image = wp_get_attachment_image_src($gallery_idd, 'wp_rem_media_11');
                                            if ( isset($image[0]) ) {
                                                if ( $counter > 7 ) {
                                                    ?>
                                                    <a style="display: none;"></a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a data-id="gal-img-<?php echo absint($counter) ?>" style="background-image:url('<?php echo esc_url($image[0]); ?>');"></a>
                                                    <?php
                                                }
                                            }
                                            $counter ++;
                                        }
                                        ?>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <?php
                    }
                }
            }
        }

    }

    global $wp_rem_sidebar_gallery;
    $wp_rem_sidebar_gallery = new wp_rem_sidebar_gallery_element();
}