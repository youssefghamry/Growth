<?php
/**
 * The template for displaying single property
 *
 */
global $post, $wp_rem_plugin_options, $wp_rem_theme_options, $wp_rem_post_property_types, $wp_rem_author_info, $Wp_rem_Captcha, $wp_rem_form_fields_frontend;
$post_id = $post->ID;
$default_property_no_custom_fields = isset($wp_rem_plugin_options['wp_rem_property_no_custom_fields']) ? $wp_rem_plugin_options['wp_rem_property_no_custom_fields'] : '';
$wp_rem_social_network = isset($wp_rem_plugin_options['wp_rem_property_detail_page_social_network']) ? $wp_rem_plugin_options['wp_rem_property_detail_page_social_network'] : '';
$property_limits = get_post_meta($post_id, 'wp_rem_trans_all_meta', true);
$wp_rem_property_price_options = get_post_meta($post_id, 'wp_rem_property_price_options', true);
$wp_rem_property_price = '';
if ( $wp_rem_property_price_options == 'price' ) {
    $wp_rem_property_price = get_post_meta($post_id, 'wp_rem_property_price', true);
} else if ( $wp_rem_property_price_options == 'on-call' ) {
    $wp_rem_property_price = wp_rem_plugin_text_srt('wp_rem_nearby_properties_price_on_request');
}

$wp_rem_var_post_social_sharing = $wp_rem_plugin_options['wp_rem_social_share'];
wp_enqueue_script('wp-rem-prettyPhoto');
wp_enqueue_script('wp-rem-reservation-functions');
wp_enqueue_style('wp-rem-prettyPhoto');
wp_enqueue_script('wp-rem-property-map');

// checking review in on in property type
$wp_rem_property_type = get_post_meta($post_id, 'wp_rem_property_type', true);
/*
 * member data
 */
$wp_rem_property_member_id = get_post_meta($post_id, 'wp_rem_property_member', true);
$wp_rem_property_member_id = isset($wp_rem_property_member_id) ? $wp_rem_property_member_id : '';
$wp_rem_post_loc_address_member = get_post_meta($wp_rem_property_member_id, 'wp_rem_post_loc_address_member', true);
$wp_rem_member_title = '';
if ( isset($wp_rem_property_member_id) && $wp_rem_property_member_id <> '' ) {
    $wp_rem_member_title = get_the_title($wp_rem_property_member_id);
}
$wp_rem_member_link = 'javascript:void(0)';
if ( isset($wp_rem_property_member_id) && $wp_rem_property_member_id <> '' ) {
    $wp_rem_member_link = get_the_permalink($wp_rem_property_member_id);
}
$member_image_id = get_post_meta($wp_rem_property_member_id, 'wp_rem_profile_image', true);
$member_image = wp_get_attachment_url($member_image_id);
$wp_rem_member_phone_num = get_post_meta($wp_rem_property_member_id, 'wp_rem_phone_number', true);
$wp_rem_member_email_address = get_post_meta($wp_rem_property_member_id, 'wp_rem_email_address', true);
$wp_rem_member_email_address = isset($wp_rem_member_email_address) ? $wp_rem_member_email_address : '';
/*
 * member data end 
 */
$wp_rem_post_loc_address_property = get_post_meta($post_id, 'wp_rem_post_loc_address_property', true);
$wp_rem_post_phone = get_post_meta($post_id, 'wp_rem_property_contact_phone', true);
$wp_rem_post_contact_web = get_post_meta($post_id, 'wp_rem_property_contact_web', true);
$wp_rem_post_loc_latitude = get_post_meta($post_id, 'wp_rem_post_loc_latitude_property', true);
$wp_rem_post_loc_longitude = get_post_meta($post_id, 'wp_rem_post_loc_longitude_property', true);
$wp_rem_property_type = isset($wp_rem_property_type) ? $wp_rem_property_type : '';
if ( $property_type_post = get_page_by_path($wp_rem_property_type, OBJECT, 'property-type') )
    $property_type_id = $property_type_post->ID;
$property_type_id = isset($property_type_id) ? $property_type_id : '';
$property_type_id = wp_rem_wpml_lang_page_id($property_type_id, 'property-type');
$wp_rem_property_type_feature_img_switch = get_post_meta($property_type_id, 'wp_rem_social_share_element', true);
$wp_rem_property_type_price_switch = get_post_meta($property_type_id, 'wp_rem_property_type_price', true);
$wp_rem_property_type_cover_img_switch = get_post_meta($post_id, 'wp_rem_transaction_property_cimage', true);
$wp_rem_property_type_claim_list_switch = get_post_meta($property_type_id, 'wp_rem_claim_property_element', true);
$wp_rem_property_type_flag_list_switch = get_post_meta($property_type_id, 'wp_rem_report_spams_element', true);
$wp_rem_property_type_social_share_switch = get_post_meta($property_type_id, 'wp_rem_social_share_element', true);
$wp_rem_print_switch = get_post_meta($property_type_id, 'wp_rem_print_switch', true);
$wp_rem_claim_switch = get_post_meta($property_type_id, 'wp_rem_claim_switch', true);
$wp_rem_flag_switch = get_post_meta($property_type_id, 'wp_rem_flag_switch', true);
$wp_rem_property_type_det_desc_switch = get_post_meta($property_type_id, 'wp_rem_property_detail_length_switch', true);
$wp_rem_property_type_det_desc_length = get_post_meta($property_type_id, 'wp_rem_property_desc_detail_length', true);
$wp_rem_property_type_walkscores_switch = get_post_meta($property_type_id, 'wp_rem_walkscores_options_element', true);
$wp_rem_property_type_vieww = get_post_meta($property_type_id, 'wp_rem_property_detail_page', true);
$wp_rem_property_type_vieww = isset($wp_rem_property_type_vieww) ? $wp_rem_property_type_vieww : '';
$wp_rem_env_res_all_lists = get_post_meta($post_id, 'wp_rem_env_res', true);
$wp_rem_env_res_title = get_post_meta($post_id, 'wp_rem_env_res_heading', true);
$wp_rem_env_res_description = get_post_meta($post_id, 'wp_rem_env_res_description', true);
/*
 * Banner slider data
 */
$gallery_ids_list = get_post_meta($post_id, 'wp_rem_detail_page_gallery_ids', true);
$gallery_pics_allowed = get_post_meta($post_id, 'wp_rem_transaction_property_pic_num', true);
$count_all = ( isset($gallery_ids_list) && is_array($gallery_ids_list) && sizeof($gallery_ids_list) > 0 ) ? count($gallery_ids_list) : 0;
if ( $count_all > $gallery_pics_allowed ) {
    $count_all = $gallery_pics_allowed;
}

/*
 * Property Elements Settings
 */
$wp_rem_enable_features_element = get_post_meta($post_id, 'wp_rem_enable_features_element', true);
$wp_rem_enable_video_element = get_post_meta($post_id, 'wp_rem_enable_video_element', true);
$wp_rem_enable_yelp_places_element = get_post_meta($post_id, 'wp_rem_enable_yelp_places_element', true);
$wp_rem_enable_appartment_for_sale_element = get_post_meta($post_id, 'wp_rem_enable_appartment_for_sale_element', true);
$wp_rem_enable_file_attachments_element = get_post_meta($post_id, 'wp_rem_enable_file_attachments_element', true);
$wp_rem_enable_floot_plan_element = get_post_meta($post_id, 'wp_rem_enable_floot_plan_element', true);
$wp_rem_property_is_featured = get_post_meta($post_id, 'wp_rem_property_is_featured', true);
$wp_rem_transaction_property_phone = get_post_meta($post_id, 'wp_rem_transaction_property_phone', true);
$wp_rem_transaction_property_website = get_post_meta($post_id, 'wp_rem_transaction_property_website', true);
/*
 * Banner slider data end 
 */

if ( $wp_rem_property_type_det_desc_length < 0 ) {
    $wp_rem_property_type_det_desc_length = 50;
}

if ( isset($_GET['price']) && $_GET['price'] == 'yes' ) {
    echo wp_rem_all_currencies();
    echo wp_rem_get_currency(100, true);
}

$no_image_class = '';
if ( ! has_post_thumbnail() ) {
    $no_image_class = ' no-image';
}

//get custom fields
$cus_field_arr = array();
$property_type = '';
$property_type = get_post_meta($post_id, 'wp_rem_property_type', true);
$wp_rem_property_zoom = get_post_meta($post_id, 'wp_rem_post_loc_zoom_property', true);

$member_profile_status = get_post_meta($post_id, 'property_member_status', true);

$wp_rem_property_type_cus_fields = $wp_rem_post_property_types->wp_rem_types_custom_fields_array($property_type);

$property_type_id = '';
if ( $property_type != '' ) {
    $property_type_post = get_posts(array( 'posts_per_page' => '1', 'post_type' => 'property-type', 'name' => "$property_type", 'post_status' => 'publish' ));
    $property_type_id = isset($property_type_post[0]->ID) ? $property_type_post[0]->ID : 0;
}

$map_topper = 'on';

$default_zoom_level = ( isset($wp_rem_plugin_options['wp_rem_map_zoom_level']) && $wp_rem_plugin_options['wp_rem_map_zoom_level'] != '' ) ? $wp_rem_plugin_options['wp_rem_map_zoom_level'] : 10;
if ( $wp_rem_property_zoom == '' || $wp_rem_property_zoom == 0 ) {
    $wp_rem_property_zoom = $default_zoom_level;
}

// get all categories
$wp_rem_cate = '';
$wp_rem_cate_str = '';
$wp_rem_property_category = get_post_meta($post_id, 'wp_rem_property_category', true);

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

$map_dynmaic_no = rand(1000000, 99999999);

$top_map = wp_rem_element_hide_show($post_id, 'top_map');
$top_slider = wp_rem_element_hide_show($post_id, 'top_slider');

$wp_rem_single_view = wp_rem_property_detail_page_view($post_id);

$map_view_class = '';
$banner_view_class = 'hidden';
if ( $wp_rem_single_view == 'detail_view4' ) {
    $map_view_class = 'hidden';
    $banner_view_class = '';
}
if ( $wp_rem_single_view == 'detail_view3' && $top_map != 'on' ) {
    $map_view_class = 'hidden';
    $banner_view_class = '';
}
if ( $wp_rem_single_view == 'detail_view4' && ($top_slider != 'on' || $count_all <= 0) ) {
    $map_view_class = '';
    $banner_view_class = 'hidden';
}

$http_request = wp_rem_server_protocol();
if ( ($top_map == 'on' || ($top_slider == 'on' && $count_all > 0)) && $member_profile_status == 'active' ) {
    wp_enqueue_style('swiper');
    wp_enqueue_script('swiper');

    if ( $top_map == 'on' ) {
        ?>
        <div class="page-section wp-rem-property-map <?php echo esc_html($map_view_class); ?>">
            <div class="map-fullwidth map-holder"> 
                <?php
                $near_by_options = get_post_meta($property_type_id, 'wp_rem_near_by_options_element', true);
                $near_by_places = false;
                if ( $near_by_options == 'on' ) {
                    $near_by_places = true;
                    ?>
                    <div class="container dominant-places-wrapper">
                        <?php echo wp_rem_map_markers_nearby($map_dynmaic_no); ?>
                    </div> 
                    <?php
                } else {
                    ?>
                    <div class="dominant-places-wrapper" style="visibility: hidden;"></div>
                    <?php
                }
                ?>
                <div class="container">                
                    <ul class="map-actions">
                        <?php if ( $top_slider == 'on' && $count_all > 0 ) { ?>
                            <li id="slider-view" class="slider-view">
                                <a href="javascript:void(0);">
                                    <?php
                                    $map_slider_icon = ( isset($wp_rem_plugin_options['wp_rem_map_slider_view_icon']) && $wp_rem_plugin_options['wp_rem_map_slider_view_icon'] != '' ) ? $wp_rem_plugin_options['wp_rem_map_slider_view_icon'] : '';
                                    if ( $map_slider_icon != '' ) {
                                        ?>
                                        <img src="<?php echo wp_get_attachment_url($map_slider_icon); ?>" alt=""/>
                                    <?php } ?>
                                    <?php echo wp_rem_plugin_text_srt('wp_rem_properties_detail_view'); ?>
                                </a>
                            </li>
                        <?php } ?>
                        <li id="map-view" class="map-view active" data-lat="<?php echo esc_attr($wp_rem_post_loc_latitude); ?>" data-lng="<?php echo esc_attr($wp_rem_post_loc_longitude); ?>">
                            <a href="javascript:void(0);">
                                <?php $map_slider_icon = ( isset($wp_rem_plugin_options['wp_rem_map_view_icon']) && $wp_rem_plugin_options['wp_rem_map_view_icon'] != '' ) ? $wp_rem_plugin_options['wp_rem_map_view_icon'] : ''; ?>
                                <?php if ( $map_slider_icon != '' ) { ?>
                                    <img src="<?php echo wp_get_attachment_url($map_slider_icon); ?>" alt=""/>
                                <?php } ?>
                                <?php echo wp_rem_plugin_text_srt('wp_rem_properties_detail_location'); ?>
                            </a>
                        </li>
                        <li data-id="terrain" id="map-view-street" class="map-view-street">                            
                            <a href="javascript:void(0);">
                                <?php $map_slider_icon = ( isset($wp_rem_plugin_options['wp_rem_map_street_view_icon']) && $wp_rem_plugin_options['wp_rem_map_street_view_icon'] != '' ) ? $wp_rem_plugin_options['wp_rem_map_street_view_icon'] : ''; ?>
                                <?php if ( $map_slider_icon != '' ) { ?>
                                    <img src="<?php echo wp_get_attachment_url($map_slider_icon); ?>" alt=""/>
                                <?php } ?>
                                <?php echo wp_rem_plugin_text_srt('wp_rem_properties_detail_street_view'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="map_canvas_custom_298157">
                    <?php
                    $map_marker_icon = get_post_meta($property_type_id, 'wp_rem_property_type_marker_image', true);
                    $map_marker_icon = wp_get_attachment_url($map_marker_icon);
                    $map_atts = array(
                        'map_rand_num' => $map_dynmaic_no,
                        'map_height' => '647',
                        'map_lat' => $wp_rem_post_loc_latitude,
                        'map_lon' => $wp_rem_post_loc_longitude,
                        'map_zoom' => $wp_rem_property_zoom,
                        'map_type' => '',
                        'map_info' => $wp_rem_post_loc_address_property, //$wp_rem_post_comp_address,
                        'map_info_width' => '200',
                        'map_info_height' => '350',
                        'map_marker_icon' => $map_marker_icon,
                        'map_show_marker' => 'false',
                        'map_controls' => 'true',
                        'map_draggable' => 'true',
                        'map_scrollwheel' => 'false',
                        'map_border' => '',
                        'map_border_color' => '',
                        'wp_rem_map_style' => '',
                        'wp_rem_map_class' => '',
                        'wp_rem_map_directions' => 'off',
                        'wp_rem_map_circle' => '',
                        'map_det_view' => 'detial-v4',
                        'property_id' => $post_id,
                        'wp_rem_nearby_places' => $near_by_places,
                    );
                    if ( function_exists('wp_rem_map_content') ) {
                        wp_rem_map_content($map_atts);
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php
    if ( $top_slider == 'on' && $count_all > 0 ) {
        if ( is_array($gallery_ids_list) && sizeof($gallery_ids_list) > 0 ) {
            ?>
            <div class="banner wp-rem-property-banner <?php echo esc_html($banner_view_class); ?>">
                <div id="property-banner-slider" class="property-banner-slider map-holder">
                    <ul class="map-actions">
                        <li id="slider-view1" class="slider-view active">
                            <a href="javascript:void(0);">
                                <?php $map_slider_icon = ( isset($wp_rem_plugin_options['wp_rem_map_slider_view_icon']) && $wp_rem_plugin_options['wp_rem_map_slider_view_icon'] != '' ) ? $wp_rem_plugin_options['wp_rem_map_slider_view_icon'] : ''; ?>
                                <?php if ( $map_slider_icon != '' ) { ?>
                                    <img src="<?php echo wp_get_attachment_url($map_slider_icon); ?>" alt=""/>
                                <?php } ?>
                                <?php echo wp_rem_plugin_text_srt('wp_rem_properties_detail_view'); ?>
                            </a>
                        </li>
                        <?php if ( $top_map == 'on' ) { ?>
                            <li id="map-view1" class="map-view" data-lat="<?php echo esc_attr($wp_rem_post_loc_latitude); ?>" data-lng="<?php echo esc_attr($wp_rem_post_loc_longitude); ?>">
                                <a href="javascript:void(0);">
                                    <?php
                                    $map_slider_icon = ( isset($wp_rem_plugin_options['wp_rem_map_view_icon']) && $wp_rem_plugin_options['wp_rem_map_view_icon'] != '' ) ? $wp_rem_plugin_options['wp_rem_map_view_icon'] : '';
                                    if ( $map_slider_icon != '' ) {
                                        ?>
                                        <img src="<?php echo wp_get_attachment_url($map_slider_icon); ?>" alt=""/>
                                    <?php } ?>
                                    <?php echo wp_rem_plugin_text_srt('wp_rem_properties_detail_location'); ?>
                                </a>
                            </li>
                            <li data-id="terrain" id="map-view-street1" class="map-view-street">                            
                                <a href="javascript:void(0);">
                                    <?php
                                    $map_slider_icon = ( isset($wp_rem_plugin_options['wp_rem_map_street_view_icon']) && $wp_rem_plugin_options['wp_rem_map_street_view_icon'] != '' ) ? $wp_rem_plugin_options['wp_rem_map_street_view_icon'] : '';
                                    if ( $map_slider_icon != '' ) {
                                        ?>
                                        <img src="<?php echo wp_get_attachment_url($map_slider_icon); ?>" alt=""/>
                                    <?php } ?>
                                    <?php echo wp_rem_plugin_text_srt('wp_rem_properties_detail_street_view'); ?>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <div class="container">
                        <div class="profile-info">
                            <?php
                            if ( $wp_rem_property_type_price_switch == 'on' && $wp_rem_property_price_options != 'none' ) {
                                if ( $wp_rem_property_price_options == 'on-call' ) {
				    $phone_number = get_post_meta($wp_rem_property_member_id, 'wp_rem_phone_number', true);
                                    $phone_number = get_post_meta($property_id, 'wp_rem_phone_number_property', true);
                                    echo '<span class="price text-color">' . force_balance_tags($wp_rem_property_price) . '</span>:'.$phone_number;
                                } else {
                                    $property_info_price = wp_rem_property_price($post_id, $wp_rem_property_price, '<span class="guid-price">', '</span>');
                                    echo '<span class="price text-color">' . force_balance_tags($property_info_price) . '</span>';
                                }
                            }
                            if ( get_the_title($post_id) != '' ) {
                                ?>
                                <h2><?php the_title(); ?></h2>
                                <?php
                            } ?>
    				<address>
					<?php if (isset($wp_rem_post_loc_address_property) && $wp_rem_post_loc_address_property != '') { ?><span><i class="icon-location-pin2"></i><?php echo esc_html($wp_rem_post_loc_address_property); ?></span> <?php } ?>
					<?php if (isset($wp_rem_transaction_property_website) && $wp_rem_transaction_property_website == 'on') { ?><?php if (isset($wp_rem_post_contact_web) && $wp_rem_post_contact_web != '') { ?><span><i class="icon-globe3"></i><a target="_blank"  href="<?php echo esc_url($wp_rem_post_contact_web); ?>"><?php echo esc_html($wp_rem_post_contact_web); ?></a></span> <?php } } ?>
					<?php if (isset($wp_rem_transaction_property_phone) && $wp_rem_transaction_property_phone == 'on') { ?><?php if (isset($wp_rem_post_phone) && $wp_rem_post_phone != '') { ?><span><i class="icon-phone4"></i>  <a href="tel:<?php echo $wp_rem_post_phone; ?>"><?php echo esc_html($wp_rem_post_phone); ?></a></span> <?php } } ?>
    				</address>
				<?php
                            // property custom fields.
                            $cus_fields = array( 'content' => '' );
                            $cus_fields = apply_filters('wp_rem_custom_fields', $post_id, $cus_fields, '', true, true, true, 'detail-view');
                            if ( isset($cus_fields['content']) && $cus_fields['content'] != '' ) {
                                ?>
                                <ul class="categories-holder">
                                    <?php echo wp_rem_allow_special_char($cus_fields['content']); ?>
                                </ul>
                                <?php
                            }
                            if ( isset($wp_rem_property_member_id) && $wp_rem_property_member_id != '' ) {
                                if ( isset($member_image) && $member_image != '' ) {
                                    ?>
                                    <div class="img-holder">
                                        <figure>
                                            <a href="<?php echo esc_url($wp_rem_member_link); ?>">
                                                <img src="<?php echo esc_url($member_image); ?>" alt="" />
                                            </a>
                                        </figure>
                                    </div>
                                <?php } ?>
                                <div class="text-holder">
                                    <?php if ( isset($wp_rem_member_title) && $wp_rem_member_title != '' ) { ?>
                                        <a href="<?php echo esc_url($wp_rem_member_link); ?>">
                                            <h5><?php echo esc_html($wp_rem_member_title); ?></h5>
                                        </a>
                                        <?php
                                    }
                                    if ( isset($wp_rem_member_phone_num) && $wp_rem_member_phone_num != '' ) {
                                        $all_data = '';
                                        $all_data = $wp_rem_author_info->wp_rem_member_member_phone_num($wp_rem_member_phone_num, '', '<strong>', '</strong>');
                                        echo force_balance_tags($all_data);
                                        ?>

                                    <?php } ?>
                                    <div class="field-select-holder">
                                        <?php echo do_action('wp_rem_opening_hours_element_html', $wp_rem_property_member_id, 'property-v3'); ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="swiper-container ">
                        <div class="swiper-wrapper">
                            <?php
                            $counter = 1;
                            foreach ( $gallery_ids_list as $gallery_idd ) {
                                if ( $counter <= $count_all ) {
                                    ?>
                                    <div class="swiper-slide" style="background: url(<?php echo wp_get_attachment_url($gallery_idd); ?>) no-repeat; background-size:100% 100%; padding:90px 0 170px 0;">
                                    </div>
                                    <?php
                                } $counter ++;
                            }
                            ?>
                        </div>

                        <!-- Add Arrows -->
                        <div class="swiper-button-prev"> <i class="icon-chevron-thin-left"></i></div>
                        <div class="swiper-button-next"><i class="icon-chevron-thin-right"></i></div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>
    <div class="detail-nav-wrap">
        <div class="detail-nav-toggler"> <?php echo wp_rem_plugin_text_srt('wp_rem_property_property_features'); ?><span class="icon-angle-down"></span></div>
        <div class="detail-nav detail-nav-map">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $sub_nav = array( 'content' => '' );
                        $sub_nav = apply_filters('wp_rem_navbar_html', $sub_nav, $post_id);
                        if ( isset($sub_nav['content']) && $sub_nav['content'] != '' ) {
                            echo wp_rem_allow_special_char($sub_nav['content']);
                        }
                        ?>
                        <div class="property-favourite-list">
                            <?php
                            $before_label = wp_rem_plugin_text_srt('wp_rem_property_save_to_favourite');
                            $after_label = wp_rem_plugin_text_srt('wp_rem_property_remove_to_favourite');
                            $figcaption_div = true;
                            $book_mark_args = array(
                                'before_label' => $before_label,
                                'after_label' => $after_label,
                                'before_icon' => '<i class="icon-heart-o"></i>',
                                'after_icon' => '<i class="icon-heart5"></i>',
                            );
                            do_action('wp_rem_favourites_frontend_button', $post_id, $book_mark_args, $figcaption_div);
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<div id="main">
    <div class="page-section" style="padding:20px 0;">
        <div class="property-detail">
            <div class="container">
                <div class="row" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Product">
                    <?php
                    if ( $member_profile_status == 'active' ) {
                        ?>
                        <div class="page-content col-lg-8 col-md-8 col-sm-12 col-xs-12" >
                            <div class="list-detail-options">
                                <div class="title-area">
                                    <?php
                                    $is_sold = wp_rem_is_property_sold($post_id);

                                    if ( $wp_rem_property_type_price_switch == 'on' && $wp_rem_property_price_options != 'none' && $is_sold != true ) {
                                        ?>
                                        <div class="price-holder" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                            <span class="property-price">
                                                <?Php
                                                if ( $wp_rem_property_price_options == 'on-call' ) {
						    $phone_number = get_post_meta($post_id, 'wp_rem_phone_number_property', true);
                                                    echo '<span class="new-price text-color">' . force_balance_tags($wp_rem_property_price) . '</span> : '.$phone_number;
                                                } else {
                                                    $property_info_price = wp_rem_property_price($post_id, $wp_rem_property_price, '<span class="guid-price">', '</span>');
                                                    $wp_rem_get_currency_sign = wp_rem_get_currency_sign('code');
                                                    echo '<span itemprop="priceCurrency" style="display:none;" content="' . $wp_rem_get_currency_sign . '"></span>';
                                                    echo '<span class="new-price text-color" itemprop="price">' . force_balance_tags($property_info_price) . '</span>';
                                                }
                                                ?>
                                            </span>
                                        </div>
                                     <?php } elseif($is_sold == true) {
                                        ?>
                                        <div class="price-holder">
                                            <span  class="property-price" itemprop="offers" itemscope itemtype="<?php echo force_balance_tags($http_request); ?>schema.org/Offer">
                                                <?php echo'<span class="new-price text-color" itemprop="price">' . wp_rem_plugin_text_srt('wp_rem_property_sold_out_txt') . '</span>'; ?>
                                            </span>
                                        </div>
                                    <?php } ?> 
                                    <?php if ( $wp_rem_claim_switch != 'off' || $wp_rem_flag_switch != 'off' || $wp_rem_print_switch != 'off' ) { ?>
                                        <div class="claims-holder">
                                            <?php if ( $wp_rem_print_switch != 'off' ) { ?>
                                                <div class="print-page">
                                                    <a href="javascript:void(0)" onclick="wp_rem_property_detail_print()"><i class="icon-printer2"></i><?php echo wp_rem_plugin_text_srt('wp_rem_print') ?><span id="property-print-loader"></span></a>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            if ( $wp_rem_claim_switch != 'off' ) {
                                                do_action('claim_property_from', $post_id, '<div class="claim-property">', '</div>');
                                            }
                                            if ( $wp_rem_flag_switch != 'off' ) {
                                                do_action('flag_property_from', $post_id, '<div class="flag-property">', '</div>');
                                            }
                                            ?>
                                        </div>
                                    <?php } ?>
                                    <?php if ( get_the_title($post_id) != '' ) { ?>
                                        <h2 itemprop="name"><?php the_title(); ?></h2>
                                        <?php
                                    }?>
				<address>
					<?php if (isset($wp_rem_post_loc_address_property) && $wp_rem_post_loc_address_property != '') { ?><span><i class="icon-location-pin2"></i><?php echo esc_html($wp_rem_post_loc_address_property); ?></span> <?php } ?>
					<?php if (isset($wp_rem_transaction_property_website) && $wp_rem_transaction_property_website == 'on') { ?><?php if (isset($wp_rem_post_contact_web) && $wp_rem_post_contact_web != '') { ?><span><i class="icon-globe3"></i><a target="_blank"  href="<?php echo esc_url($wp_rem_post_contact_web); ?>"><?php echo esc_html($wp_rem_post_contact_web); ?></a></span> <?php } } ?>
					<?php if (isset($wp_rem_transaction_property_phone) && $wp_rem_transaction_property_phone == 'on') { ?><?php if (isset($wp_rem_post_phone) && $wp_rem_post_phone != '') { ?><span><i class="icon-phone4"></i>  <a href="tel:<?php echo $wp_rem_post_phone; ?>"><?php echo esc_html($wp_rem_post_phone); ?></span> <?php } } ?>
				</address>
                                    <div class="property-data">
                                        <ul>
                                            <?php if ( $wp_rem_property_is_featured == 'on' ) { ?>
                                                <li class="featured-property">
                                                    <span class="bgcolor"><?php echo wp_rem_plugin_text_srt('wp_rem_property_featured'); ?></span>
                                                </li>
                                            <?php }
                                            ?>
                                            <li  class="prop-type"><i class="icon-home3"></i><?php echo wp_rem_property_type_link($property_type_id); ?> <?php
                                                if ( $wp_rem_cate_str != '' ) {
                                                    echo wp_rem_plugin_text_srt('wp_rem_property_type_in');
                                                }
                                                ?>
                                            </li>
                                            <?php if ( $wp_rem_cate_str != '' ) { ?>
                                                <li class="prop-category"><?php echo wp_rem_allow_special_char($wp_rem_cate_str); ?></li>
                                            <?php } ?>
                                            <?php do_action('wp_rem_detail_compare_btn', $post_id, '<li class="prop-compare">', '</li>'); ?>
                                            <?php if ( wp_rem_element_hide_show($post_id, 'social_networks') == 'on' ) { ?>
                                                <li class="prop-share">
                                                    <div class="property-social-links">
                                                        <span class="social-share"><?php echo wp_rem_plugin_text_srt('wp_rem_property_social_share_text') ?></span>
                                                        <?php do_action('wp_rem_social_sharing'); ?>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <?php ?>
                                </div>
                                <?php do_action('wp_rem_enquire_arrange_buttons_element_html', $post_id, 'view-3'); ?>
                            </div>
                            <?php
                            do_action('wp_rem_custom_fields_html', $post_id, 'view-3');

                            $display_summary_content = true;
                            $display_summary_content = apply_filters('wp_rem_display_property_summary_content', $display_summary_content);
                            if ( $display_summary_content == true ) {
                                // DESCRIPTION AND FEATURE CONTENT START
                                $my_postid = $post_id; //This is page id or post id
                                $content_post = get_post($my_postid);
                                $content = $content_post->post_content;
                                $content = apply_filters('the_content', $content);
                                $content = str_replace(']]>', ']]&gt;', $content);
                                $wp_rem_property_summary = get_post_meta($post_id, 'wp_rem_property_summary', true);
                                $wp_rem_property_summary = isset($wp_rem_property_summary) ? $wp_rem_property_summary : '';
                                if ( $wp_rem_property_summary != '' || $content != '' ) {
                                    ?>
                                    <div id="property-detail" class="description-holder" itemprop="description">
                                        <?php if ( $wp_rem_property_summary != '' ) { ?>
                                            <div class="property-feature">
                                                <div class="element-title">
                                                    <h3><?php echo wp_rem_plugin_text_srt('wp_rem_property_property_key_detail'); ?></h3>
                                                </div>
                                                <?php echo force_balance_tags(str_replace("<br/>", '</p><p>', str_replace("<br />", '</p><p>', nl2br($wp_rem_property_summary)))); ?>
                                            </div>
                                            <?php
                                        }?>
					<?php if ($content != '') { ?>    
					    <div class="property-dsec">
						<div class="element-title">
						    <h3><?php echo wp_rem_plugin_text_srt('wp_rem_property_property_desc'); ?></h3>
						</div>
						<?php
						if (!empty($wp_rem_property_type_det_desc_switch) && $wp_rem_property_type_det_desc_switch == 'on') {
						    $trimmed_content = wp_trim_words($content, $wp_rem_property_type_det_desc_length, NULL);
						    echo $trimmed_content;
						} else {
						    echo force_balance_tags($content);
						}
						?>
					    </div> 
				    <?php } ?> 
                                    </div>
                                    <?php
                                } // DESCRIPTION AND FEATURE CONTENT END   
                            }

                            // Fields
                            do_action('wp_rem_property_fields_frontend_ui', $post_id);

                            // Amenities
                            if ( $wp_rem_enable_features_element != 'off' ) {
                                do_action('wp_rem_features_element_html', $post_id);
                            }

                            // Virtual Tour
                            do_action('wp_rem_property_vitual_tour_html', $post_id);

                            // Video
                            if ( $wp_rem_enable_video_element != 'off' ) {
                                do_action('wp_rem_property_video_html', $post_id);
                            }

                            // Nearby Places
                            //do_action('wp_rem_property_sidebar_map_html', $post_id, 'view-5');
                            // Yelp Near By Places
                            if ( $wp_rem_enable_yelp_places_element != 'off' ) {
                                do_action('wp_rem_property_yelp_results_html', $post_id);
                            }

                            // Walk Scores
                            if ( ! empty($wp_rem_property_type_walkscores_switch) && $wp_rem_property_type_walkscores_switch == 'on' ) {
                                do_action('wp_rem_property_walk_score_results_html', $post_id, 'default');
                            }

                            // Apartment for Sale 
                            if ( $wp_rem_enable_appartment_for_sale_element != 'off' ) {
                                do_action('wp_rem_property_apartment_html', $post_id);
                            }

                            // Files attachments 
                            if ( $wp_rem_enable_file_attachments_element != 'off' ) {
                                do_action('wp_rem_attachments_html', $post_id);
                            }

                            $type_floor_plans = get_post_meta($property_type_id, 'wp_rem_floor_plans_options_element', true);
                            if ( $type_floor_plans == 'on' && $wp_rem_enable_floot_plan_element != 'off' ) {
                                $floor_plans = get_post_meta($post_id, 'wp_rem_floor_plans', true);
                                $floor_plans = empty($floor_plans) ? array() : $floor_plans;
                                if ( count($floor_plans) > 0 ) :
                                    ?>
                                    <div class="architecture-holder" id="floor-plans">
                                        <div class="element-title">
                                            <h3><?php echo wp_rem_plugin_text_srt('wp_rem_floor_plans'); ?></h3>
                                        </div>
                                        <?php $active = 'active'; ?>
                                        <ul class="nav nav-tabs">
                                            <?php
                                            $counter = 1;
                                            foreach ( $floor_plans as $key => $floor_plan ) :
                                                ?>
                                                <?php
                                                if ( $key == 1 ) {
                                                    $active = '';
                                                }
                                                $tab_id = 'floor-img' . $counter;
                                                ?>
                                                <li class="<?php echo esc_html($active); ?>"><a data-toggle="tab" href="#<?php echo sanitize_title($tab_id); ?>"><?php echo esc_html($floor_plan['floor_plan_title']); ?></a></li>
                                                <?php
                                                $counter ++;
                                            endforeach;
                                            ?>
                                        </ul>
                                        <div class="tab-content">
                                            <?php $active = 'active'; ?>
                                            <?php
                                            $counter = 1;
                                            foreach ( $floor_plans as $key => $floor_plan ) :
                                                ?>
                                                <?php
                                                if ( $key == 1 ) {
                                                    $active = '';
                                                }
                                                $tab_id = 'floor-img' . $counter;
                                                $floor_id = '';
                                                if ( isset($floor_plan['floor_plan_title']) && $floor_plan['floor_plan_title'] != '' ) {
                                                    $floor_id = 'id="' . sanitize_title($tab_id) . '"';
                                                }
                                                $counter ++;
                                                ?>
                                                <div <?php echo ($floor_id); ?> class="tab-pane fade in <?php echo esc_html($active); ?>">
                                                    <p><?php echo esc_html($floor_plan['floor_plan_title']); ?></p>
                                                    <img src="<?php echo wp_get_attachment_url($floor_plan['floor_plan_image']); ?>" alt=""/>
						    <p><?php echo esc_html($floor_plan['floor_plan_description']); ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php
                                endif;
                            }

                            // Floor Plans Documents Frontend Ui Hook
                            do_action('wp_rem_property_floor_plan_documents_ui', $property_type_id, $post_id);

                            do_action('property_type_faq_frontend', $post_id);
                            if ( isset($wp_rem_plugin_options['wp_rem_property_static_text_block']) && $wp_rem_plugin_options['wp_rem_property_static_text_block'] != '' ) {

                                $environmental_text = isset($wp_rem_plugin_options['wp_rem_property_static_envior_text']) ? $wp_rem_plugin_options['wp_rem_property_static_envior_text'] : '';
                                ?>
                                <div class="element-title">
                                    <h3><?php echo esc_html($environmental_text) ?></h3>
                                </div>
                                <div class="property-static-text">
                                    <?php echo htmlspecialchars_decode($wp_rem_plugin_options['wp_rem_property_static_text_block']); ?>
                                </div>
                                <?php
                            }

                            do_action('wp_rem_author_info_html', $post_id, 'view-3');
                            ?>
                            <?php do_action('wp_rem_reviews_ui', $post_id) ?>
                        </div>
                        <div class="sidebar col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <?php do_action('wp_rem_sidebar_gallery_html', $post_id, 'detail_view3'); ?>
                            <?php do_action('wp_rem_sidebar_map_html', $post_id, 'detail_view3'); ?>
                            <?php
                            $active_plugin = false;
                            $active_plugin = apply_filters('wp_rem_qwad_plugin_active', $active_plugin);
                            if ( $active_plugin ) {
                                do_action('wp_rem_sedebar_enquiry_form', $post_id, 'detail_view3');
                            } else {
                                do_action('wp_rem_sidebar_contact_html', $post_id, 'detail_view3');
                            }
                            ?>
                            <?php do_action('wp_rem_sidebar_member_info_html', $post_id, 'detail_view3'); ?>
                            <?php do_action('wp_rem_payment_calculator_html', $post_id, 'detail_view3'); ?>
                        </div>
                        <?php
                    } else {
                        ?> 
                        <div class=" col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="member-inactive">
                                <i class="icon-warning"></i>
                                <span><?php echo wp_rem_plugin_text_srt('wp_rem_user_profile_not_active'); ?></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php do_action('wp_rem_nearby_properties_element_html', $post_id); ?>
</div>